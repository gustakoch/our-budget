<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    protected $table = "users";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function isFirstAccess()
    {
        if (session('user')['firstAccess'] == 1) {
            return true;
        }

        return false;
    }
}
