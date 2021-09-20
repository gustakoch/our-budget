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

    public function getExpensesByCreditCardMonthYear($creditCard, $month, $year) {
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
            AND e.year = ?
            ORDER BY e.budgeted_amount - e.realized_amount = 0 asc
        ', [session('user')['id'], $creditCard, $month, $year]);

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
                        'reason_cancelled' => $reasonCancelled,
                        'realized_amount' => 0
                    ]);
                $totalCancelled++;
            }
        }

        return [
            'ok' => true,
            'msg' => "$totalCancelled parcelas canceladas com sucesso"
        ];
    }

    public function getExpensesForPay($invoice, $month, $year)
    {
        $expenses = DB::select("
            SELECT *
            FROM expenses
            WHERE invoice = ?
            AND user_id = ?
            AND month = ?
            AND year = ?
            AND cancelled = 0
        ", [$invoice, session('user')['id'], $month, $year]);

        return $expenses;
    }

    public function expensesTotalSum($month, $year)
    {
        $expensesSum = DB::selectOne('
            SELECT SUM(budgeted_amount) expenses_sum
            FROM expenses
            WHERE user_id = ?
            AND cancelled = 0
            AND month = ?
            AND year = ?
        ', [session('user')['id'], $month, $year]);

        return $expensesSum->expenses_sum;
    }

    private function getColorBrightness($color)
    {
        $red = hexdec(substr($color, 1, 2));
        $green = hexdec(substr($color, 3, 2));
        $blue = hexdec(substr($color, 5, 2));

        return (($red * 299) + ($green * 587) + ($blue * 114)) / 1000;
    }

    public function totalAmountExpensesByCategories($month, $year)
    {
        $totalGeneral = $this->expensesTotalSum($_SESSION['month'], $_SESSION['year']);
        $expenses = DB::select('
            SELECT
                c.description category
                , sum(e.budgeted_amount) total
                , c.color
            FROM expenses e
                , categories c
            WHERE e.category = c.id
            AND user_id = ?
            AND cancelled = 0
            AND "month" = ?
            AND "year" = ?
            GROUP BY c.description, c.color
            ORDER BY total DESC
        ', [session('user')['id'], $month, $year]);

        foreach ($expenses as $expense) {
            $expense->percentage = number_format((($expense->total / $totalGeneral) * 100), 2);
            $expense->color_brightness = $this->getColorBrightness($expense->color);
        }

        return $expenses;
    }
}
