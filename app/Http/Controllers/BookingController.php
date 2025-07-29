<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Customer;
use Illuminate\Support\Facades\Schema;
use DB;

class BookingController extends Controller
{
    // view page all booking
    public function allbooking(Request $request)
    {
        $query = Booking::with(['customer', 'room', 'roomType']);

        // Filter by guest name (search in both guest_name and legacy name fields)
        if ($request->filled('guest_name')) {
            $guestName = $request->guest_name;
            $query->where(function ($q) use ($guestName) {
                $q->where('guest_name', 'LIKE', '%' . $guestName . '%')
                  ->orWhere('name', 'LIKE', '%' . $guestName . '%');
            });
        }

        // Filter by booking status
        if ($request->filled('booking_status')) {
            $query->where('booking_status', $request->booking_status);
        }

        // Filter by payment status  
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $allBookings = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('formbooking.allbooking', compact('allBookings'));
    }
    // booking add  
    public function bookingAdd()
    {
        try {
            // Try to get data using models (enhanced way)
            $roomTypes = RoomType::all();
            $customers = Customer::all();
            // Get available rooms (not currently occupied by active bookings)
            $rooms = Room::where(function ($query) {
                $query->where('status', 'available')
                    ->orWhereNull('status');
            })
                ->whereDoesntHave('bookings', function ($query) {
                    $query->whereIn('booking_status', ['confirmed', 'checked_in'])
                        ->where('check_in_date', '<=', now())
                        ->where('check_out_date', '>=', now());
                })
                ->with('roomType')
                ->get();
            return view('formbooking.bookingadd', compact('roomTypes', 'customers', 'rooms'));
        } catch (\Exception $e) {
            // Fallback to old way if enhanced columns don't exist yet
            $data = DB::table('room_types')->get();
            $user = DB::table('users')->get();
            $roomTypes = $data;
            $customers = $user;
            $rooms = Room::all();
            return view('formbooking.bookingadd', compact('data', 'user', 'roomTypes', 'customers', 'rooms'));
        }
    }
    // booking edit
    public function bookingEdit($bkg_id)
    {
        try {
            // Get booking with relationships
            $bookingEdit = Booking::with(['customer', 'room', 'roomType'])
                ->where('bkg_id', $bkg_id)
                ->first();

            if (!$bookingEdit) {
                Toastr::error('Booking not found!', 'Error');
                return redirect()->route('form/allbooking');
            }

            // Get all room types for dropdown
            $roomTypes = RoomType::all();

            // Get all customers for dropdown
            $customers = Customer::all();

            // Get available rooms (including current room if already assigned)
            $rooms = Room::where(function ($query) use ($bookingEdit) {
                $query->where('status', 'available')
                    ->orWhereNull('status')
                    ->orWhere('id', $bookingEdit->room_id); // Include current room
            })
                ->whereDoesntHave('bookings', function ($query) use ($bookingEdit) {
                    $query->whereIn('booking_status', ['confirmed', 'checked_in'])
                        ->where('id', '!=', $bookingEdit->id) // Exclude current booking
                        ->where(function ($dateQuery) use ($bookingEdit) {
                            $checkIn = $bookingEdit->check_in_date ?? $bookingEdit->arrival_date;
                            $checkOut = $bookingEdit->check_out_date ?? $bookingEdit->depature_date;

                            if ($checkIn && $checkOut) {
                                $dateQuery->whereBetween('check_in_date', [$checkIn, $checkOut])
                                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                                    ->orWhere(function ($overlapQuery) use ($checkIn, $checkOut) {
                                        $overlapQuery->where('check_in_date', '<=', $checkIn)
                                            ->where('check_out_date', '>=', $checkOut);
                                    });
                            }
                        });
                })
                ->with('roomType')
                ->get();

            return view('formbooking.bookingedit', compact('bookingEdit', 'roomTypes', 'customers', 'rooms'));

        } catch (\Exception $e) {
            // Fallback to old method
            $bookingEdit = DB::table('bookings')->where('bkg_id', $bkg_id)->first();

            if (!$bookingEdit) {
                Toastr::error('Booking not found!', 'Error');
                return redirect()->route('form/allbooking');
            }

            // Try to get room types, customers, and rooms
            try {
                $roomTypes = RoomType::all();
                $customers = Customer::all();
                $rooms = Room::all();
            } catch (\Exception $ex) {
                // Even more basic fallback
                $roomTypes = collect();
                $customers = collect();
                $rooms = collect();
            }

            return view('formbooking.bookingedit', compact('bookingEdit', 'roomTypes', 'customers', 'rooms'));
        }
    }
    // booking save record
    public function saveRecord(Request $request)
    {
        // Enhanced validation for new booking system
        $validation = [
            'guest_name' => 'required|string|max:255',
            'guest_count' => 'required|integer|min:1',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
        ];
        // Add validation for enhanced fields if they exist
        if ($request->has('room_id') && $request->room_id) {
            $validation['room_id'] = 'required|exists:rooms,id';
        }
        if ($request->has('customer_id') && $request->customer_id) {
            $validation['customer_id'] = 'nullable|exists:customers,id';
        }
        if ($request->has('room_type_id') && $request->room_type_id) {
            $validation['room_type_id'] = 'nullable|exists:room_types,id';
        }
        // Fallback validation for legacy fields
        if (!$request->has('guest_name')) {
            $validation['name'] = 'required|string|max:255';
        }
        if (!$request->has('check_in_date')) {
            $validation['arrival_date'] = 'required|string|max:255';
            $validation['depature_date'] = 'required|string|max:255';
        }
        $request->validate($validation);
        DB::beginTransaction();
        try {
            // Generate booking ID
            $bkg_id = 'BK-' . str_pad((Booking::count() + 1), 4, '0', STR_PAD_LEFT);
            // Handle file upload
            $file_name = null;
            if ($request->hasFile('fileupload')) {
                $photo = $request->file('fileupload');
                $file_name = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('/assets/upload/'), $file_name);
            }
            $booking = new Booking;
            $booking->bkg_id = $bkg_id;
            // Enhanced booking fields (if available)
            if ($request->has('guest_name')) {
                $booking->guest_name = $request->guest_name;
                $booking->guest_email = $request->guest_email;
                $booking->guest_phone = $request->guest_phone;
                $booking->guest_count = $request->guest_count;
                $booking->check_in_date = $request->check_in_date;
                $booking->check_out_date = $request->check_out_date;
                $booking->total_amount = $request->total_amount;
                $booking->payment_status = $request->payment_status ?? 'pending';
                $booking->booking_status = $request->booking_status ?? 'pending';
                $booking->booking_source = $request->booking_source ?? 'walk_in';
                $booking->special_requests = $request->special_requests;
                // Connect to room and customer
                if ($request->room_id && Schema::hasColumn('bookings', 'room_id')) {
                    $booking->room_id = $request->room_id;
                }
                if ($request->customer_id && $request->customer_id != '' && Schema::hasColumn('bookings', 'customer_id')) {
                    $booking->customer_id = $request->customer_id;
                }
                if ($request->room_type_id && $request->room_type_id != '' && Schema::hasColumn('bookings', 'room_type_id')) {
                    $booking->room_type_id = $request->room_type_id;
                }
            }
            // Legacy booking fields (for backward compatibility)
            $booking->name = $request->guest_name ?? $request->name;

            // Get room type name from selected room
            $roomTypeName = 'Standard';
            if ($request->room_id) {
                $selectedRoom = Room::with('roomType')->find($request->room_id);
                if ($selectedRoom && $selectedRoom->roomType) {
                    $roomTypeName = $selectedRoom->roomType->room_name;
                } elseif ($selectedRoom && $selectedRoom->room_type) {
                    $roomTypeName = $selectedRoom->room_type;
                }
            }
            $booking->room_type = $roomTypeName;
            $booking->total_numbers = $request->guest_count ?? $request->total_numbers;
            $booking->date = $request->date ?? now()->format('Y-m-d');
            $booking->time = $request->time ?? now()->format('H:i');
            $booking->arrival_date = $request->check_in_date ?? $request->arrival_date;
            $booking->depature_date = $request->check_out_date ?? $request->depature_date;
            $booking->email = $request->guest_email ?? $request->email;
            $booking->ph_number = $request->guest_phone ?? $request->phone_number;
            $booking->fileupload = $file_name;
            $booking->message = $request->special_requests ?? $request->message;
            $booking->save();
            // Update room status to occupied if room is selected and booking is confirmed
            if ($request->room_id && Schema::hasColumn('rooms', 'status')) {
                $status = ($request->booking_status === 'confirmed') ? 'occupied' : 'available';
                Room::where('id', $request->room_id)->update(['status' => $status]);
            }
            DB::commit();
            Toastr::success('Booking created successfully!', 'Success');
            return redirect()->route('form/allbooking');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Booking creation failed: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }
    // update record
    public function updateRecord(Request $request)
    {
        // Enhanced validation for booking update
        $validation = [
            'bkg_id' => 'required|string|exists:bookings,bkg_id',
            'guest_name' => 'required|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:20',
            'room_type' => 'nullable|string|max:255',
            'room_type_id' => 'nullable|exists:room_types,id',
            'room_id' => 'required|exists:rooms,id',
            'customer_id' => 'nullable|exists:customers,id',
            'guest_count' => 'required|integer|min:1|max:6',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:pending,partial,paid,refunded',
            'booking_status' => 'nullable|in:pending,confirmed,checked_in,checked_out,cancelled,no_show',
            'booking_source' => 'nullable|in:walk_in,phone,email,website,agency',
            'fileupload' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'special_requests' => 'nullable|string|max:1000',
        ];
        $request->validate($validation);
        DB::beginTransaction();
        try {
            $booking = Booking::where('bkg_id', $request->bkg_id)->firstOrFail();
            // Handle file upload
            $file_name = $request->hidden_fileupload;
            if ($request->hasFile('fileupload')) {
                // Delete old file if exists
                if ($booking->fileupload && file_exists(public_path('/assets/upload/' . $booking->fileupload))) {
                    unlink(public_path('/assets/upload/' . $booking->fileupload));
                }
                $photo = $request->file('fileupload');
                $file_name = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('/assets/upload/'), $file_name);
            }
            // Update booking data (both new and legacy fields)
            $update = [
                // Enhanced fields
                'guest_name' => $request->guest_name,
                'guest_email' => $request->guest_email,
                'guest_phone' => $request->guest_phone,
                'guest_count' => $request->guest_count,
                'check_in_date' => $request->check_in_date,
                'check_out_date' => $request->check_out_date,
                'total_amount' => $request->total_amount,
                'payment_status' => $request->payment_status ?? 'pending',
                'booking_status' => $request->booking_status ?? 'pending',
                'booking_source' => $request->booking_source ?? 'walk_in',
                'special_requests' => $request->special_requests,
                'fileupload' => $file_name,
                // Legacy fields for backward compatibility
                'name' => $request->guest_name,
                'room_type' => $request->room_type ?: $this->getRoomTypeName($request->room_id),
                'total_numbers' => $request->guest_count,
                'arrival_date' => $request->check_in_date,
                'depature_date' => $request->check_out_date,
                'email' => $request->guest_email,
                'ph_number' => $request->guest_phone,
                'message' => $request->special_requests,
            ];

            // Add enhanced fields if they exist in database
            if ($request->room_id && Schema::hasColumn('bookings', 'room_id')) {
                $update['room_id'] = $request->room_id;
            }
            if ($request->customer_id && Schema::hasColumn('bookings', 'customer_id')) {
                $update['customer_id'] = $request->customer_id;
            }
            if ($request->room_type_id && Schema::hasColumn('bookings', 'room_type_id')) {
                $update['room_type_id'] = $request->room_type_id;
            }
            $booking->update($update);
            // Update room status based on booking status
            $oldRoomId = $booking->room_id;
            $newRoomId = $request->room_id;

            if (Schema::hasColumn('rooms', 'status')) {
                // If room changed, free up the old room
                if ($oldRoomId && $oldRoomId != $newRoomId) {
                    Room::where('id', $oldRoomId)->update(['status' => 'available']);
                }

                // Update new room status
                if ($newRoomId) {
                    $newRoomStatus = 'occupied'; // default
                    if ($request->booking_status === 'checked_out' || $request->booking_status === 'cancelled') {
                        $newRoomStatus = 'available';
                    } elseif ($request->booking_status === 'confirmed' || $request->booking_status === 'checked_in') {
                        $newRoomStatus = 'occupied';
                    } else {
                        $newRoomStatus = 'available'; // for pending, no_show, etc.
                    }
                    Room::where('id', $newRoomId)->update(['status' => $newRoomStatus]);
                }
            }
            DB::commit();
            Toastr::success('Booking updated successfully!', 'Success');
            return redirect()->route('form/allbooking');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Update booking failed: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }
    // delete record booking
    public function deleteRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($request->id);
            // Delete associated file if exists
            if ($booking->fileupload && file_exists(public_path('/assets/upload/' . $booking->fileupload))) {
                unlink(public_path('/assets/upload/' . $booking->fileupload));
            }
            // Update room status back to available if it was occupied
            if ($booking->room_id && Schema::hasColumn('rooms', 'status')) {
                Room::where('id', $booking->room_id)->update(['status' => 'available']);
            }
            $booking->delete();
            DB::commit();
            Toastr::success('Booking deleted successfully!', 'Success');
            return redirect()->route('form/allbooking');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to delete booking: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
    // Check room availability for given dates (AJAX endpoint)
    public function checkRoomAvailability(Request $request)
    {
        try {
            $checkIn = $request->check_in_date;
            $checkOut = $request->check_out_date;
            $roomTypeId = $request->room_type_id;
            $excludeBookingId = $request->exclude_booking_id; // For edit mode
            if (!$checkIn || !$checkOut) {
                return response()->json(['error' => 'Check-in and check-out dates are required'], 400);
            }
            // Get available rooms for the given dates
            $availableRooms = Room::where(function ($query) {
                $query->where('status', 'available')
                    ->orWhereNull('status');
            })
                ->when($roomTypeId, function ($query) use ($roomTypeId) {
                    return $query->where('room_type_id', $roomTypeId);
                })
                ->whereDoesntHave('bookings', function ($query) use ($checkIn, $checkOut, $excludeBookingId) {
                    $query->whereIn('booking_status', ['confirmed', 'checked_in'])
                        ->where(function ($dateQuery) use ($checkIn, $checkOut) {
                            $dateQuery->whereBetween('check_in_date', [$checkIn, $checkOut])
                                ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                                ->orWhere(function ($overlapQuery) use ($checkIn, $checkOut) {
                                    $overlapQuery->where('check_in_date', '<=', $checkIn)
                                        ->where('check_out_date', '>=', $checkOut);
                                });
                        });
                    // Exclude current booking when editing
                    if ($excludeBookingId) {
                        $query->where('id', '!=', $excludeBookingId);
                    }
                })
                ->with('roomType')
                ->get();
            return response()->json([
                'available_rooms' => $availableRooms->map(function ($room) {
                    return [
                        'id' => $room->id,
                        'name' => $room->name ?: ($room->room_number ? 'Room ' . $room->room_number : 'Room ' . $room->id),
                        'rate' => $room->rent,
                        'room_type_id' => $room->room_type_id,
                        'room_type_name' => $room->roomType->room_name ?? $room->room_type,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to check availability: ' . $e->getMessage()], 500);
        }
    }

    // Helper method to get room type name from room ID
    private function getRoomTypeName($roomId)
    {
        if (!$roomId) return 'Standard';

        $room = Room::with('roomType')->find($roomId);
        if ($room && $room->roomType) {
            return $room->roomType->room_name;
        } elseif ($room && $room->room_type) {
            return $room->room_type;
        }

        return 'Standard';
    }
}
