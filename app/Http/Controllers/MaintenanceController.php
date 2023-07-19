<?php

namespace App\Http\Controllers;

use App\Models\AppConfigModel;
use App\Models\CategoryModel;
use App\Models\CreditCardModel;
use App\Models\ExpenseModel;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    private $expenseModel;
    private $categoryModel;
    private $appConfigModel;
    private $creditCardModel;

    public function __construct(ExpenseModel $expenseModel, CategoryModel $categoryModel, AppConfigModel $appConfigModel, CreditCardModel $creditCardModel)
    {
        $this->expenseModel = $expenseModel;
        $this->categoryModel = $categoryModel;
        $this->appConfigModel = $appConfigModel;
        $this->creditCardModel = $creditCardModel;
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
        $expenses = $this->expenseModel->getFilteredExpenses($params);

        return response()->json(['ok' => true, 'expenses' => $expenses]);
    }
}
