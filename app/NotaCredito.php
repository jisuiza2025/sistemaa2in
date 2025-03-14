<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;

class NotaCredito extends Model
{
     protected  static $pdo;

    protected function __construct()

    { 
        static::$pdo = DB::getPdo();
    }

    protected function tipo_cambio(){

        $stmt = static::$pdo->prepare("begin WEB_COR_TIPO_CAMBIO(:c); end;");
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }

    protected function obtener_info_nota_cred($request){


        $p1 = Auth::user()->empresa; 

        $p2 = $request->tipo_doc;
        $p3 = $request->num_doc;

        $stmt = static::$pdo->prepare("begin WEB_DOCUEMNTOS_INFONOTA(:p1, :p2,:p3,:c); end;");
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

    protected function list_table_anulacion($request){

        $p1 = Auth::user()->empresa; 

        $p2 = $request->tipo_doc;
        $p3 = $request->num_doc;

        $stmt = static::$pdo->prepare("begin WEB_CXCDOCUMENTOS_PAGOS(:p1, :p2,:p3,:c); end;");
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
    

    protected function save_nota_credito($request){

        $p1 = Auth::user()->empresa; 

       
       $p2=$request->tipo_documento;
       $p3=$request->centro_fact;
       $p4=$request->tipo_doc_grupo;
       $p5=$request->num_doc_grupo;
       $p6=$request->anula_total;
       $p7=$request->sub_total;
       $p8=$request->igv;       
       $p9=$request->total;
       $p10 = Auth::user()->codigo;       
       $p11=$request->motivo; 
       $p12=$request->comentario;

        $stmt = static::$pdo->prepare("begin WEB_CXCDOCUMENTOS_NC_REG(:p1, :p2,:p3,:p4, :p5, :p6, :p7, :p8, :p9, :p10, :p11, :p12, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_INT);
        $stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $p8, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $p9, PDO::PARAM_STR);
        $stmt->bindParam(':p10', $p10, PDO::PARAM_STR);
        $stmt->bindParam(':p11', $p11, PDO::PARAM_STR);
        $stmt->bindParam(':p12', $p12, PDO::PARAM_STR);
        $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;
    } 


    protected function validar_modal(){

      

        $stmt = static::$pdo->prepare("begin WEB_COR_TIPO_CAMBIO_VAL(:c); end;");
       
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

         oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

       
     
        return $list;
    }  


    protected function verificar_nota_credito($request){

        $p1 = Auth::user()->empresa; 
        $p2=$request->tipo_doc;
        $p3=$request->num_doc;

        $stmt = static::$pdo->prepare("begin WEB_DOCUMENTO_FE_COUNTERROR(:p1, :p2, :p3, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
     
        return $rpta;
    }

    protected function anular_nota_credito($request){

        $p1 = Auth::user()->empresa; 
        $p2=$request->num_doc;

        $stmt = static::$pdo->prepare("begin WEB_CXCDOCUMENTOS_ANULAR_NC(:p1, :p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
     
        return $rpta;
    } 

    protected function campo_tipo_doc($request){

        $p1 = Auth::user()->empresa; 
        $p2=$request->centro_fact;

        $stmt = static::$pdo->prepare("begin WEB_TIPO_DOCUMENTO_FACTNCND(:p1, :p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();
        
        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        
        
        $result =array();

        
        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["TIPO_DOCUMENTO"],"text"=>$value["DESCRIPCION"]);
        }

        
        return $result;
    }

}
 