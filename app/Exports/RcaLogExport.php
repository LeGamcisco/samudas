<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RcaLogExport implements FromCollection, WithHeadings
{
    protected $table;
    protected $where;
    public function __construct(string $table, string $where) {
        $this->table = $table;
        $this->where = $where;
    }
    public function headings(): array
    {
        return ["Stack", "Sensor", "Unit", "Measured","Corrected O2", "Raw Value", "Timestamp"];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table($this->table)
            ->selectRaw("stacks.name as stack_name, sensors.name as sensor_name, units.name as unit_name, measured,corrected, raw, $this->table.created_at")
            ->leftJoin("sensors","$this->table.sensor_id","=","sensors.id")
            ->leftJoin("units","sensors.unit_id","=","units.id")
            ->leftJoin("stacks","sensors.stack_id","=","stacks.id")
            ->whereRaw($this->where)->get();
    }
}
