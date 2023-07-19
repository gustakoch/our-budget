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

    public function getExpenseByid($id)
    {
        $expense = DB::table('expenses')
            ->select(
                '*',
                DB::raw("to_char(created_at, 'dd/mm/yyyy hh24:mi:ss') as created_at"),
                DB::raw("to_char(updated_at, 'dd/mm/yyyy hh24:mi:ss') as updated_at"))
            ->where('id', $id)
            ->first();

        return $expense;
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
                , e.description asc
            	, c.description asc
        ', [session('user')['id'], $creditCard, $month, $year]);

        return $expenses;
    }

    public function cancelExpenses($id, $reasonCancelled)
    {
        $currentInstallment = ExpenseInstallmentsModel::where('expense', $id)->first();
        $currentAndFollowingInstallments = ExpenseInstallmentsModel::where('hash_installment', $currentInstallment->hash_installment)->get();
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

        return ['ok' => true, 'msg' => "$totalCancelled parcelas canceladas com sucesso"];
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
        $totalGeneral = $this->expensesTotalSum($_SESSION['month']['id'], $_SESSION['year']);
        $expenses = DB::select("
            SELECT c.description category
                , sum(e.budgeted_amount) total
                , c.color
              FROM expenses e
                , categories c
             WHERE e.category = c.id
               AND user_id = ?
               AND cancelled = 0
               AND month = ?
               AND year = ?
          GROUP BY c.description, c.color
          ORDER BY total DESC", [session('user')['id'], $month, $year]
        );
        foreach ($expenses as $expense) {
            $expense->percentage = number_format((($expense->total / $totalGeneral) * 100), 2);
            $expense->color_brightness = $this->getColorBrightness($expense->color);
        }

        return $expenses;
    }

    public function getExpensesYears()
    {
        $years = DB::select('
            SELECT distinct year
            FROM expenses
            WHERE user_id = ?
            ORDER by year
        ', [session('user')['id']]);

        return $years;
    }

    public function getDistinctYears()
    {
        $years = DB::select("SELECT distinct year FROM expenses ORDER BY year");

        return $years;
    }

    public function getExpensesReport(
        $category,
        $startMonth,
        $endMonth,
        $startYear,
        $endYear,
        $users
    ) {
        $expenses = DB::select("
            SELECT
                e.description expense_name
                , c.description category_name
                , m.description month_name
                , e.year
                , e.budgeted_amount
                , e.realized_amount
                , (e.budgeted_amount - e.realized_amount) pending_amount
                , CASE WHEN
                    e.installments > 1 THEN
                    e.installment || ' de ' || e.installments
                  ELSE '-'
                END installment
                , u.name user_name
            FROM expenses e
                , categories c
                , users u
                , months m
            WHERE category = ?
            AND c.id = e.category
            AND u.id = e.user_id
            AND m.id = e.month
            AND e.month BETWEEN ? AND ?
            AND e.year BETWEEN ? AND ?
            AND e.user_id in (".$users.")
            ORDER BY e.month
                , e.description
        ", [$category, $startMonth, $endMonth, $startYear, $endYear]);

        return $expenses;
    }

    public function userExpenses($year, $month)
    {
        $expenses = DB::table('expenses')
            ->join('categories', 'expenses.category', 'categories.id')
            ->where('expenses.user_id', '=', session('user')['id'])
            ->where('expenses.credit_card', '=', null)
            ->where('expenses.year', '=', $year)
            ->where('expenses.month', '=', $month)
            ->select('expenses.*', 'categories.description as category_description')
            ->orderBy(DB::raw('expenses.budgeted_amount - expenses.realized_amount = 0'), 'asc')
            ->orderBy('expenses.description', 'asc')
            ->orderBy('categories.description', 'asc')
            ->get();

        return $expenses;
    }

    public function getFilteredExpenses($id)
    {
        $expenses = DB::table('expenses e')
            ->select('e.*', 'c.description as category_description, m.description as month_description, u.nickname as user_name')
            ->select(DB::raw('(e.budgeted_amount - e.realized_amount) as pending_amount'))
            ->join('categories c', 'e.category', 'c.id')
            ->join('months m', 'e.month', 'm.id')
            ->join('users u', 'e.user_id', 'u.id')
            ->where('e.id', '=', $id)
            ->get();

        return $expenses;

        $expenses2 = DB::select("
            SELECT e.id
                , e.description
                , e.category
                , e.installments
                , e.installment
                , e.month
                , e.year
                , e.budgeted_amount
                , e.realized_amount
                , (e.budgeted_amount - e.realized_amount) pending_amount
                , c.description as category_description
                , m.description as month_description
                , u.nickname as user_name
            FROM expenses e
                , categories c
                , months m
                , users u
            WHERE e.category = c.id
            AND e.month = m.id
            AND e.user_id = u.id
            AND e.id = ?", [$id]);

        return $expenses;
    }
}
