<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExpensesHistoryEntriesModel extends Model
{
    use HasFactory;
    protected $table = "expenses_history_entries";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getTotalExpenseSum($idExpense)
    {
        $total = DB::selectOne("
            SELECT SUM(value)
            FROM expenses_history_entries
            WHERE expense_id = ?
            AND user_id = ?
            LIMIT 1
        ", [$idExpense, session('user')['id']]);

        return $total->sum ? floatval($total->sum) : 0;
    }

    public function getAllEntriesByExpense($idExpense)
    {
        $entries = DB::select("
            SELECT
                value
                , to_char(created_at, 'dd/mm/yyyy às hh24:mi') date
            FROM expenses_history_entries
            WHERE expense_id = ?
            ORDER BY created_at DESC
        ", [$idExpense]);

        return $entries;
    }

    public function verifyIsExpenseHasHistory($idExpense)
    {
        $hasHistory = DB::selectOne("
            SELECT count(*) qtde
            FROM expenses_history_entries
            WHERE expense_id = ?
        ", [$idExpense]);

        return $hasHistory->qtde;
    }
}
