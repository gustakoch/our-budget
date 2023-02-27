<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\ExpenseModel;
use App\Models\RecipeModel;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private $categoryModel;
    private $expenseModel;
    private $recipeModel;
    private $userModel;

    public function __construct(
        CategoryModel $categoryModel,
        ExpenseModel $expenseModel,
        RecipeModel $recipeModel,
        User $userModel
    ) {
        $this->categoryModel = $categoryModel;
        $this->expenseModel = $expenseModel;
        $this->recipeModel = $recipeModel;
        $this->userModel = $userModel;
    }

    public function expensesByCategory()
    {
        session_start();

        $categories = $this->categoryModel->getOnlyExpensesCategories();
        $years = $this->expenseModel->getDistinctYears();
        $users = $this->userModel->getAll();
        $months = array(
            '1' => 'Janeiro',
            '2' => 'Fevereiro',
            '3' => 'Março',
            '4' => 'Abril',
            '5' => 'Maio',
            '6' => 'Junho',
            '7' => 'Julho',
            '8' => 'Agosto',
            '9' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        );

        return view('reports.expenses-by-category', [
            'categories' => $categories,
            'months' => $months,
            'years' => $years,
            'users' => $users,
            'actualMonth' => $_SESSION['month'],
            'actualYear' => $_SESSION['year']
        ]);
    }

    public function search()
    {
        $data = request()->all();

        if (!isset($data['category']) || empty($data['category'])) {
            return response()->json([
                'ok' => false,
                'message' => 'Por favor, selecione uma categoria.'
            ]);
        }

        $order = [];
        $users = "";
        $searchType = $data['search-type'];

        if (!in_array(session('user')['role'], ['1', '2'])) {
            $users = session('user')['id'];
        } else {
            $users = implode(',', $data['user']);
        }

        foreach ($data['category'] as $category) {
            if ($searchType == '1') {
                // Entradas
                $results = $this->recipeModel->getRecipesReport(
                    $category,
                    $data['start_month'],
                    $data['end_month'],
                    $data['start_year'],
                    $data['end_year'],
                    $users
                );
            } elseif ($searchType == '2') {
                // Saídas
                $results = $this->expenseModel->getExpensesReport(
                    $category,
                    $data['start_month'],
                    $data['end_month'],
                    $data['start_year'],
                    $data['end_year'],
                    $users
                );
            }

            $categoryModel = CategoryModel::where('id', $category)
                ->select('description')
                ->first();

            // Just for order the categories who has expenses first then the others
            if (count($results) > 0) {
                $order['have'][] = [
                    'category' => $categoryModel->description,
                    'results' => $results
                ];
            } else {
                $order['nothing'][] = [
                    'category' => $categoryModel->description,
                    'results' => $results
                ];
            }
        }

        if (!isset($order['have'])) {
            $order['have'] = [];
        }

        if (!isset($order['nothing'])) {
            $order['nothing'] = [];
        }

        $report = array_merge($order['have'], $order['nothing']);

        return response()->json([
            'ok' => true,
            'data' => $report
        ]);
    }

    public function allRecipes()
    {
        session_start();

        $categories = $this->categoryModel->getOnlyRecipesCategories();
        $years = $this->expenseModel->getDistinctYears();
        $users = $this->userModel->getAll();
        $months = array(
            '1' => 'Janeiro',
            '2' => 'Fevereiro',
            '3' => 'Março',
            '4' => 'Abril',
            '5' => 'Maio',
            '6' => 'Junho',
            '7' => 'Julho',
            '8' => 'Agosto',
            '9' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        );

        return view('reports.recipes-all', [
            'categories' => $categories,
            'months' => $months,
            'years' => $years,
            'users' => $users,
            'actualMonth' => $_SESSION['month'],
            'actualYear' => $_SESSION['year']
        ]);
    }
}
