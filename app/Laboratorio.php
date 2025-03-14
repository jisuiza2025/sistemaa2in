<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Laboratorio extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
 	protected static function list_informes_laboratorio($request){

    $cia = Auth::user()->empresa;

      $contrato = trim($request->contrato);
      $cliente = $request->cliente;
      $estado = ($request->estado=='E')?1:0;
      $tipo = $request->tipo;


        
        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

   		$end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');



         if(count((array)$request->servicios)>0){

          $servicio = implode(",",$request->servicios);
        }else{

          $servicio = null;

        }

        $flag = (filter_var($request->fecha, FILTER_VALIDATE_BOOLEAN))?1:0; 


       
        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_LIST_SENDINFOLAB (:p1, :p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $cliente, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $start, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $end, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $servicio, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $flag, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }

	
   	 //new mx
  protected static function get_doc_byte($numcontrato, $tipo)
  {

    $p1 = Auth::user()->empresa;
    $p2 = $numcontrato;
    $p3 = $tipo;

    $stmt = static::$pdo->prepare("begin WEB_CONTRATO_DOCBINARY_GETTIPO(:p1,:p2,:p3,:c); end;");

    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);

    return $list[0];
  }

  //new mx
  protected static function atender_envio_informecom($numcontrato,$estado)
  {
      $p1 = Auth::user()->empresa;
      $p2 = $numcontrato;
      $p3 = Auth::user()->codigo;
      $p4 = $estado;
      $stmt = static::$pdo->prepare("begin WEB_ATENDER_ENVIO_INFORMECOM(:p1,:p2,:p3,:p4,:rpta); end;");

      $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
      $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
      $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
      $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
      $stmt->execute();

      return $rpta;
  }






  protected static function informe_laboratorio_descarga_laboratorio($contrato,$colecta){

    $cia = Auth::user()->empresa;

    
    

    

       
        $stmt = static::$pdo->prepare("begin LAB_COLECTAS_INFORME (:p1, :p2,:p3, :c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $colecta, PDO::PARAM_STR);
        
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }



    protected static function informe_laboratorio_descarga_serologia($contrato){

    $cia = Auth::user()->empresa;

    
    

    

        $stmt = static::$pdo->prepare("begin WEB_INFORME_SEROLOGIA_CAB(:p1,:p2,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
      
        
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }


    protected static function informe_laboratorio_descarga_serologia2($contrato){

    $cia = Auth::user()->empresa;

    
    

    

       
        $stmt = static::$pdo->prepare("begin WEB_INFORME_SEROLOGIA_DET (:p1, :p2, :c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
      
        
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }
   



   


   	
    
}
