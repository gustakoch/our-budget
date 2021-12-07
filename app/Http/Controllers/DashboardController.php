<?php

namespace App\Http\Controllers;

use App\Models\AppConfigModel;
use App\Models\Configuration;
use App\Models\CreditCardInvoiceModel;
use App\Models\CreditCardModel;
use App\Models\ExpenseModel;
use App\Models\RecipeModel;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;
use stdClass;

class DashboardController extends Controller
{
    private $user;
    private $creditCardModel;
    private $recipeModel;
    private $expenseModel;
    private $creditCardInvoiceModel;
    private $appConfigModel;

    public function __construct(
        User $user,
        CreditCardModel $creditCardModel,
        RecipeModel $recipeModel,
        ExpenseModel $expenseModel,
        CreditCardInvoiceModel $creditCardInvoiceModel,
        AppConfigModel $appConfigModel
    ) {
        $this->user = $user;
        $this->creditCardModel = $creditCardModel;
        $this->recipeModel = $recipeModel;
        $this->expenseModel = $expenseModel;
        $this->creditCardInvoiceModel = $creditCardInvoiceModel;
        $this->appConfigModel = $appConfigModel;
    }

    public function index()
    {
        if ($this->user->isFirstAccess()) {
            return redirect()->route('initial');
        }

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

        $months = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

        session_start();
        $_SESSION['month'] = $month;
        $_SESSION['monthName'] = $months[$month -1];
        $_SESSION['year'] = $year;

        $years = $this->expenseModel->getExpensesYears();
        if (!$years) {
            $years[] = new stdClass();
            $years[0]->year = $currentYear;
        }

        $activeRecipeCategories = DB::table('categories')
            ->where('belongs_to', 1)
            ->where('active', 1)
            ->orderBy('description', 'asc')
            ->get();
        $activeExpenseCategories = DB::table('categories')
            ->where('belongs_to', 2)
            ->where('active', 1)
            ->orderBy('description', 'asc')
            ->get();
        $allRecipeCategories = DB::table('categories')
            ->where('belongs_to', 1)
            ->orderBy('description', 'asc')
            ->get();
        $allExpenseCategories = DB::table('categories')
            ->where('belongs_to', 2)
            ->orderBy('description', 'asc')
            ->get();

        $userRecipes = DB::table('recipes')
            ->join('categories', 'recipes.category', 'categories.id')
            ->where('recipes.user_id', '=', session('user')['id'])
            ->select('recipes.*', 'categories.description as category_description')
            ->get();

        $currentRecipes = [];
        $amount = 0;

        foreach ($userRecipes as $recipe) {
            if ($recipe->month == $month) {
                array_push($currentRecipes, $recipe);
                $amount += (float) $recipe->budgeted_amount;
            }
        }

        $userExpenses = DB::table('expenses')
            ->join('categories', 'expenses.category', 'categories.id')
            ->where('expenses.user_id', '=', session('user')['id'])
            ->where('expenses.credit_card', '=', null)
            ->where('expenses.year', '=', $year)
            ->select('expenses.*', 'categories.description as category_description')
            ->orderBy(DB::raw('expenses.budgeted_amount - expenses.realized_amount = 0'), 'asc')
            ->orderBy('expenses.description', 'asc')
            ->get();

        $expensesPeriod0 = [];
        $expensesPeriod1 = [];
        $expensesPeriod2 = [];
        $amountExpensesPeriod0 = 0;
        $amountExpensesPeriod1 = 0;
        $amountExpensesPeriod2 = 0;
        $realizedExpensesPeriod0 = 0;
        $realizedExpensesPeriod1 = 0;
        $realizedExpensesPeriod2 = 0;
        $pendingExpensesPeriod0 = 0;
        $pendingExpensesPeriod1 = 0;
        $pendingExpensesPeriod2 = 0;

        foreach ($userExpenses as $expense) {
            if ($expense->month == $month) {
                if ($expense->period == 0) {
                    array_push($expensesPeriod0, $expense);

                    if ($expense->cancelled == 0) {
                        $amountExpensesPeriod0 += (float) $expense->budgeted_amount;
                        $realizedExpensesPeriod0 += (float) $expense->realized_amount;
                        $pendingExpensesPeriod0 += (float) $expense->budgeted_amount - $expense->realized_amount;
                    }
                }
                elseif ($expense->period == 1) {
                    array_push($expensesPeriod1, $expense);

                    if ($expense->cancelled == 0) {
                        $amountExpensesPeriod1 += (float) $expense->budgeted_amount;
                        $realizedExpensesPeriod1 += (float) $expense->realized_amount;
                        $pendingExpensesPeriod1 += (float) $expense->budgeted_amount - $expense->realized_amount;
                    }
                }
                else {
                    array_push($expensesPeriod2, $expense);

                    if ($expense->cancelled == 0) {
                        $amountExpensesPeriod2 += (float) $expense->budgeted_amount;
                        $realizedExpensesPeriod2 += (float) $expense->realized_amount;
                        $pendingExpensesPeriod2 += (float) $expense->budgeted_amount - $expense->realized_amount;
                    }
                }
            }
        }

        $cards = $this->creditCardModel->getCardsWithFlags();
        $allCreditCardExpenses = [];

        foreach ($cards as $card) {
            $cardExpenses = $this->expenseModel->getExpensesByCreditCardMonthYear(
                $card->id,
                $_SESSION['month'],
                $_SESSION['year']
            );

            if (!$cardExpenses) {
                continue;
            }

            $invoice = $this->creditCardInvoiceModel->getInvoiceByCreditCardAndMonthAndYear(
                $card->id,
                $_SESSION['month'],
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

            $allCreditCardExpenses[] = $expenseObject;
        }

        // Data for the resume section...
        $resumeTotal = [];
        $resumeTotal['recipes'] = $this->recipeModel->recipesTotalSum($_SESSION['month'], $_SESSION['year']);
        $resumeTotal['expenses'] = $this->expenseModel->expensesTotalSum($_SESSION['month'], $_SESSION['year']);
        $resumeTotal['diff'] = floatval($resumeTotal['recipes']) - floatval($resumeTotal['expenses']);

        $expensesByCategories = $this->expenseModel->totalAmountExpensesByCategories($_SESSION['month'], $_SESSION['year']);
        $configNumberOfInstallments = $this->appConfigModel->getNumberOfInstallments();

        return view('dashboard', [
            'month' => $month,
            'year' => $year,
            'months' => $months,
            'years' => $years,
            'activeRecipeCategories' => $activeRecipeCategories,
            'activeExpenseCategories' => $activeExpenseCategories,
            'allRecipeCategories' => $allRecipeCategories,
            'allExpenseCategories' => $allExpenseCategories,
            'recipes' => $currentRecipes,
            'expenses0' => $expensesPeriod0,
            'expenses1' => $expensesPeriod1,
            'expenses2' => $expensesPeriod2,
            'budgeted_amount' => $amount,
            'budgeted_amount_expenses0' => $amountExpensesPeriod0,
            'realized_amount_expenses0' => $realizedExpensesPeriod0,
            'pending_amount_expenses0' => $pendingExpensesPeriod0,
            'budgeted_amount_expenses1' => $amountExpensesPeriod1,
            'realized_amount_expenses1' => $realizedExpensesPeriod1,
            'pending_amount_expenses1' => $pendingExpensesPeriod1,
            'budgeted_amount_expenses2' => $amountExpensesPeriod2,
            'realized_amount_expenses2' => $realizedExpensesPeriod2,
            'pending_amount_expenses2' => $pendingExpensesPeriod2,
            'type_expenses' => '30',
            'cards' => $cards,
            'creditCardExpenses' => $allCreditCardExpenses,
            'resumeData' => [
                'totals' => $resumeTotal,
                'expensesCategories' => $expensesByCategories
            ],
            'config' => [
                'installments' => $configNumberOfInstallments
            ]
        ]);
    }

    public function config()
    {
        if ($this->user->isFirstAccess()) {
            return redirect()->route('initial');
        }

        $configs = $this->appConfigModel->getAllConfigs();

        return view('config', [
            'configs' => $configs,
        ]);
    }

    public function firstAccess()
    {
        $data = request()->all();

        User::where('id', session('user')['id'])
            ->update([
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

        return response()->json([
            'ok' => true,
            'message' => 'Processo validado com sucesso!',
        ]);
    }

    public function initial()
    {
        if (!$this->user->isFirstAccess()) {
            return redirect()->route('dashboard');
        }

        return view('initial');
    }

    public function appUrl()
    {
        return response()->json([
            'appUrl' => $_ENV['APP_URL']
        ]);
    }
}
