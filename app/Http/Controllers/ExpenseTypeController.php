<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class ExpenseTypeController extends Controller
{
    public function expenseTypesList()
    {
        $expenseTypes = ExpenseType::paginate(10);
        return view('expense_types.expense_types_list', compact('expenseTypes'));
    }

    public function expenseTypesAdd()
    {
        try {
            return view('expense_types.expense_type_add');
        } catch (\Exception $e) {
            Toastr::error('Failed to open add expense type page.', 'Error');
            return redirect()->back();
        }
    }

    public function saveExpenseType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:expense_types,name',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $expenseType = new ExpenseType;
            $expenseType->name = $request->name;
            $expenseType->description = $request->description;
            $expenseType->status = $request->input('status', 'active');
            $expenseType->save();

            DB::commit();
            Toastr::success('Expense type added successfully :)', 'Success');
            return redirect()->route('form.expensetype.list');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to add expense type :)', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function editExpenseType($id)
    {
        try {
            $expenseType = ExpenseType::findOrFail($id);
            return view('expense_types.expense_type_edit', compact('expenseType'));
        } catch (\Exception $e) {
            Toastr::error('Failed to load expense type edit page.', 'Error');
            return redirect()->back();
        }
    }

    public function updateExpenseType(Request $request, $id)
    {
        $request->validate([
            'name' => "required|string|max:255|unique:expense_types,name,{$id},id",
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        DB::beginTransaction();
        try {
            $expenseType = ExpenseType::findOrFail($id);

            $expenseType->update([
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->input('status', 'active'),
            ]);

            DB::commit();
            Toastr::success('Expense type updated successfully :)', 'Success');
            return redirect()->route('form.expensetype.list');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to update expense type :)', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function deleteExpenseType($id)
    {
        DB::beginTransaction();
        try {
            $expenseType = ExpenseType::findOrFail($id);
            
            // Check if expense type has related expenses
            if ($expenseType->expenses()->count() > 0) {
                Toastr::error('Cannot delete expense type with existing expenses.', 'Error');
                return redirect()->back();
            }
            
            $expenseType->delete();
            DB::commit();
            Toastr::success('Expense type deleted successfully :)', 'Success');
            return redirect()->route('form.expensetype.list');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to delete expense type :)', 'Error');
            return redirect()->back();
        }
    }
}