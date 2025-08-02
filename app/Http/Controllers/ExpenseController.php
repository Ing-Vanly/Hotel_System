<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;

class ExpenseController extends Controller
{
    public function expensesList(Request $request)
    {
        $query = Expense::with('expenseType');

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('expense_type')) {
            $query->where('expense_type_id', $request->expense_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        $expenseTypes = ExpenseType::where('status', 'active')->get();
        $expenses = $query->orderBy('expense_date', 'desc')->paginate(10);

        return view('expenses.expenses_list', compact('expenses', 'expenseTypes'));
    }

    public function expensesAdd()
    {
        try {
            $expenseTypes = ExpenseType::where('status', 'active')->get();
            return view('expenses.expense_add', compact('expenseTypes'));
        } catch (\Exception $e) {
            Toastr::error('Failed to open add expense page.', 'Error');
            return redirect()->back();
        }
    }

    public function saveExpense(Request $request)
    {
        $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'receipt' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        DB::beginTransaction();
        try {
            $file_name = null;
            if ($request->hasFile('receipt')) {
                $receipt = $request->file('receipt');
                $file_name = rand() . '.' . $receipt->getClientOriginalExtension();
                $receipt->move(public_path('/assets/upload/'), $file_name);
            }

            $expense = new Expense;
            $expense->expense_type_id = $request->expense_type_id;
            $expense->title = $request->title;
            $expense->description = $request->description;
            $expense->amount = $request->amount;
            $expense->expense_date = $request->expense_date;
            $expense->receipt = $file_name;
            $expense->status = $request->input('status', 'pending');
            $expense->save();

            DB::commit();
            Toastr::success('Expense added successfully :)', 'Success');
            return redirect()->route('form.expense.list');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to add expense :)', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function editExpense($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $expenseTypes = ExpenseType::where('status', 'active')->get();
            return view('expenses.expense_edit', compact('expense', 'expenseTypes'));
        } catch (\Exception $e) {
            Toastr::error('Failed to load expense edit page.', 'Error');
            return redirect()->back();
        }
    }

    public function updateExpense(Request $request, $id)
    {
        $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'receipt' => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:2048',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        DB::beginTransaction();
        try {
            $expense = Expense::findOrFail($id);

            if ($request->hasFile('receipt')) {
                if ($expense->receipt && file_exists(public_path('/assets/upload/' . $expense->receipt))) {
                    @unlink(public_path('/assets/upload/' . $expense->receipt));
                }
                $receipt = $request->file('receipt');
                $file_name = rand() . '.' . $receipt->getClientOriginalExtension();
                $receipt->move(public_path('/assets/upload/'), $file_name);
            } else {
                $file_name = $expense->receipt;
            }

            $expense->update([
                'expense_type_id' => $request->expense_type_id,
                'title' => $request->title,
                'description' => $request->description,
                'amount' => $request->amount,
                'expense_date' => $request->expense_date,
                'receipt' => $file_name,
                'status' => $request->input('status', 'pending'),
            ]);

            DB::commit();
            Toastr::success('Expense updated successfully :)', 'Success');
            return redirect()->route('form.expense.list');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to update expense :)', 'Error');
            return redirect()->back()->withInput();
        }
    }

    public function deleteExpense($id)
    {
        DB::beginTransaction();
        try {
            $expense = Expense::findOrFail($id);
            if ($expense->receipt && file_exists(public_path('/assets/upload/' . $expense->receipt))) {
                @unlink(public_path('/assets/upload/' . $expense->receipt));
            }
            $expense->delete();
            DB::commit();
            Toastr::success('Expense deleted successfully :)', 'Success');
            return redirect()->route('form.expense.list');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to delete expense :)', 'Error');
            return redirect()->back();
        }
    }
}