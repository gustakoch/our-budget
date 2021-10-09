<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AppConfigModel extends Model
{
    use HasFactory;
    protected $table = "app_configs";
    protected $primaryKey = "id";
    protected $guarded = [];

    public function getAllConfigs()
    {
        $configs = DB::select("SELECT *
        FROM app_configs");

        return $configs;
    }

    public function getNumberOfInstallments()
    {
        $config = DB::selectOne("SELECT
            value
        FROM app_configs
        WHERE uuid_code = '1628d46b-059e-42c6-8727-fa09cdae4323'");

        return intval($config->value);
    }
}
