<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Doctores extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
    

    

    protected static function get_data_proveedor_doctores($request){

      $cia    = Auth::user()->empresa;

      $ruc = trim($request->ruc);

     


      $stmt = static::$pdo->prepare("begin WEB_CXPPROVEEDOR_LIST(:p1,:p2,:c); end;");

      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $ruc, PDO::PARAM_STR);
     

      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
      $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    
    }


   
    protected static function list_doctores($request){

      $cia    = Auth::user()->empresa;

      $doctor = (empty($request->identificacion))?'X':$request->identificacion;

      $estado = (empty($request->estado))?'X':$request->estado;


      $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_LIST(:p1, :p2,:p3,:c); end;");

      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $doctor, PDO::PARAM_STR);
      $stmt->bindParam(':p3', $estado, PDO::PARAM_STR);

      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
      $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    
    }



  
  

  protected static function get_info_doctor_mantenimiento($request){

      $cia    = Auth::user()->empresa;

      $doctor = $request->identificacion;

      $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_INFO_DNI(:p1, :p2,:c); end;");

      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $doctor, PDO::PARAM_STR);
     

      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
      $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    
    }
    

  protected static function get_data_secretaria_medico($request){

      $cia    = Auth::user()->empresa;

      $doctor = $request->identificacion;

      $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_SECRE_LIST(:p1, :p2,:c); end;");

      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $doctor, PDO::PARAM_STR);
     

      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
      $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    
    }


 protected static function get_info_medico_especialidad($request){

      $cia    = Auth::user()->empresa;

      $doctor = $request->identificacion;

      $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_ESPECIALOBDA(:p1, :p2,:c); end;");

      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $doctor, PDO::PARAM_STR);
     

      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
      $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    
    }


protected static function list_tipo_titulos(){

    
      $stmt = static::$pdo->prepare("begin  WEB_COR_TITULO_LISTADO(:c); end;");
    
    
      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
      $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
         $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["TITULO"],"text"=>$value["TITULO"]);
        }
        
        return $result;
    
    }




protected static function get_data_mpago_medico($request){

    
      $cia    = Auth::user()->empresa;

      $doctor = $request->identificacion;

      $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_MEDIOPAGO_OB(:p1, :p2,:c); end;");

      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $doctor, PDO::PARAM_STR);
     

      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
      $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    
    }


protected static function list_tipo_documentos(){

    
      $stmt = static::$pdo->prepare("begin WEB_TIPOS_DOCUMENTOS(:c); end;");
    
    
      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
      $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
         $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_DOCUMENTO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;
    
    }




protected static function filter_articulos_doctor($request){

    $p1    = Auth::user()->empresa;

    $p2 = $request->get('q');
  
    $stmt = static::$pdo->prepare("begin  WEB_INVARTICULOS_LIST(:p1,:p2, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        $moneda_cia =($p1=='001')?'DOL':'SOL';

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO_ARTICULO"],"text"=>$value["DESCRIPCION"],"source"=>$moneda_cia.'-'.$value["PRECIO"]);
        }
        
        return $result;

    
    }


protected static function filter_proveedor($request){

    $p1    = Auth::user()->empresa;

    $p2 = $request->get('q');
  
    $stmt = static::$pdo->prepare("begin WEB_CXPPROVEEDOR_LIST(:p1,:p2, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["FULL_NOMBRE"]);
        }
        
        return $result;

    
    }

 

    
    protected static function filter_clinica_doctores($request){

    $p1    = Auth::user()->empresa;

    $p2 = $request->get('q');
  
    $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_CLINICA(:p1,:p2, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO"],"text"=>$value["CLINICA"]);
        }
        
        return $result;

    
    }
    

    protected static function get_colectas_por_medico($request){

    $p1    = Auth::user()->empresa;

    $p2 = $request->identificacion;
  
    $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_NROCOLECTAS(:p1,:p2, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CANTIDAD_COLECTAS"],"text"=>$value["CANTIDAD_COLECTAS"]);
        }
        
        return $result;

    
    }






protected static function get_full_doctoresq($request){

    $p1  = Auth::user()->empresa;

    $p2 = $request->q;
  
    $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_Q01(:p1,:p2, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
       
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO"],"text"=>$value["FULL_NOMBRE"]);
        }
        
        return $result;

    
    }

