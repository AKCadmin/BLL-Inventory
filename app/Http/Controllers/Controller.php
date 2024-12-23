<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

abstract class Controller
{
    protected function setupDatabaseConnection()
    {

        if (Session::has('db_name')) {
            $dbName = session('db_name');
            

            if ($dbName) {

                config(['database.connections.pgsql.database' => $dbName]);

                DB::purge('pgsql');

                try {
                    DB::connection('pgsql')->getPdo();
                } catch (\Exception $e) {
                    abort(500, 'Could not connect to the specified database: ' . $e->getMessage());
                }
            } else {
                abort(400, 'Database name not set in session.');
            }
        }
    }
}
