<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Contrato extends Model
{


  protected static $pdo;

  protected function __construct()
  {
    static::$pdo = DB::getPdo();
  }




 protected static function get_responsable_contrato_incentivo($request){

       
        $cia = Auth::user()->empresa;
        $servicio= $request->servicio;
        $tipo= $request->tipo;
    

        $stmt = static::$pdo->prepare("begin WEB_RESP_INCENT_CONTRATO(:p1,:p2,:p3, :c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $servicio, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $tipo, PDO::PARAM_STR);
    
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

protected static function get_reporte_incentivo_contrato($request){



 $cia = Auth::user()->empresa;
 $servicio =$request->servicio;
        $periodo = Carbon::parse($request->periodo)->format('m/Y');

        $tipo =$request->tipo;
        
        $responsable =$request->responsable;

      $stmt = static::$pdo->prepare("begin WEB_RPT_CTRLINCENTIVO_CONTRATO(:p1,:p2,:p3,:p4,:p5,:rpta); end;");

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
  protected static function list_contratos_seguimiento($request)
  {



    $cia = Auth::user()->empresa;

    $cliente = $request->cliente;

    $responsable = $request->responsable;

    $numero_contrato = trim($request->numero_contrato);

    $exacta = $request->exacta;

    $start = ($request->date[0] == 'null' || $request->date[0] == null) ? '' : Carbon::parse($request->date[0])->format('Y-m-d');

    $end = ($request->date[1] == 'null' || $request->date[1] == null) ? '' : Carbon::parse($request->date[1])->format('Y-m-d');



    $vendedor = $request->vendedor;

    $servicio = $request->servicio;


    $filtra_fecha = $request->filtra_fecha;



    $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_CONTROL_LIST(:p1,:p2,:p3,:p4,:p5, :p6,:p7,:p8,:p9,:p10,:c); end;");


    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $cliente, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $responsable, PDO::PARAM_STR);
    $stmt->bindParam(':p4', $numero_contrato, PDO::PARAM_STR);
    $stmt->bindParam(':p5', $exacta, PDO::PARAM_STR);
    $stmt->bindParam(':p6', $start, PDO::PARAM_STR);
    $stmt->bindParam(':p7', $end, PDO::PARAM_STR);
    $stmt->bindParam(':p8', $vendedor, PDO::PARAM_STR);
    $stmt->bindParam(':p9', $servicio, PDO::PARAM_STR);
    $stmt->bindParam(':p10', $filtra_fecha, PDO::PARAM_STR);
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }


  protected static function list_contratos($request)
  {



    $cia = Auth::user()->empresa;

    $cliente = $request->cliente;

    $responsable = $request->responsable;

    $numero_contrato = trim($request->numero_contrato);

    $exacta = $request->exacta;

    $start = ($request->date[0] == 'null' || $request->date[0] == null) ? '' : Carbon::parse($request->date[0])->format('Y-m-d');

    $end = ($request->date[1] == 'null' || $request->date[1] == null) ? '' : Carbon::parse($request->date[1])->format('Y-m-d');



    $vendedor = $request->vendedor;

    if (count((array) $request->servicio) > 0) {

      $servicio = implode(",", $request->servicio);
    } else {

      $servicio = null;

    }



    $filtra_fecha = $request->filtra_fecha;

    $tiporep = $request->tipo_rep;

    $tipo_fecha = ($request->tipo_fecha == "true") ? 0 : 1;



    $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_LIST(:p0,:p1,:p2,:p3,:p4,:p5, :p6,:p7,:p8,:p9,:p10,:p11,:c); end;");

    $stmt->bindParam(':p0', $tiporep, PDO::PARAM_STR);
    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $cliente, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $responsable, PDO::PARAM_STR);
    $stmt->bindParam(':p4', $numero_contrato, PDO::PARAM_STR);
    $stmt->bindParam(':p5', $exacta, PDO::PARAM_STR);
    $stmt->bindParam(':p6', $start, PDO::PARAM_STR);
    $stmt->bindParam(':p7', $end, PDO::PARAM_STR);
    $stmt->bindParam(':p8', $vendedor, PDO::PARAM_STR);
    $stmt->bindParam(':p9', $servicio, PDO::PARAM_STR);
    $stmt->bindParam(':p10', $filtra_fecha, PDO::PARAM_STR);
    $stmt->bindParam(':p11', $tipo_fecha, PDO::PARAM_STR);
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }


  protected static function genera_contrato_impresion($contrato, $variable)
  {



    $cia = Auth::user()->empresa;

    //$stmt = static::$pdo->prepare("begin WEB_CONTRATO_IMPRESION (:p1,:p2,:p3,:c); end;");
    $stmt = static::$pdo->prepare("begin WEB_CONTRATO_IMPRESION_DATOS (:p1,:p2,:p3,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $variable, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }


  protected static function list_ubicacion_almacenaje($request)
  {



    $cia = Auth::user()->empresa;

    $contrato = $request->contrato;


    $stmt = static::$pdo->prepare("begin WEB_UBICACION_DESVINCULACION (:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }




  protected static function get_info_laboratorio($contrato)
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


  protected static function get_info_medico($contrato)
  {



    $cia = Auth::user()->empresa;




    $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATO_PAGOMED(:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }

  protected static function get_detalle_desvinculados($contrato)
  {



    $cia = Auth::user()->empresa;




    $stmt = static::$pdo->prepare("begin WEB_MOSTRAR_DETALLE_DESV(:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }

  protected static function get_info_costos($contrato)
  {



    $cia = Auth::user()->empresa;




    $stmt = static::$pdo->prepare("begin WEB_VENCONTRATO_PESTCOSTOS (:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }



  protected static function obtener_documento_notarial($contrato)
  {

    $tipo = '017';

    $no_cia = Auth::user()->empresa;


    $query = DB::select("SELECT DOCUMENTO_ELECTRONICO FROM VEN_CONTRATOS_PDF WHERE  TIPO = ? AND NO_CIA=? AND NUMERO_CONTRATO=?", array($tipo, $no_cia, $contrato));

    $decode = json_decode(json_encode($query), true);

    $documento = (isset($decode[0]["documento_electronico"])) ? $decode[0]["documento_electronico"] : '';

    return $documento;


  }


  protected static function confirmar_desvinculacion($request)
  {




    $cia = Auth::user()->empresa;

    $contrato = $request->des_contrato;

    $motivo = $request->des_motivo;

    $situacion = $request->des_situacion;

    $observacion = $request->des_obs;

    $fecha_desvinculacion = $request->des_fedes;

    $registro_desvinculacion = $request->des_registro;

    $user = Auth::user()->codigo;

    $estado = $request->des_estado;

    $almacen = json_decode($request->list_tabla);

    $tanque = (isset($almacen[0]->TANQUE)) ? $almacen[0]->TANQUE : '';
    $cuadrante = (isset($almacen[0]->CUADRANTE)) ? $almacen[0]->CUADRANTE : '';
    $rack = (isset($almacen[0]->RACK)) ? $almacen[0]->RACK : '';
    $nivel = (isset($almacen[0]->NIVEL)) ? $almacen[0]->NIVEL : '';
    $posicion = (isset($almacen[0]->POSICION)) ? $almacen[0]->POSICION : '';




    $file_carta = '';

    if ($request->file('des_file')) {

      $directorio = 'cartas_notarial/';

      $ext = strtolower($request->file('des_file')->getClientOriginalExtension());

      $fileName = str_random() . '.' . $ext;

      $request->file('des_file')->move($directorio, $fileName);

      $file_carta = $fileName;
    }






    $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATOS_DESVINCULAR(:p1,:p2,:p3,:p4,:p5, :p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:rpta); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $motivo, PDO::PARAM_STR);
    $stmt->bindParam(':p4', $situacion, PDO::PARAM_STR);
    $stmt->bindParam(':p5', $observacion, PDO::PARAM_STR);
    $stmt->bindParam(':p6', $fecha_desvinculacion, PDO::PARAM_STR);
    $stmt->bindParam(':p7', $registro_desvinculacion, PDO::PARAM_STR);
    $stmt->bindParam(':p8', $user, PDO::PARAM_STR);
    $stmt->bindParam(':p9', $estado, PDO::PARAM_STR);
    $stmt->bindParam(':p10', $tanque, PDO::PARAM_STR);
    $stmt->bindParam(':p11', $cuadrante, PDO::PARAM_STR);
    $stmt->bindParam(':p12', $rack, PDO::PARAM_STR);
    $stmt->bindParam(':p13', $nivel, PDO::PARAM_STR);
    $stmt->bindParam(':p14', $posicion, PDO::PARAM_STR);
    $stmt->bindParam(':p15', $file_carta, PDO::PARAM_STR);
    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

    $stmt->execute();

    return $rpta;

  }



  protected static function confirmar_edicion_control_contrato($request)
  {




    $cia = Auth::user()->empresa;

    $contrato = $request->contrato;

    $entrega = (empty($request->entrega)) ? null : Carbon::parse($request->entrega)->format('d/m/Y');

    $firma = (empty($request->firma)) ? null : Carbon::parse($request->firma)->format('d/m/Y');

    $comision = (empty($request->comision)) ? null : Carbon::parse($request->comision)->format('d/m/Y');

    $kit = (empty($request->kit)) ? null : Carbon::parse($request->kit)->format('d/m/Y');



    $usuario = Auth::user()->codigo;


    $stmt = static::$pdo->prepare("begin WEB_CONTRATO_CONTROL_REG(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:rpta); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $entrega, PDO::PARAM_STR);
    $stmt->bindParam(':p4', $firma, PDO::PARAM_STR);
    $stmt->bindParam(':p5', $kit, PDO::PARAM_STR);
    $stmt->bindParam(':p6', $comision, PDO::PARAM_STR);
    $stmt->bindParam(':p7', $usuario, PDO::PARAM_STR);
    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

    $stmt->execute();

    return $rpta;

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



  protected static function save_edit_contrato($request)
  {



    $cia = Auth::user()->empresa;

    $contrato = $request->contrato;

    $estado = $request->estado;

    $medico = $request->medico;

    $clinica = $request->clinica;

    $fecha_parto = Carbon::parse($request->fecha_parto)->format('Y-m-d');


    $debito_automatico = ($request->debito_automatico) ? 'S' : 'N';

    $flag_sin_pago_medico = ($request->vm_sin_pago_medico) ? 'S' : 'N';

    $flag_con_prueba_sereologica = ($request->vm_con_prueba_ser) ? 'S' : 'N';


    $situacion = $request->situacion;

    $comentario = trim($request->comentario);

    $stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_VER_UPDATE(:p1,:p2,:p3,:p4,:p5, :p6,:p7,:p8,:p9,:p10,:p11,:rpta); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $estado, PDO::PARAM_STR);
    $stmt->bindParam(':p4', $medico, PDO::PARAM_STR);
    $stmt->bindParam(':p5', $clinica, PDO::PARAM_STR);
    $stmt->bindParam(':p6', $fecha_parto, PDO::PARAM_STR);
    $stmt->bindParam(':p7', $debito_automatico, PDO::PARAM_STR);
    $stmt->bindParam(':p8', $flag_sin_pago_medico, PDO::PARAM_STR);
    $stmt->bindParam(':p9', $flag_con_prueba_sereologica, PDO::PARAM_STR);
    $stmt->bindParam(':p10', $situacion, PDO::PARAM_STR);
    $stmt->bindParam(':p11', $comentario, PDO::PARAM_STR, 1000);
    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

    $stmt->execute();

    return $rpta;

  }


  protected static function solicitar_desvinculacion_contrato($request)
  {


    $cia = Auth::user()->empresa;

    $contrato = $request->contrato;



    $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATOS_SOLICIDESV(:p1,:p2,:rpta); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);

    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

    $stmt->execute();

    return $rpta;

  }



  protected static function solicitar_desvinculacion_contrato_get_servicio($contrato)
  {



    $cia = Auth::user()->empresa;

    $stmt = static::$pdo->prepare("begin WEB_VENCONTRATOS_SERVICIOLETRA (:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);




    $json = json_decode(json_encode($list), true);


    $servicio = (isset($json[0]['SERVICIO'])) ? $json[0]['SERVICIO'] : '';

    return $servicio;

  }



  protected static function solicitar_desvinculacion_contrato_get_file($plantilla, $contrato)
  {



    $cia = Auth::user()->empresa;

    $stmt = static::$pdo->prepare("begin WEB_VENPLANTILLA_CONTRATOS_GET  (:p1,:p2,:p3,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $plantilla, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $contrato, PDO::PARAM_STR);
    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    $json = json_decode(json_encode($list), true);


    $file = (isset($json[0]['NOMBRE_ARCHIVO'])) ? $json[0]['NOMBRE_ARCHIVO'] : '';


    return $file;

  }


  protected static function solicitar_desvinculacion_contrato_get_data($contrato)
  {



    $cia = Auth::user()->empresa;

    $stmt = static::$pdo->prepare("begin WEB_GET_CONTRATO_DAT_PLANTILLA  (:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);


    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);

    return $list;

  }



  //PLANTILLAS PARA CONTRATOS


  protected static function list_tabla_plantillas($request)
  {



    $cia = Auth::user()->empresa;



    $stmt = static::$pdo->prepare("begin  WEB_LIST_VENPLANTILLA_CONT (:p1,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);


    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }






  protected static function get_data_plantilla($request)
  {



    $cia = Auth::user()->empresa;

    $plantilla = $request->idplantilla;


    $stmt = static::$pdo->prepare("begin  WEB_LIST_VENPLANTILLA_CONT_OBT (:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $plantilla, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }

  protected static function salvar_nueva_plantilla($request)
  {




    $flag = ($request->flag == 'EDITAR') ? 1 : 0;

    $id_plantilla = trim($request->id_plantilla);

    $cia = Auth::user()->empresa;

    $nombre = $request->vm_nombre_plantilla;

    //$file = $request->file;

    $tipo = $request->vm_tipo_sangre;

    $aseguradora = (empty($request->vm_aseguradora)) ? 0 : trim($request->vm_aseguradora);

    $plan = (empty($request->vm_plan)) ? 0 : $request->vm_plan;

    $mama_soltera = $request->mama_soltera;

    $tipo_plantilla = $request->vm_tipo_plantilla;


    $file = '';


    if ($request->file('file')) {



      $extension = strtolower($request->file('file')->getClientOriginalExtension());


      if ($extension != 'docx') {

        return 3;

      }


      $directorio = ($cia == '001') ? 'formatos_nuevos/ICTC/' : 'formatos_nuevos/LAZO_DE_VIDA/';

      $fileName = $request->file('file')->getClientOriginalName();


      $request->file('file')->move($directorio, $fileName);

      $file = $fileName;


    } else {



      $query = DB::select("SELECT NOMBRE_ARCHIVO FROM ven_plantilla_contratos WHERE NO_CIA = ? AND ID_PLANTILLA = ? ", array($cia, $id_plantilla));


      $decode = json_decode(json_encode($query), true);

      $file = $decode[0]['nombre_archivo'];

      if (empty($file)) {


        return 4;


      }


    }


    $stmt = static::$pdo->prepare("begin WEB_VEN_PLANTILLA_CONTRATOS_IU(:p1,:p2,:p3,:p4,:p5, :p6,:p7,:p8,:p9,:p10,:rpta); end;");

    $stmt->bindParam(':p1', $flag, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $id_plantilla, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p4', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':p5', $file, PDO::PARAM_STR);
    $stmt->bindParam(':p6', $tipo, PDO::PARAM_STR);
    $stmt->bindParam(':p7', $aseguradora, PDO::PARAM_STR);
    $stmt->bindParam(':p8', $plan, PDO::PARAM_STR);
    $stmt->bindParam(':p9', $mama_soltera, PDO::PARAM_STR);
    $stmt->bindParam(':p10', $tipo_plantilla, PDO::PARAM_STR);


    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

    $stmt->execute();

    return $rpta;

  }




  protected static function get_parametros_plantillas_colectas($request)
  {



    $contrato = self::get_parametros_plantillas_tipo(5, 'Contrato_Colecta');
    $evaluacion = self::get_parametros_plantillas_tipo(5, 'Evaluacion');
    $procesamiento = self::get_parametros_plantillas_tipo(5, 'Procesamiento');
    $criopreservacion = self::get_parametros_plantillas_tipo(5, 'Criopreservacion');

    return array($contrato, $evaluacion, $procesamiento, $criopreservacion);


  }



  protected static function get_parametros_plantillas($request)
  {



    $contrato = self::get_parametros_plantillas_tipo(3, 'Contrato');
    $cliente = self::get_parametros_plantillas_tipo(3, 'Cliente');
    $empresa = self::get_parametros_plantillas_tipo(3, 'Empresa');

    return array($contrato, $cliente, $empresa);


  }



  protected static function get_parametros_plantillas_tipo($cad, $tipo)
  {


    $p1 = $cad;

    $stmt = static::$pdo->prepare("begin  WEB_LISTVALORCAD_TABLAS (:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $tipo, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }



  //PLANTILLAS COLECTAS





  protected static function tipos_plantilla_colectas()
  {


    $p1 = 2;

    $p2 = 'TIPO_SERVICIO';

    $stmt = static::$pdo->prepare("begin  WEB_LISTVALORCAD_TABLAS (:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);




    $result = array();

    foreach ($list as $value) {

      $result[] = array("id" => $value["VALORCAD"], "text" => $value["VALORCAD"]);
    }

    return $result;

  }


  protected static function list_tabla_plantillas_colectas($request)
  {


    $p1 = Auth::user()->empresa;

    $stmt = static::$pdo->prepare("begin  WEB_LAB_PLANTILLA_COLECTAS (:p1,:c); end;");

    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);


    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }



  protected static function get_data_plantilla_colectas($request)
  {


    $p1 = Auth::user()->empresa;

    $p2 = $request->idplantilla;

    $stmt = static::$pdo->prepare("begin  WEB_LAB_PLANTILLA_COLECTAS_DAT (:p1,:p2,:c); end;");

    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }



  protected static function salvar_nueva_plantilla_colectas($request)
  {






    $id_plantilla = trim($request->id_plantilla);

    $cia = Auth::user()->empresa;

    $nombre = $request->vm_nombre_plantilla;

    $formato = $request->vm_formato_plantilla;

    $tipo = $request->vm_tipo_plantilla;




    $remitente = $request->correo_remitente;

    $copiar_correo = $request->copiar_correo;

    $asunto_correo = $request->asunto_correo;


    $cuerpo_correo = $request->vm_cuerpo_correo;

    $mama_soltera = ($request->mama_soltera) ? 'S' : 'N';



    $file = '';


    if ($request->file('file')) {

      $filesize = filesize($request->file('file'));

      $filesize = round($filesize / 1024 / 1024, 1); //MB

      // if($filesize>12){

      //   return 5;
      // }


      $extension = strtolower($request->file('file')->getClientOriginalExtension());


      if ($extension != 'docx') {

        return 3;

      }


      $directorio = ($cia == '001') ? 'formatos_colectas/ICTC/' : 'formatos_colectas/LAZO_DE_VIDA/';

      $fileName = $request->file('file')->getClientOriginalName();


      $request->file('file')->move($directorio, $fileName);

      $file = $fileName;


    } else {

      //ARCHIVO OBLIGATORIO PARA FORMATO PLANTILLAS


      if ($formato != 'S') {


        $query = DB::select("SELECT NOMBRE_ARCHIVO FROM LAB_PLANTILLA_COLECTAS WHERE NO_CIA = ? AND ID_PLANTILLA = ? ", array($cia, $id_plantilla));


        $decode = json_decode(json_encode($query), true);

        $file = $decode[0]['nombre_archivo'];

        if (empty($file)) {


          return 4;


        }

      }




    }


    if ($id_plantilla == "0") {


      $stmt = static::$pdo->prepare("begin WEB_LAB_PLANTILLA_COLECTAS_INS(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9:,p10,:rpta); end;");

      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $nombre, PDO::PARAM_STR);
      $stmt->bindParam(':p3', $file, PDO::PARAM_STR);
      $stmt->bindParam(':p4', $tipo, PDO::PARAM_STR);
      $stmt->bindParam(':p5', $formato, PDO::PARAM_STR);
      $stmt->bindParam(':p6', $remitente, PDO::PARAM_STR);
      $stmt->bindParam(':p7', $asunto_correo, PDO::PARAM_STR);
      $stmt->bindParam(':p8', $cuerpo_correo, PDO::PARAM_STR);
      $stmt->bindParam(':p9', $copiar_correo, PDO::PARAM_STR);
      $stmt->bindParam(':p10', $mama_soltera, PDO::PARAM_STR);


      $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

      $stmt->execute();

      return $rpta;


    } else {


      $stmt = static::$pdo->prepare("begin WEB_LAB_PLANTILLA_COLECTAS_UPD(:p0,:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:rpta); end;");

      $stmt->bindParam(':p0', $id_plantilla, PDO::PARAM_STR);
      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $nombre, PDO::PARAM_STR);
      $stmt->bindParam(':p3', $file, PDO::PARAM_STR);
      $stmt->bindParam(':p4', $tipo, PDO::PARAM_STR);
      $stmt->bindParam(':p5', $formato, PDO::PARAM_STR);
      $stmt->bindParam(':p6', $remitente, PDO::PARAM_STR);
      $stmt->bindParam(':p7', $asunto_correo, PDO::PARAM_STR);
      $stmt->bindParam(':p8', $cuerpo_correo, PDO::PARAM_STR);
      $stmt->bindParam(':p9', $copiar_correo, PDO::PARAM_STR);
      $stmt->bindParam(':p10', $mama_soltera, PDO::PARAM_STR);


      $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

      $stmt->execute();

      return $rpta;

    }


  }


  protected static function confirmar_eliminacion_contrato($request)
  {



    $cia = Auth::user()->empresa;

    $contrato = $request->contrato;

    $motivo = $request->motivo;




    $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_ANULAR(:p1,:p2,:p3,:rpta); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $motivo, PDO::PARAM_STR);

    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

    $stmt->execute();

    return $rpta;

  }


  protected static function list_contratos_indicadores()
  {



    $cia = Auth::user()->empresa;

    //DEL AÑO


    $inicio = Carbon::now()->startOfYear()->format('Y-m-d');
    $fin = Carbon::now()->endOfYear()->format('Y-m-d');

    //DEL MES



    $inicio_mes = Carbon::now()->startOfMonth()->format('Y-m-d');
    $fin_mes = Carbon::now()->endOfMonth()->format('Y-m-d');



    $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_LIST_INDICADORES (:p1,:p2,:p3,:p4,:p5,:c); end;");

    $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $inicio, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $fin, PDO::PARAM_STR);
    $stmt->bindParam(':p4', $inicio_mes, PDO::PARAM_STR);
    $stmt->bindParam(':p5', $fin_mes, PDO::PARAM_STR);

    $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);

    $stmt->execute();

    oci_execute($cursor, OCI_DEFAULT);
    oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC);
    oci_free_cursor($cursor);



    return $list;

  }


}