<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseInstallmentsModel extends Model
{
    use HasFactory;
    protected $table = "expense_installments";
    protected $primaryKey = "id";
    protected $guarded = [];
}
