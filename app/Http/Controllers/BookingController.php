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
    public function allbooking()
    {
        $allBookings = DB::table('bookings')->get();
        return view('formbooking.allbooking',compact('allBookings'));
    }

    // booking add
    public function bookingAdd()
    {
        try {
            // Try to get data using models (enhanced way)
            $roomTypes = RoomType::all();
            $customers = Customer::all();
            $rooms = Room::where('status', 'available')->orWhereNull('status')->get();
            
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
        $bookingEdit = DB::table('bookings')->where('bkg_id',$bkg_id)->first();
        return view('formbooking.bookingedit',compact('bookingEdit'));
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
            $booking->room_type = $request->room_type ?? 'Standard';
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

            // Update room status to occupied if room is selected
            if ($request->room_id && Schema::hasColumn('rooms', 'status')) {
                Room::where('id', $request->room_id)->update(['status' => 'occupied']);
            }
            
            DB::commit();
            Toastr::success('Booking created successfully!', 'Success');
            return redirect()->route('form/allbooking');
            
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Booking creation failed: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    // update record
    public function updateRecord(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!empty($request->fileupload)) {
                $photo = $request->fileupload;
                $file_name = rand() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('/assets/upload/'), $file_name);
            } else {
                $file_name = $request->hidden_fileupload;
            }

            $update = [
                'bkg_id' => $request->bkg_id,
                'name'   => $request->name,
                'room_type'  => $request->room_type,
                'total_numbers' => $request->total_numbers,
                'date'   => $request->date,
                'time'   => $request->time,
                'arrival_date'   => $request->arrival_date,
                'depature_date'  => $request->depature_date,
                'email'   => $request->email,
                'ph_number' => $request->phone_number,
                'fileupload'=> $file_name,
                'message'   => $request->message,
            ];

            Booking::where('bkg_id',$request->bkg_id)->update($update);
        
            DB::commit();
            Toastr::success('Updated booking successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Update booking fail :)','Error');
            return redirect()->back();
        }
    }

    // delete record booking
    public function deleteRecord(Request $request)
    {
        try {

            Booking::destroy($request->id);
            unlink('assets/upload/'.$request->fileupload);
            Toastr::success('Booking deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();
            Toastr::error('Booking delete fail :)','Error');
            return redirect()->back();
        }
    }

}
