<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class MediosCaptacion extends Model
{   
 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}
   
 	protected static function list_mediosCaptacion(){

 		$p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_VEN_SEINFORMO_LIST(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
	}
	
	protected static function get_subdetails_medios_captacion_edit($request){

 		$p1 = Auth::user()->empresa;

 		$p2 = $request->informo;
 		$p3 = $request->codigo;
 		$p4 = $request->codigo_evento;

        $stmt = static::$pdo->prepare("begin WEB_VEN_SEINFORMOD2_OBTDATOS(:p1,:p2,:p3,:p4, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
	}

	
	protected static function get_subdetails_medios_captacion($request){

 		$p1 = Auth::user()->empresa;
 		$p2 = $request->informo;
 		$p3 = $request->codigo;

        $stmt = static::$pdo->prepare("begin WEB_VEN_SEINFORMOD2_LIST(:p1,:p2,:p3, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
	}

    protected static function list_mediosCaptacion_Detalle($request){

 		$p1 = Auth::user()->empresa;
 		$p2 = trim($request->identificacion);

        $stmt = static::$pdo->prepare("begin WEB_VEN_SEINFORMOD1_LIST(:p1, :p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }



    protected static function get_item_mediosCaptacion_nivel_1($request){

 		$p1 = Auth::user()->empresa;
 		$p2 = trim($request->informa);
 		$p3 = trim($request->codigo);

        $stmt = static::$pdo->prepare("begin WEB_VEN_SEINFORMOD1_OBTDATOS(:p1, :p2, :p3, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }
    

    protected static function get_item_mediosCaptacion($request){

    	$p1 = Auth::user()->empresa;
		$p2 = trim($request->identificacion);

		$stmt = static::$pdo->prepare("begin WEB_VEN_SEINFORMO_OBTDATOS(:p1, :p2, :c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
			
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
	   	oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
	    oci_free_cursor($cursor);
	        
	    return $list;  
	}
   	
  	protected static function save_MediosCaptacion($request){

		$p1 = Auth::user()->empresa;
		$p2 = trim($request->informa);
		$p3 = trim($request->descripcion);
		$p4 = trim($request->estado);

		$stmt = static::$pdo->prepare("begin WEB_VEN_SEINFORMO_INUPD(:p1,:p2,:p3,:p4,:rpta); end;");
		
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $p4, PDO::PARAM_STR);

		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);	
		$stmt->execute();
		return $rpta;	
    }



    protected static function save_MediosCaptacion_nivel_1($request){

		$p1 = Auth::user()->empresa;
		$p2 = trim($request->informa);
 		$p3 = trim($request->codigo);
		$p4 = trim($request->descripcion);
		$p5 = trim($request->estado);
		$p6 = $request->tabla;
		$p7 = $request->efectividad;


		$stmt = static::$pdo->prepare("begin WEB_VEN_SEINFORMOD1_INUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:rpta); end;");
		
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


    protected static function save_sub_details_medio_captacion($request){

    	
		$p1 = Auth::user()->empresa;

		$p2 = $request->informo;
		$p3 = $request->codigo;
		$p4 = ($request->codigo_details=='nuevo')?0:$request->codigo_details;
		
		$p5 = trim($request->descripcion);
		$p6 = $request->direccion;
		$p7 = (!empty($request->desde))?Carbon::parse($request->desde)->format('Y-m-d'):'';
		$p8 = (!empty($request->hasta))?Carbon::parse($request->hasta)->format('Y-m-d'):'';
		$p9 = trim($request->asistentes);
		$p10 = trim($request->tema);
		$p11 = ($request->estado=='ACT')?'A':'I';
		$p12 = trim($request->observaciones);
		
			$p13 = $request->efectividad;


		

		$stmt = static::$pdo->prepare("begin WEB_VEN_SEINFORMOD2_INUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:rpta); end;");
		
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
		$stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
		$stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
		$stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
		$stmt->bindParam(':p8', $p8, PDO::PARAM_STR);
		$stmt->bindParam(':p9', $p9, PDO::PARAM_STR);
		$stmt->bindParam(':p10', $p10, PDO::PARAM_STR);
		$stmt->bindParam(':p11', $p11, PDO::PARAM_STR);
		$stmt->bindParam(':p12', $p12, PDO::PARAM_STR);
		$stmt->bindParam(':p13', $p13, PDO::PARAM_STR);

		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);	
		$stmt->execute();
		return $rpta;	
    }


    
}
