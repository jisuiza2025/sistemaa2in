<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class TipoCambio extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
 	protected static function list_tipoCambio($request){

        
        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('Y-m-d');

   		$end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('Y-m-d');


        $stmt = static::$pdo->prepare("begin WEB_COR_TIPOCAMBIO_LISTADO(:p1, :p2, :c); end;");
        $stmt->bindParam(':p1', $start, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $end, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }

	
   	protected static function valida_nuevo_tipo_cambio($request){

   		$p1 = Carbon::parse($request->fecha)->format('Y-m-d');
		$p2 = $request->clase_cambio;

		
		$stmt = static::$pdo->prepare("begin WEB_COR_TIPOCAMBIO_VALID(:p1,:p2,:rpta); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);	
		$stmt->execute();

		return $rpta;


   	}



    protected static function valida_nuevo_tipo_cambio_hoy(){

      $p1 = Carbon::now()->format('Y-m-d');
      $p2 = '02';

    
    $stmt = static::$pdo->prepare("begin WEB_COR_TIPOCAMBIO_VALID(:p1,:p2,:rpta); end;");
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT); 
    $stmt->execute();

    return $rpta;


    }



   	protected static function get_tipo_cambio(){

   		
        $stmt = static::$pdo->prepare("begin WEB_COR_TIPO_CAMBIO(:c); end;");
      
 
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;


   	}



   	
   	protected static function save_tipoCambio($request){

		//$p1 = Carbon::parse($request->fecha)->format('d/m/Y');
		$p1 = $request->fecha;
		$p2 = $request->clase_cambio;
		$p3 = $request->moneda_origen;
		$p4 = $request->moneda_destino;
		$p5 = trim($request->factor);
    $p6 = Auth::user()->codigo;
		$p7 = $request->observacion;

		$stmt = static::$pdo->prepare("begin WEB_COR_TIPOCAMBIO_INPUD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:rpta); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
		$stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
    $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
    $stmt->bindParam(':p7', $p7, PDO::PARAM_STR);

		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);	
		$stmt->execute();
		return $rpta;	

			
    } 
}
