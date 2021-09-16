<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreditCardInvoiceModel extends Model
{
    use HasFactory;
    protected $table = "credit_card_invoices";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getInvoiceByCreditCardAndMonthAndYear($creditCard, $month, $year)
    {
        $invoice = DB::selectOne("
            SELECT id
                , credit_card
                , month
                , year
                , payment
                , to_char(pay_day, 'DD/MM/YYYY às HH24:MI') pay_day
            FROM credit_card_invoices
            WHERE credit_card = ?
            AND month = ?
            AND year = ?
            AND user_id = ?
            LIMIT 1
        ", [$creditCard, $month, $year, session('user')['id']]);

        return $invoice;
    }

    public function getAllInvoicesByCreditCard($creditCard)
    {
        $invoices = DB::select("
            SELECT *
            FROM credit_card_invoices
            WHERE credit_card = ?
            AND user_id = ?
            ORDER BY id
        ", [$creditCard, session('user')['id']]);

        return $invoices;
    }
}
