<?php

use Illuminate\Support\Facades\DB;

if(!function_exists("get_tables")){

    function get_tables($like){
        $tables = [];
        $dbName = config('database.connections.pgsql.database') ?? "trudas";
        $results = DB::select("SELECT table_schema,table_name, table_catalog FROM information_schema.tables WHERE table_catalog = '{$dbName}' AND table_type = 'BASE TABLE' AND table_schema = 'public' and table_name like '{$like}%' ORDER BY table_name;");
        foreach ($results as $result) {
            $tables[] = $result->table_name;
        }
        return $tables;
    }
}