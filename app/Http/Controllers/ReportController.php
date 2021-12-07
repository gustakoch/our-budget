<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use App\Models\ExpenseModel;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private $categoryModel;
    private $expenseModel;
    private $userModel;

    public function __construct(
        CategoryModel $categoryModel,
        ExpenseModel $expenseModel,
        User $userModel
    ) {
        $this->categoryModel = $categoryModel;
        $this->expenseModel = $expenseModel;
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

        $users = implode(',', $data['user']);
        $order = [];

        foreach ($data['category'] as $category) {
            $expenses = $this->expenseModel->getExpensesReport(
                $category,
                $data['start_month'],
                $data['end_month'],
                $data['start_year'],
                $data['end_year'],
                $users
            );

            $categoryModel = CategoryModel::where('id', $category)
                ->select('description')
                ->first();

            // Just for order the categories who has expenses first then the others
            if (count($expenses) > 0) {
                $order['have'][] = [
                    'category' => $categoryModel->description,
                    'expenses' => $expenses
                ];
            } else {
                $order['nothing'][] = [
                    'category' => $categoryModel->description,
                    'expenses' => $expenses
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
}
