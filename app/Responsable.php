<?php

namespace App;
use DB;
use Auth;
use PDO;

use Illuminate\Database\Eloquent\Model;

class Responsable extends Model
{
	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}
	
    protected static function list_responsable(){

    	$cia    = Auth::user()->empresa;		

   		$stmt = static::$pdo->prepare("begin WEB_CONFIG_RESPONSABLE_LIST(:p1,:c); end;");

		  $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		  
		  $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		  $stmt->execute();

		    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
       
        return $list;
    }  

    protected static function get_obtener_datos($request){

    	$cia    = Auth::user()->empresa;	

    	$cod_user    = $request->cod_user;	

        $desde    = $request->desde;  

        $hasta    = $request->hasta;  	

        $id    = $request->id;  

   		$stmt = static::$pdo->prepare("begin WEB_CONFIG_RESPONSABLE_OBDAT(:p1,:p2,:p3,:p4,:p5,:c); end;");

		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);

		$stmt->bindParam(':p2', $cod_user, PDO::PARAM_STR);

        $stmt->bindParam(':p3', $desde, PDO::PARAM_STR);

        $stmt->bindParam(':p4', $hasta, PDO::PARAM_STR);

        $stmt->bindParam(':p5', $id, PDO::PARAM_STR);
		  
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        
       
        return $list;
    } 

    protected static function eliminar_responsable($request){

   
        // $p1 = Auth::user()->empresa; 

        // $p2 = $request->cod_user;


        // $p3 = $request->desde;
        
        // $p4 = $request->hasta; 

         $id = $request->id; 
        
        $stmt = static::$pdo->prepare("begin WEB_CONFIG_RESPONSABLE_DELE(:p1,:c); end;");
        $stmt->bindParam(':p1', $id, PDO::PARAM_STR);
       

        $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();

        
     
        return $rpta;

    
    } 

    protected static function validar_responsable($request){

   
        $p1 = Auth::user()->empresa; 

        $p2 = $request->cod_user;
        $stmt = static::$pdo->prepare("begin WEB_CONFIG_RESPONSABLE_VALID(:p1, :p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
     
        return $rpta;

    
    } 

    
     protected static function save_responsable($request){
        
    //dd($request);
    //die();

        $p1 = Auth::user()->empresa; 

        $p2 = $request->cod_user;
       
        
        $desde = trim($request->desde);
        $desde_list = explode(",", $desde);

        $desde1 = (empty($desde_list[0]))?0:$desde_list[0];
        //$desde2 = (empty($desde_list[1]))?0:$desde_list[1];
         $desde2 =0;

        $p3 = $desde1.'.'.$desde2;


        $hasta = trim($request->hasta);
        $hasta_list = explode(",", $hasta);
        $hasta1 = (empty($hasta_list[0]))?0:$hasta_list[0];
        //$hasta2 = (empty($hasta_list[1]))?0:$hasta_list[1];
        $hasta2 = 0;
        $p4 = $hasta1.'.'.$hasta2;



        $p5 = ($request->par)?'S':'N';
        $p6 = ($request->impar)?'S':'N';
        $p7 = ($request->aviso_cobranza)?'S':'N';
        $p8 = ($request->atender)?'1':'0';

         $msj=null;


         $p9 = $request->id;


        $stmt = static::$pdo->prepare("begin WEB_CONFIG_RESPONSABLE_INUPD(:p1,:p2,:p3,:p4, :p5, :p6, :p7, :p8, :p9, :c,:msj); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $p8, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $p9, PDO::PARAM_STR);
        $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
         $stmt->bindParam(':msj', $msj, PDO::PARAM_STR,1000);
        
        $stmt->execute();
     
        return array($rpta,$msj);

    
    }
}
