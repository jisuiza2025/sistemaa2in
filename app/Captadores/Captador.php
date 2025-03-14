<?php

namespace App\Captadores;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;
use App\Botones;
class Captador extends Model
{   
    
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
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

            $servicios = trim($data[0]['capt_av_servicios']);

        }

        return $servicios;
}


protected static function get_reporte_incentivo($request){



 $cia = Auth::user()->empresa;
 $servicio =$request->servicio;
        $periodo = Carbon::parse($request->periodo)->format('m/Y');
        
      

      $stmt = static::$pdo->prepare("begin WEB_RPT_CTRLINCENTIVO_CAPTA(:p1,:p2,:p3,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
         $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $periodo, PDO::PARAM_STR);
        
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;




}


protected static function get_servicios_captador(){


        $cia = Auth::user()->empresa;
        $dni = Auth::user()->identificacion;
        
        //$dni = 73736326;

      $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTADOR_GET(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $dni, PDO::PARAM_STR);
        
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }







protected static function get_list_carga_masiva_by_token($token){


        $cia = Auth::user()->empresa;
       

        

      $stmt = static::$pdo->prepare("begin WEB_VEN_VALIDA_CAPTA_MAS_LIST(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $token, PDO::PARAM_STR,100000);
      
        
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }


protected static function salvar_carga_validaciones_captaciones($rows,$ciaselect,$servicio,$token){


        $cia = Auth::user()->empresa;
         $usuario = Auth::user()->codigo;

        
       
      $stmt = static::$pdo->prepare("begin WEB_VEN_VALIDA_CAPTA_MAS(:p1,:p2,:p3,:p4,:p5,:p6,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $token, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $ciaselect, PDO::PARAM_STR);
         $stmt->bindParam(':p4', $servicio, PDO::PARAM_STR);
          $stmt->bindParam(':p5', $rows, PDO::PARAM_STR,100000);
          $stmt->bindParam(':p6', $usuario, PDO::PARAM_STR);
      
     $stmt->bindParam(':rpta', $list, PDO::PARAM_INT);
        
          $stmt->execute();

        


        
        return $list;

       
        



  }


protected static function datos_captacion_token_top_10($token){


        $cia = Auth::user()->empresa;

        

      $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTADCIONES_DUP(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $token, PDO::PARAM_STR);
        
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }





protected static function detalle_linea_tiempo($request){


        $cia =$request->cia;
        $token =$request->token;
        

      $stmt = static::$pdo->prepare("begin WEB_CAPTACION_LINEATIEMPO(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $token, PDO::PARAM_STR);
        
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }

protected static function datos_captacion_token($token){


        $cia = Auth::user()->empresa;

        

      $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTACION_DATOS(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $token, PDO::PARAM_STR);
        
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }


protected static function tracking_list($request){


        $cia = Auth::user()->empresa;

        $usuario = Auth::user()->codigo;

        $captador = trim($request->captador);

        $nombres = trim($request->nombres);
        $correo = trim($request->correo);
        $celular = trim($request->celular);


        $tipo = $request->tipo;
        $medio = $request->medio;

        $clinica = $request->clinica;
        $medico = $request->medico;


        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

      $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');

      
      $flag = filter_var($request->flag_fecha, FILTER_VALIDATE_BOOLEAN);

      if($flag){

        $start = null;
        $end = null;
      }

      $ver_todos = (filter_var($request->ver_todos, FILTER_VALIDATE_BOOLEAN))?1:0;

      $ciudad = trim($request->ciudad);

      //servicios
      

      $servicios = ($request->servicios=='null')?NULL:$request->servicios;


      

    
     

      $stmt = static::$pdo->prepare("begin WEB_VEN_CAP_TRACKING(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $medio, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $clinica, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $medico, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $captador, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $nombres, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $celular, PDO::PARAM_STR);
        $stmt->bindParam(':p10', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':p11', $start, PDO::PARAM_STR);
        $stmt->bindParam(':p12', $end, PDO::PARAM_STR);
        $stmt->bindParam(':p13', $ciudad, PDO::PARAM_STR);
        $stmt->bindParam(':p14', $ver_todos, PDO::PARAM_STR);

        $stmt->bindParam(':p15', $servicios, PDO::PARAM_STR);

       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }




protected static function data_duplicado_valida_captacion($request,$flag){


        $cia = $request->cia;

        $token = ($flag ==1 )?$request->token:$request->tokenvalidodp;
       
       

      $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTACION_DATOS(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $token, PDO::PARAM_STR);
        
      
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }

protected static function masiva_duplicados_list($request){


        $cia = Auth::user()->empresa;

        $usuario = trim($request->captador);

        $nombres = trim($request->nombres);
        $correo = trim($request->correo);
        $celular = trim($request->celular);

      $stmt = static::$pdo->prepare("begin WEB_VEN_CAP_VALIDADPMANUAL(:p1,:p2,:p3,:p4,:p5,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $nombres, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $celular, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $correo, PDO::PARAM_STR);
      

       
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
        $ciudades = null;
        $vendedores = null;

       if(count((array)$request->captadores)>0){

        $captadores = implode(",",$request->captadores);
       }

        if(count((array)$request->medios)>0){

        $medios = implode(",",$request->medios);
       }

        if(count((array)$request->ciudades)>0){

        $ciudades = implode(",",$request->ciudades);
       }

       if(count((array)$request->vendedores)>0){

        $vendedores = implode(",",$request->vendedores);
       }


        
      
        $criterioA = $request->criterioA;
        $criterioB = $request->criterioB;


        $xservicios = self::permisos_servicios();


      
  
      $stmt = static::$pdo->prepare("begin WEB_VEN_CAP_ASIGNARVEND(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $captadores, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $medios, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $ciudades, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $vendedores, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $criterioA, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $criterioB, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $xservicios, PDO::PARAM_STR);
      


       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }

protected static function criterios_evaluacion(){



        
        $stmt = static::$pdo->prepare("begin WEB_VEN_CRITERIOSASIGVEND(:c); end;");
      

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
     
        return $list;

}



  protected static function filter_captador_companias($request){

        $p1 = Auth::user()->empresa;

        $p2 = strtoupper($request->get('q'));
        
        $stmt = static::$pdo->prepare("begin CRM_COMPANIAS_CAPTACION(:p1,:p2, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["RAZON_SOCIAL"]);
        }
        
        return $result;

    
    }



  protected static function filter_captador_cod($request){

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
            
            $result[] = array("id"=>$value["CODIGO_USUARIO"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;

    
    }


  protected static function top_10_registros_captacion_usuario($codigo){

        $cia = Auth::user()->empresa;
        


        $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTADORES_TOP_10(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $codigo, PDO::PARAM_STR);
      

       
       $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
         $stmt->execute();

          oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;

       
        



  }





protected static function set_filas_masiva_excel($data,$cia){



  $rows = '';


  

    foreach ($data as $value) {
      
      $date = date('Y-m-d H:i:s');

      $token = sha1( md5( uniqid( $date, true ) ) );

      $institucion = (empty($value[0]))?0:trim($value[0]);
      $medico = (empty($value[1]))?0:trim($value[1]);


      
      $nombres = (empty($value[2]))?0:trim($value[2]);

      $apepat = (empty($value[3]))?0:trim($value[3]);
      $apemat = (empty($value[4]))?0:trim($value[4]);


      $dni = (empty($value[5]))?0:trim($value[5]);
      $celular = (empty($value[6]))?0:trim($value[6]);
      $correo = (empty($value[7]))?0:trim($value[7]);

      //$parto = ($value[8] == '01/01/1970')?0:Carbon::parse($value[8])->format('d/m/Y');

      if($value[8] == '01/01/1970'){

        $parto  = "";

      }else{

             $dateInput = trim($value[8]);

             if(empty($dateInput)){

                $parto  = "";



             }else{


                  if($dateInput[2]=="/" && $dateInput[5]=="/"){

                      $parto = $dateInput;

                  }else{

                      $oldDate = strtotime($dateInput);

                      $parto = date('d/m/Y',$oldDate);

                  }

             }


            
      }
      

       //$rows.= $token.','.$cia.','.$nombres.','.$apepat.','.$apemat.','.$dni.','.$celular.','.$correo.','.$institucion.','.$medico.','.$parto.'|';

        $rows.= $token.','.$institucion.','.$medico.','.$nombres.','.$apepat.','.$apemat.','.$dni.','.$celular.','.$correo.','.$parto.'|';
    }


    $rows = rtrim($rows,'|');

    return $rows;
}





protected static function confirmar_retiro_submit($request){

        $cia   =  $request->cia;
      
        $token = $request->token;
        
        $comentario = trim($request->comentario);

        $usuario = Auth::user()->codigo;


        $stmt = static::$pdo->prepare("begin WEB_CAP_ASIGNAVEND_RETIRA(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $token, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $comentario, PDO::PARAM_STR);  
        $stmt->bindParam(':rpta', $list, PDO::PARAM_INT);
        
          $stmt->execute();

        


        
        return $list;

       
    

  }

protected static function valida_medico_clinica($row){

        $cia   =  Auth::user()->empresa;
      

        

        $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTACION_VAL_INST_DOC(:p1,:p2,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $row, PDO::PARAM_STR,10000); 
        $stmt->bindParam(':rpta', $list, PDO::PARAM_STR,10000);
        
          $stmt->execute();

        


        
        return $list;

       
    

  }

protected static function confirmar_habilitacion_duplicado($request){

        $cia   =  Auth::user()->empresa;
        $comentario = trim($request->comentario);
        $token  = $request->token;
        $usuario  = Auth::user()->codigo;

        

        $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTACION_HABILITARDP(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $token, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $comentario, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $usuario, PDO::PARAM_STR);
     
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();

        return $rpta ;

       
    

  }

protected static function confirmar_operacion_duplicado($request,$sublist){

      

        $cia   =  Auth::user()->empresa;
      
        $usuario  = Auth::user()->codigo;

        $data = $request->data;

        

        $tokens ='';

        foreach($sublist as $values){

          $tokens .= $values["TOKEN"].",";
        }

         $tokens = rtrim($tokens,',');


         
         
        $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTACION_CONFIRMADP(:p1,:p2,:p3,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
       
        $stmt->bindParam(':p2', $usuario, PDO::PARAM_STR);

        $stmt->bindParam(':p3', $tokens, PDO::PARAM_STR,100000000);
     
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();

        return $rpta ;

       
    

  }

  


    
 protected static function registro_captacion_masiva_guardar($request,$listx){

        //$cia   = $request->cia;
        $medio = $request->medio;
        $tipo  = $request->tipo;
        $ficha = $request->ficha;
        
        $usuario = Auth::user()->codigo;
        $cia = Auth::user()->empresa;
       
        
        $list = self::set_filas_masiva_excel($listx,$cia);

        
        

        $ciudad = $request->ciudad;
        
       
       $servicios = $request->servicios;

       // if(count((array)$request->servicios)>0){

       //  $servicios = implode(",",$request->servicios); 
       // }
       

        $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTADORES_INSERT_MAS(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $medio, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $ficha, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $ciudad, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $list, PDO::PARAM_STR,100000000);
        $stmt->bindParam(':p8', $servicios, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();

        return $rpta ;

       
    

  }


  protected static function valida_duplicidad_registro_individual($celular,$dni,$correo){

        // $cia = Auth::user()->empresa;
       


        // $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTADORES_VAL_INDV(:p1,:p2,:p3,:p4,:rpta); end;");

        // $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        // $stmt->bindParam(':p2', $dni, PDO::PARAM_STR);
        // $stmt->bindParam(':p3', $correo, PDO::PARAM_STR);
        // $stmt->bindParam(':p4', $celular, PDO::PARAM_STR);

       
        // $stmt->bindParam(':rpta', $rpta, PDO::PARAM_STR,1000);
        
        // $stmt->execute();

        // return $rpta ;

       
       return '';
        



  }
  

   protected static function set_contactos_modal_captacion($request){

        $cia = Auth::user()->empresa;

        $contrato = $request->contrato;


        $stmt = static::$pdo->prepare("begin WEB_CLIENTES_DATBITACORA(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        $result = array();

        foreach ($list as $value) {
          
          $result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;

     

    }

   
   protected static function salvar_registro_individual($request){

       
   
        $cia = Auth::user()->empresa;

        

        $date = date('Y-m-d H:i:s');


        $token = sha1( md5( uniqid( $date, true ) ) );


        //$cia    = $request->registroCia;
        $medio  = $request->registroMedioCap;
        $tipo   = $request->registroTipoCap;
        //$ficha  = $request->registroFichaCap;
        $ficha  = $request->vm_ultima_ficha;
        $institucion = $request->registroInstitucion;
        $medico      = $request->registroMedico;


        $nombres = mb_strtoupper(trim($request->registroFullName));
        $celular = trim($request->registroCelular);
        $dni     = trim($request->registroDNI);
        $correo  = trim($request->registroCorreo);

        $parto   = (!empty($request->registroFParto))?Carbon::parse($request->registroFParto)->format('d/m/Y'):'';

        $mensaje=trim($request->mensaje);

        $contrato_ref = $request->contrato_ref;

        $cliente_ref = $request->cliente_ref;

        $usuario = Auth::user()->codigo;

        $institucion_text= $request->institucion_text;
        $medico_text= $request->medico_text;

        $XFLAGPAGWEB=0;
        $XFLAGWEBIND= $request->flag_individual;
        $XFLAGWEBMAS=0;
        $XFLAGMOVIL=0;
        $XFLAGEXTERNO=0;
        $XFLAGANUALIDAD = $request->flag_anualidad;
        $XFLAGVENDEDOR=0;


        $apepat = mb_strtoupper(trim($request->registroApepat));
        $apemat = mb_strtoupper(trim($request->registroApemat));

        $ciudad = $request->registroCiudad;





        $prioridad = (filter_var($request->prioridad, FILTER_VALIDATE_BOOLEAN))?'S':'N';

      




      
        $multiple_servicio = $request->multiple_servicios;

        $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTADORES_INSERT(:p1,:p2,:p3,:p31,:p32,:p4,:p5, :p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,:p18,:p19,:p20,:p21,:p22,:p23,:p24,:p25,:p26,:p27,:p28,:rpta); end;");

          $stmt->bindParam(':p1', $token, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $nombres, PDO::PARAM_STR);

          $stmt->bindParam(':p31', $apepat, PDO::PARAM_STR);
          $stmt->bindParam(':p32', $apemat, PDO::PARAM_STR);


          $stmt->bindParam(':p4', $dni, PDO::PARAM_STR);
          $stmt->bindParam(':p5', $celular, PDO::PARAM_STR);
          $stmt->bindParam(':p6', $correo, PDO::PARAM_STR);
          $stmt->bindParam(':p7', $institucion, PDO::PARAM_STR);
          $stmt->bindParam(':p8', $institucion_text, PDO::PARAM_STR);
          $stmt->bindParam(':p9', $medico, PDO::PARAM_STR);
          $stmt->bindParam(':p10', $medico_text, PDO::PARAM_STR);
          $stmt->bindParam(':p11', $parto, PDO::PARAM_STR);
          $stmt->bindParam(':p12', $mensaje, PDO::PARAM_STR);
          $stmt->bindParam(':p13', $medio, PDO::PARAM_STR);
          $stmt->bindParam(':p14', $tipo, PDO::PARAM_STR);
          $stmt->bindParam(':p15', $ficha, PDO::PARAM_STR);
          $stmt->bindParam(':p16', $usuario, PDO::PARAM_STR);
          $stmt->bindParam(':p17', $contrato_ref, PDO::PARAM_STR);
          $stmt->bindParam(':p18', $cliente_ref, PDO::PARAM_STR);
          $stmt->bindParam(':p19', $XFLAGPAGWEB, PDO::PARAM_STR);
          $stmt->bindParam(':p20', $XFLAGWEBIND, PDO::PARAM_STR);
          $stmt->bindParam(':p21', $XFLAGWEBMAS, PDO::PARAM_STR);
          $stmt->bindParam(':p22', $XFLAGMOVIL, PDO::PARAM_STR);
          $stmt->bindParam(':p23', $XFLAGEXTERNO, PDO::PARAM_STR);
          $stmt->bindParam(':p24', $XFLAGANUALIDAD, PDO::PARAM_STR);
          $stmt->bindParam(':p25', $XFLAGVENDEDOR, PDO::PARAM_STR);
          $stmt->bindParam(':p26', $ciudad, PDO::PARAM_STR);
          $stmt->bindParam(':p27', $multiple_servicio, PDO::PARAM_STR);
          $stmt->bindParam(':p28', $prioridad, PDO::PARAM_STR);

          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

          return $rpta;

        


   }

    

   
   protected static function graficos_grupos_captacion($request){

        $cia = Auth::user()->empresa;

          $vendedores = null;

       if(count((array)$request->vendedores)>0){

        $vendedores = implode(",",$request->vendedores);
       }
       



        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/y');

      $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/y');


     $stmt = static::$pdo->prepare("begin WEB_ASIGNACIONGRUPO_RPTGRF(:p1,:p2,:p3,:p4,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $vendedores, PDO::PARAM_STR,100000);
          $stmt->bindParam(':p3', $start, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $end, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);


        
        return $list;

     

    }


   protected static function graficos_medios_captacion($request){

        $cia = Auth::user()->empresa;

          $vendedores = null;

       if(count((array)$request->vendedores)>0){

        $vendedores = implode(",",$request->vendedores);
       }
       


        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

      $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');


        $stmt = static::$pdo->prepare("begin WEB_ASIGNACIONMEDIO_RPTGRF(:p1,:p2,:p3,:p4,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $vendedores, PDO::PARAM_STR,10000);
          $stmt->bindParam(':p3', $start, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $end, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);


        
        return $list;

     

    }









 protected static function set_asignaciones_vendedor($request){

        $cia = Auth::user()->empresa;

          $vendedores = null;

       if(count((array)$request->vendedores)>0){

        $vendedores = implode(",",$request->vendedores);
       }



        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

      $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');


$stmt = static::$pdo->prepare("begin WEB_VEN_VENDEDORESASIGNA(:p1,:p2,:p3,:p4, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $vendedores, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $start, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $end, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);


        
        return $list;

     

    }


     protected static function list_captacion(){

        $p1 = Auth::user()->empresa;
        $p2='';
        
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


    protected static function filter_vendedor(){

        $p1 = Auth::user()->empresa;
        $p2='';
        
        $stmt = static::$pdo->prepare("begin WEB_VENDCAP_LIST_AUTOMPLETA(:p1,:p2, :c); end;");
        
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


    protected static function filter_vendedor_por_servicios(){

        $p1 = Auth::user()->empresa;

        $p2 = self::permisos_servicios();

        $p3='';
        
        $stmt = static::$pdo->prepare("begin WEB_VENDCAP_LIST_AUTO_XSERV(:p1,:p2,:p3,:c); end;");
        
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
            
            $result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;

    
    }

    

 protected static function filter_captador_por_servicios(){

        $p1 = Auth::user()->empresa;

        $p2 = self::permisos_servicios();

        $p3='';
        
        $stmt = static::$pdo->prepare("begin WEB_CAPTADOR_LISTA_AUTO_SERV(:p1,:p2,:p3,:c); end;");
        
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
            
            $result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;

    
    }


protected static function confirmar_asignaciones_vendedor($request,$listx){


        
        
        
        $cia = Auth::user()->empresa;

        $usuario = Auth::user()->codigo;
        
        //$list = $request->data;

        $txt = "";

       

        foreach ($listx as $value) {
          
          $txt.= $value["TOKEN"].'|'.$value["VENDEDOR"].',';

        }

       
        

        $rows = rtrim($txt,',');
        

       

        $stmt = static::$pdo->prepare("begin WEB_VEN_CAPASIGNA_VENDEDOR(:p1,:p2,:p3,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $rows, PDO::PARAM_STR,1000000);
      
        $stmt->bindParam(':p3', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();

        return $rpta ;


}




//reportes

protected static function report_get_cia_vendedor($request){

        $user = Auth::user()->codigo;



        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

      $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');


      $select_serivicios =($request->select_serivicios=='null')?NULL:$request->select_serivicios;

      $select_cia =$request->select_cia;





$stmt = static::$pdo->prepare("begin WEB_CRM_RPTCAPTACION_VENDEDOR(:p1,:p2,:p3,:p4, :c); end;");

          $stmt->bindParam(':p1', $select_cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $select_serivicios, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $start, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $end, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);


        
        return $list;

     

    }


protected static function report_get_cia_captador($request){

        $user = Auth::user()->codigo;



        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

      $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');


      $select_serivicios =($request->select_serivicios=='null')?NULL:$request->select_serivicios;

      $select_cia =$request->select_cia;





$stmt = static::$pdo->prepare("begin WEB_CRM_RPTCAPTACION_CAPTADOR(:p1,:p2,:p3,:p4, :c); end;");

          $stmt->bindParam(':p1', $select_cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $select_serivicios, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $start, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $end, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);


        
        return $list;

     

    }


protected static function report_get_cia_medio($request){

        $user = Auth::user()->codigo;



        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

      $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');


      $select_serivicios =($request->select_serivicios=='null')?NULL:$request->select_serivicios;

      $select_cia =$request->select_cia;





$stmt = static::$pdo->prepare("begin WEB_CRM_RPTCAPTACION_MEDIOS(:p1,:p2,:p3,:p4, :c); end;");

          $stmt->bindParam(':p1', $select_cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $select_serivicios, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $start, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $end, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);


        
        return $list;

     

    }
    


protected static function report_get_periodo_cia($request){

        $user = Auth::user()->codigo;



        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

      $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');


      $select_serivicios =($request->select_serivicios=='null')?NULL:$request->select_serivicios;

     
      //$select_cia =$request->select_cia;


        $select_cia =($request->select_cia=='null')?NULL:$request->select_cia;



$stmt = static::$pdo->prepare("begin WEB_CRM_RPTCANTCAPTACION(:p1,:p2,:p3,:p4, :c); end;");

          $stmt->bindParam(':p1', $select_cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $select_serivicios, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $start, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $end, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);


        
        return $list;

     

    }



protected static function report_get_cia_estados($request){

        $user = Auth::user()->codigo;



        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

      $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');


      $select_serivicios =($request->select_serivicios=='null')?NULL:$request->select_serivicios;

      $select_cia =$request->select_cia;





$stmt = static::$pdo->prepare("begin WEB_CRM_RPTESTADO_CAPTACION(:p1,:p2,:p3,:p4, :c); end;");

          $stmt->bindParam(':p1', $select_cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $select_serivicios, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $start, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $end, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);


        
        return $list;

     

    }






   
}
