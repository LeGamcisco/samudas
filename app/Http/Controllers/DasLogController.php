<?php

namespace App\Http\Controllers;

use App\Exports\DasLogExport;
use App\Models\DasLog;
use App\Models\Sensor;
use App\Models\Stack;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DasLogController extends Controller
{
    public function index($stackId=null){
        if($stackId == null) $stackId = Stack::orderBy("id")->first()->id;
        $stacks = Stack::get(["id","name"]);
        $stack = Stack::find($stackId);
        $parameters = Sensor::where(["stack_id"=> $stackId, "is_show" => 1])->get();
        $tables = array_filter(get_tables("das_log"), function($q){
            return $q != "das_logs";
        });
        return view("das-log.index", compact("parameters","stack","stacks","stackId", "tables"));
    }
    public function export(Request $request){
        try{
            // set timeout
            ini_set('max_execution_time', 0);
            echo "Exporting...";
            $table = $request->data_source ?? "das_logs";
            $parameterId = $request->parameter_id;
            $stackId = $request->stack_id;
            $isSent = $request->is_sent;
            $startAt = $request->start_at; 
            $endAt = $request->end_at; 
            $where = "1=1";
            if($parameterId) $where.=" and $table.parameter_id = '{$parameterId}'";
            if($stackId) $where.=" and $table.parameter_id in (select parameter_id from sensors where stack_id = '{$stackId}')";
            if($startAt) $where.=" and time_group >= '{$startAt}'";
            if($endAt) $where.=" and time_group <= '{$endAt}'";
            if($isSent) $where.=" and is_sent = '{$isSent}'";
            // return (new DasLogExport($table, $where))->download("das_log.xlsx");
            return Excel::download(new DasLogExport($table, $where), 'das_log.xlsx');
        }catch(Exception $e){
            return back()->with("error", $e->getMessage());
        }
    }
    public function datatable(Request $request){
        try{
            $table = $request->data_source ?? "das_logs";
            $parameterId = $request->parameter_id;
            $stackId = $request->stack_id;
            $isSent = $request->is_sent;
            $startAt = $request->start_at; 
            $endAt = $request->end_at; 
            $where = "1=1";
            if($parameterId) $where.=" and $table.parameter_id = '{$parameterId}'";
            if($stackId) $where.=" and $table.parameter_id in (select parameter_id from sensors where stack_id = '{$stackId}')";
            if($startAt) $where.=" and time_group >= '{$startAt}'";
            if($endAt) $where.=" and time_group <= '{$endAt}'";
            if($isSent) $where.=" and is_sent = '{$isSent}'";
            $data = DB::table($table)
                ->selectRaw("$table.*,sensors.name as parameter_name, units.name as unit_name")
                ->leftJoin("sensors","$table.parameter_id","=","sensors.parameter_id")
                ->leftJoin("units","sensors.unit_id","=","units.id")
                ->whereRaw($where);
            return datatables()->of($data)->toJson();
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
