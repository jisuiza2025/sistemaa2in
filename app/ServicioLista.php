<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class ServicioLista extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    

    protected static function save_servicio($request){

    	$p1 = Auth::user()->empresa;
    	$p2 = $request->articulo;
    	$p3 = $request->servicio;
    	$p4 = ($request->anualidad)?1:0;

    	

    	$stmt =  static::$pdo->prepare("begin WEB_CONFIG_SERVICIOS_INPUPD(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        
        return $rpta;
    }
    


    
}
