<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;

class DocVenta extends Model
{
    protected  static $pdo;

    protected function __construct()

    { 
        static::$pdo = DB::getPdo();
    }

    protected  static function list_documento_venta($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->tipo_doc;
     
      $p3 = $request->num_doc;
      
      $p4 = $request->numero_contrato;
      $p5 = $request->cliente;
      $p6 = $request->num_operacion;
      $p7 = ($request->fecha_activa=="true")?1:0;

      
        
     

      $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('Y-m-d');

    $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('Y-m-d');

   
       
        $stmt = static::$pdo->prepare("begin WEB_CXC_DOCUMENTOS_LISTADO(:p1, :p2, :p3, :p4, :p5, :p6, :p7, :p8, :p9, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $p7, PDO::PARAM_INT);
        $stmt->bindParam(':p8', $start, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $end, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      

        return $list;
    }





    protected static function cuotas_detalle_documento_venta($request){

        $cia    = Auth::user()->empresa;    

        $tipo_doc   = $request->tipo_doc;   

        $num_doc    = $request->num_contrato; 

       
        //$num_doc    = '01700000185';  

        

        $stmt = static::$pdo->prepare("begin WEB_VENFACTURACUOTAALL_CUOT(:p1, :p2, :p3, :c); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);

        $stmt->bindParam(':p2', $tipo_doc, PDO::PARAM_STR);

        $stmt->bindParam(':p3', $num_doc, PDO::PARAM_STR);
          
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);


        return $list;

    }  



    protected static function reporte_facturacion($request){

        $cia    = Auth::user()->empresa;    

        $tipo_doc   = $request->tipo_doc;   

        $num_doc    = $request->num_contrato; 

       
        //$num_doc    = '01700000185';  

        

        $stmt = static::$pdo->prepare("begin WEB_REPORT_DOCUMENTO_PAGO(:p1, :p2, :p3, :c); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);

        $stmt->bindParam(':p2', $tipo_doc, PDO::PARAM_STR);

        $stmt->bindParam(':p3', $num_doc, PDO::PARAM_STR);
          
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);


        return $list;

    }  

     protected  static function list_documento_detalle($request){

        $p1 = Auth::user()->empresa;

        $p2   = $request->tipo_doc; 

        $p3    = $request->num_doc;

        //$p3    = '01700000185';

        $stmt = static::$pdo->prepare("begin WEB_CXC_DOCUMENTOS_RF_LISTA(:p1, :p2, :p3, :c); end;");
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

    protected  static function list_referencia($request){

      $p1 = Auth::user()->empresa;

        $p2   = $request->tipo_doc; 

       

      $p3    = $request->num_doc;

     
      $stmt = static::$pdo->prepare("begin WEB_CXC_DOCUMENTOS_REFEN_LISTA(:p1, :p2, :p3, :c); end;");
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

     protected  static function anular_visible($request){

        $p1 = Auth::user()->empresa;

     
        $p2   = $request->tipo_doc; 

        $p3    = $request->num_doc;  
        
     
        $stmt = static::$pdo->prepare("begin WEB_CXC_DOCUMENTOS_ANULAR_VAL(:p1, :p2, :p3, :c); end;");
      $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
      $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
      $stmt->bindParam(':c', $cursor, PDO::PARAM_INT);
      
        
        $stmt->execute();

        return $cursor;
    } 

    protected  static function documento_mensaje($request){

        $p1 = Auth::user()->empresa;

        $p2   = $request->tipo_doc; 

        $p3    = $request->num_doc;  

        $stmt = static::$pdo->prepare("begin WEB_FACTURACION_ELECT_MENSA(:p1,:p2,:p3,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();

        return $rpta;
    }

    protected  static function list_info_fac($request){

        $p1 = Auth::user()->empresa;

        $p2   = $request->tipo_doc; 

        $p3    = $request->num_doc;
        $stmt = static::$pdo->prepare("begin WEB_FACTURACION_ELECT_INFO(:p1, :p2, :p3, :c); end;");

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

     protected  static function anular_documento($request){

        $p1 = Auth::user()->empresa;

        $p2   = $request->tipo_doc; 

        $p3    = $request->num_doc;  

        $p4    = $request->motivo_anulacion;  

        $p5    = Auth::user()->id; 

        $stmt = static::$pdo->prepare("begin WEB_ANULAR_DOCUMENTOS(:p1,:p2,:p3,:p4, :p5, :rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();

        return $rpta;
    }
    protected  static function asignar_contrato($request){
        try {
        $p1 = Auth::user()->empresa;

        $p2   = $request->tipo_doc; 

        $p3    = $request->num_doc;  

        $p4    = $request->num_contrato;  

        $p5    =$request->fecha_desde;
        
        $p6    =$request->fecha_hasta;

        $stmt = static::$pdo->prepare("begin WEB_CXC_DOCUMENTOS_ASIGNAR_CON(:p1,:p2,:p3,:p4, :p5,:p6); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
         
        //$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return 1;
        }
        catch(Exception $e){
             
            return 99;
        }
        
    }

   
}
