<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Support\Facades\DB;
use App\Models\RecipeModel;

class RecipesController extends Controller
{
    private $categoryModel;
    private $recipeModel;

    public function __construct(CategoryModel $categoryModel, RecipeModel $recipeModel)
    {
        $this->categoryModel = $categoryModel;
        $this->recipeModel = $recipeModel;
    }

    public function index()
    {
        session_start();
        $month = $_SESSION['month']['id'];
        $year = $_SESSION['year'];
        $monthRecipes = $this->recipeModel->userRecipes($year, $month);
        $monthAmount = 0;
        foreach ($monthRecipes as $recipe) {
            $monthAmount += (float) $recipe->budgeted_amount;
        }

        return response()->json(['recipes' => $monthRecipes, 'budgeted_amount' => $monthAmount]);
    }

    public function store()
    {
        session_start();
        $data = request()->all();
        $repeatNextMonths = isset($data['repeat_next_months']) ? 1 : 0;
        if ($repeatNextMonths == '1') {
            for ($i = $_SESSION['month']['id']; $i <= 12; $i++) {
                RecipeModel::create([
                    'description' => mb_convert_case($data['description'], MB_CASE_TITLE, "UTF-8"),
                    'category' => (int) $data['category'],
                    'budgeted_amount' => $data['budgeted_amount'],
                    'repeat_next_months' => $repeatNextMonths,
                    'month' => $i,
                    'year' => $_SESSION['year'],
                    'user_id' => session('user')['id']
                ]);
            }
        } else {
            RecipeModel::create([
                'description' => mb_convert_case($data['description'], MB_CASE_TITLE, "UTF-8"),
                'category' => (int) $data['category'],
                'budgeted_amount' => $data['budgeted_amount'],
                'repeat_next_months' => $repeatNextMonths,
                'month' => $_SESSION['month']['id'],
                'year' => $_SESSION['year'],
                'user_id' => session('user')['id']
            ]);
        }

        return response()->json(['ok' => true, 'message' => 'Entrada cadastrada com sucesso!']);
    }

    public function update()
    {
        $data = request()->all();
        if ($data['category_active'] == 1) {
            RecipeModel::where('id', $data['id_recipe'])->update([
                'description' => mb_convert_case($data['description_edit'], MB_CASE_TITLE, "UTF-8"),
                'category' => $data['category_edit'],
                'budgeted_amount' => $data['budgeted_amount_edit']
            ]);
        } else {
            RecipeModel::where('id', $data['id_recipe'])->update([
                'description' => mb_convert_case($data['description_edit'], MB_CASE_TITLE, "UTF-8"),
                'budgeted_amount' => $data['budgeted_amount_edit']
            ]);
        }

        return response()->json(['ok' => true, 'message' => 'Entrada atualizada com sucesso']);
    }

    public function show($id)
    {
        $recipe = RecipeModel::find($id);
        $recipe['category_active'] = $this->categoryModel->isCategoryActive($recipe['category']);

        return response()->json($recipe);
    }

    public function destroy($id)
    {
        $recipe = RecipeModel::find($id);
        $recipe->delete();

        return response()->json(['ok' => true, 'msg' => 'Entrada removida com sucesso!']);
    }
}
