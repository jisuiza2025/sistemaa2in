<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Serologia extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
 	protected static function list_seguimiento_serologico($request){

        $cia    = Auth::user()->empresa;


        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

   		  $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');

        $cliente =$request->cliente;
        $estado = $request->estado;
        $contrato=trim($request->contrato);

        $tipo=trim($request->tipo);

        $stmt = static::$pdo->prepare("begin LAB_COLECTAS_SEROLOGIA (:p1,:p2,:p3,:p4,:p5,:p6,:p7,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $cliente, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $start, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $end, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $tipo, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }

	

  

    
    protected static function pdf_consentimiento($contrato){

        $cia    = Auth::user()->empresa;


      

        $stmt = static::$pdo->prepare("begin WEB_FORMATO_CONSENTIMIENTO(:p1,:p2,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
       
       
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }


    protected static function guarda_nueva_bitacora_serologia($request){

        

    $p1 = Auth::user()->empresa;
    $p2 = $request->contrato;
    $p3 = $request->observaciones;

    $p4 = (empty($request->proximo_contacto))?null:Carbon::parse($request->proximo_contacto)->format('Y-m-d h:i:s');
   
    $p5 = Auth::user()->codigo;
    $p6 = (empty($request->atencion))?null:Carbon::parse($request->atencion)->format('Y-m-d h:i:s');
    $p7 = ($request->estado)?'C':'P';




    $stmt = static::$pdo->prepare("begin WEB_REGISTRO_BITACORASEROLOGIA(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:rpta); end;");
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



   	protected static function salvar_generacion_solicitud_serologia($request){

   	$p1 = Auth::user()->empresa;
		$p2 = $request->contrato;
    $p3 = Carbon::parse($request->fecha)->format('d/m/Y');
    $p4 = $request->laboratorio;
    $p5 = $request->servicio;
    $p6 = Auth::user()->codigo;

		
		$stmt = static::$pdo->prepare("begin WEB_REGISTRO_SERVICIO_LAB(:p1,:p2,:p3,:p4,:p5,:p6,:rpta); end;");
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










  

  protected static function set_listado_bitacora_serologia($request){

        $cia    = Auth::user()->empresa;


        $contrato=trim($request->contrato);


        $stmt = static::$pdo->prepare("begin WEB_LISTA_BITACORASEROLOGIA(:p1,:p2,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }


  protected static function ver_servicio_informacion_serologia($request){

        $cia    = Auth::user()->empresa;


        $contrato=trim($request->contrato);


        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_LABORATORIO_GET (:p1,:p2,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }

protected static function list_laboratorios(){

        $cia    = Auth::user()->empresa;


        
       

        $stmt = static::$pdo->prepare("begin WEB_LIST_LABORATORIOS (:p1,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);



        $result = array();

        foreach ($list as $value) {
          
          $result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;
        
       
    }


    
    


    protected static function list_seguimiento_serologico_servicios($request){

        $cia    = Auth::user()->empresa;

        $empresa = $request->empresa;
        
       

        $stmt = static::$pdo->prepare("begin WEB_LIST_SERVICIOSLAB(:p1,:p2,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $empresa, PDO::PARAM_STR);
        
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);



        $result = array();

        foreach ($list as $value) {
          
          $result[] = array("id"=>$value["CODIGO_SERVICIO"],"text"=>$value["DESCRIPCION_SERVICIO"]);
        }
        
        return $result;
        
       
    }






   	
 
}
