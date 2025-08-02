<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_type_id',
        'title',
        'description',
        'amount',
        'expense_date',
        'receipt',
        'status',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the expense type that owns this expense
     */
    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }
}