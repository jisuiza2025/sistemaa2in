<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use Carbon\Carbon;
use App\TipoCambio;
use DB;

class InsertarTipoCambio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:tcambio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insertar Tipo de Cambio';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
    

      $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';
      $fecha = Carbon::now()->format('Y-m-d');

     
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/tipo-cambio-sunat?fecha=' . $fecha,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 2,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Referer: https://apis.net.pe/tipo-de-cambio-sunat-api',
          'Authorization: Bearer ' . $token
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
     
      $tipoCambioSunat = json_decode($response);
      

       if(!empty($tipoCambioSunat)){


          if(isset($tipoCambioSunat->venta)){

             $compra = $tipoCambioSunat->compra;

             $venta = $tipoCambioSunat->venta;

             //$date =  Carbon::parse($tipoCambioSunat->fecha)->format('Y-m-d');

             $date =  Carbon::now()->format('Y-m-d');


              $fecha = $date;
              $clase_cambio = '02' ;//venta
              $moneda_origen = 'SOL';
              $moneda_destino = 'SOL';
              $factor = $venta;
              $usuario ='ICTC';
              $observacion = 'INSERTADO DESDE API TIPO DE CAMBIO APISNET';

             

            
              $stmt = DB::getPdo()->prepare("begin WEB_COR_TIPOCAMBIO_INPUD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:rpta); end;");
              $stmt->bindParam(':p1', $fecha, \PDO::PARAM_STR);
              $stmt->bindParam(':p2', $clase_cambio, \PDO::PARAM_STR);
              $stmt->bindParam(':p3', $moneda_origen, \PDO::PARAM_STR);
              $stmt->bindParam(':p4', $moneda_destino, \PDO::PARAM_STR);
              $stmt->bindParam(':p5', $factor, \PDO::PARAM_STR);
              $stmt->bindParam(':p6', $usuario, \PDO::PARAM_STR);
              $stmt->bindParam(':p7', $observacion, \PDO::PARAM_STR);

              $stmt->bindParam(':rpta', $rpta,\PDO::PARAM_INT);   
              $stmt->execute();

              $this->info($rpta);


          }


        }
        
        

      
      

      
    }
}
