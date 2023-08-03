<?php

namespace App\Http\Controllers;

use App\Models\AppConfigModel;
use App\Models\CategoryModel;
use App\Models\CreditCardModel;
use App\Models\ExpenseModel;
use App\Models\ExpensesHistoryEntriesModel;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    private $expenseModel;
    private $categoryModel;
    private $appConfigModel;
    private $creditCardModel;
    private $expensesHistoryEntriesModel;

    public function __construct(
        ExpenseModel $expenseModel,
        CategoryModel $categoryModel,
        AppConfigModel $appConfigModel,
        CreditCardModel $creditCardModel,
        ExpensesHistoryEntriesModel $expensesHistoryEntriesModel
    ) {
        $this->expenseModel = $expenseModel;
        $this->categoryModel = $categoryModel;
        $this->appConfigModel = $appConfigModel;
        $this->creditCardModel = $creditCardModel;
        $this->expensesHistoryEntriesModel = $expensesHistoryEntriesModel;
    }

    public function index()
    {
        $expenseCategories = $this->categoryModel->activeExpenseCategories();
        $configNumberOfInstallments = $this->appConfigModel->getNumberOfInstallments();
        $userCards = $this->creditCardModel->getCardsWithFlags();

        return view('maintenance.index', [
            'expenseCategories' => $expenseCategories,
            'typeExpenses' => '30',
            'installments' => $configNumberOfInstallments,
            'cards' => $userCards
        ]);
    }

    public function search()
    {
        $params = request()->all();
        if (!$params['id_expense']) {
            return response()->json(['ok' => false, 'message' => 'É necessário informar uma saída.']);
        }
        $expenses = $this->expenseModel->getFilteredExpenses($params['id_expense']);

        return response()->json(['ok' => true, 'expenses' => $expenses]);
    }

    public function update()
    {
        $params = request()->all();
        if ($params['amount_expense']) {
            ExpensesHistoryEntriesModel::create([
                'expense_id' => $params['id_expense'],
                'value' => $params['amount_expense'],
                'user_id' => session('user')['id']
            ]);
        }
        $expense = ExpenseModel::find($params['id_expense']);
        $realizedAmount =
            $params['amount_expense']
            ? floatval($expense->realized_amount) + floatval($params['amount_expense'])
            : floatval($expense->realized_amount);

        $totalExpense = $this->expensesHistoryEntriesModel->getTotalExpenseSum($params['id_expense']);
        ExpenseModel::where('id', $params['id_expense'])->update([
            'description' => mb_convert_case($params['description_expense'], MB_CASE_TITLE, "UTF-8"),
            'category' => $params['category_expense'],
            'budgeted_amount' => $expense->budgeted_amount,
            'realized_amount' => floatval($realizedAmount),
        ]);

        return response()->json(['ok' => true, 'message' => 'Saída ajustada com sucesso.']);
    }
}
