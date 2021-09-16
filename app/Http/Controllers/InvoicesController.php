<?php

namespace App\Http\Controllers;

use App\Models\CreditCardInvoiceModel;
use App\Models\ExpenseModel;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    private $expenseModel;

    public function __construct(ExpenseModel $expenseModel)
    {
        $this->expenseModel = $expenseModel;
        session_start();
    }

    public function pay()
    {
        $data = request()->all();
        $expenses = $this->expenseModel->getExpensesForPay($data['invoiceId'], $_SESSION['month'], $_SESSION['year']);

        foreach ($expenses as $expense) {
            ExpenseModel::where('id', $expense->id)
                ->update([
                    'realized_amount' => $expense->budgeted_amount
                ]);
        }

        CreditCardInvoiceModel::where('id', $data['invoiceId'])
            ->update([
                'payment' => 1,
                'pay_day' => date('Y-m-d H:i:s')
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'A fatura foi paga com sucesso'
        ]);
    }
}
