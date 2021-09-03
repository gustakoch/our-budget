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
            SELECT
                id
                , description
                , to_char(created_at, 'DD/MM/YYYY HH24:MI') created_at
                , CASE
                    WHEN belongs_to = 1 THEN 'Receita'
                    WHEN belongs_to = 2 THEN 'Despesa'
                END AS type
            FROM categories
            WHERE active = 1
            ORDER BY id
        ");

        return $categories;
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
}
