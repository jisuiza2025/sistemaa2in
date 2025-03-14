<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class TablaMaestra extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
 	protected static function list_tablaMaestra(){

        $stmt = static::$pdo->prepare("begin WEB_TABLAMAESTRA_LISTAR(:c); end;");
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }



    


    protected static function valida_nuevo_registro($request){



       
        $p1 = trim($request->tipo);
        $p2 = trim($request->codigo);
        $p3 = trim($request->tabla);
        $p4 = trim($request->etiqueta); 



        $stmt = static::$pdo->prepare("begin WEB_TABLAMAESTRA_ADMIN_VAL(:p1,:p2,:p3,:p4,:rpta); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();

    
        return $rpta;
    }


    protected static function list_tablaMaestra_adm($request){


    	$p1 = trim($request->tabla);

        $p2 = trim($request->etiqueta);

        $stmt = static::$pdo->prepare("begin WEB_TABLAMAESTRA_ADMIN_LIST(:p1,:p2,:c); end;");
    	
    	$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }



    protected static function save_edit_tablamaestra($request){

    	$p1 = trim($request->tipo);
    	$p2 = trim($request->tabla);
    	$p3 = trim($request->codigo);
    	$p4 = trim($request->descripcion);
    	$p5 = trim($request->modo);

        $etiqueta = trim($request->etiqueta);    	

    	if ($p5 == 'editar') {

    		$stmt = static::$pdo->prepare("begin WEB_TABLAMAESTRA_ADMIN_UPD(:p1,:p2,:p3,:p4,:etiqueta,:rpta); end;");
    	}
    	else{

    		if ($p1 == 1) {

    			$p3 = '';
    		}

    		$stmt = static::$pdo->prepare("begin WEB_TABLAMAESTRA_ADMIN_INP(:p1,:p2,:p3,:p4,:etiqueta,:rpta); end;");
    	}
    	  
    	
    	$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
  		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
  		$stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':etiqueta', $etiqueta, PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);	
		$stmt->execute();
		return $rpta;
    }


    

    protected static function anular_tablaMaestra_adm($request){

		$p1 = trim($request->tipo);
		$p2 = trim($request->codigo);
		$p3 = trim($request->tabla);
        $p4 = trim($request->etiqueta);
		
		$stmt = static::$pdo->prepare("begin WEB_TABLAMAESTRA_ADMIN_DEL(:p1, :p2, :p3,;p4, :c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
		$stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
		
		$stmt->execute();
        return $rpta;
    }

    

}
