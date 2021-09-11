<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\ExpenseInstallmentsModel;
use App\Models\ExpenseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
    private $expenseModel;
    private $categoryModel;

    public function __construct(ExpenseModel $expenseModel, CategoryModel $categoryModel)
    {
        $this->expenseModel = $expenseModel;
        $this->categoryModel = $categoryModel;
    }

    public function store()
    {
        $data = request()->all();

        session_start();
        $month = $_SESSION['month'];
        $year = $_SESSION['year'];

        $repeatNextMonths = isset($data['repeat_next_months_expense']) ? 1 : 0;
        $period = isset($data['period_expense']) ? $data['period_expense'] : 0;
        $data['credit_card'] = isset($data['credit_card']) ? $data['credit_card'] : null;

        if ($repeatNextMonths == '1') {
            for ($i = $month; $i <= 12; $i++) {
                ExpenseModel::create([
                    'description' => mb_convert_case($data['description_expense'], MB_CASE_TITLE, "UTF-8"),
                    'category' => (int) $data['category_expense'],
                    'period' => $period,
                    'budgeted_amount' => $data['budgeted_amount_expense'],
                    'repeat_next_months' => $repeatNextMonths,
                    'month' => $i,
                    'year' => $year,
                    'user_id' => session('user')['id'],
                    'credit_card' => $data['credit_card']
                ]);
            }
        } else {
            $installmentsExpense = (int) $data['installments_expense'];

            if ($installmentsExpense > 1) {
                $uniqid = uniqid();

                for ($i = 1; $i <= $installmentsExpense; $i++) {
                    $expense = ExpenseModel::create([
                        'description' => mb_convert_case($data['description_expense'], MB_CASE_TITLE, "UTF-8"),
                        'category' => (int) $data['category_expense'],
                        'budgeted_amount' => $data['budgeted_amount_expense'],
                        'period' => $period,
                        'installments' => $data['installments_expense'],
                        'installment' => $i,
                        'month' => $month++,
                        'year' => $year,
                        'user_id' => session('user')['id'],
                        'credit_card' => $data['credit_card']
                    ]);

                    ExpenseInstallmentsModel::create([
                        'expense' => $expense->id,
                        'hash_installment' => $uniqid
                    ]);
                }

                return response()->json([
                    'ok' => true,
                    'message' => 'Despesa cadastrada com sucesso!',
                    'period' => $period
                ]);
            }

            ExpenseModel::create([
                'description' => mb_convert_case($data['description_expense'], MB_CASE_TITLE, "UTF-8"),
                'category' => (int) $data['category_expense'],
                'budgeted_amount' => $data['budgeted_amount_expense'],
                'period' => $period,
                'repeat_next_months' => $repeatNextMonths,
                'month' => $month,
                'year' => $year,
                'user_id' => session('user')['id'],
                'credit_card' => $data['credit_card']
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Despesa cadastrada com sucesso!',
            'period' => $period
        ]);
    }

    public function destroy($id)
    {
        $expense = ExpenseModel::find($id);
        $expense->delete();

        return response()->json([
            'ok' => true,
            'msg' => 'Despesa removida com sucesso!',
            'period' => (int) $expense->period
        ]);
    }

    public function revert($id)
    {
        $expense = ExpenseModel::find($id);

        ExpenseModel::where('id', $expense['id'])
            ->where('user_id', session('user')['id'])
            ->update([
                'cancelled' => 0,
                'reason_cancelled' => null
            ]);

        return response()->json([
            'ok' => true,
            'msg' => 'Parcela revertida com sucesso!',
        ]);
    }

    public function cancelOneInstallment()
    {
        $data = request()->all();

        ExpenseModel::where('id', $data['id_expense'])
            ->update([
                'cancelled' => 1,
                'reason_cancelled' => $data['reason_cancelled']
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'Despesa atualizada com sucesso'
        ]);
    }

    public function cancelAllInstallments()
    {
        $data = request()->all();

        $cancelInstallments = $this->expenseModel->cancelExpenses(
            $data['id_expense'],
            $data['reason_cancelled_all']
        );

        if ($cancelInstallments['ok']) {
            return response()->json([
                'ok' => true,
                'message' => $cancelInstallments['msg']
            ]);
        } else {
            return response()->json([
                'ok' => false,
                'message' => 'Erro interno ao cancelar parcelas. Verifique com o suporte'
            ]);
        }
    }

    public function show($id)
    {
        $expense = ExpenseModel::find($id);
        $expense['category_active'] = $this->categoryModel->isCategoryActive($expense['category']);

        return response()->json($expense);
    }

    public function update()
    {
        $data = request()->all();
        $data['credit_card_edit'] = isset($data['credit_card_edit']) ? $data['credit_card_edit'] : null;

        if ($data['category_active'] == 1) {
            ExpenseModel::where('id', $data['id_expense'])
                ->update([
                    'description' => mb_convert_case($data['description_expense_edit'], MB_CASE_TITLE, "UTF-8"),
                    'category' => $data['category_expense_edit'],
                    'period' => $data['period_expense_edit'],
                    'budgeted_amount' => $data['budgeted_amount_expense_edit'],
                    'realized_amount' => $data['realized_amount_expense_edit'],
                    'credit_card' => $data['credit_card_edit']
                ]);
        } else {
            ExpenseModel::where('id', $data['id_expense'])
                ->update([
                    'description' => mb_convert_case($data['description_expense_edit'], MB_CASE_TITLE, "UTF-8"),
                    'period' => $data['period_expense_edit'],
                    'budgeted_amount' => $data['budgeted_amount_expense_edit'],
                    'realized_amount' => $data['realized_amount_expense_edit'],
                    'credit_card' => $data['credit_card_edit']
                ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Despesa atualizada com sucesso'
        ]);
    }
}
