<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Brian2694\Toastr\Facades\Toastr;  // <-- Import Toastr here

class LeaveTypeController extends Controller
{
    public function index()
    {
        $leaveTypes = LeaveType::all();
        return view('leavetype.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('leavetype.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:leave_types,name',
            'description' => 'nullable|string',
            'max_leave_count' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            LeaveType::create([
                'name' => $request->name,
                'description' => $request->description,
                'max_leave_count' => $request->max_leave_count,
            ]);

            DB::commit();
            Toastr::success('Leave Type created successfully :)', 'Success');
            return redirect()->route('leavetype.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LeaveType Store Error: ' . $e->getMessage());
            Toastr::error('Failed to create leave type :)', 'Error');
            return back()->withInput();
        }
    }

    public function edit(string $id)
    {
        $leave_type = LeaveType::findOrFail($id);
        return view('leavetype.edit', compact('leave_type'));
    }

    public function update(Request $request, string $id)
    {
        $leave_type = LeaveType::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:leave_types,name,' . $leave_type->id,
            'description' => 'nullable|string',
            'max_leave_count' => 'required|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            $leave_type->update([
                'name' => $request->name,
                'description' => $request->description,
                'max_leave_count' => $request->max_leave_count,
            ]);

            DB::commit();
            Toastr::success('Leave Type updated successfully :)', 'Success');
            return redirect()->route('leavetype.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LeaveType Update Error: ' . $e->getMessage());
            Toastr::error('Failed to update leave type :)', 'Error');
            return back()->withInput();
        }
    }

    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();

            $leave_type = LeaveType::findOrFail($id);
            $leave_type->delete();

            DB::commit();

            Toastr::success('Leave Type deleted successfully :)', 'Success');
            return redirect()->route('leavetype.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('LeaveType Delete Error: ' . $e->getMessage());

            Toastr::error('Failed to delete leave type. Please try again.', 'Error');
            return redirect()->back();
        }
    }
}
