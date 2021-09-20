<?php

namespace App\Http\Controllers;

use App\Models\ExpenseModel;

class ChartController extends Controller
{
    private $expenseModel;

    public function __construct(ExpenseModel $expenseModel)
    {
        $this->expenseModel = $expenseModel;
        session_start();
    }

    public function index()
    {
        $expensesByCategories = $this->expenseModel->totalAmountExpensesByCategories(
            $_SESSION['month'],
            $_SESSION['year']
        );

        return response()->json($expensesByCategories);
    }
}
