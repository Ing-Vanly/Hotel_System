<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Room;
use App\Models\Customer;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use DB;

class FrontDeskController extends Controller
{
    // Today's arrivals - guests checking in today
    public function todaysArrivals()
    {
        $arrivals = Booking::where('check_in_date', today())
                          ->whereIn('booking_status', ['confirmed', 'pending'])
                          ->with(['customer', 'room'])
                          ->orderBy('check_in_time')
                          ->get();
                          
        return view('frontdesk.arrivals', compact('arrivals'));
    }
    
    // Today's departures - guests checking out today
    public function todaysDepartures()
    {
        $departures = Booking::where('check_out_date', today())
                            ->where('booking_status', 'checked_in')
                            ->with(['customer', 'room'])
                            ->orderBy('check_out_time')
                            ->get();
                            
        return view('frontdesk.departures', compact('departures'));
    }
    
    // In-house guests - currently staying guests
    public function inHouseGuests()
    {
        $inHouseGuests = Booking::where('booking_status', 'checked_in')
                               ->where('check_in_date', '<=', today())
                               ->where('check_out_date', '>=', today())
                               ->with(['customer', 'room'])
                               ->orderBy('room_id')
                               ->get();
                               
        return view('frontdesk.in-house', compact('inHouseGuests'));
    }
    
    // Check-in process
    public function checkIn($bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        if ($booking->booking_status !== 'confirmed') {
            Toastr::error('Only confirmed bookings can be checked in', 'Error');
            return redirect()->back();
        }
        
        return view('frontdesk.checkin', compact('booking'));
    }
    
    // Process check-in
    public function processCheckIn(Request $request, $bookingId)
    {
        $request->validate([
            'actual_check_in_time' => 'required',
            'room_id' => 'required|exists:rooms,id',
            'guest_count' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($bookingId);
            
            // Update booking status
            $booking->update([
                'booking_status' => 'checked_in',
                'actual_check_in' => now(),
                'checked_in_by' => auth()->id(),
                'room_id' => $request->room_id,
                'guest_count' => $request->guest_count,
                'notes' => $request->notes
            ]);
            
            // Update room status
            Room::where('id', $request->room_id)->update(['status' => 'occupied']);
            
            DB::commit();
            Toastr::success('Guest checked in successfully!', 'Success');
            return redirect()->route('frontdesk.in-house');
            
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Check-in failed: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
    
    // Check-out process
    public function checkOut($bookingId)
    {
        $booking = Booking::with(['room', 'customer'])->findOrFail($bookingId);
        
        if ($booking->booking_status !== 'checked_in') {
            Toastr::error('Guest is not checked in', 'Error');
            return redirect()->back();
        }
        
        // Calculate final bill
        $nights = $booking->check_in_date->diffInDays($booking->check_out_date);
        $roomCharges = $booking->room_rate * $nights;
        $taxes = $roomCharges * 0.18; // 18% tax
        $totalAmount = $roomCharges + $taxes;
        
        return view('frontdesk.checkout', compact('booking', 'roomCharges', 'taxes', 'totalAmount'));
    }
    
    // Process check-out
    public function processCheckOut(Request $request, $bookingId)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,upi,bank_transfer',
            'amount_paid' => 'required|numeric|min:0',
            'checkout_notes' => 'nullable|string'
        ]);
        
        DB::beginTransaction();
        try {
            $booking = Booking::findOrFail($bookingId);
            
            // Update booking status
            $booking->update([
                'booking_status' => 'checked_out',
                'actual_check_out' => now(),
                'checked_out_by' => auth()->id(),
                'paid_amount' => $request->amount_paid,
                'payment_status' => $request->amount_paid >= $booking->total_amount ? 'paid' : 'partial',
                'notes' => $booking->notes . "\nCheckout: " . $request->checkout_notes
            ]);
            
            // Update room status to dirty (needs cleaning)
            Room::where('id', $booking->room_id)->update(['status' => 'dirty']);
            
            DB::commit();
            Toastr::success('Guest checked out successfully!', 'Success');
            return redirect()->route('frontdesk.departures');
            
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Check-out failed: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
    
    // Walk-in booking
    public function walkInBooking()
    {
        $availableRooms = Room::where('status', 'available')->get();
        $roomTypes = \App\Models\RoomType::where('is_active', true)->get();
        
        return view('frontdesk.walkin', compact('availableRooms', 'roomTypes'));
    }
    
    // Room assignment - assign specific room to booking
    public function roomAssignment()
    {
        $unassignedBookings = Booking::whereNull('room_id')
                                   ->where('booking_status', 'confirmed')
                                   ->where('check_in_date', '<=', today()->addDays(1))
                                   ->get();
                                   
        $availableRooms = Room::where('status', 'available')->get();
        
        return view('frontdesk.room-assignment', compact('unassignedBookings', 'availableRooms'));
    }
    
    // Guest folio - guest's bill and services
    public function guestFolio($bookingId)
    {
        $booking = Booking::with(['room', 'customer'])->findOrFail($bookingId);
        
        // Calculate stay details
        $checkInDate = $booking->actual_check_in ? Carbon::parse($booking->actual_check_in) : $booking->check_in_date;
        $nights = $checkInDate->diffInDays($booking->check_out_date);
        
        $roomCharges = $booking->room_rate * $nights;
        $taxes = $roomCharges * 0.18;
        $totalAmount = $roomCharges + $taxes - $booking->discount_amount;
        
        return view('frontdesk.folio', compact('booking', 'nights', 'roomCharges', 'taxes', 'totalAmount'));
    }
    
    // Night audit - daily closing procedures
    public function nightAudit()
    {
        $date = request('date', today());
        
        // Get statistics for the day
        $stats = [
            'total_arrivals' => Booking::whereDate('actual_check_in', $date)->count(),
            'total_departures' => Booking::whereDate('actual_check_out', $date)->count(),
            'occupancy_rate' => $this->calculateOccupancyRate($date),
            'revenue' => $this->calculateDayRevenue($date),
            'in_house_guests' => Booking::where('booking_status', 'checked_in')->count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'dirty_rooms' => Room::where('status', 'dirty')->count(),
        ];
        
        return view('frontdesk.night-audit', compact('stats', 'date'));
    }
    
    private function calculateOccupancyRate($date)
    {
        $totalRooms = Room::count();
        $occupiedRooms = Booking::where('booking_status', 'checked_in')
                               ->whereDate('check_in_date', '<=', $date)
                               ->whereDate('check_out_date', '>', $date)
                               ->count();
                               
        return $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;
    }
    
    private function calculateDayRevenue($date)
    {
        return Booking::whereDate('actual_check_out', $date)
                     ->sum('paid_amount');
    }
}