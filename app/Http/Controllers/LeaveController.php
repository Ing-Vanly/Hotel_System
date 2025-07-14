<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = Leave::with(['employee', 'leaveType'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('employees.leaves', compact('leaves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Debug: Get all employees first to see what we have
        $allEmployees = Employee::all();
        $activeEmployees = Employee::where('status', 'active')->get();
        
        // For now, let's get all employees to debug the issue
        $employees = Employee::all();
        $leaveTypes = LeaveType::all();

        // Debug information (you can remove this later)
        \Log::info('Total employees: ' . $allEmployees->count());
        \Log::info('Active employees: ' . $activeEmployees->count());
        \Log::info('All employees data: ', $allEmployees->toArray());

        return view('leave.create', compact('employees', 'leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,approved,rejected,cancelled'
        ]);

        // Check for overlapping leave requests
        $overlapping = Leave::where('employee_id', $request->employee_id)
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($overlapping) {
            Toastr::error('Employee already has leave scheduled for the selected dates.', 'Error');
            return redirect()->back()->withInput();
        }

        try {
            Leave::create($request->all());
            Toastr::success('Leave request created successfully.', 'Success');
            return redirect()->route('leave.index');
        } catch (\Exception $e) {
            Toastr::error('Failed to create leave request.', 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        $leave->load(['employee', 'leaveType']);
        return view('leave.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        // For now, get all employees to debug the issue
        $employees = Employee::all();
        $leaveTypes = LeaveType::all();

        return view('leave.edit', compact('leave', 'employees', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,approved,rejected,cancelled'
        ]);

        // Check for overlapping leave requests (excluding current leave)
        $overlapping = Leave::where('employee_id', $request->employee_id)
            ->where('id', '!=', $leave->id)
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                    ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                    });
            })
            ->exists();

        if ($overlapping) {
            Toastr::error('Employee already has leave scheduled for the selected dates.', 'Error');
            return redirect()->back()->withInput();
        }

        try {
            $leave->update($request->all());
            Toastr::success('Leave request updated successfully.', 'Success');
            return redirect()->route('leave.index');
        } catch (\Exception $e) {
            Toastr::error('Failed to update leave request.', 'Error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        try {
            $leave->delete();
            Toastr::success('Leave request deleted successfully.', 'Success');
            return redirect()->route('leave.index');
        } catch (\Exception $e) {
            Toastr::error('Failed to delete leave request.', 'Error');
            return redirect()->back();
        }
    }

    /**
     * Approve leave request
     */
    public function approve(Leave $leave)
    {
        $leave->update(['status' => 'approved']);
        Toastr::success('Leave request approved successfully.', 'Success');
        return redirect()->back();
    }

    /**
     * Reject leave request
     */
    public function cancel(Leave $leave)
    {
        try {
            $leave->update(['status' => 'rejected']);
            Toastr::success('Leave request rejected successfully.', 'Success');
        } catch (\Exception $e) {
            // Fallback: try with 'cancelled' if 'rejected' fails
            try {
                $leave->update(['status' => 'cancelled']);
                Toastr::success('Leave request cancelled successfully.', 'Success');
            } catch (\Exception $e2) {
                Toastr::error('Failed to update leave status. Please check database configuration.', 'Error');
                \Log::error('Leave status update failed: ' . $e->getMessage() . ' | Fallback error: ' . $e2->getMessage());
            }
        }
        return redirect()->back();
    }
}
