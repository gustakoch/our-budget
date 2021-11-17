<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

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

    public function isLoggedIn()
    {
        if (is_null(session('user'))) {
            return false;
        }

        return true;
    }

    public function getAll()
    {
        $users = DB::select("
            SELECT
                u.id
                , u.name
                , u.email
                , u.role
                , r.description role_name
                , CASE
                    WHEN u.first_access = 0 THEN 'Não'
                    WHEN u.first_access = 1 THEN 'Sim'
                END AS first_access
                , CASE
                    WHEN u.active = 0 THEN 'Não'
                    WHEN u.active = 1 THEN 'Sim'
                END AS active
                , to_char(u.created_at, 'DD/MM/YYYY HH24:MI') created_at
            FROM users u
                , roles r
            WHERE r.id = u.role
            ORDER BY u.name
        ");

        return $users;
    }

    public function generatePassword($size)
    {
        $capital = "ABCDEFGHIJKLMNOPQRSTUVYXWZ";
        $small = "abcdefghijklmnopqrstuvyxwz";
        $numbers = "0123456789";
        $symbols = "!@#$%&*()_+=";

        $password = "";
        $password .= str_shuffle($capital);
        $password .= str_shuffle($small);
        $password .= str_shuffle($numbers);
        $password .= str_shuffle($symbols);

        return substr(str_shuffle($password), 0, $size);
    }

    public function getUserInfo($id)
    {
        $userInfo = DB::selectOne("select
            id
            , name
            , email
            , role
            , first_access
            , active
        from users
        where id = ?
        ", [$id]);

        return $userInfo;
    }
}
