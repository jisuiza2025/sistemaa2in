<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class TipoDocumento extends Model
{   
 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}
   
 	protected static function list_tipoDocumento(){

 		$p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_TIPOS_DOCUMENTOS_LIST(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }


    protected static function list_grupo(){

    	$p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin  WEB_COR_GRUPOS_LISTADO(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_GRUPO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;
    }
	

	protected static function list_impuesto(){

        $stmt = static::$pdo->prepare("begin WEB_COR_IMPUESTOS_LIST(:c); end;");
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;
    }


    protected static function list_sunat(){

    	$p1 = '';
        $stmt = static::$pdo->prepare("begin WEB_COR_SUNAT_AUTOCOMP(:p1, :c); end;");
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_SUNAT"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;
    }
    


    protected static function desactiva_tipoDocumento($request){

		$p1 = Auth::user()->empresa;
		$p2 = trim($request->identificacion);
		
		$stmt = static::$pdo->prepare("begin WEB_TIPO_DOCUMENTOS_DELET(:p1, :p2, :c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
		
		$stmt->execute();
        return $rpta;
    }


    protected static function get_item_tipoDocumento($request){

    	$p1 = Auth::user()->empresa;
		$p2 = trim($request->identificacion);

		$stmt = static::$pdo->prepare("begin WEB_TIPOS_DOCUMENTOS_OBTDATOS(:p1, :p2, :c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
			
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
	   	oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
	    oci_free_cursor($cursor);
	        
	    return $list;  
	}
   	
   	protected static function valida_documento_existente($request){

   		$p1 = Auth::user()->empresa;
		$p2 = trim($request->tipoDoc);


   		$stmt = static::$pdo->prepare("begin WEB_TIPO_DOCUMENTOS_VALD(:p1,:p2,:rpta); end;");
			$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
			$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

			$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);	
			$stmt->execute();


		return $rpta;

   	}


  	protected static function save_tipoDocumento($request){

		$p1 = Auth::user()->empresa;
		$p2 = trim($request->tipoDoc);
		$p3 = trim($request->descripcion);
		$p4 = $request->tipoMovimiento;
		$p5 = $request->impuesto;
		$p6 = trim($request->secuencia);
		$p7 = trim($request->doc_anular);
		$p8  = ($request->reg_ventas)?'S':'N';
		$p9  = $request->sunat;
		$p10 = $request->grupo;


		$stmt = static::$pdo->prepare("begin WEB_TIPO_DOCUMENTOS_INUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:rpta); end;");


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

		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);	
		$stmt->execute();
		return $rpta;

			
    } 
}
