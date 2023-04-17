<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecipeModel extends Model
{
    use HasFactory;
    protected $table = "recipes";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function recipesTotalSum($month, $year)
    {
        $recipesSum = DB::selectOne('
            SELECT SUM(budgeted_amount) recipes_sum
            FROM recipes
            WHERE user_id = ?
            AND month = ?
            AND year = ?
        ', [session('user')['id'], $month, $year]);

        return $recipesSum->recipes_sum;
    }

    public function getRecipesReport(
        $category,
        $startMonth,
        $endMonth,
        $startYear,
        $endYear,
        $users
    ) {
        $recipes = DB::select("
            SELECT r.description
                , m.description month_name
                , r.year
                , r.budgeted_amount
                , u.name user_name
              FROM recipes r
                , months m
                , users u
             WHERE r.month = m.id
               AND u.id = r.user_id
               AND category = ?
               AND r.month between ? AND ?
               AND r.year between ? AND ?
               AND r.user_id in (".$users.")
          ORDER BY r.month
            , r.description",
        [$category, $startMonth, $endMonth, $startYear, $endYear]);

        return $recipes;
    }

    public function userRecipes($year, $month)
    {
        $recipes = DB::table('recipes')
            ->join('categories', 'recipes.category', 'categories.id')
            ->where('recipes.user_id', '=', session('user')['id'])
            ->where('recipes.year', '=', $year)
            ->where('recipes.month', '=', $month)
            ->select('recipes.*', 'categories.description as category_description')
            ->orderBy('recipes.description', 'asc')
            ->orderBy('categories.description', 'asc')
            ->get();

        return $recipes;
    }
}
