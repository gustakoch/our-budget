<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthModel extends Model
{
    use HasFactory;
    protected $table = "months";
    protected $primaryKey = "id";
    protected $guarded = [];
}
