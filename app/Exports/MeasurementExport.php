<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MeasurementExport implements FromCollection, WithHeadings
{
    use Exportable;
    protected $whereRaw;
    protected $table;
    public function __construct(string $table, string $whereRaw)
    {
        $this->table = $table;
        $this->whereRaw = $whereRaw;
    }
    public function headings(): array
    {
        return ["Stack", "Sensor", "Unit", "Measured", "Corrected", "Timegroup"];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table($this->table)
            ->selectRaw("stacks.name as stack_name, sensors.name as sensor_name, units.name as unit_name, measured, corrected, time_group")
            ->leftJoin("sensors","$this->table.parameter_id","=","sensors.parameter_id")
            ->leftJoin("units","sensors.unit_id","=","units.id")
            ->leftJoin("stacks","sensors.stack_id","=","stacks.id")
            ->whereRaw($this->whereRaw)->get();
    }
}
