<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreditCardModel extends Model
{
    use HasFactory;
    protected $table = "credit_cards";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getCardsWithFlags()
    {
        $cards = DB::select('
            SELECT
                ca.*
                , cf.description card_flag_name
            FROM credit_cards ca
            LEFT JOIN card_flags cf
            ON (cf.id = ca.flag)
            WHERE ca.active = 1
            ORDER BY ca.description;
        ');

        return $cards;
    }

    public function getCardsWithFlagsById($id)
    {
        $cards = DB::selectOne('
            SELECT
                ca.*
                , cf.description card_flag_name
            FROM credit_cards ca
            LEFT JOIN card_flags cf
            ON (cf.id = ca.flag)
            WHERE ca.id = ?;
        ', [$id]);

        return $cards;
    }
}
