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
}
