<?php

namespace App\Http\Controllers;

use App\Models\AppConfigModel;
use App\Models\BillingModel;
use App\Models\CategoryModel;
use App\Models\CreditCardInvoiceModel;
use App\Models\CreditCardModel;
use App\Models\ExpenseModel;
use App\Models\MonthModel;
use App\Models\NotificationModel;
use App\Models\RecipeModel;
use App\Models\User;
use DateTime;
use stdClass;

class DashboardController extends Controller
{
    private $user;
    private $creditCardModel;
    private $recipeModel;
    private $expenseModel;
    private $creditCardInvoiceModel;
    private $appConfigModel;
    private $billingModel;
    private $categoryModel;

    public function __construct(
        User $user,
        CreditCardModel $creditCardModel,
        RecipeModel $recipeModel,
        ExpenseModel $expenseModel,
        CreditCardInvoiceModel $creditCardInvoiceModel,
        AppConfigModel $appConfigModel,
        BillingModel $billingModel,
        CategoryModel $categoryModel
    ) {
        $this->user = $user;
        $this->creditCardModel = $creditCardModel;
        $this->recipeModel = $recipeModel;
        $this->expenseModel = $expenseModel;
        $this->creditCardInvoiceModel = $creditCardInvoiceModel;
        $this->appConfigModel = $appConfigModel;
        $this->billingModel = $billingModel;
        $this->categoryModel = $categoryModel;
    }

