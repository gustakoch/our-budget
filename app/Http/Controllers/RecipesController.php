<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RecipeModel;
use DateTime;

class RecipesController extends Controller
{
    private $categoryModel;

    public function __construct(CategoryModel $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    public function index()
    {
        session_start();
        $month = $_SESSION['month']['id'];

        $userRecipes = DB::table('recipes')
            ->join('categories', 'recipes.category', 'categories.id')
            ->where('recipes.user_id', '=', session('user'))['id']
            ->select('recipes.*', 'categories.description as category_description')
            ->get();

        $monthRecipes = [];
        $monthAmount = 0;

        foreach ($userRecipes as $recipe) {
            if ($recipe->month == $month) {
                array_push($monthRecipes, $recipe);
                $monthAmount += (float) $recipe->budgeted_amount;
            }
        }

        return response()->json([
            'recipes' => $monthRecipes,
            'budgeted_amount' => $monthAmount
        ]);
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

        return response()->json([
            'ok' => true,
            'message' => 'Entrada cadastrada com sucesso!'
        ]);
    }

    public function update()
    {
        $data = request()->all();

        if ($data['category_active'] == 1) {
            RecipeModel::where('id', $data['id_recipe'])
                ->update([
                    'description' => mb_convert_case($data['description_edit'], MB_CASE_TITLE, "UTF-8"),
                    'category' => $data['category_edit'],
                    'budgeted_amount' => $data['budgeted_amount_edit']
                ]);
        } else {
            RecipeModel::where('id', $data['id_recipe'])
                ->update([
                    'description' => mb_convert_case($data['description_edit'], MB_CASE_TITLE, "UTF-8"),
                    'budgeted_amount' => $data['budgeted_amount_edit']
                ]);
        }

        return response()->json([
            'ok' => true,
            'message' => 'Entrada atualizada com sucesso',
        ]);
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

        return response()->json([
            'ok' => true,
            'msg' => 'Entrada removida com sucesso!'
        ]);
    }
}