protected static function get_cabecera_lprecio_medico($request){

    $p1  = Auth::user()->empresa;

    $p2 = $request->identificacion;
  
    $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_ESPE_LIST(:p1,:p2, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
       
        return $list;

    
    }

protected static function listar_otros_proveedores_ruc_medicos($request){

    $p1  = Auth::user()->empresa;

    $p2 = $request->identificacion;
  
    $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_OTRORUCLIST(:p1,:p2, :c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
       
        return $list;

    
    }



    protected static function list_tipo_especialidad(){

  
  
    $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_ESPECIAL(:c); end;");
    
    
  
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["ESPECIALIDAD"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }

     protected static function valida_duplicado_doctor($request){

        $p1    = Auth::user()->empresa;

        $p2 = trim($request->identificacion);
  
        $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_VAL(:p1,:p2, :rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }



    

    protected static function guardar_nuevo_proveedor_mant_doctores($request){

        
        $p1    = Auth::user()->empresa;

        $p2 = trim($request->ruc);

        $p3 = trim($request->descripcion);

        $p4 = trim($request->direccion);

        $p5 = ($request->estado)?'ACT':'INC';

        $p6 = $request->banco;

        $p7 = $request->moneda;

        $p8 = $request->tipo_pago;

        $p9 = $request->forma_pago;

        $p10 = trim($request->numero_cuenta);
    
        $p11 = trim($request->cci);

        $stmt = static::$pdo->prepare("begin WEB_IU_PROVEEDOR_DATOS(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:rpta); end;");
    
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

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }


    protected static function valida_nuevo_proveedor_medico($request){

        $p1    = Auth::user()->empresa;

        $p2 = $request->identificacion;

        $p3 = $request->proveedor;
  
        $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_OTRORUCVAL(:p1,:p2,:p3, :rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }

    protected static function elimina_proveedor_oruc_medico($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->medico;

        $p3 = $request->proveedor;
  
        $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_OTRORUC_DEL(:p1,:p2,:p3, :rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }


     protected static function guardar_nuevos_proveedores_medico($data){

        $p1 = Auth::user()->empresa;

        $p2 = $data['MEDICO'];

        $p3 = $data['RUC'];

        $p4 = (trim($data['ESTADO'])=='ACTIVO')?'A':'I';
  
        $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_OTRORUC_IU(:p1,:p2,:p3,:p4,:rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }


    



    protected static function get_valida_clinica_especialidad($request){

        $p1    = Auth::user()->empresa;

        $p2 = trim($request->identificacion);

        $p3 = trim($request->codigo_clinica);
  
        $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_CLINICA_VAL(:p1,:p2,:p3, :rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }

     protected static function guardar_nuevo_doctor($request){

        $p1    = Auth::user()->empresa;
        $p2 = $request->tipodocumento;
        $p3 = $request->identificacion;
        $p4 = $request->ruc;
        $p5 = $request->titulo;
        $p6 = $request->colegiatura;
        $p7 = $request->nombre;
        $p8 = $request->direccion;
        $p9 = $request->ubigeoid;
        $p10 = $request->correo;
        $p11 = $request->celular;
        $p12 = $request->telefono;
        $p13 = $request->feingreso;
        $p14 = $request->estado;
        $p15 = Auth::user()->codigo;

  
        $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_INUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15, :rpta); end;");
    
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
        $stmt->bindParam(':p13', $p13, PDO::PARAM_STR);
        $stmt->bindParam(':p14', $p14, PDO::PARAM_STR);
        $stmt->bindParam(':p15', $p15, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }
	
   	

    protected static function elimina_clinica_hospital($request){

        $p1    = Auth::user()->empresa;

        $p2 = $request->doctor;
    
        $p3 = $request->clinica;

        $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_UBI_CLIDELET(:p1,:p2,:p3, :rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }

    protected static function guardar_nueva_secretaria_medico($request){

        $p1    = Auth::user()->empresa;

        $p2 = $request->identificacion;

        $p3 = $request->nombre;
    
        $p4 = $request->telefono;

        $p5 = $request->celular;

        $p6 = $request->correo;

        $p7 = $request->nacimiento;

        $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_SECRE_INUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7, :rpta); end;");
    
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


  

   protected static function guardar_nuevo_mpago_medico($request){


        $p1    = Auth::user()->empresa;

        $p2 = $request->identificacion;

        $p3 = $request->banco;
    
        $p4 = $request->moneda;

        $p5 = $request->tipo_cta;

        $p6 = $request->forma_pago;

        $p7 = $request->ncuenta;

        $p8 = $request->cci;

        $p9 = $request->observacion;
        

        $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_MEDIOPAGO_IU(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9 ,:rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $p8, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $p9, PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }


    

    

    protected static function valida_nuevo_medico_medico($request){


        $p1    = Auth::user()->empresa;

        $p2 = $request->doctor;

       
    
      
    

        $stmt = static::$pdo->prepare("begin  WEB_VENTERAPEUTAS_Q01_VAL(:p1,:p2,:rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
      
        

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }





protected static function eliminaDoctorAsociado($request){


        $p1    = Auth::user()->empresa;

        $p2 = $request->doctor;

        $p3 = $request->articulo;
    
      
    

        $stmt = static::$pdo->prepare("begin  WEB_VENTERAPEUTAS_ESPECIAL_DEL(:p1,:p2,:p3,:rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }
    
    protected static function valida_nuevo_articulo_medico($request){


        $p1    = Auth::user()->empresa;

        $p2 = $request->doctor;

        $p3 = $request->articulo;
    
      
    

        $stmt = static::$pdo->prepare("begin  WEB_VENTERAPEUTAS_LISTPRE_VAL(:p1,:p2,:p3,:rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }


    protected static function guardar_especialidad_medico($list){

        $p1    = Auth::user()->empresa;

        $p2 = $list['IDENTIFICACION'];
      

        $p3 = $list['CLINICA'];

        $p4 = $list['DIRECCION'];

        $p5 = $list['ESPECIALIDAD'];

        $p6 = $list['NACIMIENTO'];

        $p7 = $list['ACTIVIDAD'];

        $p8 = $list['CELULAR'];

        $p9 = $list['COLECTA'];

        $p10 = Auth::user()->codigo;

        $p11 = $list['CLASIFICACION'];

        $p12 = $list['CAPTADOR'];

        $stmt = static::$pdo->prepare("begin WEB_VENTERAPEUTAS_UBICAC_INUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:rpta); end;");
    
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


    //pago a medicos   - contratos
    


     protected static function list_pagos_medicos($request){

      

      $cia    = Auth::user()->empresa;

      
      $contrato = trim($request->vm_contrato);

      $estado = $request->vm_estado;

      // $desde = Carbon::parse($request->vm_desde)->format('d/m/Y');

      // $hasta = Carbon::parse($request->vm_hasta)->format('d/m/Y');



      $desde = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

        $hasta  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');


        $analisis = $request->analisis;


      $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATOS_PAGOS_LIST(:p1,:p2,:p3,:c); end;");

      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
      $stmt->bindParam(':p3', $analisis, PDO::PARAM_STR);


      /*$stmt->bindParam(':p4', $desde, PDO::PARAM_STR);
      $stmt->bindParam(':p5', $hasta, PDO::PARAM_STR);
      $stmt->bindParam(':p6', $analisis, PDO::PARAM_STR);*/

      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
      $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    
    }
   







protected static function confirmar_eliminacion_agregacion_pago($request){

        
        $cia    = Auth::user()->empresa;
        $usuario    = Auth::user()->codigo;
        
            $contrato = trim($request->contrato);
            $estado = $request->estado;
            $comentario = $request->comentario;
           
             $tipo =  $request->tipo;//1 retirar 2 incluye

           if($tipo == 1){

            $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_RETIROPAGOMED(:p1,:p2,:p3,:p4,:rpta); end;");

           }else{

             $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_INCLUIRPAGOMED(:p1,:p2,:p3,:rpta); end;");

           }



        
    
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
        
        if($tipo == 1){

           $stmt->bindParam(':p3', $comentario, PDO::PARAM_STR);
            $stmt->bindParam(':p4', $usuario, PDO::PARAM_STR);
        }else{

             $stmt->bindParam(':p3', $usuario, PDO::PARAM_STR);
        }


        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }


    protected static function genera_pago_medico_pago($list,$request){

        
        $cia    = Auth::user()->empresa;

        
            
            $banco = $request->banco;
            $moneda = $request->moneda;
            $cta_cargo =$request->cta_cargo;
            $proceso =$request->proceso;

            $fecha_proceso =(empty($request->fecha_proceso))?null: Carbon::parse($request->fecha_proceso)->format('d/m/Y');

            $hora_ejecucion =$request->hora_ejecucion;
            $peramnencia =$request->peramnencia;
            $itf =$request->itf;



           



        $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATOS_PAGOS_REG(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:rpta); end;");
    
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $banco, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $moneda, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $cta_cargo, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $proceso, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $fecha_proceso, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $hora_ejecucion, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $peramnencia, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $itf, PDO::PARAM_STR);
       
        $stmt->bindParam(':p10', $list, PDO::PARAM_STR,10000);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }


    protected static function list_medicos_asociados_lista_precio($request){

    $p1  = Auth::user()->empresa;

    $p2 = $request->medico;

    $p3 = $request->articulo;
  
    $stmt = static::$pdo->prepare("begin WEB_INVARTICULOS_COMPARTECON(:p1,:p2,:p3,:c); end;");
    
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




    protected static function get_cuenta_Cargo_pago_medico($request){

      $cia    = Auth::user()->empresa;

      $moneda = $request->moneda;

      $banco = $request->banco;

      $stmt = static::$pdo->prepare("begin WEB_GETNROCUENTACARGO(:p1,:p2,:p3,:c); end;");

      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $moneda, PDO::PARAM_STR);
      $stmt->bindParam(':p3', $banco, PDO::PARAM_STR);

      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
      $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    
    }


    

    protected static function list_pagos_realizados_medicos($request){

        $cia    = Auth::user()->empresa;


        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('Y-m-d');

          $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('Y-m-d');

       
        $estado = $request->estado;
      


        $stmt = static::$pdo->prepare("begin WEB_VEN_GUIAPAGO_MEDICOS_LIST (:p1,:p2,:p3,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      

        $stmt->bindParam(':p2', $start, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $end, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }


    


    protected static function ver_detalle_list_pago_guias_medicos($request){

        $cia    = Auth::user()->empresa;

        $guia = $request->guia;
      
        $stmt = static::$pdo->prepare("begin WEB_VEN_GUIAPAGO_MEDDET_LIST(:p1,:p2,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $guia, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }

     protected static function eliminar_guia_pago_realizado_medico($request){

    $p1  = Auth::user()->empresa;

    $p2 = $request->guia;

 
  
    $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATOS_PAGOS_ANL(:p1,:p2,:rpta); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
  
  
    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
   
    $stmt->execute();

  
        return $rpta;

    
    }



    protected static function confirmar_guia_pago_realizado_medico($request){

    $p1  = Auth::user()->empresa;

    $p2 = $request->guia;

    $p3 = Carbon::parse($request->fecha_guia)->format('d/m/Y');

    $p4 = $request->numero_operacion;
  
    $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATOS_PAGOS_ACT(:p1,:p2,:p3,:p4,:rpta); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
    $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
   
        $stmt->execute();

  
        return $rpta;

    
    }


    

    protected static function retirar_detalle_guia_pago_realizado_medico($request){

    $p1  = Auth::user()->empresa;

    $p2 = $request->guia;

    $p3 = $request->contrato;

    
  
    $stmt = static::$pdo->prepare("begin WEB_VEN_GUIAPAGO_RETIRA_CONT(:p1,:p2,:p3,:rpta); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
   
    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
   
        $stmt->execute();

  
        return $rpta;

    
    }


    protected static function genera_txt_pago_medico($guia){

    $p1  = Auth::user()->empresa;

    $p2 = $guia;

  
    $stmt = static::$pdo->prepare("begin WEB_VEN_GUIAPAGO_MEDICOS_TXT(:p1,:p2,:c); end;");
    
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
   
     $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;

    
    }

    

    

    
}
