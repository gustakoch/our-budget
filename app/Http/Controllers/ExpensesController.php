<?php

namespace App\Http\Controllers;

use App\Models\BillingModel;
use App\Models\CategoryModel;
use App\Models\CreditCardInvoiceModel;
use App\Models\ExpenseInstallmentsModel;
use App\Models\ExpenseModel;
use App\Models\ExpensesHistoryEntriesModel;
use Illuminate\Http\Request;
use stdClass;

class ExpensesController extends Controller
{
    private $expenseModel;
    private $expenseInstallmentModel;
    private $expensesHistoryEntriesModel;
    private $categoryModel;
    private $creditCardInvoiceModel;
    private $month;
    private $year;

    public function __construct(
        ExpenseModel $expenseModel,
        ExpenseInstallmentsModel $expenseInstallmentModel,
        CategoryModel $categoryModel,
        CreditCardInvoiceModel $creditCardInvoiceModel,
        ExpensesHistoryEntriesModel $expensesHistoryEntriesModel
    ) {
        $this->expenseModel = $expenseModel;
        $this->categoryModel = $categoryModel;
        $this->creditCardInvoiceModel = $creditCardInvoiceModel;
        $this->expenseInstallmentModel = $expenseInstallmentModel;
        $this->expensesHistoryEntriesModel = $expensesHistoryEntriesModel;

        session_start();
        $this->month = $_SESSION['month']['id'];
        $this->year = $_SESSION['year'];
    }

