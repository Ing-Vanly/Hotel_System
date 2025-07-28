<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Schema;
use DB;

class RoomsController extends Controller
{
    // index page
    public function allrooms()
    {
        // Use existing column until migration is run
        $allRooms = Room::orderBy('name')->get();
        return view('room.allroom',compact('allRooms'));
    }
    // add room page
    public function addRoom()
    {
        // Use existing room_types table structure
        $roomTypes = DB::table('room_types')->get();
        return view('room.addroom', compact('roomTypes'));
    }
    // edit room
    public function editRoom($bkg_room_id)
    {
        $roomEdit = Room::where('bkg_room_id', $bkg_room_id)->firstOrFail();
        $roomTypes = DB::table('room_types')->get();
        return view('room.editroom', compact('roomTypes', 'roomEdit'));
    }

    // save record room
    public function saveRecordRoom(Request $request)
    {
        // Validate only existing columns until migration is run
        $validation = [
            'name'          => 'required|string|max:255',
            'room_type'     => 'required|string|max:255',
            'ac_non_ac'     => 'required|string|max:255',
            'food'          => 'required|string|max:255',
            'bed_count'     => 'required|numeric|min:1',
            'charges_for_cancellation' => 'required|numeric|min:0',
            'rent'          => 'required|numeric|min:0',
            'phone_number'  => 'nullable|string|max:255',
            'fileupload'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'message'       => 'nullable|string|max:255',
        ];

        // Add validation for new columns only if they exist in the request
        if ($request->has('room_number')) {
            $validation['room_number'] = 'nullable|string|max:255';
        }
        if ($request->has('floor_number')) {
            $validation['floor_number'] = 'nullable|integer|min:1';
        }
        if ($request->has('max_occupancy')) {
            $validation['max_occupancy'] = 'nullable|integer|min:1';
        }
        if ($request->has('status')) {
            $validation['status'] = 'nullable|in:available,occupied,maintenance,dirty,out_of_order';
        }

        $request->validate($validation);

        DB::beginTransaction();
        try {
            // Generate room ID
            $bkg_room_id = 'RM-' . str_pad((Room::count() + 1), 4, '0', STR_PAD_LEFT);

            $file_name = null;
            if ($request->hasFile('fileupload')) {
                $photo = $request->file('fileupload');
                $file_name = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('/assets/upload/'), $file_name);
            }

            $room = new Room;
            $room->bkg_room_id = $bkg_room_id;
            $room->name = $request->name;
            $room->room_type = $request->room_type;
            $room->ac_non_ac = $request->ac_non_ac;
            $room->food = $request->food;
            $room->bed_count = $request->bed_count;
            $room->charges_for_cancellation = $request->charges_for_cancellation;
            $room->rent = $request->rent;
            $room->phone_number = $request->phone_number;
            $room->fileupload = $file_name;
            $room->message = $request->message;
            
            // Try to save new enhanced fields (only if columns exist)
            try {
                if ($request->room_number && Schema::hasColumn('rooms', 'room_number')) {
                    $room->room_number = $request->room_number;
                }
                if ($request->floor_number && Schema::hasColumn('rooms', 'floor_number')) {
                    $room->floor_number = $request->floor_number;
                }
                if ($request->max_occupancy && Schema::hasColumn('rooms', 'max_occupancy')) {
                    $room->max_occupancy = $request->max_occupancy;
                }
                if (Schema::hasColumn('rooms', 'status')) {
                    $room->status = $request->status ?: 'available';
                }
                if (Schema::hasColumn('rooms', 'is_active')) {
                    $room->is_active = true;
                }
            } catch (\Exception $e) {
                // Ignore errors if columns don't exist yet
            }
            
            $room->save();
            
            DB::commit();
            Toastr::success('Room created successfully!', 'Success');
            return redirect()->route('form/allrooms/page');
            
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to create room: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }
    }

    // update record
    public function updateRecord(Request $request)
    {
        // Validate input
        $validation = [
            'name'          => 'required|string|max:255',
            'room_type'     => 'required|string|max:255',
            'ac_non_ac'     => 'required|string|max:255',
            'food'          => 'required|string|max:255',
            'bed_count'     => 'required|numeric|min:1',
            'charges_for_cancellation' => 'required|numeric|min:0',
            'rent'          => 'required|numeric|min:0',
            'phone_number'  => 'nullable|string|max:255',
            'fileupload'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'message'       => 'nullable|string|max:255',
        ];

        $request->validate($validation);

        DB::beginTransaction();
        try {
            if (!empty($request->fileupload)) {
                $photo = $request->fileupload;
                $file_name = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('/assets/upload/'), $file_name);
            } else {
                $file_name = $request->hidden_fileupload;
            }

            $update = [
                'name'   => $request->name,
                'room_type'  => $request->room_type,
                'ac_non_ac'  => $request->ac_non_ac,
                'food'  => $request->food,
                'bed_count'  => $request->bed_count,
                'charges_for_cancellation'  => $request->charges_for_cancellation,
                'rent'  => $request->rent,
                'phone_number' => $request->phone_number,
                'fileupload'=> $file_name,
                'message'   => $request->message,
            ];
            Room::where('bkg_room_id',$request->bkg_room_id)->update($update);
        
            DB::commit();
            Toastr::success('Updated room successfully!','Success');
            return redirect()->route('form/allrooms/page');
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Update room failed: ' . $e->getMessage(),'Error');
            return redirect()->back()->withInput();
        }
    }

    // delete record
    public function deleteRecord(Request $request)
    {
        try {

            Room::destroy($request->id);
            unlink('assets/upload/'.$request->fileupload);
            Toastr::success('Room deleted successfully :)','Success');
            return redirect()->back();
        
        } catch(\Exception $e) {

            DB::rollback();
            Toastr::error('Room delete fail :)','Error');
            return redirect()->back();
        }
    }
}
