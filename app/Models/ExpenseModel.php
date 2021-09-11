<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ExpenseModel extends Model
{
    use HasFactory;
    protected $table = "expenses";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getAccumulatedTotals($month, $period)
    {
        $accumulatedTotals = DB::select('
                SELECT
                    coalesce(sum(budgeted_amount), 0) budgeted_accumulated
                    , coalesce(sum (realized_amount), 0) realized_accumulated
                    , coalesce(sum (budgeted_amount - realized_amount), 0) pending_accumulated
                FROM expenses e
                WHERE e.month = ?
                AND e.period = ?
                AND e.cancelled = 0
        ', [$month, $period]);

        return $accumulatedTotals;
    }

    public function getExpensesByCreditCard($creditCard, $month) {
        $expenses = DB::select('
            SELECT
                e.*
                , c.description category_description
                , cc.description credit_card_description
                , cc.invoice_day
            FROM expenses e
                , categories c
                , credit_cards cc
            WHERE e.category = c.id
            AND cc.id = e.credit_card
            AND e.user_id = ?
            AND credit_card = ?
            AND e.month = ?
            ORDER BY e.budgeted_amount - e.realized_amount = 0 asc
        ', [session('user')['id'], $creditCard, $month]);

        return $expenses;
    }

    public function cancelExpenses($id, $reasonCancelled)
    {
        $currentInstallment = ExpenseInstallmentsModel::where('expense', $id)->first();
        $currentAndFollowingInstallments = ExpenseInstallmentsModel::where(
            'hash_installment', $currentInstallment->hash_installment
        )->get();

        $totalCancelled = 0;

        foreach ($currentAndFollowingInstallments as $item) {
            if ($item->expense >= $id) {
                ExpenseModel::where('id', $item->expense)
                    ->update([
                        'cancelled' => 1,
                        'reason_cancelled' => $reasonCancelled
                    ]);
                $totalCancelled++;
            }
        }

        return [
            'ok' => true,
            'msg' => "$totalCancelled parcelas canceladas com sucesso"
        ];
    }
}
