<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class RoomTypeController extends Controller
{
    public function index()
    {
        try {
            $roomTypes = RoomType::orderBy('room_name')->get();
            return view('form.roomtype.list', compact('roomTypes'));
        } catch (Exception $e) {
            // Fallback to DB query if table doesn't have new columns yet
            $roomTypes = DB::table('room_types')->orderBy('room_name')->get();
            return view('form.roomtype.list', compact('roomTypes'));
        }
    }

    public function create()
    {
        return view('form.roomtype.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:255|unique:room_types',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'amenities' => 'nullable|string',
        ]);

        try {
            $amenities = $request->amenities ? 
                json_encode(array_map('trim', explode(',', $request->amenities))) : 
                null;

            RoomType::create([
                'room_name' => $request->room_name,
                'description' => $request->description,
                'base_price' => $request->base_price,
                'max_occupancy' => $request->max_occupancy,
                'amenities' => $amenities,
                'is_active' => $request->has('is_active'),
            ]);

            toastr()->success('Room type created successfully');
            return redirect()->route('roomtype.index');
        } catch (Exception $e) {
            // Fallback for old database structure
            DB::table('room_types')->insert([
                'room_name' => $request->room_name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            toastr()->success('Room type created successfully');
            return redirect()->route('roomtype.index');
        }
    }

    public function edit($id)
    {
        try {
            $roomType = RoomType::findOrFail($id);
            return view('form.roomtype.edit', compact('roomType'));
        } catch (Exception $e) {
            $roomType = DB::table('room_types')->where('id', $id)->first();
            if (!$roomType) {
                toastr()->error('Room type not found');
                return redirect()->route('roomtype.index');
            }
            return view('form.roomtype.edit', compact('roomType'));
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'room_name' => 'required|string|max:255|unique:room_types,room_name,' . $id,
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'amenities' => 'nullable|string',
        ]);

        try {
            $roomType = RoomType::findOrFail($id);
            
            $amenities = $request->amenities ? 
                json_encode(array_map('trim', explode(',', $request->amenities))) : 
                null;

            $roomType->update([
                'room_name' => $request->room_name,
                'description' => $request->description,
                'base_price' => $request->base_price,
                'max_occupancy' => $request->max_occupancy,
                'amenities' => $amenities,
                'is_active' => $request->has('is_active'),
            ]);

            toastr()->success('Room type updated successfully');
            return redirect()->route('roomtype.index');
        } catch (Exception $e) {
            // Fallback for old database structure
            DB::table('room_types')->where('id', $id)->update([
                'room_name' => $request->room_name,
                'updated_at' => now(),
            ]);

            toastr()->success('Room type updated successfully');
            return redirect()->route('roomtype.index');
        }
    }

    public function destroy($id)
    {
        try {
            $roomType = RoomType::findOrFail($id);
            
            // Check if room type is being used by any rooms
            if ($roomType->rooms()->exists()) {
                toastr()->error('Cannot delete room type. It is being used by rooms.');
                return redirect()->route('roomtype.index');
            }
            
            $roomType->delete();
            toastr()->success('Room type deleted successfully');
        } catch (Exception $e) {
            // Fallback for old database structure
            DB::table('room_types')->where('id', $id)->delete();
            toastr()->success('Room type deleted successfully');
        }

        return redirect()->route('roomtype.index');
    }

    public function getAvailableRoomsForDates(Request $request)
    {
        $request->validate([
            'room_type_id' => 'required|exists:room_types,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
        ]);

        try {
            $roomType = RoomType::findOrFail($request->room_type_id);
            $availableRooms = $roomType->availableRoomsForDates(
                $request->check_in, 
                $request->check_out
            );

            return response()->json([
                'success' => true,
                'rooms' => $availableRooms
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking room availability'
            ], 500);
        }
    }
}