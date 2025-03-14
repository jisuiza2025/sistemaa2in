<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;

class RecojoColecta extends Model
{
    protected  static $pdo;

    protected function __construct()

    {
        static::$pdo = DB::getPdo();
    }

    protected function list_recojo(){

        $cia    = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_LABCOLECTAS_LITSRECOLEC(:p1,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        return $list;
    }  


    protected function datos_propietario_dental_colectas($request){

        $cia    = Auth::user()->empresa;

        $contrato = $request->contrato;


        $stmt = static::$pdo->prepare("begin WEB_VENPROSPECTOS_OBTDAT_PROPI(:p1,:p2,:c); end;");

        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
        
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        return $list;
    }  

    protected function list_rec_modal($request){

        $cia    = Auth::user()->empresa;

        $num_contrato=$request->n_contrato_modal;

        $cliente=$request->select_cliente;

        $stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_LITSRECOLEC(:p1,:p2,:p3,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $num_contrato, PDO::PARAM_STR);

         $stmt->bindParam(':p3', $cliente, PDO::PARAM_STR);
        
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

     

        return $list;
    }  

    protected function lista_datos_contrato($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->num_contrato;
        
        $stmt = static::$pdo->prepare("begin WEB_VENCONTRATO_VER_Q2(:p1,:p2, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    } 

    protected function list_contactos_tab($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->num_contrato;
        
        $stmt = static::$pdo->prepare("begin WEB_CLIENTES_DATBITACORA(:p1,:p2, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    } 

    protected function lista_clientes($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->num_contrato;
        
        $stmt = static::$pdo->prepare("begin WEB_COR_USUARIOS_ACTIVO_ACOMPL( :c); end;");

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }




protected static function filter_responsable_constancia_colecta($request){

       
        
        $p1 = $request->get('q');
    
        $stmt = static::$pdo->prepare("begin WEB_CORUSUARIO_PORCODIGO(:p1, :c); end;");
        
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






protected function set_tipos_servicio_constancia_recojo($request){

        $p1 = Auth::user()->empresa;

        $p2 = trim($request->numero_base);
        
        $stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_SERVICIO_LIST(:p1,:p2,:c); end;");


        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);

        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }





protected function set_datos_complementarios_contrato_edit_colecta($request){

        $p1 = Auth::user()->empresa;

        $p2 = trim($request->contrato);
        
        $stmt = static::$pdo->prepare("begin WEB_LAB_COLECTAS_OBTDAT(:p1,:p2,:c); end;");


        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);

        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }


protected function list_contactos_recojo_colecta($request){

        $p1 = Auth::user()->empresa;

        $p2 = trim($request->contrato);
        
        $stmt = static::$pdo->prepare("begin WEB_CLIENTES_DATBITACORA_BASE(:p1,:p2,:c); end;");


        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);

        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }

    protected static function eliminar_recojo_colecta($request){

        

        $p1 = Auth::user()->empresa;

        $p2 = trim($request->contrato);

        $p3 =  Auth::user()->codigo;
  
        $stmt = static::$pdo->prepare("begin WEB_LABCOLECTAS_ANULAR(:p1,:p2,:p3, :rpta); end;");
    
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }


   




protected static function get_data_envio_lab_correo_colectas($request){

        $cia = Auth::user()->empresa;

        $contrato = $request->contrato;
        

        $stmt = static::$pdo->prepare("begin WEB_LABCOLECTAS_ENVIARRECOJ(:p1,:p2,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
         
        
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     

    }


    protected static function get_llaves_plantilla_colectas($numero_colecta,$numero_base){

        $cia = Auth::user()->empresa;

        

        $stmt = static::$pdo->prepare("begin WEB_LABCOLECTAS_IMPRRECOJO(:p1,:p2,:p3,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $numero_colecta, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $numero_base, PDO::PARAM_STR);
        
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
        
        return $list;

     

    }


    protected function set_cuerpo_sms_notificacion($contrato){

        $cia    = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_ENVIO_WHATSAPP_Q1(:p1,:p2,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        return $list;
    }  



     protected function set_cuerpo_sms_notificacion_vendedores($contrato){

        $cia    = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_ENVIO_WHATSAPP_Q2(:p1,:p2,:c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        return $list;
    } 


    protected static function registro_colecta($request){

        

        $p1 = Auth::user()->empresa;

        $p2 = $request->g1_fecha_reg.' '.$request->g1_hora_actual;
        

        if(empty($request->g1_fecha_reg) && !empty($request->g1_hora_actual)){

                $p2 ='';

            }
            
        // $p2 = (empty($request->g1_fecha_reg))?'':Carbon::parse($request->g1_fecha_reg)->format('Y-m-d');

        

        $p3 =  $request->g1_estado;
  
        $p4 =  $request->g1_fecha.' '.$request->g1_hora;

        if(empty($request->g1_fecha) && !empty($request->g1_hora)){

                $p4 ='';

            }

        // $p4 =  (empty($request->g1_fecha))?'':Carbon::parse($request->g1_fecha)->format('Y-m-d');

        $p5 =  $request->g1_colectas;

        $p6 =  $request->g1_responsable;

        $p7 =  $request->g1_usuario;

        $p8 =  $request->g1_quien_llamo;

        $p9 =  $request->g1_relacion_registro;

        $p10 =  $request->g1_quien_contesto;



        $p11 =  $request->g2_fecha.' '.$request->g2_hora;

        if(empty($request->g2_fecha) && !empty($request->g2_hora)){

                $p11 ='';

            }

        // $p11 =  (empty($request->g2_fecha))?'':Carbon::parse($request->g2_fecha)->format('Y-m-d');

        $p12 =  $request->g2_quien_entrega_dni;

        $p13 =  $request->g2_quien_entrega_nombre;

        $p14 =  $request->g2_relacion;

        $p15 =  $request->g2_nombre_propietario;

        $p16 =  $request->g2_dni_propietario;

        $p17 =  $request->g2_sexo_propietario;



        $p18 =  $request->g3_fecha.' '.$request->g3_hora;

        if($p18==" "){

                $p18 =null;

            }

        if(empty($request->g3_fecha) && !empty($request->g3_hora)){

                $p18 ='';

            }


        //$p18 =  (empty($request->g3_fecha))?'':Carbon::parse($request->g3_fecha)->format('Y-m-d');

        $p19 =  $request->g3_fecha_recepcion.' '.$request->g3_hora_recepcion;

        if(empty($request->g3_fecha_recepcion) && !empty($request->g3_hora_recepcion)){

                $p19 ='';

            }


        if($p19==" "){

                $p19 =null;

            }

        
       

        //$p19 =  (empty($request->g3_fecha_recepcion))?'':Carbon::parse($request->g3_fecha_recepcion)->format('Y-m-d');

        $p20 =  $request->g3_obs;

        $p21= rtrim($request->servicios_seleccionados,',');


        $p22 = implode(",",$request->vm_multiple_servicio);


       

        
        

        $stmt = static::$pdo->prepare("begin WEB_LABCOLECTAS_INPUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,:p18,:p19,:p20,:p21,:p22, :rpta); end;");
    
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
        $stmt->bindParam(':p19', $p19, PDO::PARAM_STR);
        $stmt->bindParam(':p20', $p20, PDO::PARAM_STR);

        $stmt->bindParam(':p21', $p21, PDO::PARAM_STR);
        $stmt->bindParam(':p22', $p22, PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
    
        $stmt->execute();

  
        return $rpta;

    
    }

    

}
