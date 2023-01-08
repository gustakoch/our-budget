<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\MonthModel;
use App\Models\AppConfigModel;
use App\Models\ExpenseModel;
use App\Models\RecipeModel;
use App\Models\SubmitExpenseModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmitExpenseController extends Controller
{
    private $submitExpenseModel;
    private $appConfigModel;
    private $expenseModel;
    private $userModel;

    public function __construct(
        SubmitExpenseModel $submitExpenseModel,
        AppConfigModel $appConfigModel,
        ExpenseModel $expenseModel,
        User $userModel
    ) {
        $this->submitExpenseModel = $submitExpenseModel;
        $this->appConfigModel = $appConfigModel;
        $this->userModel = $userModel;
        $this->expenseModel = $expenseModel;
    }

    public function index()
    {
        session_start();

        $configNumberOfInstallments = $this->appConfigModel->getNumberOfInstallments();
        $activeExpenseCategories = DB::table('categories')
            ->where('belongs_to', 2)
            ->where('active', 1)
            ->orderBy('description', 'asc')
            ->get();
        $pendingSubmittedExpenses = $this->submitExpenseModel->getSubmittedExpenses([0, 2]);
        $approvedSubmittedExpenses = $this->submitExpenseModel->getSubmittedExpenses([1]);
        $activeUsers = $this->userModel->getActives();
        $months = MonthModel::all();
        $years = $this->expenseModel->getDistinctYears();

        return view('submit-expense.index', [
            'pendingSubmittedExpenses' => $pendingSubmittedExpenses,
            'approvedSubmittedExpenses' => $approvedSubmittedExpenses,
            'installments' => $configNumberOfInstallments,
            'expenseCategories' => $activeExpenseCategories,
            'users' => $activeUsers,
            'months' => $months,
            'years' => $years
        ]);
    }

    public function show($id)
    {
        $submittedExpense = SubmitExpenseModel::find($id);

        if (!$submittedExpense) {
            return response()->json([
                'ok' => false,
                'message' => 'Erro ao localizar lançamento de saída.',
                'data' => null
            ]);
        }

        return response()->json([
            'ok' => true,
            'message' => '',
            'data' => $submittedExpense
        ]);
    }

    public function store(Request $request)
    {
        session_start();
        $data = $request->all();

        SubmitExpenseModel::create([
            'description' => mb_convert_case($data['description'], MB_CASE_TITLE, "UTF-8"),
            'category' => $data['category'],
            'month' => $data['month'],
            'year' => $_SESSION['year'],
            'installments' => $data['installments'],
            'value' => $data['amount'],
            'from_user' => session('user')['id'],
            'to_user' => $data['to_user']
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Saída enviada com sucesso!'
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $fieldChanged = true;

        $submittedExpense = SubmitExpenseModel::find($data['id_submitted_expense']);

        if ($submittedExpense['description'] == $data['description']
            && $submittedExpense['category'] == $data['category']
            && $submittedExpense['month'] == $data['month']
            && $submittedExpense['to_user'] == $data['to_user']
            && $submittedExpense['installments'] == $data['installments']
            && $submittedExpense['value'] == $data['amount']) {
                $fieldChanged = false;
        }

        if (!$fieldChanged) {
            return response()->json([
                'ok' => false,
                'fieldChanged' => false,
                'message' => 'Algum dado deve ser alterado. Os campos permaneceram iguais.'
            ]);
        }

        SubmitExpenseModel::where('id', $data['id_submitted_expense'])
            ->update([
                'description' => mb_convert_case($data['description'], MB_CASE_TITLE, "UTF-8"),
                'category' => $data['category'],
                'month' => $data['month'],
                'to_user' => $data['to_user'],
                'installments' => $data['installments'],
                'value' => $data['amount'],
                'status' => 0,
                'refuse_details' => null
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'Lançamento atualizado com sucesso!'
        ]);
    }

    public function refuse(Request $request)
    {
        $data = $request->all();

        SubmitExpenseModel::where('id', $data['id_submitted_expense'])
            ->update([
                'status' => 2,
                'refuse_details' => $data['refuse_detail']
            ]);

        return response()->json([
            'ok' => true,
            'message' => 'Lançamento recusado com sucesso!'
        ]);
    }

    public function convertToExpense($id)
    {
        $submittedExpense = SubmitExpenseModel::find($id);
        $month = $submittedExpense['month'];
        $year = $submittedExpense['year'];

        for ($i = 1; $i <= $submittedExpense['installments']; $i++) {
            if ($month == 13) {
                $month = 1;
                $year++;
            }

            RecipeModel::create([
                'description' => mb_convert_case($submittedExpense['description'], MB_CASE_TITLE, "UTF-8"),
                'category' => 38,
                'month' => $month,
                'year' => $year,
                'repeat_next_months' => 0,
                'budgeted_amount' => floatval($submittedExpense['value']) / intval($submittedExpense['installments']),
                'user_id' => $submittedExpense['from_user'],
                'submitted_expense_id' => $submittedExpense['id']
            ]);

            ExpenseModel::create([
                'description' => mb_convert_case($submittedExpense['description'], MB_CASE_TITLE, "UTF-8"),
                'category' => $submittedExpense['category'],
                'installment' => $i,
                'installments' => $submittedExpense['installments'],
                'month' => $month,
                'year' => $year,
                'budgeted_amount' => floatval($submittedExpense['value']) / intval($submittedExpense['installments']),
                'user_id' => $submittedExpense['to_user'],
                'submitted_expense_id' => $submittedExpense['id']
            ]);

            $month++;
        }

        $submittedExpense->status = 1;
        $submittedExpense->save();

        return response()->json([
            'ok' => true,
            'message' => 'Saída lançada com sucesso!'
        ]);
    }

    public function destroy($id)
    {
        $submittedExpense = SubmitExpenseModel::find($id);
        $submittedExpense->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Lançamento removido com sucesso!'
        ]);
    }
}
