<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;

class Maestro extends Model
{   
    

	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

        
        

    protected static function options_tablas_d1(){

        $p1 = 24;

       
        
        $stmt = static::$pdo->prepare("begin cor_tablas_xtipo(:p1, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO"],"text"=>$value["DESCRIPCION"]);
        }
        
        //return $result;

         $result = array_merge(array(array('id'=>null,'text'=>'')),$result);

         return $result;
    
    }


        protected static function cmb_servicios_filtro_all(){

       
    
        $p1= 22;
        
    
        $stmt = static::$pdo->prepare("begin COR_TABLAS_XTIPO(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["VALORCAD"],"text"=>$value["DESCRIPCION"]);
        }
        

        $result = array_merge(array(array('id'=>'null','text'=>'TODOS')),$result);
        return $result;

        

    
    }





    protected static function cmb_servicios_filtro(){

       
    
        $p1= 22;
        
    
        $stmt = static::$pdo->prepare("begin COR_TABLAS_XTIPO(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["VALORCAD"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

        

    
    }


	protected static function list_categoria(){

   		//aplica tambien para clase de cliente
   		
		$p1 = Auth::user()->empresa;

        
	
		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_CLASE(:p1, :c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
	
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["CLASE_CLIENTES"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

        

    
    }
    

     protected static function list_cor_tablas($p1){

        
        
       

        
    
        $stmt = static::$pdo->prepare("begin COR_TABLAS_XTIPO(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["VALORCAD"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

        

    
    }


    protected static function list_categorizacion_cmb(){

        
        
        $p1 = 14;

        
    
        $stmt = static::$pdo->prepare("begin COR_TABLAS_XTIPO(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["VALORNUM"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

        

    
    }


    protected static function list_tipo_documento(){

   
	
		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_DOCUMENTOS(:c); end;");
		
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["CODIGO_DOCUMENTO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }

    
    
    protected static function list_estado_civil(){

   
	
		$stmt = static::$pdo->prepare("begin WEB_CLIENTE_ESTADO_CIVIL(:c); end;");
		
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["ESTADO_CIVIL"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }

    protected static function list_ocupacion(){

		$p1 = '';
	
		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_OCUPACION(:p1, :c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
	
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["CODIGO_CARGO"],"text"=>$value["NOMBRE_CARGO"]);
        }
        
        return $result;

    
    }
   

    protected static function list_paises(){

		$p1 = '';
	
		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_PAISES(:p1, :c); end;");
		
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
	
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result = array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["CODIGO_PAIS"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;

    
    }


    protected static function list_departamento(){

		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_DEPARTAMENTO(:c); end;");
		
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
        	
           $result[] = array("id"=>$value["DEPARTAMENTO"],"text"=>$value["DEPARTAMENTO"]);
        	
        }
        
        return $result;

    
    }



    protected static function filter_ubigeo($request){

		$p1 = $request->get('q');
	
		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_UBIGEO(:p1, :c); end;");
		
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
	
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_UBIGEO"],"text"=>$value["FULL_UBIGEO"]);
        }
        
        return $result;

    
    }

    
    protected static function filter_medio_concatenado($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->get('q');
        
        $stmt = static::$pdo->prepare("begin WEB_MEDIOSCAPTACIONFIL(:p1,:p2, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }

    protected static function filter_cliente($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->get('q');
        
        $stmt = static::$pdo->prepare("begin WEB_CLIENTES_AUTOCOMPLETAR(:p1,:p2, :c); end;");
        
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
        
        return $result;

    
    }

    protected static function filter_vendedor($request){

        $p1 = Auth::user()->empresa;

        $p2 = strtoupper($request->get('q'));
        
        $stmt = static::$pdo->prepare("begin WEB_VENDEDOR_LISTA_AUTOMPLETA(:p1,:p2, :c); end;");
        
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
        
        return $result;

    
    }




    protected static function filter_clinica($request){

        $p1 = Auth::user()->empresa;

        $p2 = strtoupper($request->get('q'));
        
        $stmt = static::$pdo->prepare("begin WEB_HOSPITALES_AUTOCOMPLETA(:p1,:p2, :c); end;");
        
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
        
        return $result;

    
    }


    protected static function filter_captador($request){

        $p1 = Auth::user()->empresa;

        $p2 = strtoupper($request->get('q'));
        
        $stmt = static::$pdo->prepare("begin WEB_CAPTADOR_LISTA_AUTOMPLETA(:p1,:p2, :c); end;");
        
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
        
        return $result;

    
    }

    protected static function filter_medico($request){

        $p1 = Auth::user()->empresa;

        $p2 = strtoupper($request->get('q'));
        
        $stmt = static::$pdo->prepare("begin WEB_MEDICO_AUTOCOMPLETA(:p1,:p2, :c); end;");
        
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
        
        return $result;

    
    }


    protected static function list_captacion(){

        $p1 = Auth::user()->empresa;

        
        $stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_SEINFORMO(:p1, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["SE_INFORMO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }

    

    protected static function list_captacion_ficha_tabla($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->captacion;

        $p3 = $request->tipo;

        $p4='';
        
        $stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_SEINFORMO_D3(:p1,:p2,:p3,:p4,:c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_EVENTO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;


    
    }


    protected static function list_captacion_tipo($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->captacion;

        $stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_SEINFORMO_D1(:p1, :p2,:c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();


        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO"],"text"=>$value["DESCRIPCION"],"tabla"=>$value["FLAGTABLA"]);
        }
        
        return $result;

    
    }

    protected static function list_captacion_ficha($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->captacion;

        $p3 = $request->tipo;

        $stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_SEINFORMO_D2(:p1, :p2,:p3,:c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_EVENTO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }


    protected static function list_estado_prospecto(){

        $p1 = Auth::user()->empresa;


        $stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_ESTADO(:p1,:c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_ESTADO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }


    protected static function list_precio($request){

        $p1 = Auth::user()->empresa;

        //$p2 = strtoupper($request->get('q'));

        $p2='';
       
        
        $stmt = static::$pdo->prepare("begin WEB_LISTPRECIO_AUTOCOMPLETA(:p1,:p2, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["LISTA_PRECIO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }


    protected static function list_servicio($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->lista_precio;
        
        $p3 = $request->moneda;

        $stmt = static::$pdo->prepare("begin WEB_LISPRECIO_ARTICULOS(:p1,:p2,:p3, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_ARTICULO"],"text"=>$value["SERVICIO"].' / '.$value["PRECIO"]);
        }
        
        return $result;

    
    }



    protected static function list_aseguradora(){

        $p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_ASEGURADORA_LISTA(:p1, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR); 
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["RAZONSOCIAL"]);
        }
        
        return $result;

    
    }



    protected static function list_planes($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->identificacion;

       

        $stmt = static::$pdo->prepare("begin WEB_ASEGURADORA_PLANSEGURO(:p1,:p2,:c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR); 
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR); 
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_PLAN"],"text"=>$value["DESCRIPCION"].' / '.$value["PORCENTAJE"]);
        }
        
        return $result;

    
    }


    protected static function get_lista_precios(){

        $p1 = Auth::user()->empresa;

      
        $stmt = static::$pdo->prepare("begin WEB_LISTAPRECIO_LIST(:p1,:c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR); 
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        
        return $list;

    
    }


    protected static function list_articulos($request){

        $p1 = Auth::user()->empresa;

        $p2 = strtoupper($request->get('q'));
        
        $stmt = static::$pdo->prepare("begin WEB_ARTICULOS_LIST_AUTOCOMP(:p1,:p2, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_ARTICULO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }


    protected static function get_list_servicio(){

        
        $p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_CONFIG_SERVICIOS_LIST(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;

    
    
    }




    protected static function get_list_servicio2(){

        $p1 = 2;

       
        
        $stmt = static::$pdo->prepare("begin cor_tablas_xtipo(:p1, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["VALORCAD"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }




    protected static function list_vendedores($request){

        
        $p1 = Auth::user()->empresa;

        $p2 = $request->activo;

        $stmt = static::$pdo->prepare("begin WEB_VENDEDORES_LIST(:p1,:p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;

    
    
    }

    protected function filter_responsable(){      

        

        $p1 = Auth::user()->empresa;
        
        $stmt = static::$pdo->prepare("begin WEB_RESPONSABLE_COBRANZA(:p1,:c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        $todos = array("id"=>0,"text"=>"TODOS");

        array_push($result, $todos);

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_USUARIO"],"text"=>$value["NOMBRE"]);
        }
        


        return $result;
        

    }



    protected static function list_captadores($request){

        $p1 = Auth::user()->empresa;

        $p2 = ($request->activo==1)?'A':'I';
        
        $stmt = static::$pdo->prepare("begin WEN_CAPTADORES_LISTADO(:p1,:p2, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
       
        
        return $list;

    
    }
    
   

   protected static function filter_cor_user($request){

       

        $p1 = strtoupper($request->get('q'));
        
        $stmt = static::$pdo->prepare("begin WEB_COR_USUARIOS_AUTOCOMP(:p1, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
       

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["DNI"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;

    
    }

    protected static function filter_cor_user2($request){

       

        $p1 = strtoupper($request->get('q'));
        
        $stmt = static::$pdo->prepare("begin WEB_COR_USUARIOS_AUTOCOMP(:p1, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
       

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_USUARIO"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;

    
    }

    protected static function list_clinicas($request){

       
        $p1 = Auth::user()->empresa;

        $p2 = $request->clinica;

        $p3 = $request->estado;
        
        $stmt = static::$pdo->prepare("begin WEB_HOSPITALES_LIST(:p1,:p2,:p3,:c); end;");
        
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



    protected static function get_item_clinica($request){

       
        $p1 = Auth::user()->empresa;

        $p2 = trim($request->clinica);
        
        $stmt = static::$pdo->prepare("begin WEB_HOSPITALES_LIST_EDIT(:p1,:p2, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
       
        
        return $list;

    
    }



    protected static function list_centros($request){

       
        $p1 = Auth::user()->empresa;

        $p2 = $request->centro;
        
        $stmt = static::$pdo->prepare("begin WEB_CENTRO_FACT_LISTADO(:p1,:p2, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
       
        
        return $list;

    
    }



    protected static function documentos_centros_facturacion(){

       

        $p1 = Auth::user()->empresa;
        
        $stmt = static::$pdo->prepare("begin WEB_CENTRO_FACT_TIPODOC_LIS(:p1, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
       

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


    

     protected static function usuarios_autocompletar_cf(){

       

      
        
        $stmt = static::$pdo->prepare("begin WEB_COR_USUARIOS_ACTIVO_ACOMPL( :c); end;");
        
      
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_USUARIO"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;

    
    }

    protected static function list_monedas(){

        $stmt = static::$pdo->prepare("begin WEB_MONEDA_LIST(:c); end;");
        
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["MONEDA"],"text"=>strtoupper($value["DESCRIPCION"]));
        }
        
        return $result;
    }


    protected static function list_clase_cambio(){

        $stmt = static::$pdo->prepare("begin WEB_COR_CLASECAMBIO_LIST(:c); end;");
        
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CLASE_CAMBIO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;
    }


    protected static function get_responsables_llamadas(){

        $p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_RESPONSABLE_COBRANZA_Q01 (:p1,:c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        $todos = array("id"=>0,"text"=>"TODOS");

        array_push($result, $todos);
        
        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_USUARIO"],"text"=>$value["NOMBRE"]);
        }
        
        
        return $result;
    }

    //facturacion
    //
    //
    protected static function facturacion_centros(){

        $p1 = Auth::user()->empresa;

        $p2 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin WEB_COR_USUARIO_CENTRO_LIST (:p1,:p2,:c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        
        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CENTRO"],"text"=>$value["DESCRIPCION"]);
        }
        
        
        return $result;
    }


    protected static function get_igv(){


        $stmt = static::$pdo->prepare("begin WEB_CALCULO_IGV (:c); end;");
        
      
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $igv = (!isset($list[0]['IGV']))?0:$list[0]['IGV'];

        return $igv;
        
    }

    protected function filter_tipo_doc($request){              

        $p1 = Auth::user()->empresa;
        
        $p2 = $request->get('q');

        $stmt = static::$pdo->prepare("begin WEB_VEN_TIPOSDOCUMENTOS_LISTA(:p1,:p2, :c); end;");
        
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



protected function filter_comunicacion(){    

        $stmt = static::$pdo->prepare("begin WEB_VEN_MEDIOS_CONTACTO_Q01( :c); end;");

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["MEDIO"],"text"=>$value["DESCRIPCION"]);
        }
       
        
        return $result;
        

    }
    protected function get_medios_comunicacion(){              

        
        
        $stmt = static::$pdo->prepare("begin WEB_MEDIOSCOMUNICACION_LISTA(:c); end;");
        
        

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["MEDIO"],"text"=>$value["DESCRIPCION"]);
        }
        
        
        return $result;
        

    }


    protected function get_list_bancos(){              

        
        $p1 = Auth::user()->empresa;
        $p2 = '';

        $stmt = static::$pdo->prepare("begin WEB_COR_BANCOS (:p1,:p2,:c); end;");
        
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_BANCO"],"text"=>$value["NOMBRE"]);
        }
        
        
        return $result;
        

    }


    protected function get_list_medios_pago(){              

        
        $p1 = Auth::user()->empresa;
        $p2 = '';

        $stmt = static::$pdo->prepare("begin WEB_CXCTIPOS_DOCUMENTOS_MP (:p1,:p2,:c); end;");
        
        
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


    protected function get_list_tipo_documentos(){              

        
        $p1 = Auth::user()->empresa;
       

        $stmt = static::$pdo->prepare("begin WEB_TIPOSDOCUMENTOS_Q1 (:p1,:c); end;");
        
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
      
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


    protected function motivo_nota_credito(){    

        $stmt = static::$pdo->prepare("begin WEB_CXC_MOTIVO_NOTACREDITO( :c); end;");

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

       
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_MOTIVO"],"text"=>$value["DESCRIPCION"]);
        }
       
        
        return $result;
        

    }

    //desvincular familia
    //
    

    protected function situacion_contratos(){    

        $p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_SITUACION_CONTRATOS (:p1,:c); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

       
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_SITUACION"],"text"=>$value["DESCRIPCION"]);
        }
       
        
        return $result;
        

    }


    protected function motivos_contratos(){    

        $p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_MOTIVOBAJA_CONTRATOS  (:p1,:c); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

       
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_MOTIVO"],"text"=>$value["DESCRIPCION"]);
        }
       
        
        return $result;
        

    }


    protected function estados_contratos(){    

        $p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_ESTADO_CONTRATOS  (:p1,:c); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

       
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_ESTADO"],"text"=>$value["DESCRIPCION"]);
        }
       
        
        return $result;
        

    }
    

    protected static function filter_terapeuta($request){

    $p1    = Auth::user()->empresa;

    $p2 = $request->get('q');
  
    $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_FILTRO(:p1,:p2, :c); end;");
    
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
        
        return $result;

    
    }

//PLANTILLAS CONTRATO

    
    protected static function get_cad_listado($p1,$p2){

   
  
  
    $stmt = static::$pdo->prepare("begin WEB_LISTVALORCAD_TABLAS(:p1,:p2, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["VALORCAD"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }

    protected static function get_tipos_plantillas_tipo_mantenimiento(){

    $p1    = '2';
    $p2    = 'TIPO_SERVICIO';
  
  
    $stmt = static::$pdo->prepare("begin WEB_LISTVALORCAD_TABLAS(:p1,:p2, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["VALORCAD"],"text"=>$value["VALORCAD"]);
        }
        
        return $result;

    
    }




    protected static function get_aseguradoras_plantillas_mantenimiento(){

    $p1    = Auth::user()->empresa;
   
  

    $stmt = static::$pdo->prepare("begin WEB_LIST_VEN_PLANES_SEGUROS(:p1, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
   
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["CIA_SEGURO"]);
        }
        
        return $result;

    
    }






    protected static function get_planes_by_aseguradora($request){

    $p1    = Auth::user()->empresa;

    $p2    = $request->aseguradora;
  
  
    $stmt = static::$pdo->prepare("begin WEB_LISTADO_PLANES(:p1,:p2, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_PLAN"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }


    protected static function lista_comentarios_contrato($contrato){

         $p1    = Auth::user()->empresa;

        $p2    = $contrato;
    
        $stmt = static::$pdo->prepare("begin CXC_DOC_DET_LIST_COMENTARIO(:p1,:p2,:c); end;");
        

    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            //$result[] = array("id"=>$value["DESCRIPCION"],"text"=>$value["DESCRIPCION"]);
             $result[] = $value["DESCRIPCION"];
        }
        
        return $result;

    
    }
    
}
