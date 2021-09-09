<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardFlagModel extends Model
{
    use HasFactory;
    protected $table = "card_flags";
    protected $primaryKey = "id";
    protected $guarded = [];
}
