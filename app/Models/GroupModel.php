<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupModel extends Model
{
    protected $table = "groups";
    protected $primaryKey = "id";
    protected $guarded = [];
}
