<?php

namespace App\Http\Controllers;

use App\Exports\MeasurementExport;
use App\Exports\MeasurementKLHKExport;
use App\Models\Sensor;
use App\Models\Stack;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MeasurementController extends Controller
{
    public function index($stackId=null){
        if($stackId == null) $stackId = Stack::orderBy("id")->first()->id;
        $stacks = Stack::get(["id","name"]);
        $stack = Stack::find($stackId);
        $parameters = Sensor::where(["stack_id"=> $stackId, "is_show" => 1])->get();
        $tables = array_filter(get_tables("measurement"), function($q){
            return $q != "measurements";
        });
        return view("measurement.index", compact("parameters","stack","stacks","stackId", "tables"));
    }

    public function datatable(Request $request){
        try{
            $table = $request->data_source ?? "measurements";
            $parameterId = $request->parameter_id;
            $stackId = $request->stack_id;
            $startAt = $request->start_at; 
            $endAt = $request->end_at; 
            $where = "1=1";
            if($parameterId) $where.=" and $table.parameter_id = '{$parameterId}'";
            if($stackId) $where.=" and $table.parameter_id in (select parameter_id from sensors where stack_id = '{$stackId}')";
            if($startAt) $where.=" and $table.created_at >= '{$startAt}'";
            if($endAt) $where.=" and $table.created_at <= '{$endAt}'";
            $data = DB::table($table)
                ->selectRaw("$table.*,sensors.name as parameter_name, units.name as unit_name")
                ->leftJoin("sensors","$table.parameter_id","=","sensors.parameter_id")
                ->leftJoin("units","sensors.unit_id","=","units.id")
                ->whereRaw($where);
            return datatables()->of($data)->toJson();
        }catch(\Exception $e){
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    public function export(Request $request){
        try{
            // set timeout
            ini_set('max_execution_time', 0);
            echo "Exporting...";
            $table = $request->data_source ?? "measurements";
            $parameterId = $request->parameter_id;
            $stackId = $request->stack_id;
            $startAt = $request->start_at; 
            $endAt = $request->end_at; 
            $where = "1=1";
            if($parameterId) $where.=" and $table.parameter_id = '{$parameterId}'";
            if($stackId) $where.=" and $table.parameter_id in (select parameter_id from sensors where stack_id = '{$stackId}')";
            if($startAt) $where.=" and time_group >= '{$startAt}'";
            if($endAt) $where.=" and time_group <= '{$endAt}'";

            $filename = "measurement_".Carbon::now()->format("Y-m-d his").".xlsx";
            return (new MeasurementExport($table, $where))->download($filename);
        }catch(Exception $e){
            return back()->with("error", $e->getMessage());
        }
    }

    public function exportKLHK(Request $request){
        try{
            $request->validate([
                "parameter_id" => "required",
            ],[
                "parameter_id.required" => "Parameter is required",
            ]);
            // set timeout
            ini_set('max_execution_time', 0);
            echo "Exporting, please wait...";
            $tableName = $request->data_source ?? "measurements";
            $parameterId = $request->parameter_id;
            $startAt = $request->start_at;
            $endAt = $request->end_at;
            $stackId = $request->stack_id;
            $where = "1=1";
            $parameter = Sensor::where("parameter_id",$parameterId)->first();
            if($parameterId) $where.=" and $tableName.parameter_id = '{$parameterId}'";
            if($stackId) $where.=" and $tableName.parameter_id in (select parameter_id from sensors where stack_id = '{$stackId}')";
            if($startAt) $where.=" and $tableName.created_at >= '{$startAt}'";
            if($endAt) $where.=" and $tableName.created_at <= '{$endAt}'";
            $query = DB::table($tableName)
                ->selectRaw("$tableName.*,sensors.name as parameter_name, units.name as unit_name")
                ->leftJoin("sensors","$tableName.parameter_id","=","sensors.parameter_id")
                ->leftJoin("units","sensors.unit_id","=","units.id")
                ->whereRaw($where);

            $filename = Str::title($parameter->name)."_".Carbon::parse($startAt)->format("d-m-Y")."_".Carbon::parse($endAt)->format("d-m-Y").".xls";
            return (new MeasurementKLHKExport($query, $tableName, $stackId))->download($filename);
        }catch(Exception $e){
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

}
