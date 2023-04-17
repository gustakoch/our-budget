<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CategoryModel extends Model
{
    use HasFactory;
    protected $table = "categories";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getAll()
    {
        $categories = DB::select("
            SELECT id
                , description
                , to_char(created_at, 'DD/MM/YYYY HH24:MI') created_at
                , CASE
                    WHEN belongs_to = 1 THEN 'Entrada'
                    WHEN belongs_to = 2 THEN 'Saída'
                END AS type
                , color
            FROM categories
            WHERE active = 1
            ORDER BY belongs_to, description");
        foreach ($categories as $category) {
            $category->color_brightness = $this->getColorBrightness($category->color);
        }

        return $categories;
    }

    private function getColorBrightness($color)
    {
        $red = hexdec(substr($color, 1, 2));
        $green = hexdec(substr($color, 3, 2));
        $blue = hexdec(substr($color, 5, 2));

        return (($red * 299) + ($green * 587) + ($blue * 114)) / 1000;
    }

    public function isCategoryActive($idCategory)
    {
        $status = DB::selectOne('
            SELECT
                active
            FROM categories
            WHERE id = ?
        ', [$idCategory]);

        return $status->active;
    }

    public function getOnlyExpensesCategories()
    {
        $expenseCategories = DB::select("
                SELECT id
                    , description
                    , active
                    , case when
                        active = 0 then '(Desativado)'
                    else ''
                    end active_description
                    , to_char(created_at, 'dd/mm/yyyy hh24:mi') created_at
                  FROM categories
                 WHERE belongs_to = 2
              ORDER BY description");

        return $expenseCategories;
    }

    public function getOnlyRecipesCategories()
    {
        $recipesCategories = DB::select("
                SELECT id
                    , description
                    , active
                    , case when
                        active = 0 then '(Desativado)'
                    else ''
                    end active_description
                    , to_char(created_at, 'dd/mm/yyyy hh24:mi') created_at
                  FROM categories
                 WHERE belongs_to = 1
              ORDER BY description");

        return $recipesCategories;
    }

    public function activeRecipeCategories()
    {
        $categories = DB::table('categories')
            ->where('belongs_to', 1)
            ->where('active', 1)
            ->whereNotIn('id', [38])
            ->orderBy('description', 'asc')
            ->get();

        return $categories;
    }

    public function activeExpenseCategories()
    {
        $categories = DB::table('categories')
            ->where('belongs_to', 2)
            ->where('active', 1)
            ->orderBy('description', 'asc')
            ->get();

        return $categories;
    }
}
