<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExpenseInstallmentsModel extends Model
{
    use HasFactory;
    protected $table = "expense_installments";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getInstallmentsToExtend($expenseId)
    {
        $expenses = DB::select("
            SELECT
                ei.expense
                , e.month
	            , e.year
            FROM expense_installments ei
                , expenses e
            WHERE e.id = ei.expense
            AND (e.budgeted_amount - e.realized_amount) != 0
            AND hash_installment = (SELECT hash_installment
                FROM expense_installments
                WHERE expense = ?
                GROUP BY hash_installment)
        ", [$expenseId]);

        return $expenses;
    }
}
