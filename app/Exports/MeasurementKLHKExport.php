<?php

namespace App\Exports;

use App\Models\Sensor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MeasurementKLHKExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $query;

    protected $tableName;


    public function __construct($query, $tableName)
    {
        $this->query = $query;
        $this->tableName = $tableName;
    }

    public function query()
    {
        return $this->query->orderBy("time_group","desc")->orderBy("parameter_id");
    }

    public function map($data):array{
        $sensor = Sensor::where("parameter_id", $data->parameter_id)->first();
        $stackId = $sensor->stack_id;
        $time_group = Carbon::parse($data->time_group)->format('d-m-Y');
        $startHour = Carbon::parse($data->time_group)->subHour(1)->format('H:00');
        $endHour = Carbon::parse($data->time_group)->format('H:00');
        $flowrate = DB::table($this->tableName)->whereRaw("parameter_id in (select parameter_id from sensors where stack_id = '$stackId' and code = 'flow') and time_group = '$data->time_group'")->avg("measured");
        $o2 = DB::table($this->tableName)->whereRaw("parameter_id in (select parameter_id from sensors where stack_id = '$stackId' and extra_parameter = 1) and time_group = '$data->time_group'")->avg("measured");
        return [
            "'$time_group",
            ("$startHour-$endHour"),
            $data->corrected,
            $flowrate,
            $o2,
        ];
    }

    public function headings():array{
        return [
            'TANGGAL',
            'JAM',
            'KONSENTRASI (MG/NM3)',
            'LAJU ALIR (M3/DETIK)',
            'OKSIGEN (%)',
        ];
    }
}
