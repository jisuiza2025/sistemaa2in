<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Reporte extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
    protected static function get_tabla_reporte_analisis($request){




       $cia =Auth::user()->empresa;
        $servicio =  $request->servicio;
        $anio =  $request->anio;
        $tipo = $request->tipo;
      
        $stmt = static::$pdo->prepare("begin WEB_CRM_RPT_ANALISIS_ANUALI(:p1,:p2,:p3, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);
           $stmt->bindParam(':p3', $tipo, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;



    }



      protected static function export_report_analisis($servicio,$tipo,$btn){

        $cia =Auth::user()->empresa;
     

          if($btn == 1){

             $stmt = static::$pdo->prepare("begin WEB_RPT_ANL_CNTREAL(:p1,:p2,:p3,:c); end;");

          }elseif($btn == 2){

              $stmt = static::$pdo->prepare("begin WEB_RPT_ANL_PAGOSADEL(:p1,:p2,:p3,:c); end;");

          }elseif($btn == 3){
            
             $stmt = static::$pdo->prepare("begin WEB_RPT_ANL_COBRANZA(:p1,:p2,:p3,:c); end;");
          }
      
       

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $tipo, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     }


      

     


	protected static function reporte_contrato_estado_cuenta($request){

        $cia =Auth::user()->empresa;
        $num_contrato =  $request->num_contrato;

      
        $stmt = static::$pdo->prepare("begin WEB_ESTADO_CUENTA_SERVICIO(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $num_contrato, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     }
   	

     // ANALISIS
     

    protected static function proyeccion_cobranzas_anualidad($desde){

        $cia =Auth::user()->empresa;
       
        $desde = Carbon::parse($desde)->format('Ym');

        

        $stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_PROYCOBRANRES(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $desde, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     }


     protected static function detalle_proyeccion_cobranzas_anualidad($desde){

        $cia =Auth::user()->empresa;
       

        $desde = Carbon::parse($desde)->format('Ym');

        $stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_PROYCOBRANDET(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $desde, PDO::PARAM_STR);
         $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     }

     protected static function resumen_facturacion_anualidad_periodo($desde){

      
     

        $cia =Auth::user()->empresa;
       
        
         $desde = Carbon::parse($desde)->format('d/m/Y');


        $stmt = static::$pdo->prepare("begin WEB_VENFAC_ANUA_PERIODO_RES(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $desde, PDO::PARAM_STR);
         $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     }


      protected static function detalle_resumen_facturacion_anualidad_periodo($desde,$hasta){

        $cia =Auth::user()->empresa;
       
        $desde = Carbon::parse($desde)->format('d/m/Y');
        $hasta = Carbon::parse($hasta)->format('d/m/Y');
        
       

        $stmt = static::$pdo->prepare("begin WEB_VENFAC_ANUA_PERIODO_DET(:p1,:p2,:P3, :c); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $desde, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $hasta, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     }


     protected static function informacion_contratos_periodo_vencimiento($desde){

        $cia =Auth::user()->empresa;
       

        $desde = Carbon::parse($desde)->format('d/m/Y');

        $stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_PERIODO_INFO(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $desde, PDO::PARAM_STR);
         $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     }

     protected static function facturacion_anualidades($desde,$hasta){

        $cia =Auth::user()->empresa;
       

        $desde = Carbon::parse($desde)->format('d/m/Y');
        $hasta = Carbon::parse($hasta)->format('d/m/Y');


        $stmt = static::$pdo->prepare("begin WEB_VENFACTURACION_ANUALIDADES(:p1,:p2,:p3, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $desde, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $hasta, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     }
   

    
}
