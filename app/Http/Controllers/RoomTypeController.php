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
        $roomTypes = RoomType::orderBy('room_name')->get();
        return view('roomtype.list', compact('roomTypes'));
    }

    public function create()
    {
        return view('roomtype.add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:255|unique:room_types,room_name',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'amenities' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        try {
            RoomType::create([
                'room_name' => $request->room_name,
                'description' => $request->description,
                'base_price' => $request->base_price,
                'max_occupancy' => $request->max_occupancy,
                'amenities' => $request->amenities
                    ? json_encode(array_map('trim', explode(',', $request->amenities)))
                    : null,
                'status' => $request->status ?? 'active',
            ]);

            toastr()->success('Room type created successfully');
            return redirect()->route('roomtype.index');
        } catch (Exception $e) {
            toastr()->error('Failed to create room type: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $roomType = RoomType::findOrFail($id);
        return view('roomtype.edit', compact('roomType'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'room_name' => 'required|string|max:255|unique:room_types,room_name,' . $id,
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'max_occupancy' => 'required|integer|min:1',
            'amenities' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        try {
            $roomType = RoomType::findOrFail($id);

            $roomType->update([
                'room_name' => $request->room_name,
                'description' => $request->description,
                'base_price' => $request->base_price,
                'max_occupancy' => $request->max_occupancy,
                'amenities' => $request->amenities
                    ? json_encode(array_map('trim', explode(',', $request->amenities)))
                    : null,
                'status' => $request->status ?? 'active',
            ]);

            toastr()->success('Room type updated successfully');
            return redirect()->route('roomtype.index');
        } catch (Exception $e) {
            toastr()->error('Failed to update room type: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $roomType = RoomType::findOrFail($id);

            if ($roomType->rooms()->count()) {
                toastr()->error('Cannot delete room type. It is used by some rooms.');
                return redirect()->route('roomtype.index');
            }

            $roomType->delete();
            toastr()->success('Room type deleted successfully');
        } catch (Exception $e) {
            toastr()->error('Failed to delete room type: ' . $e->getMessage());
        }

        return redirect()->route('roomtype.index');
    }
}