    public function store()
    {
        $data = request()->all();

        $repeatNextMonths = isset($data['repeat_next_months_expense']) ? 1 : 0;
        $period = isset($data['period_expense']) ? $data['period_expense'] : 0;
        $expensePaid = isset($data['make_expense_paid']) ? $data['make_expense_paid'] : 0;
        $data['credit_card'] = isset($data['credit_card']) ? $data['credit_card'] : null;
        $installmentsExpense = intval($data['installments_expense']);
        $invoice = new stdClass();
        $invoice->id = null;

        if ($data['credit_card']) {
            if ($installmentsExpense > 1) {
                $uniqid = uniqid();

                for ($i = 1; $i <= $installmentsExpense; $i++) {
                    if ($this->month == 13) {
                        $this->month = 1;
                        $this->year++;
                    }

                    $invoice = $this->creditCardInvoiceModel->getInvoiceByCreditCardAndMonthAndYear(
                        $data['credit_card'],
                        $this->month,
                        $this->year
                    );

                    if ($invoice) {
                        CreditCardInvoiceModel::where('id', $invoice->id)
                            ->update([
                                'credit_card' => $data['credit_card'],
                                'payment' => 0,
                                'pay_day' => null
                            ]);

                        $expense = ExpenseModel::create([
                            'description' => mb_convert_case($data['description_expense'], MB_CASE_TITLE, "UTF-8"),
                            'category' => (int) $data['category_expense'],
                            'budgeted_amount' => $data['budgeted_amount_expense'],
                            'period' => $period,
                            'installments' => $installmentsExpense,
                            'installment' => $i,
                            'month' => $this->month,
                            'year' => $this->year,
                            'user_id' => session('user')['id'],
                            'credit_card' => $data['credit_card'],
                            'invoice' => $invoice->id
                        ]);

                        ExpenseInstallmentsModel::create([
                            'expense' => $expense->id,
                            'hash_installment' => $uniqid
                        ]);

                        $this->month++;
                    } else {
                        $invoice = CreditCardInvoiceModel::create([
                            'credit_card' => $data['credit_card'],
                            'user_id' => session('user')['id'],
                            'month' => $this->month,
                            'year' => $this->year
                        ]);

                        if ($expensePaid) {
                            $realizedAmount = $data['budgeted_amount_expense'];
                        } else {
                            $realizedAmount = 0;
                        }

                        $expense = ExpenseModel::create([
                            'description' => mb_convert_case($data['description_expense'], MB_CASE_TITLE, "UTF-8"),
                            'category' => (int) $data['category_expense'],
                            'budgeted_amount' => $data['budgeted_amount_expense'],
                            'realized_amount' => $realizedAmount,
                            'period' => $period,
                            'installments' => $data['installments_expense'],
                            'installment' => $i,
                            'month' => $this->month,
                            'year' => $this->year,
                            'user_id' => session('user')['id'],
                            'credit_card' => $data['credit_card'],
                            'invoice' => $invoice->id
                        ]);

                        ExpenseInstallmentsModel::create([
                            'expense' => $expense->id,
                            'hash_installment' => $uniqid
                        ]);

                        $this->month++;
                    }
                }
            } else {
                $invoice = $this->creditCardInvoiceModel->getInvoiceByCreditCardAndMonthAndYear($data['credit_card'], $this->month, $this->year);

                if ($invoice) {
                    CreditCardInvoiceModel::where('id', $invoice->id)
                        ->update([
                            'credit_card' => $data['credit_card'],
                            'payment' => 0,
                            'pay_day' => null
                        ]);

                    ExpenseModel::create([
                        'description' => mb_convert_case($data['description_expense'], MB_CASE_TITLE, "UTF-8"),
                        'category' => (int) $data['category_expense'],
                        'budgeted_amount' => $data['budgeted_amount_expense'],
                        'period' => $period,
                        'repeat_next_months' => $repeatNextMonths,
                        'month' => $this->month,
                        'year' => $this->year,
                        'user_id' => session('user')['id'],
                        'credit_card' => $data['credit_card'],
                        'invoice' => $invoice->id
                    ]);
                } else {
                    $invoice = CreditCardInvoiceModel::create([
                        'credit_card' => $data['credit_card'],
                        'user_id' => session('user')['id'],
                        'month' => $this->month,
                        'year' => $this->year
                    ]);

                    if ($expensePaid) {
                        $realizedAmount = $data['budgeted_amount_expense'];
                    } else {
                        $realizedAmount = 0;
                    }

                    ExpenseModel::create([
                        'description' => mb_convert_case($data['description_expense'], MB_CASE_TITLE, "UTF-8"),
                        'category' => (int) $data['category_expense'],
                        'budgeted_amount' => $data['budgeted_amount_expense'],
                        'realized_amount' => $realizedAmount,
                        'period' => $period,
                        'repeat_next_months' => $repeatNextMonths,
                        'month' => $this->month,
                        'year' => $this->year,
                        'user_id' => session('user')['id'],
                        'credit_card' => $data['credit_card'],
                        'invoice' => $invoice->id
                    ]);
                }
            }

            return response()->json([
                'ok' => true,
                'message' => 'Saída cadastrada com sucesso no cartão de crédito.'
            ]);
        }

        if ($repeatNextMonths == '1') {
            for ($i = $this->month; $i <= 12; $i++) {
                ExpenseModel::create([
                    'description' => mb_convert_case($data['description_expense'], MB_CASE_TITLE, "UTF-8"),
                    'category' => (int) $data['category_expense'],
                    'period' => $period,
                    'budgeted_amount' => $data['budgeted_amount_expense'],
                    'repeat_next_months' => $repeatNextMonths,
                    'month' => $i,
                    'year' => $this->year,
                    'user_id' => session('user')['id'],
                    'credit_card' => $data['credit_card'],
                    'invoice' => $invoice->id
                ]);
            }
        } else {
            if ($installmentsExpense > 1) {
                $uniqid = uniqid();

                for ($i = 1; $i <= $installmentsExpense; $i++) {
                    if ($this->month == 13) {
                        $this->month = 1;
                        $this->year++;
                    }

                    $expense = ExpenseModel::create([
                        'description' => mb_convert_case($data['description_expense'], MB_CASE_TITLE, "UTF-8"),
                        'category' => (int) $data['category_expense'],
                        'budgeted_amount' => $data['budgeted_amount_expense'],
                        'period' => $period,
                        'installments' => $data['installments_expense'],
                        'installment' => $i,
                        'month' => $this->month++,
                        'year' => $this->year,
                        'user_id' => session('user')['id'],
                        'credit_card' => $data['credit_card'],
                        'invoice' => $invoice->id
                    ]);

                    ExpenseInstallmentsModel::create([
                        'expense' => $expense->id,
                        'hash_installment' => $uniqid
                    ]);
                }

                return response()->json([
                    'ok' => true,
                    'message' => 'Saída cadastrada com sucesso!',
                    'period' => $period
                ]);
            }

            if ($expensePaid) {
                $realizedAmount = $data['budgeted_amount_expense'];
            } else {
                $realizedAmount = 0;
            }

            ExpenseModel::create([
                'description' => mb_convert_case($data['description_expense'], MB_CASE_TITLE, "UTF-8"),
                'category' => (int) $data['category_expense'],
                'budgeted_amount' => $data['budgeted_amount_expense'],
                'realized_amount' => $realizedAmount,
                'period' => $period,
                'repeat_next_months' => $repeatNextMonths,
                'month' => $this->month,
                'year' => $this->year,
                'user_id' => session('user')['id'],
                'credit_card' => $data['credit_card'],
                'invoice' => $invoice->id
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Saída cadastrada com sucesso!',
            'period' => $period
        ]);
    }

    public function destroy($id)
    {
        $expense = ExpenseModel::find($id);
        $expense->delete();

        if ($expense->submitted_expense_id) {
            $submittedExpense = BillingModel::find($expense->submitted_expense_id);
            $submittedExpense->delete();
        }

        return response()->json([
            'ok' => true,
            'msg' => 'Saída removida com sucesso!',
            'period' => (int) $expense->period
        ]);
    }

    public function destroyAll($id)
    {
        $installments = $this->expenseInstallmentModel->installmentsToRemove($id);

        foreach ($installments as $installment) {
            $expense = ExpenseModel::find($installment->expense);
            $expense->delete();
        }

        return response()->json([
            'ok' => true,
            'message' => 'O parcelamento foi excluído com sucesso.'
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
                'reason_cancelled' => $data['reason_cancelled'],
                'realized_amount' => 0
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'Saída atualizada com sucesso'
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
        $expense = $this->expenseModel->getExpenseByid($id);
        $expense->category_active = $this->categoryModel->isCategoryActive($expense->category);
        $expense->has_history = $this->expensesHistoryEntriesModel->verifyIsExpenseHasHistory($id);

        return response()->json($expense);
    }

    public function update()
    {
        $data = request()->all();

        if (!isset($data['credit_card_edit']) || $data['credit_card_edit'] == '0') {
            $data['credit_card_edit'] = null;
        }

        $invoice = new stdClass();
        $invoice->id = null;

        // Credit card was informed...
        if ($data['credit_card_edit'] != null) {
            $invoice = $this->creditCardInvoiceModel->getInvoiceByCreditCardAndMonthAndYear(
                $data['credit_card_edit'],
                $_SESSION['month']['id'],
                $_SESSION['year']
            );

            // Is there an invoice already open...
            if (!$invoice) {
                $invoice = CreditCardInvoiceModel::create([
                    'credit_card' => $data['credit_card_edit'],
                    'user_id' => session('user')['id'],
                    'month' => $this->month,
                    'year' => $this->year
                ]);
            } else {
                // Check all expenses on that credit card...
                $expenses = $this->expenseModel->getExpensesByCreditCardMonthYear(
                    $data['credit_card_edit'],
                    $this->month,
                    $this->year
                );

                $expensesPaid = 0;

                foreach ($expenses as $expense) {
                    if ($expense->id == $data['id_expense']) {
                        if (floatval($data['budgeted_amount_expense_edit']) == floatval($data['realized_amount_expense_edit'])) {
                            $expensesPaid++;
                        }
                    }

                    if ($expense->realized_amount == $expense->budgeted_amount) {
                        $expensesPaid++;
                    }
                }

                if (floatval($data['budgeted_amount_expense_edit']) == floatval($data['realized_amount_expense_edit'])
                    && $expensesPaid == count($expenses)) {
                        CreditCardInvoiceModel::where('id', $invoice->id)
                            ->update([
                                'credit_card' => $data['credit_card_edit'],
                                'payment' => 1,
                                'pay_day' => date('Y-m-d H:i:s')
                            ]);
                } else {
                    CreditCardInvoiceModel::where('id', $invoice->id)
                        ->update([
                            'credit_card' => $data['credit_card_edit'],
                            'payment' => 0,
                            'pay_day' => null
                        ]);
                }
            }
        }

        if ($data['realized_amount_expense_edit'] != '0') {
            ExpensesHistoryEntriesModel::create([
                'expense_id' => $data['id_expense'],
                'value' => $data['realized_amount_expense_edit'],
                'user_id' => session('user')['id']
            ]);
        }

        $totalExpense = $this->expensesHistoryEntriesModel->getTotalExpenseSum($data['id_expense']);

        if ($data['payment_method_change'] == 'N') {
            if ($data['category_active'] == 1) {
                ExpenseModel::where('id', $data['id_expense'])
                    ->update([
                        'description' => mb_convert_case($data['description_expense_edit'], MB_CASE_TITLE, "UTF-8"),
                        'category' => $data['category_expense_edit'],
                        'period' => $data['period_expense_edit'],
                        'budgeted_amount' => $data['budgeted_amount_expense_edit'],
                        'realized_amount' => floatval($totalExpense),
                        'credit_card' => $data['credit_card_edit'],
                        'invoice' => $invoice->id
                    ]);
            } else {
                ExpenseModel::where('id', $data['id_expense'])
                    ->update([
                        'description' => mb_convert_case($data['description_expense_edit'], MB_CASE_TITLE, "UTF-8"),
                        'period' => $data['period_expense_edit'],
                        'budgeted_amount' => $data['budgeted_amount_expense_edit'],
                        'realized_amount' => floatval($totalExpense),
                        'credit_card' => $data['credit_card_edit'],
                        'invoice' => $invoice->id
                    ]);
            }

            return response()->json([
                'ok' => true,
                'message' => 'Saída atualizada com sucesso'
            ]);
        } else {
            $expense = ExpenseModel::find($data['id_expense']);

            if ($expense->installments > 1) {
                $expenseInstallment = ExpenseInstallmentsModel::where('expense', $data['id_expense'])
                    ->select('hash_installment')
                    ->first();
                $expensesWithInstallments = ExpenseInstallmentsModel::where('hash_installment', $expenseInstallment['hash_installment'])
                    ->select('expense')
                    ->get();

                foreach ($expensesWithInstallments as $expenseDetail) {
                    if ($data['category_active'] == 1) {
                        ExpenseModel::where('id', $expenseDetail['expense'])
                            ->update([
                                'description' => mb_convert_case($data['description_expense_edit'], MB_CASE_TITLE, "UTF-8"),
                                'category' => $data['category_expense_edit'],
                                'period' => $data['period_expense_edit'],
                                'budgeted_amount' => $data['budgeted_amount_expense_edit'],
                                'realized_amount' => floatval($totalExpense),
                                'credit_card' => $data['credit_card_edit'],
                                'invoice' => $invoice->id
                            ]);
                    } else {
                        ExpenseModel::where('id', $expenseDetail['expense'])
                            ->update([
                                'description' => mb_convert_case($data['description_expense_edit'], MB_CASE_TITLE, "UTF-8"),
                                'period' => $data['period_expense_edit'],
                                'budgeted_amount' => $data['budgeted_amount_expense_edit'],
                                'realized_amount' => floatval($totalExpense),
                                'credit_card' => $data['credit_card_edit'],
                                'invoice' => $invoice->id
                            ]);
                    }
                }
            } else {
                if ($data['category_active'] == 1) {
                    ExpenseModel::where('id', $data['id_expense'])
                        ->update([
                            'description' => mb_convert_case($data['description_expense_edit'], MB_CASE_TITLE, "UTF-8"),
                            'category' => $data['category_expense_edit'],
                            'period' => $data['period_expense_edit'],
                            'budgeted_amount' => $data['budgeted_amount_expense_edit'],
                            'realized_amount' => floatval($totalExpense),
                            'credit_card' => $data['credit_card_edit'],
                            'invoice' => $invoice->id
                        ]);
                } else {
                    ExpenseModel::where('id', $data['id_expense'])
                        ->update([
                            'description' => mb_convert_case($data['description_expense_edit'], MB_CASE_TITLE, "UTF-8"),
                            'period' => $data['period_expense_edit'],
                            'budgeted_amount' => $data['budgeted_amount_expense_edit'],
                            'realized_amount' => floatval($totalExpense),
                            'credit_card' => $data['credit_card_edit'],
                            'invoice' => $invoice->id
                        ]);
                }
            }
        }

        return response()->json([
            'ok' => true,
            'message' => 'Saída atualizada com sucesso'
        ]);
    }

    public function verifyMetodPayment()
    {
        $data = request()->all();

        if ($data['credit_card_edit'] == '0') {
            $data['credit_card_edit'] = '';
        }

        $expense = ExpenseModel::find($data['id_expense']);

        // Verify if the credit card changes and if the installments are bigger then 1...
        if ($expense->credit_card != $data['credit_card_edit'] && $expense->installments > 1) {
            $ok = false;
            $msg = 'Alterou a forma de pagamento';
        } else {
            $ok = true;
            $msg = 'Forma de pagamento permanece a mesma';
        }

        return response()->json([
            'ok' => $ok,
            'message' => $msg,
            'invoice' => $expense->invoice
        ]);
    }

    public function extendInstallments($id)
    {
        $expensesToExtend = $this->expenseInstallmentModel->getInstallmentsToExtend($id);

        foreach ($expensesToExtend as $expense) {
            $month = $expense->month;
            $year = $expense->year;

            if ($expense->month == 12) {
                $month = 1;
                ++$year;
            } else {
                ++$month;
            }

            ExpenseModel::where('id', $expense->expense)
                ->update([
                    'month' => $month,
                    'year' => $year
                ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'As saídas foram prorrogadas com sucesso.'
        ]);
    }

    public function history($id)
    {
        $entries = $this->expensesHistoryEntriesModel->getAllEntriesByExpense($id);

        if (is_null($entries)) {
            return response()->json([
                'ok' => true,
                'data' => []
            ]);
        }

        $totalHistoryAmount = 0;

        foreach ($entries as $entry) {
            $totalHistoryAmount += $entry->value;
            $entry->value = str_replace('.', ',', $entry->value);
        }

        return response()->json([
            'ok' => true,
            'data' => $entries,
            'totalHistory' => $totalHistoryAmount
        ]);
    }

    public function pay($id)
    {
        $expense = ExpenseModel::find($id);
        $sum = $this->expensesHistoryEntriesModel->getTotalExpenseSum($id);

        if ($sum == 0) {
            ExpenseModel::where('id', $id)
                ->update([
                    'realized_amount' => $expense->budgeted_amount
                ]);

            ExpensesHistoryEntriesModel::create([
                'expense_id' => $id,
                'value' => $expense->budgeted_amount,
                'user_id' => session('user')['id']
            ]);
        } else {
            $diffMissing = ($expense->budgeted_amount - $sum);

            ExpenseModel::where('id', $id)
                ->update([
                    'realized_amount' => ($expense->realized_amount + $diffMissing)
                ]);

            ExpensesHistoryEntriesModel::create([
                'expense_id' => $id,
                'value' => $diffMissing,
                'user_id' => session('user')['id']
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Saída paga com sucesso.'
        ]);
    }
}
