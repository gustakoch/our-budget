<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BillingModel extends Model
{
    use HasFactory;
    protected $table = "submitted_expenses";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getSubmittedExpenses(array $status)
    {
        $expenses = DB::table('submitted_expenses as se')
            ->select(
                'se.id',
                'se.description',
                'm.description as month',
                'se.installments',
                'se.value',
                'se.status',
                'u.name as to_user',
                'c.description as category',
                'se.refuse_details',
                DB::raw("to_char(se.created_at, 'dd/mm/yyyy hh24:mi') as created_at"))
            ->join('categories as c', 'se.category', 'c.id')
            ->join('users as u', 'se.to_user', 'u.id')
            ->join('months as m', 'se.month', 'm.id')
            ->where('se.from_user', session('user')['id'])
            ->whereIn('se.status', $status)
            ->get();

        return $expenses;
    }

    public function getReceivedExpenses()
    {
        $expenses = DB::table('submitted_expenses as se')
            ->select(
                'se.id',
                'se.description',
                'm.description as month_name',
                'se.installments',
                'se.value',
                'se.month',
                'se.status',
                'u.name as from_user',
                'c.description as category',
                DB::raw("to_char(se.created_at, 'dd/mm/yyyy hh24:mi') as received_at"))
            ->join('categories as c', 'se.category', 'c.id')
            ->join('users as u', 'se.from_user', 'u.id')
            ->join('months as m', 'se.month', 'm.id')
            ->where('se.to_user', session('user')['id'])
            ->where('se.month', $_SESSION['month'])
            ->where('se.status', 0)
            ->get();

        return $expenses;
    }

    public function getUserSentExpense($id)
    {
        if (!$id) {
            return '';
        }

        $submittedExpense = BillingModel::find($id);
        $user = User::find($submittedExpense->from_user);

        return $user->name;
    }
}
