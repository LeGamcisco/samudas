<?php

namespace App\Http\Controllers;

use App\Exports\DasLogExport;
use App\Exports\RcaLogExport;
use App\Models\DasLog;
use App\Models\Sensor;
use App\Models\Stack;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RcaLogController extends Controller
{
    public function index($stackId=null){
        if($stackId == null) $stackId = Stack::orderBy("id")->first()->id;
        $stack = Stack::find($stackId);
        $stacks = Stack::get(["id","name"]);
        $parameters = Sensor::where(["stack_id"=> $stackId, "is_show" => 1])->get();
        $tables = array_filter(get_tables("sensor_value_rca"), function($q){
            return $q != "sensor_value_rca" && $q != "sensor_value_rca_logs";
        });
        return view("rca-log.index", compact("parameters","stack","stacks","stackId", "tables"));
    }
    public function export(Request $request){
        $table = $request->data_source ?? "sensor_value_rca";
        $sensorId = $request->sensor_id;
        $stackId = $request->stack_id;
        $startAt = $request->start_at; 
        $endAt = $request->end_at; 
        $where = "1=1";
        if($sensorId) $where.=" and $table.sensor_id = '{$sensorId}'";
        if($stackId) $where.=" and $table.sensor_id in (select id from sensors where stack_id = '{$stackId}')";
        if($startAt) $where.=" and $table.created_at >= '{$startAt}'";
        if($endAt) $where.=" and $table.created_at <= '{$endAt}'";
        return Excel::download(new RcaLogExport($table, $where), 'rca_log.xlsx');
    }
    public function datatable(Request $request){
        try{
            $table = $request->data_source ?? "sensor_value_rca";
            $sensorId = $request->sensor_id;
            $stackId = $request->stack_id;
            $startAt = $request->start_at; 
            $endAt = $request->end_at; 
            $where = "1=1";
            if($sensorId) $where.=" and $table.sensor_id = '{$sensorId}'";
            if($stackId) $where.=" and $table.sensor_id in (select id from sensors where stack_id = '{$stackId}')";
            if($startAt) $where.=" and $table.created_at >= '{$startAt}'";
            if($endAt) $where.=" and $table.created_at <= '{$endAt}'";
            $data = DB::table($table)
                ->selectRaw("$table.*,sensors.name as parameter_name, units.name as unit_name")
                ->leftJoin("sensors","$table.sensor_id","=","sensors.id")
                ->leftJoin("units","sensors.unit_id","=","units.id")
                ->whereRaw($where);
            return datatables()->of($data)->toJson();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
