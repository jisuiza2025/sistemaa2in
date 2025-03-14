<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Cliente extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
	protected static function search_cliente($request){

		$p1 = Auth::user()->empresa;

		$p2 = trim($request->documento);

		
		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_OBTENER_DATOS(:p1, :p2, :c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;


	}

	


	protected static function set_complementos_cliente($request){

		$identificacion = trim($request->identificacion);

		$prospectos = self::get_complemento_prospectos($identificacion);
		$contratos = self::get_complemento_contratos($identificacion);
		$familia = self::get_complemento_familia($identificacion);
		$ecuenta = self::get_complemento_ecuenta($identificacion);



		return array($prospectos,$contratos,$familia,$ecuenta);


	}



	protected static function get_complemento_prospectos($cliente){

		$cia = Auth::user()->empresa;
		$stmt = static::$pdo->prepare("begin WEB_VENPROSPECTOS_CLIENTES_COM(:p1, :p2,:c); end;");
		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $cliente, PDO::PARAM_STR);
		
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;

		

	}


	protected static function get_complemento_contratos($cliente){

		$cia = Auth::user()->empresa;
		$stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_CLIENTES_COM(:p1, :p2,:c); end;");
		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $cliente, PDO::PARAM_STR);
		
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;

		

	}

	protected static function get_complemento_familia($cliente){

		$cia = Auth::user()->empresa;
		$stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_CLIENTES_FAM(:p1, :p2,:c); end;");
		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $cliente, PDO::PARAM_STR);
		
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;

		

	}


	protected static function get_complemento_ecuenta($cliente){

		$cia = Auth::user()->empresa;

		$stmt = static::$pdo->prepare("begin WEB_ESTADOCUENTA_CONTRATO_CLIE(:p1, :p2,:c); end;");
		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $cliente, PDO::PARAM_STR);
		
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;

		

	}

	protected static function list_cliente($request){

   	

		$p1 = Auth::user()->empresa;

		$p2 = $request->categoria;
		
		$p3 = $request->cliente;

		$p4 = ($request->activo==1)?'ACT':'INC';

		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_LISTADO(:p1, :p2, :p3,:p4, :c); end;");
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
   	
   	

   	protected static function save_cliente($request){

   		
   
		$id_user             = Auth::user()->id;
		$id_empresa 	     = Auth::user()->empresa;
		$vm_documento 		 = $request->vm_documento;
      	$vm_ndocumento 		 = $request->vm_ndocumento;
      	$vm_tcliente 		 = $request->vm_tcliente;
      	$vm_apepat 			 = mb_strtoupper($request->vm_apepat) ;
      	$vm_apemat 			 = mb_strtoupper($request->vm_apemat) ;
      	$vm_nombres 		 = mb_strtoupper($request->vm_nombres) ;
      	$vm_razonsocial 	 = mb_strtoupper($request->vm_razonsocial); 
      	$vm_nombrecomercial  = mb_strtoupper($request->vm_nombrecomercial) ;
      	$vm_direccion 		 = mb_strtoupper($request->vm_direccion) ;
      	$vm_referencia       = mb_strtoupper($request->vm_referencia) ;
      	$vm_pais             = $request->vm_pais ;
      	
      	$vm_ocupacion 		 = $request->vm_ocupacion ;
      	$vm_residencia 		 = $request->vm_residencia ;
      	$vm_observacion 	 = $request->vm_observacion ;
      	$vm_sexo 			 = $request->vm_sexo ;
      	$vm_ecivil 			 = $request->vm_ecivil ;
      	$vm_nacimiento 	     = (!empty($request->vm_nacimiento))?Carbon::parse($request->vm_nacimiento)->format('d/m/Y'):null;
      	
      	$vm_estado 			 = $request->vm_estado ;
      	$vm_feingreso 		 = $request->vm_feingreso; 
      	$vm_fijo_contacto1   = $request->vm_fijo_contacto1 ;
      	$vm_movil_contacto1  = $request->vm_movil_contacto1; 
      	$vm_email_contacto1  = $request->vm_email_contacto1; 
      	$vm_fijo_contacto2  = $request->vm_fijo_contacto2 ;
      	$vm_movil_contacto2 = $request->vm_movil_contacto2; 
      	$vm_email_contacto2 = $request->vm_email_contacto2 ;

      	$fijo_contacto3 	= $request->vm_fijo_contacto3;
      	$movil_contacto3 	= $request->vm_movil_contacto3;
      	$correo_contacto3 	= $request->vm_email_contacto3;
      	
     	$vm_inubicable 		= ($request->vm_inubicable)?'S':'N' ;
     	


     	if( $vm_documento == "RC"){

     		$full_name = $vm_razonsocial;
     		$alias 	   = $vm_nombrecomercial;

     	}else{

     		$full_name = $vm_nombres.','.$vm_apepat.' '.$vm_apemat;
     		$alias 	   = $vm_nombres.','.$vm_apepat.' '.$vm_apemat;
     	}
     	

     	
     	$ubigeo = $request->selected_ubigeo;
     	

		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_INPUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,:p18,:p19,:p20,:p21,:p22,:p23,:p24,:p25,:p26,:p27,:p28,:p29,:p30,:rpta); end;");
		$stmt->bindParam(':p1', $id_empresa, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $vm_ndocumento, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $vm_documento, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $vm_apepat, PDO::PARAM_STR);
		$stmt->bindParam(':p5', $vm_apemat, PDO::PARAM_STR);
		$stmt->bindParam(':p6', $vm_nombres, PDO::PARAM_STR);//nombre corto
		$stmt->bindParam(':p7', $full_name, PDO::PARAM_STR);//full_name
		$stmt->bindParam(':p8', $alias, PDO::PARAM_STR); //alias
		$stmt->bindParam(':p9', $vm_direccion, PDO::PARAM_STR);
		$stmt->bindParam(':p10', $vm_referencia, PDO::PARAM_STR);
		$stmt->bindParam(':p11', $vm_residencia, PDO::PARAM_STR); //dpto
		$stmt->bindParam(':p12', $vm_pais, PDO::PARAM_STR);
		$stmt->bindParam(':p13', $ubigeo, PDO::PARAM_STR);
		$stmt->bindParam(':p14', $vm_ocupacion, PDO::PARAM_STR);
		$stmt->bindParam(':p15', $vm_fijo_contacto1, PDO::PARAM_STR);
		$stmt->bindParam(':p16', $vm_movil_contacto1, PDO::PARAM_STR);
		$stmt->bindParam(':p17', $vm_email_contacto1, PDO::PARAM_STR);
		$stmt->bindParam(':p18', $vm_fijo_contacto2, PDO::PARAM_STR);
		$stmt->bindParam(':p19', $vm_movil_contacto2, PDO::PARAM_STR);
		$stmt->bindParam(':p20', $vm_email_contacto2, PDO::PARAM_STR);
		$stmt->bindParam(':p21', $fijo_contacto3, PDO::PARAM_STR);
		$stmt->bindParam(':p22', $movil_contacto3, PDO::PARAM_STR);
		$stmt->bindParam(':p23', $correo_contacto3, PDO::PARAM_STR);
		$stmt->bindParam(':p24', $vm_nacimiento, PDO::PARAM_STR);
		$stmt->bindParam(':p25', $vm_estado , PDO::PARAM_STR);
		$stmt->bindParam(':p26', $vm_sexo, PDO::PARAM_STR);
		$stmt->bindParam(':p27', $vm_ecivil, PDO::PARAM_STR);
		$stmt->bindParam(':p28', $vm_observacion, PDO::PARAM_STR);
		$stmt->bindParam(':p29', $vm_tcliente, PDO::PARAM_STR);
		$stmt->bindParam(':p30', $vm_inubicable, PDO::PARAM_STR);
		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
		$stmt->execute();

		
        return $rpta;

    
    }

  	

    protected static function cliente_list_get_categoria($request){

   
		$p1 = Auth::user()->empresa;

		$p2 = $request->cliente;

		$query = DB::select("SELECT NVL(CLASE_CLIENTE,'03') AS CLASE FROM VEN_CLIENTES WHERE  NO_CIA = ? AND IDENTIFICACION = ?",array($p1,$p2));



		$rpta = json_decode(json_encode($query),true);
	
		return $rpta[0]['clase'];

    
    }


  	protected static function desactiva_cliente($request){

   
		$p1 = Auth::user()->empresa;

		$p2 = $request->cliente;
		
	
		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_INACTIVAR(:p1, :p2, :c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
		
		$stmt->execute();

		
     
        return $rpta;

    
    }
   	


   	protected static function cliente_aseguradora($request){

   
		$p1 = Auth::user()->empresa;
		
		$p2 = $request->cliente;

		
		$stmt = static::$pdo->prepare("begin WEB_ASEGURADORA_PLANSEGURO(:p1,:p2, :c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;

    
    }
   	
   	protected static function salvar_plan_aseguradora_cl($request){

   
		$p1 = Auth::user()->empresa;
		$p2 = $request->cliente;
		$p3 = $request->codigo;
		$p4 = $request->descripcion;
		$p5 = $request->porcentaje;
		
		$stmt = static::$pdo->prepare("begin WEB_PLANSEGURO_INPUPD(:p1,:p2,:p3,:p4,:p5 ,:rpta); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
		$stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
		
		$stmt->execute();
        return $rpta;

    
    }



    protected static function get_full_name($dni){

		$query = DB::select('SELECT NOMBRE FROM VEN_CLIENTES WHERE IDENTIFICACION=?',array($dni));

		$fullname = (isset($query[0]->nombre))?$query[0]->nombre:'';

		return $fullname;
	}

    
}
