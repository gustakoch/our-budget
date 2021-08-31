<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigurationModel extends Model
{
    use HasFactory;
    protected $table = "configurations";
    protected $primaryKey = "id";
    protected $guarded = [];
}