    public function index()
    {
        if ($this->user->isFirstAccess()) return redirect()->route('initial');
        $date = new DateTime();
        $currentMonth = intval($date->format('m'));
        $currentYear = intval($date->format('Y'));
        $queryMonth = 0;
        $queryYear = 0;
        $queryString = $_SERVER['QUERY_STRING'];
        $queryItems = explode('&', $queryString);
        if (isset($queryItems[0]) && isset($queryItems[1])) {
            $monthValues = explode('=', $queryItems[0]);
            $yearValues = explode('=', $queryItems[1]);
        }
        if (isset($monthValues[0]) && $monthValues[0] == 'month') {
            $queryMonth = $monthValues[1];
        }
        if (isset($yearValues[0]) && $yearValues[0] == 'year') {
            $queryYear = $yearValues[1];
        }
        $month = $queryMonth ? $queryMonth : $currentMonth;
        $year = $queryYear ? $queryYear : $currentYear;
        $months = MonthModel::all();
        session_start();
        $_SESSION['month'] = MonthModel::where('id', $month)->first();
        $_SESSION['year'] = $year;
        $years = $this->expenseModel->getExpensesYears();
        if (!$years) {
            $years[] = new stdClass();
            $years[0]->year = $currentYear;
        }
        $activeRecipeCategories = $this->categoryModel->activeRecipeCategories();
        $activeExpenseCategories = $this->categoryModel->activeExpenseCategories();
        $monthRecipes = $this->recipeModel->userRecipes($year, $month);
        $monthAmount = 0;
        foreach ($monthRecipes as $recipe) {
            $monthAmount += (float) $recipe->budgeted_amount;
        }
        $monthExpenses = $this->expenseModel->userExpenses($year, $month);
        $amountExpenses = 0;
        $realizedExpenses = 0;
        $pendingExpenses = 0;
        foreach ($monthExpenses as $expense) {
            $expense->to_user = $this->billingModel->getUserSentExpense($expense->submitted_expense_id);
            $expense->generate_receipt = $this->billingModel->isGeneratedReceipt($expense->submitted_expense_id);
            $expense->document = $this->billingModel->getDocument($expense->submitted_expense_id);
            if ($expense->cancelled == 0) {
                $amountExpenses += (float) $expense->budgeted_amount;
                $realizedExpenses += (float) $expense->realized_amount;
                $pendingExpenses += (float) $expense->budgeted_amount - $expense->realized_amount;
            }
        }
        $receivedExpenses = $this->billingModel->getReceivedExpenses();
        $userCards = $this->creditCardModel->getCardsWithFlags();
        $userActiveCards = $this->creditCardModel->getActiveCardsWithFlags();
        $creditCardExpenses = [];
        foreach ($userCards as $card) {
            $cardExpenses = $this->expenseModel->getExpensesByCreditCardMonthYear(
                $card->id,
                $_SESSION['month']['id'],
                $_SESSION['year']
            );
            if (!$cardExpenses) continue;
            $invoice = $this->creditCardInvoiceModel->getInvoiceByCreditCardAndMonthAndYear(
                $card->id,
                $_SESSION['month']['id'],
                $_SESSION['year']
            );
            if (!$invoice) {
                $invoice = new stdClass();
                $invoice->id = null;
                $invoice->payment = null;
                $invoice->pay_day = null;
            }
            $expenseObject = new stdClass();
            $expenseObject->credit_card = $cardExpenses[0]->credit_card;
            $expenseObject->credit_card_description = $cardExpenses[0]->credit_card_description;
            $expenseObject->invoice_day =
                str_pad($cardExpenses[0]->invoice_day, 2, "0", STR_PAD_LEFT)
                . '/'
                . str_pad($cardExpenses[0]->month, 2, "0", STR_PAD_LEFT)
                . '/'
                . $cardExpenses[0]->year;
            $expenseObject->invoice_id = $invoice->id;
            $expenseObject->invoice_payment = $invoice->payment;
            $expenseObject->pay_day = $invoice->pay_day;
            $expenseObject->total_budgeted_amount = 0;
            $expenseObject->total_realized_amount = 0;
            $expenseObject->total_pending_amount = 0;
            foreach ($cardExpenses as $cardExpense) {
                $expenseObject->periods[] = $cardExpense->period;
                if ($cardExpense->cancelled == 0) {
                    $expenseObject->total_budgeted_amount += $cardExpense->budgeted_amount;
                    $expenseObject->total_realized_amount += $cardExpense->realized_amount;
                    $expenseObject->total_pending_amount = ($expenseObject->total_budgeted_amount - $expenseObject->total_realized_amount);
                }
            }
            $expenseObject->expenses = $cardExpenses;
            $creditCardExpenses[] = $expenseObject;
        }
        $resumeTotal = [];
        $resumeTotal['recipes'] = $this->recipeModel->recipesTotalSum($_SESSION['month']['id'], $_SESSION['year']);
        $resumeTotal['expenses'] = $this->expenseModel->expensesTotalSum($_SESSION['month']['id'], $_SESSION['year']);
        $resumeTotal['diff'] = floatval($resumeTotal['recipes']) - floatval($resumeTotal['expenses']);
        $expensesByCategories = $this->expenseModel->totalAmountExpensesByCategories($_SESSION['month']['id'], $_SESSION['year']);
        $configNumberOfInstallments = $this->appConfigModel->getNumberOfInstallments();
        $notifications = NotificationModel::where('to_user', session('user')['id'])->limit(3)->orderBy('created_at', 'desc')->get();
        $numberNotifications = NotificationModel::where('to_user', session('user')['id'])->where('viewed', 0)->get()->count();

        return view('dashboard', [
            'month' => $month,
            'year' => $year,
            'months' => $months,
            'years' => $years,
            'activeRecipeCategories' => $activeRecipeCategories,
            'activeExpenseCategories' => $activeExpenseCategories,
            'recipes' => $monthRecipes,
            'expenses' => $monthExpenses,
            'receivedExpenses' => $receivedExpenses,
            'budgeted_amount' => $monthAmount,
            'budgeted_amount_expenses' => $amountExpenses,
            'realized_amount_expenses' => $realizedExpenses,
            'pending_amount_expenses' => $pendingExpenses,
            'type_expenses' => '30',
            'cards' => $userCards,
            'activeCards' => $userActiveCards,
            'creditCardExpenses' => $creditCardExpenses,
            'resumeData' => [
                'totals' => $resumeTotal,
                'expensesCategories' => $expensesByCategories
            ],
            'config' => [
                'installments' => $configNumberOfInstallments
            ],
            'notifications' => $notifications,
            'numberNotifications' => $numberNotifications
        ]);
    }

    public function config()
    {
        if ($this->user->isFirstAccess()) return redirect()->route('initial');
        $configs = $this->appConfigModel->getAllConfigs();

        return view('config', ['configs' => $configs]);
    }

    public function firstAccess()
    {
        $data = request()->all();
        User::where('id', session('user')['id'])->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'first_access' => 0,
        ]);
        $user = User::find(session('user')['id']);
        session()->put('user', [
            'id' => $user->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $user->role,
            'firstAccess' => 0
        ]);

        return response()->json(['ok' => true, 'message' => 'Processo validado com sucesso!']);
    }

    public function initial()
    {
        if (!$this->user->isFirstAccess()) return redirect()->route('dashboard');

        return view('initial');
    }

    public function appUrl()
    {
        return response()->json(['appUrl' => $_ENV['APP_URL']]);
    }
}
