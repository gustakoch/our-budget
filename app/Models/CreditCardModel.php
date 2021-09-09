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
            AND ca.user_id = ?
            ORDER BY ca.description;
        ', [session('user')['id']]);

        return $cards;
    }

    public function getCardsWithFlagsById($id)
    {
        $cards = DB::selectOne('
            SELECT
                ca.id
                , cf.description card_flag_name
                , ca.flag
                , ca.number
            FROM credit_cards ca
            LEFT JOIN card_flags cf
            ON (cf.id = ca.flag)
            WHERE ca.id = ?
            AND ca.user_id = ?;
        ', [$id, session('user')['id']]);

        return $cards;
    }
}
