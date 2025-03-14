<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class RegistroPago extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
    
	protected static function get_tc_fecha_operacion_liquidacion($request){

        
        $fecha =  Carbon::parse($request->fecha)->format('d/m/Y');

        $stmt = static::$pdo->prepare("begin WEB_COR_TIPOCAMBIO_X_FECHA(:p1,:c); end;");

          $stmt->bindParam(':p1', $fecha, PDO::PARAM_STR);
         
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list[0]['FACTOR_CAMBIO'];

     }


      protected static function get_notas_credito_liquidacion($request){
    
        
        $p1 = Auth::user()->empresa;

        if(!empty($request->contrato)){

          $p2 = trim($request->contrato);

        }elseif(!empty($request->documento)){

          $p2 = trim($request->documento);

        }elseif(!empty($request->cliente)){

          $p2 = trim($request->cliente);
        }
        

        $stmt = static::$pdo->prepare("begin WEB_CXCDOCUMENTOS_NC_SALDO(:p1,:p2,:c); end;");

          $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
         
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     }
    



    protected static function get_data_titular_cliente_regpag($request){


    $p1 = Auth::user()->empresa;
    $p2 = $request->contrato;
    
    $stmt = static::$pdo->prepare("begin  WEB_DATITULAR_CONTRATO_REGPAG(:p1,:p2,:rpta); end;");
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
    $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
     
        return $list;


  }
  
    protected static function get_documentos_liquidar($request){
    
        
        $p1 = Auth::user()->empresa;

        $p2 = $request->contrato;

        $p3 = $request->tdocumento;

        

        $stmt = static::$pdo->prepare("begin WEB_DOCUMENTOS_PORLIQUIDAR(:p1,:p2,:p3,:c); end;");

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
   	
   
   protected static function obtener_numero_liquidacion_result($list){
    
        
        $p1 = Auth::user()->empresa;

        $p2 = $list['cliente'];

        $p3 = $list['medio'];

        $p4 = $list['descripcion_medio'];

        $p5 = $list['moneda'];

        $p6 = $list['monto'];

        $p7 = $list['tcambio'];

        $p8 = $list['banco'];

        $p9 = $list['operacion'];

        $p10 = Carbon::parse($list['fecha_operacion'])->format('d/m/Y');

        $p11 = $list['contrato'];

        
        $p12 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin WEB_LIQUIDACION_DOCUMENTOS(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:rpta); end;");

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
         
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

    
          return $rpta;

     }





     protected static function registro_pago_liquidacion($details,$referencia,$cabecera){

          $identificacion = $cabecera['cliente'];

          $pago    = $cabecera['medio'];

          $tcambio = $cabecera['tcambio'];

          $nota_credito = $cabecera['num_nc'];

          

          $cia    = Auth::user()->empresa;

          $usuario = Auth::user()->codigo;
      
        foreach($details as $values){

            $tipo_documento = $values['TIPO_DOCUMENTO'];
            $num_documento = $values['NUMERO_DOCUMENTO'];
            $moneda = $values['MONEDA'];
            $monto = $values['SALDO'];

            $rpta = self::inserta_documentos_afectados($cia,$identificacion,$pago,$moneda,$monto,$tcambio,$tipo_documento,$num_documento,$usuario,$referencia,$nota_credito);

            if($rpta == 0){

                return 0;
            }

        }


        return 1;
     }

   protected static function inserta_documentos_afectados($p1,$p2,$p3,$p4,$p5,$p6,$p7,$p8,$p9,$p10,$p11){


        $stmt = static::$pdo->prepare("begin WEB_LIQUIDACION_DOCUMENTOS_RF(:p1,:p2,:p3,:p6,:p7,:p8,:p9,:p10,:p11,:rpta); end;");

          $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
          //$stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
          //$stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
          $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
          $stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
          $stmt->bindParam(':p8', $p8, PDO::PARAM_STR);
          $stmt->bindParam(':p9', $p9, PDO::PARAM_STR);
          $stmt->bindParam(':p10', $p10, PDO::PARAM_STR);
          $stmt->bindParam(':p11', $p11, PDO::PARAM_STR);
         
         
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

    
          return $rpta;

   }

   

   protected static function get_documentos_asistente_facturacion($tipo_documento,$numero_documento){



        $cia = Auth::user()->empresa;

      
        $stmt = static::$pdo->prepare("begin WEB_DOC_XLIQUIDAR_ASISFACT(:p1,:p2,:p3,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $numero_documento, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $tipo_documento, PDO::PARAM_STR);
         
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
          oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
          oci_free_cursor($cursor);

      
        
        return (isset($list[0]))?$list[0]:array();

   }

    
}
