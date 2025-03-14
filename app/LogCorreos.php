<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class LogCorreos extends Model
{   
    
    public $table ='LOG_ENVIO_CORREOS';

    public $timestamps = false;

    protected $fillable = ['NO_CIA', 'OPCION','PROCESO','USUARIO','FECHA_ENVIO','DESTINATARIOS','REMITENTE','CC','MENSAJE','ADJUNTO','ASUNTO','ENVIO','MENSAJE_ENVIO'];

    protected $sequence = null;

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

   


    protected static function inserta($rpta,$opcion,$proceso,$asunto,$mensaje,$list,$correo_logeo){

        if($rpta>0){

            $success = "ok";
            $message = "Se envió el correo de manera satisfactoria";

        }else{

            $success = "error";
            $message = "No se pudo enviar el correo";
        }


         $values = array(

                "NO_CIA" =>Auth::user()->empresa ,
                "OPCION" => $opcion ,
                "PROCESO" => $proceso ,
                "USUARIO" => Auth::user()->codigo ,
                "FECHA_ENVIO" => Carbon::now()->format('Y-m-d H:i:s') ,
                "DESTINATARIOS" => json_encode($list["destinatarios"]) ,
                "REMITENTE" => $list['config']['CORREO'] ,
                "CC" => $correo_logeo ,
                "MENSAJE" => json_encode($mensaje) ,
                "ADJUNTO" => (isset($list["formato"]))?$list["formato"]:null ,
                "ASUNTO" => $asunto ,
                "ENVIO" => $success ,
                "MENSAJE_ENVIO" => $message
               
            );


        DB::table('LOG_ENVIO_CORREOS')->insert($values);



    }


    protected static function list_correos_enviados($request){

        $cia = Auth::user()->empresa;

        $start = ($request->date[0] =='null' || $request->date[0] ==null)?'':Carbon::parse($request->date[0])->format('Y-m-d');

        $end  = ($request->date[1] =='null' || $request->date[1] ==null)?'':Carbon::parse($request->date[1])->format('Y-m-d');

          $query  = DB::table('LOG_ENVIO_CORREOS')->whereDate('FECHA_ENVIO','>=',$start)->whereDate('FECHA_ENVIO','<=',$end)->where('NO_CIA',$cia)->orderBy('FECHA_ENVIO', 'desc')->get()->toArray();

        

        
        return $query;
    }







    






   
}
