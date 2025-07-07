<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Brian2694\Toastr\Facades\Toastr;

class EmployeeController extends Controller
{
    public function employeesList(Request $request)
    {
        $query = Employee::query();

        if ($request->filled('employee_id')) {
            $query->where('employee_id', 'like', '%' . $request->employee_id . '%');
        }
        if ($request->filled('employee_name')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->employee_name . '%')
                  ->orWhere('last_name', 'like', '%' . $request->employee_name . '%');
            });
        }
        if ($request->filled('role')) {
            $query->where('position', $request->role);
        }

        $employees = $query->paginate(10);
        return view('employees.employees_list', compact('employees'));
    }

    public function employeesAdd()
    {
        try {
            $roles = Role::all();
            return view('employees.employee_add', compact('roles'));
        } catch (\Exception $e) {
            Toastr::error('Failed to open add employee page.', 'Error');
            return redirect()->back();
        }
    }

    public function saveEmployee(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:employees,email',
            'national_id' => 'required|string|unique:employees,national_id',
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:active,inactive,terminated',
        ]);

        DB::beginTransaction();
        try {
            $file_name = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $file_name = rand() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('/assets/upload/'), $file_name);
            }

            $employee = new Employee;
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->gender = $request->gender;
            $employee->dob = $request->dob;
            $employee->phone = $request->phone;
            $employee->email = $request->email;
            $employee->national_id = $request->national_id;
            $employee->address = $request->address;
            $employee->position = $request->position;
            $employee->joining_date = $request->joining_date;
            $employee->salary = $request->salary;
            $employee->photo = $file_name;
            $employee->status = $request->input('status', 'active');
            $employee->save();

            DB::commit();
            Toastr::success('Employee added successfully :)', 'Success');
            return redirect()->route('form.employee.list');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to add employee :)', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function editEmployee($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $roles = Role::all();
            return view('employees.employee_edit', compact('employee', 'roles'));
        } catch (\Exception $e) {
            Toastr::error('Failed to load employee edit page.', 'Error');
            return redirect()->back();
        }
    }

    public function updateEmployee(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date',
            'phone' => 'required|string|max:20',
            'email' => "required|email|unique:employees,email,{$id},id",
            'national_id' => "required|string|unique:employees,national_id,{$id},id",
            'address' => 'required|string',
            'position' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'nullable|in:active,inactive,terminated',
        ]);

        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);

            if ($request->hasFile('photo')) {
                if ($employee->photo && file_exists(public_path('/assets/upload/' . $employee->photo))) {
                    @unlink(public_path('/assets/upload/' . $employee->photo));
                }
                $photo = $request->file('photo');
                $file_name = rand() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('/assets/upload/'), $file_name);
            } else {
                $file_name = $employee->photo;
            }

            $employee->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'dob' => $request->dob,
                'phone' => $request->phone,
                'email' => $request->email,
                'national_id' => $request->national_id,
                'address' => $request->address,
                'position' => $request->position,
                'joining_date' => $request->joining_date,
                'salary' => $request->salary,
                'photo' => $file_name,
                'status' => $request->input('status', 'active'),
            ]);

            DB::commit();
            Toastr::success('Employee updated successfully :)', 'Success');
            return redirect()->route('form.employee.list');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to update employee :)', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function deleteEmployee($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            if ($employee->photo && file_exists(public_path('/assets/upload/' . $employee->photo))) {
                @unlink(public_path('/assets/upload/' . $employee->photo));
            }
            $employee->delete();
            DB::commit();
            Toastr::success('Employee deleted successfully :)', 'Success');
            return redirect()->route('form.employee.list');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to delete employee :)', 'Error');
            return redirect()->back();
        }
    }
}
