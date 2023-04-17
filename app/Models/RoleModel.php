<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoleModel extends Model
{
    use HasFactory;
    protected $table = "roles";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getRolesExceptMaster()
    {
        $roles = DB::select("
            SELECT *
              FROM roles
             WHERE id >= ?
          ORDER BY id
        ", [session('user')['role']]);

        return $roles;
    }
}
