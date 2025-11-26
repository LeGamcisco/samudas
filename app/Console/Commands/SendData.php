<?php

namespace App\Console\Commands;

use App\Models\Configuration;
use App\Models\DasLog;
use Exception;
use Illuminate\Console\Command;

class SendData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending data avg 1minute to DIS server';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = Configuration::first()->server_url;
        $this->info('Sending data avg 1minute to DIS server');
        $lastSent = DasLog::whereRaw("is_sent=1")->orderBy("time_group","desc")->first()->time_group ?? now()->subHour()->format("Y-m-d H:i:00");
        $this->info("Last sent: $lastSent");
        $dasLogs = DasLog::whereRaw("is_sent=0 and time_group >= '{$lastSent}'")->limit(100)->get();
        $ids = [];
        foreach ($dasLogs as $dasLog) {
            $data = [
                "parameter_id" => $dasLog->parameter_id,
                "data" => $dasLog->measured,
                "date_data" => $dasLog->time_group,
                "voltage" => $dasLog->raw,
                "unit_id" => $dasLog->unit_id
            ];
            $request = $this->sendData($url,$data);
            if($request->status == 200){
                $ids[] = $dasLog->id;
                $this->info("[$dasLog->time_group] - Data sent successfully");
            }
        }
        DasLog::whereIn("id",$ids)->update(["is_sent"=>1]);
    }
    public function sendData($url,$data){
        try{
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response);
            return $response;
        }catch(Exception $e){
            $this->error($e->getMessage());
            return;
        }
    }
}
