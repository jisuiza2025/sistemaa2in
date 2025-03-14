<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Bitacora extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

  
 	


    protected static function get_detail_bitacora_data($contrato){

       $cia = Auth::user()->empresa;

       $responsable = self::get_informacion_responsable($contrato);

       $tcambio = self::get_informacion_tipo_cambio($contrato);

       $cabecera = self::get_informacion_cabecera($contrato);

       $laboratorio = self::get_informacion_laboratorio($contrato);

       $info_lab =  self::get_info_laboratorio_ven($contrato);

       $info_contrato = self::get_info_contrato($contrato);

       $data = array('responsable'=>$responsable,'tipo_cambio'=>$tcambio,'cabecera'=>$cabecera,'laboratorio'=>$laboratorio,'infolab'=>$info_lab,'infocontrato'=>$info_contrato);

        return $data;

    }
    protected static function get_info_contrato($contrato)
  {



    $cia = Auth::user()->empresa;


    $stmt = static::$pdo->prepare("begin WEB_VENCONTRATO_VER (:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }


    protected static function get_datos_complementario_cliente_bitacora($request){

        $cia = Auth::user()->empresa;

       $identificacion = $request->identificacion;

       $list = DB::select("SELECT TELEFONO_CONTACTO2,CELULAR_CONTACTO2,MAIL_CONTACTO2,
TELEFONO_CONTACTO3,CELULAR_CONTACTO3,MAIL_CONTACTO3 FROM VEN_CLIENTES WHERE IDENTIFICACION=? AND NO_CIA=?",array($identificacion,$cia));


        return $list;

    }




protected static function get_detalle_ver_bitocora_historial($request){

      $cia = Auth::user()->empresa;

       $contrato = $request->contrato;

       $proxifecha = $request->fecha_comunicacion;

       $hora = $request->hora_comunicacion;

       $fulltime = $proxifecha.' '.$hora;
       
       $list = DB::select("SELECT QUIEN_CONTACTO,LOGRO_CONTACTO,DETALLE,MEDIO_CONTACTO,SE_INSERTO,SE_MODIFICO,EMITEDOC FROM CXC_DOCUMENTOS_DETALLE WHERE NO_CIA=? AND NUMERO_CONTRATO=? AND TO_CHAR(FECHA_COMUNICACION,'YYYY-MM-DD HH24:MI:SS')=?",array($cia,$contrato,$fulltime));


        return $list;

    }


protected static function list_deuda_familia_bitacora($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->mama;

        $p3 = $request->papa;

        $stmt = static::$pdo->prepare("begin WEB_CXCDOCUMENTOS_CXCFAMILIA(:p1,:p2,:p3, :c); end;");

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
    protected static function list_contratos_bitacora($request){

        $p1 = Auth::user()->empresa;

        $p2 = trim($request->mama);

        $p3 = trim($request->papa);

        $stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_LISTFAMILIA(:p1,:p2,:p3, :c); end;");

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


    protected static function get_informacion_responsable($contrato){

        $cia = Auth::user()->empresa;
        $stmt = static::$pdo->prepare("begin WEB_DOCUMENTOS_RESPONSABLE(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     

    }

    protected static function get_informacion_tipo_cambio(){

        
        $stmt = static::$pdo->prepare("begin WEB_COR_TIPOCAMBIO_VENCOM(:c); end;");

         
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     

    }

     protected static function get_informacion_cabecera($contrato){

        $cia = Auth::user()->empresa;
        $stmt = static::$pdo->prepare("begin WEB_DOCUMENTOS_REGBITACORA(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     

    }

    protected static function get_informacion_laboratorio($contrato){

        $cia = Auth::user()->empresa;
        $stmt = static::$pdo->prepare("begin WEB_INFO_LABORATORIO_BIT(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     

    }
    protected static function get_info_laboratorio_ven($contrato)
  {

    $cia = Auth::user()->empresa;

    $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATO_INFOLAB(:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);


    return $list;

  }

    
    protected static function update_titular_bitacora($request,$contrato){


      $contactos = $request->list_tabla_contactos;

      $contactos = json_decode($contactos,true);
      

      $emite = $request->multipleservicios;
      
      $tipo = '';

      $identificacion ='';

      if($emite=='M'){

        $tipo='MAMÁ';

      }elseif($emite=='P'){

        $tipo='PAPÁ';

      }elseif($emite=='T'){

        $tipo='TITULAR';

      }elseif($emite=='PR'){

        $tipo='PROPIETARIO';

      }
      

      foreach($contactos as $list){

          if($list["TIPO"] ==$tipo){

            $identificacion =$list["IDENTIFICACION"];

          }

      }



      $cia = Auth::user()->empresa;

      $emite = ($emite=='PR')?'O':$emite;


      \DB::update("UPDATE VEN_CONTRATOS SET TITULAR_PAGO=? WHERE NO_CIA=? AND NUMERO_CONTRATO=?",array($emite,$cia,$contrato));


      

       
        $variable = 2;

    

        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_BITA_IDENT_UPD(:p1,:p2,:p3,:p4,:rpta); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $variable, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $identificacion, PDO::PARAM_STR);
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();
          return $rpta;






    }


    protected static function salvar_nueva_bitacora($request,$contrato){



        $p1 = Auth::user()->empresa;
        $p2 = $contrato;
        $p3 = (empty($request->vm_fcomunicacion))?'':Carbon::parse($request->vm_fcomunicacion)->format('Y-m-d');

        $p4 = $request->vm_qcontacto;
        $p5 = ($request->vm_respuesta=='true')?'SI':'NO';

        $p6 = $request->vm_comentario_bit;

        $p7 = (empty($request->vm_proxcontacto))?'':Carbon::parse($request->vm_proxcontacto)->format('Y-m-d');
        $p8 = $request->vm_mcomunicacion;

        $p9 = Auth::user()->codigo;
        $p10 = Auth::user()->codigo;

        $p11 = '';
        $p12 = '';
        $p13 = '';
        $p14 = $request->responsable;//responsable del contrato
        $p15 = 'N'; //debito automatico

        

         $p16  ='';

       

         

        if ($request->file('file')) {

           $directorio      = 'adjuntos_nueva_bitacora/';

            $ext      = strtolower($request->file('file')->getClientOriginalExtension()); 

            $fileName = str_random() . '.' . $ext;

            

            //$request->file('file')->storeAs($directorio, $fileName,'public');

            copy($request->file('file')->getRealPath(), public_path($directorio).$fileName);

            $p16= $fileName;

        }


        

        $p17 = ($request->vm_respuesta_contratos_aplica=='true')?1:0;

        $p18 = $request->multipleservicios;

        
        

        $stmt = static::$pdo->prepare("begin WEB_CXCDOCUMENTOS_DETALLE_I01(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,:p18,:c); end;");

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
            $stmt->bindParam(':p16', $p16, PDO::PARAM_STR);
            $stmt->bindParam(':p17', $p17, PDO::PARAM_STR);
            $stmt->bindParam(':p18', $p18, PDO::PARAM_STR);

          $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

      

      
        
        return $rpta;

     

    }




protected static function registra_nuevo_servicio_nuevo($request){


          $date = date('Y-m-d H:i:s');

          $token = sha1( md5( uniqid( $date, true ) ) );


        $cia = Auth::user()->empresa;

           $vm_registro_captacion = $request->vm_registro_captacion;
        $vm_registro_ficha = $request->vm_registro_ficha;
        $vm_ultima_ficha = $request->vm_ultima_ficha;


        
        $contrato =  $request->contrato;

         $vm_contratos_clientes = $request->vm_contratos_clientes;

           $vm_institucion = $request->vm_institucion;
        $vm_medico = $request->vm_medico;


        $vm_cel_nuevo_ref = $request->vm_cel_nuevo_ref;
        $vm_correo_nuevo_ref = $request->vm_correo_nuevo_ref;

            $servicios = $request->servicios;

            //$servicios  = implode(',',$servicios);
          

        $vm_ser_Fe_bit = $request->vm_ser_Fe_bit;
        
        
       $prioridad = $request->prioridad;
        
       
      
        $vm_comentario_fe = $request->vm_comentario_fe;
      
      $user = Auth::user()->codigo;

    
        $stmt = static::$pdo->prepare("begin WEB_VEN_CAPTADORES_INNEWSERV(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:c); end;");

          $stmt->bindParam(':p1', $token, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $vm_registro_captacion, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $vm_registro_ficha, PDO::PARAM_STR);
          $stmt->bindParam(':p5', $vm_ultima_ficha, PDO::PARAM_STR);
          $stmt->bindParam(':p6', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':p7', $vm_contratos_clientes, PDO::PARAM_STR);
          $stmt->bindParam(':p8', $vm_institucion, PDO::PARAM_STR);
          $stmt->bindParam(':p9', $vm_medico, PDO::PARAM_STR);
          $stmt->bindParam(':p10', $vm_cel_nuevo_ref, PDO::PARAM_STR);
          $stmt->bindParam(':p11', $vm_correo_nuevo_ref, PDO::PARAM_STR);
          $stmt->bindParam(':p12', $servicios, PDO::PARAM_STR);
          $stmt->bindParam(':p13', $vm_ser_Fe_bit, PDO::PARAM_STR);

           $stmt->bindParam(':p14', $vm_comentario_fe, PDO::PARAM_STR);
            $stmt->bindParam(':p15', $user, PDO::PARAM_STR);
             $stmt->bindParam(':p16', $prioridad, PDO::PARAM_STR);

          $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

      

      
        
        return $rpta;

     

    }


    protected static function actualiza_nombre_bebe_bitacora($request){

        $cia = Auth::user()->empresa;

        $contrato =  $request->contrato;

        $nombre = $request->nombre;

    
        $stmt = static::$pdo->prepare("begin WEB_UPDATE_NOMBRE_BEBE(:p1,:p2,:p3, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $nombre, PDO::PARAM_STR);
          $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

      

      
        
        return $rpta;

     

    }


    protected static function get_contactos($request){

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

      
        $data = self::set_tabla_contactos_completa($list);

        return $data;

     

    }
 
    protected static function list_bitacora_bitacora($request){

        $cia = Auth::user()->empresa;

        $contrato = $request->contrato;
        $flag=$request->order_flag;

        $stmt = static::$pdo->prepare("begin WEB_CXCDOCUMENTOS_DETALLE_Q01(:p1,:p2, :c); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
      
        usort($list, function ($a, $b) {
          return strtotime($b["FCOMPARSE"]) - strtotime($a["FCOMPARSE"]);
        });
        //return $list;
        if(isset($flag)){
          $lista_anclados = array_filter($list,function($dato){
              return $dato["FLAG_ANCLAR"]>"0";
          });
          usort($lista_anclados, function ($a, $b) {
            return strtotime($b["FCOMPARSE"]) - strtotime($a["FCOMPARSE"]);;
          });
          $lista_datos= array_filter($list,function($dato){
            return $dato["FLAG_ANCLAR"]=="0";
          });
          usort($lista_datos, function ($a, $b) {
            return strtotime($b["FCOMPARSE"]) - strtotime($a["FCOMPARSE"]);
          });
          $data = array_merge($lista_anclados,$lista_datos) ;
           
        }else{
          usort($list, function ($a, $b) {

            //return strcmp($a["FECHA_COMUNICACION"], $b["FECHA_COMUNICACION"]);

            //return strtotime(trim($a['FECHA_COMUNICACION'])) < strtotime(trim($b['FECHA_COMUNICACION']));

            return strtotime($b["FCOMPARSE"]) - strtotime($a["FCOMPARSE"]);
        
          });
          return $list;
        }
       



      
        return $data;



    }
    

    protected static function get_detail_estado_cuenta($contrato){

        $cia = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_ESTADO_CUENTA_CONTRATO(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

    
        return $list;


    }
    
    

    
     protected static function actualiza_datos_complmentarios_bitacora($request){

        $p1 = Auth::user()->empresa;

       $p2= $request->identificacion;
       $p3= $request->telefono;
       $p4= $request->celular;
       $p5= $request->mail;
       $p6= $request->telefono2;
       $p7= $request->celular2;
       $p8= $request->mail2;
       $p9= $request->telefono3;
       $p10= $request->celular3;
       $p11= $request->mail3;
       $p12= $request->direccion;
       $p13= ($request->inubicable)?'S':'N';


        $stmt = static::$pdo->prepare("begin WEB_CLIENTES_DATBITACORA_UPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13, :c); end;");

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
          $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();

       

    
        return $rpta;


    }

    protected static function set_tabla_contactos_completa($list){

        $cia = Auth::user()->empresa;

        $data = array();

        $tipos = array('MAMÁ','PAPÁ','TITULAR','PROPIETARIO');

        $tipos_query = array();


        foreach ($list as  $value) {
           
           $tipos_query[] = $value['TIPO'];

        }

        $restante = array_diff($tipos,$tipos_query);


        foreach ($restante as $values) {
            

             $data[] = array('TIPO'  => $values,
                                'NO_CIA'=> $cia,
                                'IDENTIFICACION'=> '',
                                'CODIGO_DOCUMENTO'=> '',
                                'APATERNO'=> '',
                                'AMATERNO'=> '',
                                'NOMBRE_CORTO'=> '',
                                'NOMBRE'=> '',
                                'ALIAS'=> '',
                                'DIRECCION'=> '',
                                'TELEFONO_CONTACTO'=> '',
                                'CELULAR_CONTACTO'=> '',
                                'MAIL_CONTACTO'=> '',
                                'INUBICABLE'=> '',
                                'ORIGEN'=>'PHP'
                                );
          


        }

        return array_merge($list, $data);

    }



    //HISTOAL BITACORA
    
    protected function list_historial_bitacora($request){

      $p1 = Auth::user()->empresa;    

        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d-m-Y');

      $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d-m-Y');

     

      $p4 = (empty($request->responsable))?'':$request->responsable;
      
      $p5 = (empty($request->numero_contrato))?'':$request->numero_contrato;
      $p6 = (empty($request->exacta))?'0':$request->exacta;
     

      
    $stmt = static::$pdo->prepare("begin WEB_CXCDOCUMENTOS_BITHIST(:p1, :p2, :p3, :p4, :P5, :P6, :c); end;");
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $start, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $end, PDO::PARAM_STR);
    $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
    $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
    $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
    
    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
    
        return $list;
    }
    
    protected function save_historial_bitacora($request){

        $p1 = Auth::user()->empresa; 

        $p2 = $request->num_contrato;
        $p3 = $request->fecha_comunicacion.' '.$request->hora_comunicacion;
        

       


        $p4 = $request->contacto;
        $p5 = $request->logro;
        $p6 = $request->detalle;
        $p7 = $request->prox_contra.' '.$request->hora_proxcontrato; 
        
        
        
        $p8 =$request->med_comunicacion;
        $p9 = Auth::user()->codigo;



        $p10 = $request->emit_comprobante;


        $stmt = static::$pdo->prepare("begin WEB_CXCDOCUMENTOS_DETALLE_UDP(:p1, :p2,:p3,:p4, :p5, :p6, :p7, :p8, :p9,:p10, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $p8, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $p9, PDO::PARAM_STR);
        $stmt->bindParam(':p10',$p10, PDO::PARAM_STR);

        $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
     
        return $rpta;
    }





protected static function get_files_bitacora_data($contrato){

        
        $cia = Auth::user()->empresa;

        $result = array();

        $query = DB::select("SELECT * FROM VEN_CONTRATOS_ARCHIVOS WHERE NO_CIA=? AND NUMERO_CONTRATO=? ",array($cia,$contrato));

        $list = json_decode(json_encode($query),true);

        foreach ($list as $values ) {
          
          

          $flag_ver = ($values['archivo']!="")?true:false;


          $result[]=array("CODIGO_ARCHIVO"=>$values['codigo_archivo'],
                          "DESCRIPCION"=>$values['descripcion'],
                          "ARCHIVO"=>$values['archivo'],
                          "VER"=>$flag_ver

                        );
          


        }
        
        return $result;

    }

    protected static function confirma_nueva_descripcion_adjunto_bitacora($request){


      $contrato = $request->contrato;
      $tipo  = $request->tipo;
      $descripcion = $request->descripcion;
      $file = null;
      $ruta =null;

      $rpta = self::inserta_actualiza_files_bitacora($contrato,$tipo,$descripcion,$file,$ruta);

      return $rpta ; 


    }

    protected static function inserta_actualiza_files_bitacora($contrato,$tipo,$descripcion,$file,$ruta){



       $cia  = Auth::user()->empresa;

         $stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_ARCHIVOS_UPD(:p1, :p2,:p3,:p4, :p5,:p6,:rpta); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $file, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $ruta, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);


        $stmt->execute();
     
        return $rpta;



    }



    protected static function upload_file_bitacora($request){

      $dir      = 'files_bitacora/';

      $file = '';

        if ($request->file('file')) {

            $ext      = strtolower($request->file('file')->getClientOriginalExtension()); 
            $fileName = str_random() . '.' . $ext;
            $request->file('file')->move($dir, $fileName);

            $file     = $fileName;
        }
       
        $descripcion ='';

        $contrato = $request->contrato;

        $tipo    = $request->tipo;

        $ruta   = public_path($dir).$file;

        $rpta = self::inserta_actualiza_files_bitacora($contrato,$tipo,$descripcion,$file,$ruta);

        
     
        return $rpta;

       
    }

    protected static function registra_cliente_nuevo_bitacora($request){

      

        $cia = Auth::user()->empresa;

        $variable = '';

        if($request->variable=='PAPÁ'){
            $variable = 1;
        }elseif($request->variable=='MAMÁ'){
            $variable = 0;
        }elseif($request->variable=='TITULAR'){
            $variable = 2;
        }elseif($request->variable=='PROPIETARIO'){
            $variable = 3;
        }

        $contrato = $request->contrato;
       
        $identificacion = $request->identificacion;


        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_BITA_IDENT_UPD(:p1,:p2,:p3,:p4,:rpta); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $variable, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $identificacion, PDO::PARAM_STR);
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();
          return $rpta;

     

    }





    protected static function get_filename_plantilla_colectas($servicio,$mama_soltera){

        $cia = Auth::user()->empresa;

        $email='N';

        $stmt = static::$pdo->prepare("begin WEB_LAB_PLANTILLA_COLECTAS_DOC(:p1,:p2,:p3,:p4,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $email, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $mama_soltera, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     

    }


    protected static function get_llaves_plantilla_colectas($contrato,$numero_colecta){

        $cia = Auth::user()->empresa;

        

        $stmt = static::$pdo->prepare("begin WEB_LABCOLECTAS_INFORME_Q1(:p1,:p2,:p3,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $numero_colecta, PDO::PARAM_STR);
        
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     

    }



    protected static function get_data_envio_lab_correo($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->contrato;

        $stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_ENVIOLAB(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     

    }




protected static function modal_asigna_inubicable($request){

      

        $cia = Auth::user()->empresa;

      
       
        $identificacion = $request->identificacion;

        //cambiamos a S/N
        

        $subquery = DB::select("SELECT NVL(INUBICABLE,'N') AS INUBICABLE FROM  VEN_CLIENTES WHERE NO_CIA = ? AND IDENTIFICACION = ?",array($cia,$identificacion));


        $rpta_query = json_decode(json_encode($subquery),true);
  
        $inubicable = ($rpta_query[0]['inubicable']=='S')?'N':'S';


        //$inubicable='S';

        $stmt = static::$pdo->prepare("begin WEB_CLIENTES_INUBICABLE(:p1,:p2,:p3,:rpta); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $identificacion, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $inubicable, PDO::PARAM_STR);
        
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();
          return $rpta;

     

    }


    
    protected static function modal_activa_notificacion_cliente($request){



        $cia = Auth::user()->empresa;

       
        $identificacion = $request->identificacion;

        $flag= ($request->flag==1)?0:1;

        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_BITA_UPD_NOTI(:p1,:p2,:p3,:rpta); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
           $stmt->bindParam(':p2', $flag, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $identificacion, PDO::PARAM_STR);
        
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();
          return $rpta;

     

    }


    protected static function modal_asigna_propietario($request){



        $cia = Auth::user()->empresa;

        $variable = '';

        $emite='';

        if($request->tipo=='PAPÁ'){
            $variable = 1;

             $emite='P';

        }elseif($request->tipo=='MAMÁ'){
            $variable = 0;
             $emite='M';
        }elseif($request->tipo=='TITULAR'){
            $variable = 2;
             $emite='T';
        }elseif($request->tipo=='PROPIETARIO'){
            $variable = 3;
             $emite='O';
        }


        $variable = 2;


        $contrato = $request->contrato;
       
        $identificacion = $request->identificacion;

        


        \DB::update("UPDATE VEN_CONTRATOS SET TITULAR_PAGO=? WHERE NO_CIA=? AND NUMERO_CONTRATO=?",array($emite,$cia,$contrato));


        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_BITA_IDENT_UPD(:p1,:p2,:p3,:p4,:rpta); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $variable, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $identificacion, PDO::PARAM_STR);
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();
          return $rpta;

     

    }
    protected static function actualiza_debito_automatico($request){

      $automatico = $request->debito;
      
  
      $cia = Auth::user()->empresa;
  
      $contrato = $request->contrato;
      $rpta='';
      if(!empty($contrato)){
        $rpta = DB::update("UPDATE ven_contratos SET debito_automatic=? WHERE no_cia=? AND numero_contrato=?",array($automatico,$cia,$contrato));
      }
  
      return $rpta ;
  
    }
    protected static function anclar_detalle_bitacora($request){

      $anclar=$request->anclar;
      $cia = Auth::user()->empresa;
  
      $contrato = $request->contrato;
      
      $fecha_comunicacion=$request->fecha_comunicacion;
      //$cia=$request->cia ;
      $usuario=$request->usuario ;
      $fecha_registro=$request->fecha_registro;
      $detalle=$request->detalle;
      //SET FLAG_ANCLAR=?
      $rpta = DB::update("UPDATE CXC_DOCUMENTOS_DETALLE  SET flag_anclar=?
       WHERE no_cia=? AND numero_contrato=? and detalle=? and usuario=? and fecha_registro=?"
       ,array($anclar,$cia,$contrato,$detalle,$usuario,$fecha_registro));

      return $rpta;
    }
   
}
