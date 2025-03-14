<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Cobranza extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
    

    protected static function contratos_por_cobrar_cobradores($request){

        $p1 = Auth::user()->empresa;

         $p2 = $request->servicio;

       
        
        $stmt = static::$pdo->prepare("begin WEB_COBRADORES_CONTRATO(:p1,:p2, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["NOMBRE"]);
        }
        
     

         //$result = array_merge(array(array('id'=>' ','text'=>'TODOS')),$result);

         return $result;

    }


    

   



    



 protected static function contratos_cobrados_lista($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->cobrador; 
        $p3 = $request->btn_ver_todo;
        $p4 = $request->servicio;
        $p5 = Carbon::parse($request->periodo)->format('m/Y');

       


        if( $p3 == 0 ){

            $p2 =  Auth::user()->identificacion;

        }

      
        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_COBRADOS(:p1,:p2,:p3,:p4,:p5,:c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;


    }


    protected static function contratos_por_cobrar_lista($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->cobrador; 
        $p3 = $request->btn_ver_todo;
        $p4 = $request->servicio;
        $p5 = $request->periodo;


        if( $p3 == 0 ){

            $p2 =  Auth::user()->identificacion;

        }

      
        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_COBRAR(:p1,:p2,:p3,:p4,:p5,:c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;


    }
   

    


    protected static function confirmar_contratos_por_cobrar($request,$sub){

      

        $cia   =  Auth::user()->empresa;
        
        $servicio = $request->servicio;

        $usuario  = Auth::user()->codigo;

        $data = $request->data;

        
        

        $list_contratos ='';

        foreach($sub as $values){

          $list_contratos .= $values["NUMERO_CONTRATO"].",";
         
        }

         $list_contratos = rtrim($list_contratos,',');




         
         
        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_CAMBIACOBRADOR(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
       
        $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);

        $stmt->bindParam(':p3', $list_contratos, PDO::PARAM_STR,100000000);

        $stmt->bindParam(':p4', $usuario, PDO::PARAM_STR);

        
     
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();

        return $rpta ;

       
    

  }
}
