
<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

if (!function_exists('setDatabaseConnection')) {
    function setDatabaseConnection()
    {
        // if (Auth::user()->role != '1') {
            $databaseName = Session::get('db_name');
            //   dd($databaseName);
            if ($databaseName) {
                config(['database.connections.pgsql.database' => $databaseName]);
                DB::purge('pgsql');
                DB::connection('pgsql')->getPdo();
            }
        // }
    }
}
