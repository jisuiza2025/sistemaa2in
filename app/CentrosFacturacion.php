<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class CentrosFacturacion extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
    

    protected static function get_asignaciones_centro($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->centro;
    
        $stmt = static::$pdo->prepare("begin WEB_CENTRO_FACT_USUARIO_LIST(:p1,:p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        return $list;

    }


    protected static function get_item_series_centrofact($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->centro;
    
        $stmt = static::$pdo->prepare("begin WEB_CENTRO_FACT_SERIE_LIST(:p1,:p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        return $list;

    }

    protected static function save_centros_facturacion($request){

        $p1 = Auth::user()->empresa;
        $p2 = (empty($request->codigo))?null:$request->codigo;
        $p3 = $request->descripcion;

        
        

        $stmt = static::$pdo->prepare("begin WEB_CENTRO_FACT_INUPD(:p1,:p2,:p3,:rpta); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;

    }
    

    protected static function delete_documento_centro($request){

        $p1     = Auth::user()->empresa;
        $centro = $request->centro;
        $p3     = trim($request->documento);

        $array = explode("-", $centro);
        $p2 = trim($array[0]);

       


        $stmt = static::$pdo->prepare("begin WEB_CENTRO_FACT_SERIE_DELE(:p1,:p2,:p3,:rpta); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;

    }


    protected static function inserta_documento_centrofact($request){

        $p1 = Auth::user()->empresa;
        $p2 = trim($request->centro);
        $p3 = $request->documento;
        $p4 = trim($request->inicial);
        $p5 = trim($request->serie);
        $p6 = trim($request->final);
       


        $stmt = static::$pdo->prepare("begin WEB_CENTRO_FACT_SERIE_INPUT(:p1,:p2,:p3,:p4,:p5,:p6,:rpta); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;

    }




protected static function delete_usuario_asignado($request){

        $p1 = Auth::user()->empresa;
        
        $p2 = $request->centro;

        $p3 = $request->usuario;

       


        $stmt = static::$pdo->prepare("begin WEB_CENTRO_FACT_USUARIO_DELT(:p1,:p2,:p3,:rpta); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;

    }

    protected static function inserta_usuario_centro_fact($request){

        $p1 = Auth::user()->empresa;
        
        $p2 = $request->centro;

        $p3 = $request->usuario;

       


        $stmt = static::$pdo->prepare("begin WEB_CENTRO_FACT_USUARIO_INS(:p1,:p2,:p3,:rpta); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;

    }

    


    protected static function valida_insertar_usuario_fact($request){

        $p1 = Auth::user()->empresa;
        
        $p2 = $request->centro;

        
        $stmt = static::$pdo->prepare("begin WEB_CENTRO_FACT_VALIDACION(:p1,:p2,:rpta); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
       

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;

    }

    
}
