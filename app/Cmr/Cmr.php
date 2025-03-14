<?php

namespace App\Cmr;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;
use App\Botones;

class Cmr extends Model
{   
    
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}




protected static function get_reporte_incentivo_citas($request){



      $cia = Auth::user()->empresa;
       $servicio =$request->servicio;

        $periodo = Carbon::parse($request->periodo)->format('m/Y');
        
      $tipo =$request->tipo;

       $responsable =$request->responsable;


      $stmt = static::$pdo->prepare("begin WEB_RPT_CTRLINCENTIVO_CITA(:p1,:p2,:p3,:p4,:p5,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
         $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $periodo, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $responsable, PDO::PARAM_STR);

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;




}


protected static function vendendoresppv(){

        $p1 = Auth::user()->empresa;

        $p2 = self::permisos_servicios();

       
        
        $stmt = static::$pdo->prepare("begin WEB_VEND_VENCPROP_XSERV(:p1,:p2,:c); end;");
        
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

    protected static function captadoresppv(){

        $p1 = Auth::user()->empresa;

        $p2 = self::permisos_servicios();

        
        
        $stmt = static::$pdo->prepare("begin WEB_CAPTADOR_VENCPROP_SERV(:p1,:p2,:c); end;");
        
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
protected static function permisos_servicios(){




        $cia = Auth::user()->empresa;

        $usuario = Auth::user()->codigo;

         

        $botones = Botones::where([['USUARIO',$usuario],['NO_CIA',$cia]])->get()->toArray();

        
        $data = array();


        foreach ($botones as  $value) {
            
            $data[] = $value;
        }

        $servicios = null;

         if(isset($data[0])){

            $servicios = trim($data[0]['crm_ppv_servicios']);

        }

        return $servicios;
}

protected static function prospectos_vencidos_por_perder($request){

     $cia = Auth::user()->empresa;

        $servicios = self::permisos_servicios();

  $vendedor = $request->vendedor;
  $captador = $request->captador;
  $vencimiento = $request->vencimiento;

 $stmt = static::$pdo->prepare("begin WEB_CRM_PROSPECTOS_VENCER(:p1,:p2,:p3,:p4,:p5,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $servicios, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $vendedor, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $captador, PDO::PARAM_STR);

        $stmt->bindParam(':p5', $vencimiento, PDO::PARAM_STR);
      
       

       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;




}


protected static function vendedor_list($request){


        $cia = Auth::user()->empresa;

        $captadores = null;
        $medios = null;
        $vendedores = null;

       if(count((array)$request->captadores)>0){

        $captadores = implode(",",$request->captadores);
       }

        if(count((array)$request->medios)>0){

        $medios = implode(",",$request->medios);
       }

        

       if(count((array)$request->vendedores)>0){

        $vendedores = implode(",",$request->vendedores);
       }


        
      
      $nombre= trim($request->nombres);
$celular=trim($request->celular);
$correo= trim($request->correo);


      
  
      $stmt = static::$pdo->prepare("begin WEB_CRM_REASIGNARVEND(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $captadores, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $medios, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $vendedores, PDO::PARAM_STR);

        $stmt->bindParam(':p5', $nombre, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $celular, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $correo, PDO::PARAM_STR);
       
      
      


       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }




protected static function confirmar_nco_submit($request){

      $p1 = Auth::user()->empresa;
      $p2 = self::row_asignacion_nco($request->tabla);
     
      $p3 = Auth::user()->codigo;
     

     


      $stmt =  static::$pdo->prepare("begin WEB_CRM_VALIDARNCO(:p1,:p2,:p3,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
      
     
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        
        return $rpta;
    }

protected static function confirma_asignacion_submit($request){

      $p1 = Auth::user()->empresa;
      $p2 = self::row_asignacion($request->tabla);
      $p3 = $request->vendedor;
      $p4 = Auth::user()->codigo;
     

     


      $stmt =  static::$pdo->prepare("begin WEB_CRM_REG_ASIGNARVENDEDOR(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
     
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        
        return $rpta;
    }

    


    protected static function row_asignacion_nco($list){

      
        $row ='';

          foreach($list as $values){

        
             $row.=$values["NUMERO_PROSPECTO"].",";


          }


          $row =rtrim($row,",");


          return $row;


    }

    protected static function row_asignacion($list){

      
        $row ='';

          foreach($list as $values){

        
              if(isset($values["SELECCIONA"]) && $values["SELECCIONA"]){


              
                $row.=$values["NUMERO_PROSPECTO"].",";
              }


          }


          $row =rtrim($row,",");


          return $row;


    }
protected static function medios_crm(){

        //$p1 = Auth::user()->empresa;
        $p2='';
        
        $stmt = static::$pdo->prepare("begin WEB_TIPOSATENCIONFIL(:p2,:c); end;");
        
        //$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
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

protected static function salvar_registro_conrato_directo($request){

       
   
        //$cia = Auth::user()->empresa;

        

        $date = date('Y-m-d H:i:s');


        $token = sha1( md5( uniqid( $date, true ) ) );


        $cia    = $request->registroCia;
        $medio  = $request->registroMedioCap;
        $tipo   = $request->registroTipoCap;
        $ficha  = $request->registroFichaCap;
        $institucion = $request->registroInstitucion;
        $medico      = $request->registroMedico;


        $nombres = trim($request->registroFullName);
        $celular = trim($request->registroCelular);
        $dni     = trim($request->registroDNI);
        $correo  = trim($request->registroCorreo);

        $parto   = (!empty($request->registroFParto))?Carbon::parse($request->registroFParto)->format('d/m/Y'):'';

        $vendedor=trim($request->vm_vendedor);

        

        $usuario = Auth::user()->codigo;

       


        $apepat = trim($request->registroApepat);
        $apemat = trim($request->registroApemat);

        $ciudad = $request->registroCiudad;

        $services =  $request->registroServ;
        
        $stmt = static::$pdo->prepare("begin WEB_VEN_CAP_DIRECT_INSERT(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,:p18,:rpta); end;");

          $stmt->bindParam(':p1', $token, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $nombres, PDO::PARAM_STR);

          $stmt->bindParam(':p4', $apepat, PDO::PARAM_STR);
          $stmt->bindParam(':p5', $apemat, PDO::PARAM_STR);


          $stmt->bindParam(':p6', $dni, PDO::PARAM_STR);
          $stmt->bindParam(':p7', $celular, PDO::PARAM_STR);
          $stmt->bindParam(':p8', $correo, PDO::PARAM_STR);
          $stmt->bindParam(':p9', $institucion, PDO::PARAM_STR);
         
          $stmt->bindParam(':p10', $medico, PDO::PARAM_STR);
       
          $stmt->bindParam(':p11', $parto, PDO::PARAM_STR);
          
          $stmt->bindParam(':p12', $medio, PDO::PARAM_STR);
          $stmt->bindParam(':p13', $tipo, PDO::PARAM_STR);
          $stmt->bindParam(':p14', $ficha, PDO::PARAM_STR);
          $stmt->bindParam(':p15', $usuario, PDO::PARAM_STR);
        
          $stmt->bindParam(':p16', $ciudad, PDO::PARAM_STR);
          $stmt->bindParam(':p17', $vendedor, PDO::PARAM_STR);
          $stmt->bindParam(':p18', $services, PDO::PARAM_STR);

          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

          return $rpta;

        


   }

protected static function salvar_clases_mantenimiento($request){

   
      $p1 = $request->codigo_padre;
      $p2 = $request->codigo_hijo;
      $p3 = trim($request->descripcion);
      

      

      $p4 = $request->FLAGTAREA;
      $p5 = $request->FLAGCONTRATO;
      $p6 = $request->FLAGNOCALIFICA;

      $p7 = $request->estado;

      $stmt =  static::$pdo->prepare("begin WEB_VEN_CRM_ATEN_CLAS_INPUP(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:rpta); end;");

        
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
    
  
  protected static function cmb_asignar($cia,$prospecto){

        
        
        
        
    
        $stmt = static::$pdo->prepare("begin WEB_CRM_ASIGNAR_A_COMBO(:p1,:p2, :c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CAPTADOR"],"text"=>$value["NOMASIGNAR"]);
        }
        
        return $result;

        

    
    }



  
  protected static function iniciar_chat_crm($request){

        
        
          $cia = $request->cia;
          $prospecto = $request->prospecto;
          $usuario = Auth::user()->codigo;

          //$usuario = 'LCHAVEZ';
    
        $stmt = static::$pdo->prepare("begin WEB_CRM_CAPMSG_LISTA_PROSP(:p1,:p2,:p3,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $usuario, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

       
        
        return $list;

        

    
    }


    

protected static function modal_otros_contratos($request){

        
        
         
          
          $cia = Auth::user()->empresa;

          $prospecto = $request->prospecto;
        
    
        $stmt = static::$pdo->prepare("begin WEB_CRM_OTROSCONTRATOS_PROSP(:p1,:p2,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
    

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

       
        
        return $list;

        

    
    }

protected static function iniciar_chat_crm_by_token($request){

        
        
          $cia = $request->cia;
          $token = $request->token;
          $usuario = Auth::user()->codigo;

          //$usuario = 'LCHAVEZ';
    
        $stmt = static::$pdo->prepare("begin WEB_CRM_CAPMSG_LISTA_CAP(:p1,:p2,:p3,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $token, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $usuario, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

       
        
        return $list;

        

    
    }
  protected static function cmb_medios(){

        
        
        $p1 = '';

        
    
        $stmt = static::$pdo->prepare("begin WEB_VEN_CRM_MEDIO_COMBO(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

        

    
    }




protected static function muestra_botones_configuracion($request){

        
        
        $p1 = $request->atencion;
        $p2 = $request->clasificacion;

        
    
    $results = DB::select('SELECT NVL(FLAGTAREA,0) AS FLAGTAREA, NVL(FLAGCONTRATO,0) FLAGCONTRATO, NVL(FLAGNOCALIFICA,0) FLAGNOCALIFICA FROM VEN_CRM_ATENCION_CLAS WHERE CODIGO_ATENCION = ? AND CODIGO = ?', [$p1,$p2]);

        return $results;

        

    
    }

  protected static function cmb_clasificacion($request){

        
        
        $p1 = $request->atencion;
        $p2 = $request->get('q');

        
    
        $stmt = static::$pdo->prepare("begin WEB_VEN_CRM_ATENCIONCLAS_COMBO(:p1,:p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

        

    
    }



protected static function contratacion_combo_st_montos($servicio){

        
        $cia=Auth::user()->empresa;
        
        $q = '';

        
    
        $stmt = static::$pdo->prepare("begin WEB_CRM_SELCONTRATACION(:p1,:p2,:p3,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $q, PDO::PARAM_STR);


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

       


        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["LLAVE_CONTRATACION"],"text"=>$value["PRECIO_LISTA"]);

           

        }
        
      
        return $result;

        

    
    }



protected static function contratacion_combo_st($servicio){

        
        $cia=Auth::user()->empresa;
        
        $q = '';

        
    
        $stmt = static::$pdo->prepare("begin WEB_CRM_SELCONTRATACION(:p1,:p2,:p3,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $q, PDO::PARAM_STR);


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

         //$result2 = array();


        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["LLAVE_CONTRATACION"],"text"=>$value["NOMSERVICIO"]);

            //$result2[] = $value["LISTA_PRECIO"];

        }
        
        //return array($result,$result2);
        return $result;

        

    
    }

  
  

    protected static function anualidad_combo_st_montos($servicio){

        
        $cia=Auth::user()->empresa;

        $q = '';

        
    
        $stmt = static::$pdo->prepare("begin WEB_CRM_SELANUALIDAD(:p1,:p2,:p3,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $q, PDO::PARAM_STR);


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        


        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["LLAVE_CONTRATACION"],"text"=>$value["PRECIO_LISTA"]);

             
        }
        
          

          return $result;

        

    
    }


  protected static function anualidad_combo_st($servicio){

        
        $cia=Auth::user()->empresa;

        $q = '';

        
    
        $stmt = static::$pdo->prepare("begin WEB_CRM_SELANUALIDAD(:p1,:p2,:p3,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $q, PDO::PARAM_STR);


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

         //$result2 = array();


        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["LLAVE_CONTRATACION"],"text"=>$value["NOMSERVICIO"]);

             //$result2[] = $value["LISTA_PRECIO"];
        }
        
          //return array($result,$result2);

          return $result;

        

    
    }



  
  protected static function cmb_atenciones(){

        
        
        

        $p1 = '';
  
        $stmt = static::$pdo->prepare("begin WEB_VEN_CRM_ATENCION_COMBO(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

        

    
    }



  protected static function cmb_atencion($request){

        
        
        $p1 = $request->get('q');

        //$p1 = 'LL';
  
        $stmt = static::$pdo->prepare("begin WEB_VEN_CRM_ATENCION_COMBO(:p1, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["CODIGO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

        

    
    }






protected static function list_atencion_mantenimiento_detalle($request){


    $codigo = $request->codigo;


      $stmt = static::$pdo->prepare("begin WEB_VEN_CRM_ATENCION_CLAS_LIST(:p1,:rpta); end;");

     
        
     $stmt->bindParam(':p1', $codigo, PDO::PARAM_STR);
       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

}

protected static function list_atencion_mantenimiento($request){


   

      $stmt = static::$pdo->prepare("begin WEB_VEN_CRM_ATENCION_LIST(:rpta); end;");

     
        
  
       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

}


protected static function listado_principal_indicadores($request){


      $cia = Auth::user()->empresa;
      //$dni = trim(Auth::user()->identificacion);
       $dni = $request->dni;

      $stmt = static::$pdo->prepare("begin WEB_CRM_RESUMEN_TAREAS(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $dni, PDO::PARAM_STR);
        
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

}



protected static function listado_principal2($request){


        $cia = Auth::user()->empresa;
        //$dni = Auth::user()->identificacion;

        $dni = $request->dni;
        
        


      

        $exacta_contacto = (filter_var($request->exacta_contacto, FILTER_VALIDATE_BOOLEAN))?'S':'N' ;

        if($exacta_contacto == 'S'){


          $start = ($request->fecha_contacto[0] =='null'  ||  $request->fecha_contacto[0] == null)?'':Carbon::parse($request->fecha_contacto[0])->format('d/m/Y');

          $end  = ($request->fecha_contacto[1] =='null' ||  $request->fecha_contacto[1] == null)?'':Carbon::parse($request->fecha_contacto[1])->format('d/m/Y');

        }


        $exacta_parto =  (filter_var($request->exacta_parto, FILTER_VALIDATE_BOOLEAN) )?'S':'N' ;

        if($exacta_parto == 'S'){


          $start = ($request->fecha_parto[0] =='null'  ||  $request->fecha_parto[0] == null)?'':Carbon::parse($request->fecha_parto[0])->format('d/m/Y');

        $end = ($request->fecha_parto[1] =='null' ||  $request->fecha_parto[1] == null)?'':Carbon::parse($request->fecha_parto[1])->format('d/m/Y');

        }

        

           



        

       

        $captador = $request->captador;
        $medio = $request->medio;
        $institucion = $request->institucion;
        $vendedor = $request->vendedor;
        $ciudad = $request->ciudad;
        $medico = $request->medico;
        $contacto = trim($request->contacto);
        $celular = trim($request->celular);
        $correo = trim($request->correo);
        $prospecto = trim($request->prospecto);

        $ver_todos = (filter_var($request->vm_ver_todos, FILTER_VALIDATE_BOOLEAN))?1:0;
        

      $stmt = static::$pdo->prepare("begin WEB_CRM_DETALLE_TAREAS_FIL(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $dni, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $start, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $end, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $exacta_contacto, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $exacta_parto, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $captador, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $vendedor, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $medio, PDO::PARAM_STR);
        $stmt->bindParam(':p10', $ciudad, PDO::PARAM_STR);
        $stmt->bindParam(':p11', $institucion, PDO::PARAM_STR);
        $stmt->bindParam(':p12', $medico, PDO::PARAM_STR);
        $stmt->bindParam(':p13', $contacto, PDO::PARAM_STR);
        $stmt->bindParam(':p14', $celular, PDO::PARAM_STR);
        $stmt->bindParam(':p15', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':p16', $ver_todos, PDO::PARAM_STR);
        $stmt->bindParam(':p17', $prospecto, PDO::PARAM_STR);
      

        
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }




protected static function get_list_item_tarea($request){


        $p1 = $request->cia;
        $p2 = $request->prospecto;
        $p3 = $request->item;
        

      $stmt = static::$pdo->prepare("begin WEB_CRM_TAREA_GET(:p1,:p2,:p3,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
         $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }





protected static function get_list_item_tarea_tareas($request){


        $cia = $request->cia;
        $prospecto = $request->prospecto;
       
        

      $stmt = static::$pdo->prepare("begin WEB_CRM_TAREA_HIST(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
       
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }




protected static function modal_atencion_historial_tarea($request){


        $cia = $request->cia;
        $prospecto = $request->prospecto;
       
        

      $stmt = static::$pdo->prepare("begin WEB_CRM_TAREA_HIST(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
        
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }




protected static function get_data_info_servicios($prospecto){


        $cia = Auth::user()->empresa;
      
       
        

      $stmt = static::$pdo->prepare("begin WEB_CRM_PROSPECTOS_SERVICIOS(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
      
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }






protected static function list_panel_tareas($request){







        $p1 = Auth::user()->empresa;

        $p2 = Auth::user()->codigo;

       //$p2='MPRINZ';

        $stmt = static::$pdo->prepare("begin WEB_CRM_CONTROL_RESUMEN_TAREAS(:p1,:p2,:c); end;");

          $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

       
        



  }


protected static function listado_principal($request){



        $p1 = Auth::user()->empresa;
      
        $p2 = $request->dni;
      
        $p3 = $request->tipo;

       
        $p4 = (filter_var($request->vm_ver_todos, FILTER_VALIDATE_BOOLEAN))?1:0;

    




        // $p1 = Auth::user()->empresa;

         //$p2 = '16802051';

         //$p3 = '0';

        $stmt = static::$pdo->prepare("begin WEB_CRM_DETALLE_TAREAS(:p1,:p2,:p3,:p4,:c); end;");

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




protected static function registrar_mensaje_chat($request){


    

        $cia    = $request->cia;

        $token = $request->token;

        $mensaje = trim($request->mensaje);

        

       
      
        $usuario  = Auth::user()->codigo;

        


        $stmt = static::$pdo->prepare("begin WEB_CRM_CAPMSG_INSERT(:p1,:p2,:p3,:p4,:rpta); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $token, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $mensaje, PDO::PARAM_STR,100000);
          $stmt->bindParam(':p4', $usuario, PDO::PARAM_STR);
        
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

          return $rpta;
    
    }

protected static function registrar_no_califica_tarea($request){


    

        $cia    = $request->cia;

        $prospecto = $request->prospecto;

        $item = $request->item;

        $atencion = $request->atencion;

        $clasificacion = $request->clasificacion;

        $comentario = $request->comentario;

      
          
        $usuario  = Auth::user()->codigo;

        


        $stmt = static::$pdo->prepare("begin WEB_CRM_TAREA_NOAPL(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:rpta); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $item, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $atencion, PDO::PARAM_STR);
          $stmt->bindParam(':p5', $clasificacion, PDO::PARAM_STR);
          $stmt->bindParam(':p6', $comentario, PDO::PARAM_STR);
          $stmt->bindParam(':p7', $usuario, PDO::PARAM_STR);
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

          return $rpta;
    
    }




protected static function genera_contrato_cmr($list,$num_prospecto,$request){

      

      $item_get =  $request->item_get;

      $atencion_get = $request->atencion_get;
      
      $clasificacion_get = $request->clasificacion_get;
      
      $comentario_get = $request->comentario_get;


      $cia            = Auth::user()->empresa; 
      $nro_prospecto  = $num_prospecto ;


      $servicio = $list["CODIGO_SERVICIO"] ;
      $servicio_an    = $list["CODIGO_SERVICIO_ANL"] ;


   
      $usuario        = Auth::user()->codigo;



      $stmt = static::$pdo->prepare("begin WEB_CRM_GENERAR_CONTRATO(:cia,:nro_prospecto,:servicio,:servicio_an,:usuario,:res1,:item,:atencion,:clasificacion,:comentario,:res2); end;");


      $stmt->bindParam(':cia', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':nro_prospecto', $nro_prospecto, PDO::PARAM_STR);
      $stmt->bindParam(':servicio', $servicio, PDO::PARAM_STR);
      $stmt->bindParam(':servicio_an', $servicio_an, PDO::PARAM_STR);
      $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
      $stmt->bindParam(':res1', $contrato, PDO::PARAM_STR,10000);
      $stmt->bindParam(':item', $item_get, PDO::PARAM_STR);
      $stmt->bindParam(':atencion', $atencion_get, PDO::PARAM_STR);
      $stmt->bindParam(':clasificacion', $clasificacion_get, PDO::PARAM_STR);
      $stmt->bindParam(':comentario', $comentario_get, PDO::PARAM_STR);
      $stmt->bindParam(':res2', $mensaje, PDO::PARAM_STR,10000);


   
      
      $stmt->execute();

    
      
     
        return array($mensaje,$contrato);

    
    }

protected static function save_prospecto_detalle_cmr($list,$num_prospecto){



      $cia            = Auth::user()->empresa; 
      $nro_prospecto  = $num_prospecto ;
      $servicio = $list["COD_SERVICIO"] ;
      $moneda         = $list["MONEDA"] ;
      $servicio_contrato = $list["CODIGO_SERVICIO"] ;
      $lista_contrato = $list["LISTA_PRECIO"] ;
      $monto_servicio = str_replace(",","",$list["PRECIO"]);

      

      $id_cia_seguro  = $list["ID_COMPANIA_SEGURO"] ;

      $plan  = $list["PLAN"] ;

      $porcentaje     = $list["PORC_COBERTURA"] ;
      $moneda_an      = $list["MONEDA_ANL"] ;
      $servicio_an    = $list["CODIGO_SERVICIO_ANL"] ;
      $monto_an       = str_replace(",","",$list["PRECIO_ANL"]);

       

      $lista_an       = $list["LISTA_PRECIO_ANL"] ;
      $usuario        = Auth::user()->codigo;



      $stmt = static::$pdo->prepare("begin WEB_CRM_PROSPECTOS_SERV_INPUPD(:cia,:nro_prospecto,:servicio,:moneda,:servicio_contrato,:lista_contrato,:monto_servicio,:id_cia_seguro,:plan,:porcentaje,:moneda_an,:servicio_an,:monto_an,:lista_an,:usuario,:c); end;");


      $stmt->bindParam(':cia', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':nro_prospecto', $nro_prospecto, PDO::PARAM_STR);
      $stmt->bindParam(':servicio', $servicio, PDO::PARAM_STR);
      $stmt->bindParam(':moneda', $moneda, PDO::PARAM_STR);
      $stmt->bindParam(':servicio_contrato', $servicio_contrato, PDO::PARAM_STR);
      $stmt->bindParam(':lista_contrato', $lista_contrato, PDO::PARAM_STR);
      $stmt->bindParam(':monto_servicio', $monto_servicio, PDO::PARAM_STR);
      $stmt->bindParam(':id_cia_seguro', $id_cia_seguro, PDO::PARAM_STR);
      $stmt->bindParam(':plan', $plan, PDO::PARAM_STR);
      $stmt->bindParam(':porcentaje', $porcentaje, PDO::PARAM_STR);
      $stmt->bindParam(':moneda_an', $moneda_an, PDO::PARAM_STR);
      $stmt->bindParam(':servicio_an', $servicio_an, PDO::PARAM_STR);
      $stmt->bindParam(':monto_an', $monto_an, PDO::PARAM_STR);
      $stmt->bindParam(':lista_an', $lista_an, PDO::PARAM_STR);
      $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
     
     
      $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
      
      $stmt->execute();

      
     
        return $rpta;

    
    }

    protected static function get_data_dental_servicio($prospecto){

      
      
        $cia  = Auth::user()->empresa;

        


        $stmt = static::$pdo->prepare("begin WEB_PROSPECTO_FLAGSERVICIO(:p1,:p2,:rpta); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
        
        
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

          return $rpta;
    }


    
protected static function save_prospecto_cmr($request){

      


      $cia    = Auth::user()->empresa;
      $usuario    = Auth::user()->codigo;
  
      $rprospecto_prospecto   =$request->rprospecto_prospecto ;

      

    


     

      $rprospecto_fechaAprox  =($request->rprospecto_fechaAprox==null)?null:Carbon::parse($request->rprospecto_fechaAprox)->format('d/m/Y');

      

      $rprospecto_tipoParto   =$request->rprospecto_tipoParto ;
      
      $rprospecto_contratado  =$request->rprospecto_contratado ;
      $rprospecto_captacion   =$request->rprospecto_captacion;
      $rprospecto_tipo        =$request->rprospecto_tipo ;
      $rprospecto_nroFicha    =$request->rprospecto_nroFicha ;
  
      $rprospecto_medico      =$request->rprospecto_medico ;
      $rprospecto_clinica     =$request->rprospecto_clinica;
      $rprospecto_contrato    =($request->rprospecto_contrato==0)?'NO':'SI' ;

     

      $rprospecto_identmadre  =$request->rprospecto_identmadre ;
      $rprospecto_docmadre    =$request->rprospecto_docmadre;
      $rprospecto_sexomadre   =$request->rprospecto_sexomadre;
      $rprospecto_patmadre    =mb_strtoupper($request->rprospecto_patmadre);
      $rprospecto_matmadre    =mb_strtoupper($request->rprospecto_matmadre) ;
      $rprospecto_nommadre    =mb_strtoupper($request->rprospecto_nommadre) ;
      $rprospecto_dirmadre    =mb_strtoupper($request->rprospecto_dirmadre) ;
      $rprospecto_msoltera    =($request->rprospecto_msoltera==0)?'N':'S';
      $rprospecto_pais_madre  =$request->rprospecto_pais_madre;
      $rprospecto_ubgmadre    =$request->rprospecto_ubgmadre ;
      $rprospecto_civmadre    =$request->rprospecto_civmadre ;
      $rprospecto_nacmadre    =($request->rprospecto_nacmadre==null )?null:Carbon::parse($request->rprospecto_nacmadre)->format('d/m/Y'); 
      
      $rprospecto_movil1madre =$request->rprospecto_movil1madre ;
      $rprospecto_email1madre =$request->rprospecto_email1madre ;

      $rprospecto_tipoMadre ='M' ;
      
      
      $rprospecto_identpadre  =$request->rprospecto_identpadre ;
      $rprospecto_docpadre    =$request->rprospecto_docpadre ;
      $rprospecto_sexopadre   =$request->rprospecto_sexopadre ;
      $rprospecto_patpadre    =mb_strtoupper($request->rprospecto_patpadre)  ;
      $rprospecto_matpadre    =mb_strtoupper($request->rprospecto_matpadre)  ;
      $rprospecto_nompadre    =mb_strtoupper($request->rprospecto_nompadre)  ;
      $rprospecto_dirpadre    =mb_strtoupper($request->rprospecto_dirpadre) ;
      $rprospecto_pais_padre  =$request->rprospecto_pais_padre ;
      $rprospecto_ubgpadre    =$request->rprospecto_ubgpadre ;
      $rprospecto_civpadre    =$request->rprospecto_civpadre  ;
      $rprospecto_nacpadre    =($request->rprospecto_nacpadre==null)?null: Carbon::parse($request->rprospecto_nacpadre)->format('d/m/Y'); 
    
      $rprospecto_movil1padre =$request->rprospecto_movil1padre ;
      $rprospecto_email1padre =$request->rprospecto_email1padre ;
      

      $rprospecto_tipoPadre ='P' ;
      $contacto_mama    =($request->contacto_mama==0)?'N':'S' ;
      $contacto_papa    =($request->contacto_papa==0)?'N':'S' ;


      //DATOS DEL PROPIETARIO
      
      $rprospecto_identpropietario  =$request->rprospecto_identpropietario ;
      $rprospecto_docpropietario    =$request->rprospecto_docpropietario ;
      $rprospecto_sexopropietario   =$request->rprospecto_sexopropietario ;
      $rprospecto_patpropietario    =mb_strtoupper($request->rprospecto_patpropietario)  ;
      $rprospecto_matpropietario    =mb_strtoupper($request->rprospecto_matpropietario)  ;
      $rprospecto_nompropietario    =mb_strtoupper($request->rprospecto_nompropietario)  ;
      $rprospecto_dirpropietario    =mb_strtoupper($request->rprospecto_dirpropietario) ;
      $rprospecto_pais_propietario  =$request->rprospecto_pais_propietario ;
      $rprospecto_ubgpropietario    =$request->rprospecto_ubgpropietario ;
      $rprospecto_civpropietario   =$request->rprospecto_civpropietario  ;
      $rprospecto_nacpropietario    =($request->rprospecto_nacpropietario==null)?null: Carbon::parse($request->rprospecto_nacpropietario)->format('d/m/Y'); 
     
      $rprospecto_movil1propietario =$request->rprospecto_movil1propietario ;
      $rprospecto_email1propietario =$request->rprospecto_email1propietario ;
      

      $stmt = static::$pdo->prepare("begin WEB_CRM_PROSPECTOS_INPUPD(:cia,:rprospecto_prospecto,:rprospecto_fechaAprox,:rprospecto_tipoParto,:rprospecto_contratado,:rprospecto_medico,:rprospecto_clinica,:rprospecto_captacion,:rprospecto_tipo,:rprospecto_nroFicha,:rprospecto_contrato,:rprospecto_identmadre,:rprospecto_docmadre,:rprospecto_sexomadre,:rprospecto_patmadre,:rprospecto_matmadre,:rprospecto_nommadre,:rprospecto_dirmadre,:rprospecto_contacto_mama,:rprospecto_msoltera,:rprospecto_pais_madre,:rprospecto_ubgmadre,:rprospecto_civmadre,:rprospecto_nacmadre,:rprospecto_movil1madre,:rprospecto_email1madre,:rprospecto_tipoMadre,:rprospecto_identpadre,:rprospecto_docpadre,:rprospecto_sexopadre,:rprospecto_patpadre,:rprospecto_matpadre,:rprospecto_nompadre,:rprospecto_dirpadre,:rprospecto_contacto_papa,:rprospecto_pais_padre,:rprospecto_ubgpadre,:rprospecto_civpadre,:rprospecto_nacpadre,:rprospecto_movil1padre,:rprospecto_email1padre,:rprospecto_tipoPadre,:rprospecto_identpropietario,:rprospecto_docpropietario,:rprospecto_sexopropietario,:rprospecto_patpropietario,:rprospecto_matpropietario,:rprospecto_nompropietario,:rprospecto_dirpropietario,:rprospecto_pais_propietario,:rprospecto_ubgpropietario,:rprospecto_civpropietario,:rprospecto_nacpropietario,:rprospecto_movil1propietario,:rprospecto_email1propietario,:usuario,:rpta); end;");


      

$stmt->bindParam(':cia', $cia, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_prospecto', $rprospecto_prospecto, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fechaAprox', $rprospecto_fechaAprox, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_tipoParto', $rprospecto_tipoParto, PDO::PARAM_STR);

$stmt->bindParam(':rprospecto_contratado', $rprospecto_contratado, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_medico', $rprospecto_medico, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_clinica', $rprospecto_clinica, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_captacion', $rprospecto_captacion, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_tipo', $rprospecto_tipo, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nroFicha', $rprospecto_nroFicha, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_contrato', $rprospecto_contrato, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_identmadre', $rprospecto_identmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_docmadre', $rprospecto_docmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_sexomadre', $rprospecto_sexomadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_patmadre', $rprospecto_patmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_matmadre', $rprospecto_matmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nommadre', $rprospecto_nommadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_dirmadre', $rprospecto_dirmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_contacto_mama', $contacto_mama, PDO::PARAM_STR);


$stmt->bindParam(':rprospecto_msoltera', $rprospecto_msoltera, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_pais_madre', $rprospecto_pais_madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_ubgmadre', $rprospecto_ubgmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_civmadre', $rprospecto_civmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nacmadre', $rprospecto_nacmadre, PDO::PARAM_STR);

$stmt->bindParam(':rprospecto_movil1madre', $rprospecto_movil1madre, PDO::PARAM_STR);

$stmt->bindParam(':rprospecto_email1madre', $rprospecto_email1madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_tipoMadre', $rprospecto_tipoMadre, PDO::PARAM_STR);


$stmt->bindParam(':rprospecto_identpadre', $rprospecto_identpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_docpadre', $rprospecto_docpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_sexopadre', $rprospecto_sexopadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_patpadre', $rprospecto_patpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_matpadre', $rprospecto_matpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nompadre', $rprospecto_nompadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_dirpadre', $rprospecto_dirpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_contacto_papa', $contacto_papa, PDO::PARAM_STR);


$stmt->bindParam(':rprospecto_pais_padre', $rprospecto_pais_padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_ubgpadre', $rprospecto_ubgpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_civpadre', $rprospecto_civpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nacpadre', $rprospecto_nacpadre, PDO::PARAM_STR);

$stmt->bindParam(':rprospecto_movil1padre', $rprospecto_movil1padre, PDO::PARAM_STR);

$stmt->bindParam(':rprospecto_email1padre', $rprospecto_email1padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_tipoPadre', $rprospecto_tipoPadre, PDO::PARAM_STR);

$stmt->bindParam(':rprospecto_identpropietario', $rprospecto_identpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_docpropietario', $rprospecto_docpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_sexopropietario', $rprospecto_sexopropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_patpropietario', $rprospecto_patpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_matpropietario', $rprospecto_matpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nompropietario', $rprospecto_nompropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_dirpropietario', $rprospecto_dirpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_pais_propietario', $rprospecto_pais_propietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_ubgpropietario', $rprospecto_ubgpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_civpropietario', $rprospecto_civpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nacpropietario', $rprospecto_nacpropietario, PDO::PARAM_STR);

$stmt->bindParam(':rprospecto_movil1propietario', $rprospecto_movil1propietario, PDO::PARAM_STR);

$stmt->bindParam(':rprospecto_email1propietario', $rprospecto_email1propietario, PDO::PARAM_STR);


$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);

$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

     $stmt->execute();

    
        return $rpta;
    
    }


protected static function salvar_tarea_nueva($request){


    

        $cia    = $request->cia;

        $prospecto = $request->prospecto;

        $item = $request->item;

        $atencion = $request->atencion;

        $clasificacion = $request->clasificacion;

        $comentario = $request->comentario;

        $contactar = (!empty($request->contactar))?Carbon::parse($request->contactar)->format('d/m/Y'):null;

        $thora = $request->thora;

       
        $hora = (!empty($request->hora))?Carbon::parse($request->hora)->format('H:i:s'):null;



        $medio = $request->medio;
         
        $asignar = $request->asignar;
          
        $usuario  = Auth::user()->codigo;

        


        $stmt = static::$pdo->prepare("begin WEB_CRM_TAREA_REG(:p1,:p2,:p3,:p4,:p5, :p6,:p7,:p8,:p9,:p10,:p11,:p12,:rpta); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $item, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $atencion, PDO::PARAM_STR);
          $stmt->bindParam(':p5', $clasificacion, PDO::PARAM_STR);
          $stmt->bindParam(':p6', $comentario, PDO::PARAM_STR);
          $stmt->bindParam(':p7', $contactar, PDO::PARAM_STR);
          $stmt->bindParam(':p8', $thora, PDO::PARAM_STR);
          $stmt->bindParam(':p9', $hora, PDO::PARAM_STR);
          $stmt->bindParam(':p10', $medio, PDO::PARAM_STR);
          $stmt->bindParam(':p11', $asignar, PDO::PARAM_STR);
          $stmt->bindParam(':p12', $usuario, PDO::PARAM_STR);
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

          return $rpta;
    
    }



    protected static function automatico_servicios_contratodirecto(){

       $p1 = Auth::user()->empresa;
      
        $p2 = Auth::user()->identificacion;
      
     

  

        $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTADOR_GET(:p1,:p2,:c); end;");

          $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;


    }


    protected static function alerta_cap_ven(){



      $p1 = Auth::user()->empresa;
      
        $p2 = Auth::user()->codigo;
      
     

  

        $stmt = static::$pdo->prepare("begin WEB_USUARIO_ISVENCAP(:p1,:p2,:c); end;");

          $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;


    }


    protected static function confirma_asignacion_submit_ppperder($request){

      $p1 = Auth::user()->empresa;
      $p2 = self::row_asignacion($request->tabla);
      $p3 = $request->vendedor;
      $p4 = Auth::user()->codigo;
     

     


      $stmt =  static::$pdo->prepare("begin WEB_CRM_REG_ASIGNARVEND_PERD(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
     
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        
        return $rpta;
    }


   
}
