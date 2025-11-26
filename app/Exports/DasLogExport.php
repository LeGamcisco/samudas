<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DasLogExport implements FromCollection, WithHeadings
{
    protected $whereRaw;
    protected $table;
    public function __construct(string $table, string $whereRaw)
    {
        $this->table = $table;
        $this->whereRaw = $whereRaw;
    }
    public function headings(): array
    {
        return ["Stack", "Sensor", "Unit", "Measured", "Raw Value", "Timegroup", "Status Sent"];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table($this->table)
            ->selectRaw("stacks.name as stack_name, sensors.name as sensor_name, units.name as unit_name, measured, raw, time_group, case when is_sent = 1 then 'Sent' else 'Not Sent' end as status_sent")
            ->leftJoin("sensors","$this->table.parameter_id","=","sensors.parameter_id")
            ->leftJoin("units","sensors.unit_id","=","units.id")
            ->leftJoin("stacks","sensors.stack_id","=","stacks.id")
            ->whereRaw($this->whereRaw)->get();
    }
}
