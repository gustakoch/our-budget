<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
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

        $queryString = $_SERVER['QUERY_STRING'];
        $queryValues = explode('=', $queryString);

        if ($queryValues[0] == 'month') {
            $queryMonth = $queryValues[1];
        }

        $month = $queryMonth ? $queryMonth : $currentMonth;

        session_start();
        $_SESSION['month'] = $month;
        $_SESSION['year'] = $currentYear;

        $months = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
        ];

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

        $typeExpenses = DB::table('users')
            ->where('id', session('user')['id'])
            ->select('gradle_format')
            ->first();

        $userExpenses = DB::table('expenses')
            ->join('categories', 'expenses.category', 'categories.id')
            ->where('expenses.user_id', '=', session('user')['id'])
            ->select('expenses.*', 'categories.description as category_description')
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

        return view('dashboard', [
            'month' => $month,
            'months' => $months,
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
            'type_expenses' => isset($typeExpenses->gradle_format) ? $typeExpenses->gradle_format : '30'
        ]);
    }

    public function config()
    {
        if ($this->user->isFirstAccess()) {
            return redirect()->route('initial');
        }

        return view('config');
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
                'gradle_format' => $data['gradle_format']
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
