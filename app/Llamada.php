<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Llamada extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    protected static function set_indicadores_tabla_listado_llamadas($responsable){

        
        $p1 = Auth::user()->empresa;
        $p2 = ($responsable == '0')?'':$responsable;
       

        

        $stmt = static::$pdo->prepare("begin WEB_CXC_DOCUMENTOS_RESLLAMADAS (:p1, :p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
       
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }




    protected static function list_llamadas_filtro_todos() {
    $p1 = Auth::user()->empresa;

    $stmt = static::$pdo->prepare("begin WEB_CXC_EXCEL_COBLLAMADAS(:p1,:c); end;");
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);

  

    return collect($list); 
}






 	protected static function list_llamadas_filtro1($request){

       
        $p1 = Auth::user()->empresa;
        $p2 = $request->responsable;
        $p3 = $request->tipo;

        $p4 = $request->estado;

        $stmt = static::$pdo->prepare("begin WEB_CXC_DOCUMENTOS_COBLLAMADAS(:p1,:p2,:p3,:p4,:c); end;");
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


    protected static function list_llamadas_filtro2($request){

        
        $p1 = Auth::user()->empresa;
        $p2 = trim($request->contrato);
        $p3 = intval($request->busqueda);

        

        $p4 = $request->cliente;
        $p5 = (!empty($request->dia_mes_naci))?Carbon::parse($request->dia_mes_naci)->format('d/m'):null;
        $p6 = (!empty($request->periodo_ven))?Carbon::parse($request->periodo_ven)->format('m/Y'):null;
        //$p7 = (empty($request->anios_ven))?0:$request->anios_ven;

        $p7 =$request->anios_ven;

        //dd($p7);
        //die();

        //fecha de nacimiento
        //$p8 = (!empty($request->fec_naci))?Carbon::parse($request->fec_naci)->format('d/m/Y'):null;

        $p8_start = ($request->fec_naci[0] =='null'  ||  $request->fec_naci[0] == null)?'':Carbon::parse($request->fec_naci[0])->format('Y-m-d');

        $p8_end  = ($request->fec_naci[1] =='null' ||  $request->fec_naci[1] == null)?'':Carbon::parse($request->fec_naci[1])->format('Y-m-d');

        //dd($p8_end);
        //die();

        //fin fecha de nacmineto


        //fecha de vencimiento
        //$p9 = (!empty($request->fecha_ven))?Carbon::parse($request->fecha_ven)->format('d/m/Y'):null;

        $p9_start = ($request->fecha_ven[0] =='null'  ||  $request->fecha_ven[0] == null)?'':Carbon::parse($request->fecha_ven[0])->format('Y-m-d');

        $p9_end  = ($request->fecha_ven[1] =='null' ||  $request->fecha_ven[1] == null)?'':Carbon::parse($request->fecha_ven[1])->format('Y-m-d');

        //fin fecha vencimiento
    

        $p10 = $request->codigo_usuario;
        $p11 = trim($request->pre_contrato);


        $p12 = strtoupper(trim($request->correo));
        $p13 = trim($request->celular);
        $p14 = trim($request->fijo);

        $p15 = trim($request->cantidad_contratos);


        $stmt = static::$pdo->prepare("begin WEB_CXC_DOCUMENTOS_VENCONTRATO(:p1, :p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,:c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $p8_start, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $p8_end, PDO::PARAM_STR);

        $stmt->bindParam(':p10', $p9_start, PDO::PARAM_STR);
        $stmt->bindParam(':p11', $p9_end, PDO::PARAM_STR);
        $stmt->bindParam(':p12', $p10, PDO::PARAM_STR);
        $stmt->bindParam(':p13', $p11, PDO::PARAM_STR);
        $stmt->bindParam(':p14', $p12, PDO::PARAM_STR);
        $stmt->bindParam(':p15', $p13, PDO::PARAM_STR);
        $stmt->bindParam(':p16', $p14, PDO::PARAM_STR);
         $stmt->bindParam(':p17', $p15, PDO::PARAM_STR);


    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }






   
}
