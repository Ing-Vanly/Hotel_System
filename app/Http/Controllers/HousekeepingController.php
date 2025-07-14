<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use Brian2694\Toastr\Facades\Toastr;
use DB;

class HousekeepingController extends Controller
{
    // Room status overview
    public function roomStatus()
    {
        $rooms = Room::with(['currentBooking'])->orderBy('room_number')->get();
        
        $statusCounts = [
            'available' => Room::where('status', 'available')->count(),
            'occupied' => Room::where('status', 'occupied')->count(),
            'dirty' => Room::where('status', 'dirty')->count(),
            'maintenance' => Room::where('status', 'maintenance')->count(),
            'out_of_order' => Room::where('status', 'out_of_order')->count(),
        ];
        
        return view('housekeeping.room-status', compact('rooms', 'statusCounts'));
    }
    
    // Update room status
    public function updateRoomStatus(Request $request, $roomId)
    {
        $request->validate([
            'status' => 'required|in:available,dirty,maintenance,out_of_order',
            'notes' => 'nullable|string'
        ]);
        
        $room = Room::findOrFail($roomId);
        $room->update(['status' => $request->status]);
        
        // Log the status change
        DB::table('room_status_logs')->insert([
            'room_id' => $roomId,
            'old_status' => $room->getOriginal('status'),
            'new_status' => $request->status,
            'changed_by' => auth()->id(),
            'notes' => $request->notes,
            'created_at' => now(),
        ]);
        
        Toastr::success('Room status updated successfully!', 'Success');
        return redirect()->back();
    }
    
    // Cleaning schedule
    public function cleaningSchedule()
    {
        $dirtyRooms = Room::where('status', 'dirty')
                         ->with(['currentBooking'])
                         ->orderBy('updated_at')
                         ->get();
                         
        $maintenanceRooms = Room::where('status', 'maintenance')
                               ->orderBy('updated_at')
                               ->get();
        
        return view('housekeeping.cleaning-schedule', compact('dirtyRooms', 'maintenanceRooms'));
    }
    
    // Mark room as cleaned
    public function markAsCleaned($roomId)
    {
        $room = Room::findOrFail($roomId);
        
        if ($room->status !== 'dirty') {
            Toastr::error('Only dirty rooms can be marked as cleaned', 'Error');
            return redirect()->back();
        }
        
        $room->update(['status' => 'available']);
        
        // Log the cleaning
        DB::table('room_cleaning_logs')->insert([
            'room_id' => $roomId,
            'cleaned_by' => auth()->id(),
            'cleaned_at' => now(),
            'created_at' => now(),
        ]);
        
        Toastr::success('Room marked as cleaned and available!', 'Success');
        return redirect()->back();
    }
    
    // Maintenance requests
    public function maintenanceRequests()
    {
        $requests = DB::table('maintenance_requests')
                     ->join('rooms', 'maintenance_requests.room_id', '=', 'rooms.id')
                     ->join('users', 'maintenance_requests.requested_by', '=', 'users.id')
                     ->select('maintenance_requests.*', 'rooms.name as room_name', 'users.name as requester_name')
                     ->orderBy('maintenance_requests.priority', 'desc')
                     ->orderBy('maintenance_requests.created_at')
                     ->get();
        
        return view('housekeeping.maintenance-requests', compact('requests'));
    }
    
    // Create maintenance request
    public function createMaintenanceRequest(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'issue_type' => 'required|in:plumbing,electrical,ac,furniture,cleaning,other',
            'priority' => 'required|in:low,medium,high,urgent',
            'description' => 'required|string',
        ]);
        
        DB::beginTransaction();
        try {
            // Create maintenance request
            DB::table('maintenance_requests')->insert([
                'room_id' => $request->room_id,
                'issue_type' => $request->issue_type,
                'priority' => $request->priority,
                'description' => $request->description,
                'status' => 'pending',
                'requested_by' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Update room status if urgent
            if ($request->priority === 'urgent') {
                Room::where('id', $request->room_id)->update(['status' => 'out_of_order']);
            } else {
                Room::where('id', $request->room_id)->update(['status' => 'maintenance']);
            }
            
            DB::commit();
            Toastr::success('Maintenance request created successfully!', 'Success');
            return redirect()->back();
            
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to create maintenance request', 'Error');
            return redirect()->back();
        }
    }
    
    // Housekeeping reports
    public function housekeepingReports()
    {
        $dateFrom = request('date_from', today()->subDays(7));
        $dateTo = request('date_to', today());
        
        $reports = [
            'rooms_cleaned' => DB::table('room_cleaning_logs')
                                ->whereBetween('cleaned_at', [$dateFrom, $dateTo])
                                ->count(),
                                
            'maintenance_requests' => DB::table('maintenance_requests')
                                       ->whereBetween('created_at', [$dateFrom, $dateTo])
                                       ->count(),
                                       
            'average_cleaning_time' => $this->calculateAverageCleaningTime($dateFrom, $dateTo),
            
            'room_status_changes' => DB::table('room_status_logs')
                                      ->whereBetween('created_at', [$dateFrom, $dateTo])
                                      ->count(),
        ];
        
        return view('housekeeping.reports', compact('reports', 'dateFrom', 'dateTo'));
    }
    
    // Lost and found
    public function lostAndFound()
    {
        $items = DB::table('lost_found_items')
                  ->join('rooms', 'lost_found_items.room_id', '=', 'rooms.id')
                  ->select('lost_found_items.*', 'rooms.name as room_name')
                  ->orderBy('lost_found_items.found_date', 'desc')
                  ->get();
        
        return view('housekeeping.lost-found', compact('items'));
    }
    
    // Add lost and found item
    public function addLostFoundItem(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'item_description' => 'required|string',
            'found_date' => 'required|date',
            'location_details' => 'nullable|string',
        ]);
        
        DB::table('lost_found_items')->insert([
            'room_id' => $request->room_id,
            'item_description' => $request->item_description,
            'found_date' => $request->found_date,
            'location_details' => $request->location_details,
            'status' => 'found',
            'found_by' => auth()->id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        Toastr::success('Lost & Found item added successfully!', 'Success');
        return redirect()->back();
    }
    
    private function calculateAverageCleaningTime($dateFrom, $dateTo)
    {
        // This would calculate based on room status change logs
        // For now, return a placeholder
        return '25 minutes';
    }
}