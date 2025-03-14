<?php


namespace App\Http\Controllers\Contrato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\Prospecto;
use App\TipoCambio;
use Auth;
use Carbon\Carbon;
use App\Http\Controllers\Mantenimiento\CorreoController;

class ProspectoController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function prospectos_pendientes()
  {

    $middleRpta = $this->valida_url_permisos(17);

    if ($middleRpta["status"] != "ok") {

      return $this->redireccion_404();
    }

    $tipo_prospecto = 'PROSPND';

    $btn_prospecto_nuevo = $this->botones_usuario('prospecto_nuevo');

    $empresa_user = Auth::user()->empresa;



    return View('prospecto.index', compact('tipo_prospecto', 'btn_prospecto_nuevo', 'empresa_user'));


  }

  public function prospectos_historicos()
  {

    $middleRpta = $this->valida_url_permisos(18);

    if ($middleRpta["status"] != "ok") {

      return $this->redireccion_404();
    }

    $tipo_prospecto = 'HISPROS';

    $btn_prospecto_nuevo = $this->botones_usuario('prospecto_nuevo');

    $empresa_user = Auth::user()->empresa;

    return View('prospecto.index', compact('tipo_prospecto', 'btn_prospecto_nuevo', 'empresa_user'));


  }



  public function nuevo_prospecto_bitacora($id)
  {

    $data_prospecto = array();

    $data_servicios = array();


    //$data_padres = Prospecto::get_data_info($id);

    $data_padres = Prospecto::get_data_info_bitacora($id);


    $estado_civil = Maestro::list_estado_civil();

    $paises = Maestro::list_paises();

    $captacion = Maestro::list_captacion();

    $departamentos = Maestro::list_departamento();

    $tipo_documento = Maestro::list_tipo_documento();

    $estados = Maestro::list_estado_prospecto();

    $aseguradora = Maestro::list_aseguradora();

    $empresa_user = Auth::user()->empresa;


    $btn_genera_contrato = $this->botones_usuario('genera_contrato');

    $btn_servicios = $this->botones_usuario('agrega_servicios');

    $historico_pendiente = 'PENDIENTE';

    $medios_contacto = Maestro::get_medios_comunicacion();

    $empresa_prop = Auth::user()->empresa;

    $hora_actual = date('H:i:s');

    return View('prospecto.register', compact('data_servicios', 'data_prospecto', 'estado_civil', 'paises', 'captacion', 'departamentos', 'tipo_documento', 'estados', 'empresa_user', 'aseguradora', 'btn_genera_contrato', 'btn_servicios', 'historico_pendiente', 'data_padres', 'medios_contacto', 'empresa_prop', 'hora_actual'));


  }


  public function prospectos_registro($id)
  {


    $data_prospecto = array();

    $data_servicios = array();

    $data_padres = array();


    if (!empty($id)) {

      $data_prospecto = Prospecto::get_data_info($id);

      $data_servicios = Prospecto::get_data_info_servicios($id);
    }



    $estado_civil = Maestro::list_estado_civil();

    $paises = Maestro::list_paises();

    $captacion = Maestro::list_captacion();

    $departamentos = Maestro::list_departamento();

    $tipo_documento = Maestro::list_tipo_documento();

    $estados = Maestro::list_estado_prospecto();

    $aseguradora = Maestro::list_aseguradora();

    $empresa_user = Auth::user()->empresa;


    $btn_genera_contrato = $this->botones_usuario('genera_contrato');

    $btn_servicios = $this->botones_usuario('agrega_servicios');

    $historico_pendiente = $this->evalua_prospecto_historico_pendiente($id);

    $medios_contacto = Maestro::get_medios_comunicacion();

    $empresa_prop = Auth::user()->empresa;


    $hora_actual = date('H:i:s');


    return View('prospecto.register', compact('data_servicios', 'data_prospecto', 'estado_civil', 'paises', 'captacion', 'departamentos', 'tipo_documento', 'estados', 'empresa_user', 'aseguradora', 'btn_genera_contrato', 'btn_servicios', 'historico_pendiente', 'data_padres', 'medios_contacto', 'empresa_prop', 'hora_actual'));


  }


  protected function enviar_correo_ficha_datos_prospecto(Request $request)
  {


    $cia = Auth::user()->empresa;

    $config = ($cia == '001') ? $this->mailCriocord() : $this->mailLazoVida();


    $destinatarios = $this->set_contactos_acuerdo_prospecto($request->prospecto);



    if ($destinatarios["status"] == "error") {

      return $destinatarios;
    }



    $formato = $this->imprime_ficha_prospecto($request->prospecto, $request->flag);



    $mensaje = 'Estimada Familia ';


    $parametros = array(

      "cia" => $cia,
      "config" => $config,
      "destinatarios" => $destinatarios["data"],
      "formato" => $formato,
      "mensaje_sp" => $mensaje
    );


    $correo = new CorreoController;

    return $correo->enviar_correo_ficha_datos_prospecto($parametros);

  }


  protected function set_contactos_acuerdo_prospecto($prospecto)
  {

    $cia = Auth::user()->empresa;

    $query = DB::select("SELECT MAMA_SOLTERA,NOMBRE,EMAIL,EMAIL_2,EMAIL_3,NOMBRE2,EMAIL2 ,EMAIL2_2,EMAIL2_3 FROM VEN_PROSPECTOS WHERE NO_CIA=? AND NUMERO_PROSPECTO=?", array($cia, $prospecto));



    $data = array();


    foreach ($query as $value) {

      if (!empty($value->email)) {

        $data[$value->email] = $value->nombre;

      } else if (!empty($value->email_2)) {

        $data[$value->email_2] = $value->nombre;

      } else if (!empty($value->email_3)) {

        $data[$value->value] = $value->nombre;
      }

      if ($value->mama_soltera != "S") {


        if (!empty($value->email2)) {

          $data[$value->email2] = $value->nombre2;

        } else if (!empty($value->email2_2)) {

          $data[$value->email2_2] = $value->nombre2;

        } else if (!empty($value->email2_3)) {

          $data[$value->email2_3] = $value->nombre2;
        }


      }



    }

    if (count($data) == 0) {

      return $this->setRpta("error", "El prospecto no tiene correos asociados");
    }

    return $this->setRpta("ok", "Valido correos prospecto", $data);



  }

  protected function fechaCastellano($fecha)
  {


    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
    $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    return $nombredia . " " . $numeroDia . " de " . $nombreMes . " del " . $anio;
  }





  protected function imprime_ficha_prospecto($id, $flag)
  {


    $empresa_user = Auth::user()->empresa;


    $data_prospecto = Prospecto::get_data_info($id);







    $data_servicios = Prospecto::get_data_info_servicios($id);




    $tipo_ficha = Prospecto::get_data_tipo_ficha($id);

    $now = Carbon::now()->format('d-m-Y');


    $fecha_texto = $this->fechaCastellano($now);


    $pdf = \App::make('dompdf.wrapper');
    $pdf->setPaper('A4');



    if ($tipo_ficha == 'D') {

      //DENTAL
      $pdf->loadView('reporte.prospecto.ficha_datos_dental', compact('empresa_user', 'data_prospecto', 'data_servicios', 'tipo_ficha', 'fecha_texto'));

    } else {

      //SANGRE TEJIDO


      $pdf->loadView('reporte.prospecto.ficha_datos', compact('empresa_user', 'data_prospecto', 'data_servicios', 'tipo_ficha', 'fecha_texto'));


    }


    if ($flag == 1) {

      //return $pdf->stream();

      $fileprospecto_nombre = 'FICHA_DATOS_N_' . $id . '.pdf';


      return $pdf->download($fileprospecto_nombre);

    } else {


      $output = $pdf->output();

      $random = $this->generaRandomString(10);

      $filepath = public_path() . '/ficha_datos/' . $random . '.pdf';


      file_put_contents($filepath, $output);

      return $filepath;

    }




  }


  protected function evalua_prospecto_historico_pendiente($id)
  {


    $cia = Auth::user()->empresa;


    $query = DB::select("SELECT ESTADO FROM VEN_PROSPECTOS WHERE NO_CIA=? AND NUMERO_PROSPECTO=?", array($cia, $id));


    $decode = json_decode(json_encode($query), true);

    $tipo = 'PENDIENTE';


    if (isset($decode[0]['estado'])) {

      if ($decode[0]['estado'] != 'PRO') {


        $tipo = 'HISTORICO';
      }

    }

    if ($id == '0') {

      $tipo = 'PENDIENTE';

    }

    return $tipo;




  }






  protected function insert_update_bitacora_prospecto(Request $request)
  {





    $rpta = Prospecto::insert_update_bitacora_prospecto($request);


    if ($rpta == 1) {

      return $this->setRpta("ok", "Se procesó correctamente");

    }

    return $this->setRpta("ok", "Ocurrió un error");

  }





  protected function inserta_directo_servicio_prospecto(Request $request)
  {

    $list = $request->data;

    //actualiza datos de cabecera





    $rpta = Prospecto::save_prospecto_detalle($list[0]);


    if ($rpta == 1) {

      return $this->setRpta("ok", "Se insertó correctamente el servicio");

    }

    return $this->setRpta("ok", "Ocurrió un error al insertar");

  }






  protected function elimina_detalle_servicio_prospecto(Request $request)
  {

    if (empty($request->prospecto)) {

      return $this->setRpta("error", "No se ha generado el número de prospecto");
    }


    $rpta = Prospecto::elimina_detalle_servicio_prospecto($request);


    if ($rpta == 1) {

      return $this->setRpta("ok", "Se eliminó correctamente el servicio");

    }

    return $this->setRpta("ok", "Ocurrió un error al eliminar");

  }



  protected function list_prospecto(Request $request)
  {

    $list = Prospecto::list_prospecto($request);



    return response()->json($list);


  }



  protected function set_listado_bitacora_prospectos(Request $request)
  {

    $list = Prospecto::set_listado_bitacora_prospectos($request);


    return response()->json($list);


  }

  protected function valida_prospecto($request, $tipo)
  {

    if ($tipo == 'p') {
      $rules = [

        //prospecto
        //'rprospecto_prospecto'=> 'required',

        'rprospecto_fechaAprox'=> 'required',
        'rprospecto_tipoParto'=> 'required',
        'rprospecto_contratado'=> 'required',
        'rprospecto_captacion'=> 'required',
        'rprospecto_tipo'=> 'required',
        'rprospecto_nroFicha'=> 'required',
        'rprospecto_atendido'=> 'required',
        'rprospecto_derivado'=> 'required',



        //madre

        'rprospecto_docmadre'=> 'required',
        'rprospecto_identmadre'=> 'required',
        'rprospecto_patmadre'=> 'required',
        'rprospecto_matmadre'=> 'required',
        'rprospecto_nommadre'=> 'required',
        'rprospecto_dirmadre'=> 'required',

      ];

      $messages = [

        //prospecto

        'rprospecto_prospecto.required' => 'Ingrese Número.',
        //
        'rprospecto_fechaAprox.required' => 'Ingrese F.Parto.',
        'rprospecto_tipoParto.required' => 'Seleccione El T.de Parto.',
        'rprospecto_contratado.required' => 'Selccione Contratado',
        'rprospecto_captacion.required' => 'Seleccione Captación.',
        'rprospecto_tipo.required' => 'Seleccione Tipo de Captacion.',
        'rprospecto_nroFicha.required' => 'Seleccione N° de ficha.',
        'rprospecto_atendido.required' => 'Ingrese un vendedor.',
        'rprospecto_derivado.required' => 'Ingrese un captador.',

        //mama

        'rprospecto_identmadre.required' => 'Ingrese una Identificación.',
        'rprospecto_docmadre.required' => 'Seleccione un Tipo de documento.',
        'rprospecto_patmadre.required' => 'Ingrese un A.Paterno.',
        'rprospecto_matmadre.required' => 'Ingrese un A.Materno.',
        'rprospecto_nommadre.required' => 'Ingrese un Nombre.',
        'rprospecto_dirmadre.required' => 'Ingrese una Dirección',

      ];


    }
    //valida solo genera contrato


    if ($tipo == 'c') {

      //valida solo en contrato



      $rules = [





        //madre

        'rprospecto_docmadre' => 'required',
        'rprospecto_identmadre' => 'required',
        'rprospecto_patmadre' => 'required',
        'rprospecto_matmadre' => 'required',
        'rprospecto_nommadre' => 'required',
        'rprospecto_dirmadre' => 'required',





      ];

      $messages = [



        //mama

        'rprospecto_identmadre.required' => 'Ingrese una Identificación.',
        'rprospecto_docmadre.required' => 'Seleccione un Tipo de documento.',
        'rprospecto_patmadre.required' => 'Ingrese un A.Paterno.',
        'rprospecto_matmadre.required' => 'Ingrese un A.Materno.',
        'rprospecto_nommadre.required' => 'Ingrese un Nombre.',
        'rprospecto_dirmadre.required' => 'Ingrese una Dirección',




      ];
      $mama_soltera = ($request->rprospecto_msoltera == null) ? 1 : 0;

      if ($mama_soltera == 1) {

        $rules = array_merge($rules, [
          'rprospecto_identpadre' => 'required',
          'rprospecto_docpadre' => 'required',
          'rprospecto_patpadre' => 'required',
          'rprospecto_matpadre' => 'required',
          'rprospecto_nompadre' => 'required',
          //'rprospecto_dirpadre'=> 'required' 

        ]);

        $messages = array_merge(
          $messages,
          [
            'rprospecto_identpadre.required' => 'Ingrese una Identificación.',
            'rprospecto_docpadre.required' => 'Seleccione un Tipo de documento.',
            'rprospecto_patpadre.required' => 'Ingrese un A.Paterno.',
            'rprospecto_matpadre.required' => 'Ingrese un A.Materno.',
            'rprospecto_nompadre.required' => 'Ingrese un Nombre.',
            //'rprospecto_dirpadre.required' => 'Ingrese una Dirección'

          ]
        );

      }

      $rules = array_merge(
        $rules,

        [
          'rprospecto_prospecto' => 'required',

          //'rprospecto_fechaAprox'=> 'required',
          'rprospecto_tipoParto' => 'required',
          'rprospecto_contratado' => 'required',
          'rprospecto_captacion' => 'required',
          'rprospecto_tipo' => 'required',
          'rprospecto_nroFicha' => 'required',
          'rprospecto_atendido' => 'required',
          'rprospecto_derivado' => 'required'

        ]
      );

      $messages = array_merge(
        $messages,
        [
          'rprospecto_prospecto.required' => 'Genere Número de prospecto.',

          //'rprospecto_fechaAprox.required' => 'Ingrese F.Parto.',
          'rprospecto_tipoParto.required' => 'Seleccione El T.de Parto.',
          'rprospecto_contratado.required' => 'Seleccione Ciudad',
          'rprospecto_captacion.required' => 'Seleccione Captación.',
          'rprospecto_tipo.required' => 'Seleccione Tipo de Captacion.',
          'rprospecto_nroFicha.required' => 'Seleccione N° de ficha.',
          'rprospecto_atendido.required' => 'Ingrese un vendedor.',
          'rprospecto_derivado.required' => 'Ingrese un captador.'





        ]
      );

    }

    $validate = \Validator::make($request->all(), $rules, $messages);

    if ($validate->fails()) {

      return $this->setRpta("warning", $this->msgValidator($validate), $validate->messages());

    } else {


      $dni_mama = trim($request->rprospecto_identmadre);

      $dni_papa = trim($request->rprospecto_identpadre);

      $dni_propietario = trim($request->rprospecto_identpropietario);

      $dnis = array();

      $valida = '';

      if (!empty($dni_mama)) {

        $dnis[] = $dni_mama;

        $valida .= 'Mamá ,';
      }

      if (!empty($dni_papa)) {

        $dnis[] = $dni_papa;

        $valida .= 'Papá ,';

      }

      if (!empty($dni_propietario)) {

        $dnis[] = $dni_propietario;

        $valida .= 'Titular ,';

      }


      $cantidad_dnis = count($dnis);

      $dnis_unicos = array_unique($dnis);


      $valida = rtrim($valida, ',');


      if (count($dnis_unicos) == $cantidad_dnis) {


        return $this->setRpta("ok", "valido inputs prospectos");


      } else {


        return $this->setRpta("error", "Los números de documentos de $valida no pueden repetirse");

      }
    }





  }




  protected function confirmar_eliminacion_prospecto(Request $request)
  {


    $rpta = Prospecto::confirmar_eliminacion_prospecto($request);

    if ($rpta == 1) {

      return $this->setRpta("ok", "se eliminó el prospecto :" . $request->proespecto . " de manera satisfactoria");

    }

    return $this->setRpta("error", "Ocurrió un error al eliminar el registro");

  }


  protected function save_prospecto_detalle($request, $num_prospecto)
  {


    $data = $this->set_array_servicios($request, $num_prospecto);


    $rpta = 1;

    foreach ($data as $list) {

      Prospecto::save_prospecto_detalle($list);

    }


    return $rpta;


  }

  protected function valida_coincidencia_apellidos_numeros(Request $request)
  {








    if (empty($request->rprospecto_prospecto)) {

      //SOLO PARA NUEVOS PROSPECTOS

      if (empty($request->rprospecto_patmadre) && empty($request->rprospecto_matmadre) && empty($request->rprospecto_nommadre) && empty($request->rprospecto_patpadre) && empty($request->rprospecto_matpadre) && empty($request->rprospecto_nompadre)) {

        return $this->setRpta("ok", "valido correctamente vacios");

      } else {

        $list = Prospecto::valida_duplicado_prospecto($request);

        if (count($list) == 0) {

          return $this->setRpta("ok", "valido correctamente store");
        }

        return $this->setRpta("error", "Ya Existen prospecto(s) registrado(s) con información similar :", $list);

      }




    } else {

      return $this->setRpta("ok", "valido correctamente solo para nuevos");
    }

  }



  protected function save_prospecto(Request $request)
  {

    DB::beginTransaction();

    try {

      $valida_prospecto = $this->valida_prospecto($request, 'p');

      if ($valida_prospecto["status"] == "ok") {


        $rpta = Prospecto::save_prospecto($request);

        if ($rpta) {


          $detail = $this->save_prospecto_detalle($request, $rpta);

          if ($detail == 1) {


            DB::commit();

            return $this->setRpta("ok","Se procesó correctamente : ".$rpta,array($rpta));
            


          }

          DB::rollback();

          return $this->setRpta("error", "Ocurrió un error al guardar el detalle");


        }

        DB::rollback();

        return $this->setRpta("error", "Ocurrió un error al guardar");

      }

      return $valida_prospecto;


    } catch (\Exception $e) {

      DB::rollback();

      return $this->setRpta("error", $e->getMessage());
    }



  }



  protected function guarda_y_genera_contrato($request)
  {


    //GUARDA PROSPECTO DENTRO DE GENRAR CONTRATO


    $rpta = Prospecto::save_prospecto($request);

    if ($rpta) {


      $detail = $this->save_prospecto_detalle($request, $rpta);

      if ($detail == 1) {



        return $this->setRpta("ok", "Se procesó correctamente dentro de generar contrato: " . $rpta);


      }



      return $this->setRpta("error", "Ocurrió un error al guardar el detalle dentro de generar contrato");


    }



    return $this->setRpta("error", "Ocurrió un error al guardar el prospecto dentro de generar contrato");


  }


  protected function genera_contrato_prospecto(Request $request)
  {

    DB::beginTransaction();

    try {

      $valida_prospecto = $this->valida_prospecto($request, 'c');

      if ($valida_prospecto["status"] == "ok") {

        //validamos tipo de cambio

        $tccambio_dia = TipoCambio::valida_nuevo_tipo_cambio_hoy();


        if ($tccambio_dia == 0) {


          return $this->setRpta("error", "No se ha ingresado el tipo del cambio del dia");

        }






        //verifica si guarda datos de prospecto dentro de genarar contrato


        $verifica_prospecto = $this->guarda_y_genera_contrato($request);

        if ($verifica_prospecto["status"] == "error") {

          DB::rollback();

          return $verifica_prospecto;

        }

        $num_prospecto = $request->rprospecto_prospecto;

        $data = $this->set_array_servicios($request, $num_prospecto);


        //$rpta=true;




        foreach ($data as $list) {


          if (empty($list['nro_contrato']) && $list['edita'] == true) {



            $rpta = Prospecto::genera_contrato_prospecto2($list);

            if ($rpta[0] == false) {

              DB::rollback();

              return $this->setRpta("error", "Ocurrió un error: " . $rpta[1]);


            }

          }


        }

        DB::commit();

        return $this->setRpta("ok", "Se generó contrato satisfactoriamente");


        // if($rpta){

        //     DB::commit();

        //     return $this->setRpta("ok","Se generó contrato satisfactoriamente");

        // }

        //DB::rollback();

        //return $this->setRpta("error","Ocurrió un error");

      }

      return $valida_prospecto;


    } catch (\Exception $e) {

      DB::rollback();

      return $this->setRpta("error", $e->getMessage());
    }



  }


  protected function save_prospecto_cabecera_servicios(Request $request)
  {

    $rpta = Prospecto::save_prospecto($request);

    if ($rpta) {

      return $this->setRpta('ok', 'Se insertó de manera correcta ');

    } else {

      return $this->setRpta('ok', 'ocurrió un error al actualizar la cabecera');
    }


  }

  protected static function set_array_servicios($request, $num_prospecto)
  {


    $cia = Auth::user()->empresa;
    //$rprospecto_prospecto = $request->rprospecto_prospecto;
    $rprospecto_prospecto = $num_prospecto;
    $rprospecto_registro = Carbon::parse($request->rprospecto_registro)->format('d/m/Y');


    $rprospecto_estado = $request->rprospecto_estado;
    $rprospecto_contratado = $request->rprospecto_contratado;
    $rprospecto_contrato = ($request->rprospecto_contrato == null) ? 'NO' : 'SI';

    $rprospecto_docmadre = $request->rprospecto_docmadre;
    $rprospecto_identmadre = $request->rprospecto_identmadre;
    $rprospecto_dirmadre = $request->rprospecto_dirmadre;
    $rprospecto_ubgmadre = $request->rprospecto_ubgmadre;
    $edad_mama = '';

    $rprospecto_docpadre = $request->rprospecto_docpadre;
    $rprospecto_identpadre = $request->rprospecto_identpadre;
    $rprospecto_dirpadre = $request->rprospecto_dirpadre;
    $rprospecto_ubgpadre = $request->rprospecto_ubgpadre;
    $edad_papa = '';


    $rprospecto_medico = $request->rprospecto_medico;
    $rprospecto_clinica = $request->rprospecto_clinica;
    //servicios
    //fin
    $cod_user = Auth::user()->codigo;
    $rprospecto_atendido = $request->rprospecto_atendido;
    $rprospecto_pais_madre = $request->rprospecto_pais_madre;
    $rprospecto_pais_padre = $request->rprospecto_pais_padre;
    $rprospecto_msoltera = ($request->rprospecto_msoltera == null) ? 'N' : 'S';


    $XSINPRUEBASEROLOGICA = '';

    $tc_hoy = TipoCambio::get_tipo_cambio();

    $XTIPO_CAMBIO = (empty($tc_hoy[0]['FACTOR_CAMBIO'])) ? 3 : $tc_hoy[0]['FACTOR_CAMBIO'];


    $XCOBRADOR = '0';
    $XSANGRE = '';
    $XTEJIDOS = '';




    $servicios = json_decode($request->servicios);



    $data = array();

    foreach ($servicios as $list) {

      $moneda = $list->moneda_contrato;
      $lista_contrato = $list->lp;
      $servicio_contrato = $list->ser;
      $monto_servicio = $list->monto_contrato;
      $seguro = (isset($list->aseg)) ? $list->aseg : '';
      $plan = (isset($list->plan)) ? $list->plan : '';
      $cobertura = $list->cobertura_contrato;
      $car_seguro = $list->cargo_seguro;
      $car_cliente = $list->cargo_cliente;
      $moneda_an = $list->moneda_an;
      $lista_an = $list->lp_an;
      $servicio_an = $list->ser_an;
      $monto_an = $list->monto_anualidad;


      $numero_contrato_i = $list->numero_contrato;

      $edita = $list->flag_edita;

      $data[] = array(
        "cia" => $cia,
        "nro_contrato" => $numero_contrato_i,
        "fecha_contrato" => $rprospecto_registro,
        "estado" => $rprospecto_estado,
        "nro_prospecto" => $rprospecto_prospecto,
        "contrato_lp" => $rprospecto_contratado,
        "emergencia" => $rprospecto_contrato,
        "cod_doc1" => $rprospecto_docmadre,
        "num_doc1" => $rprospecto_identmadre,
        "direccion1" => $rprospecto_dirmadre,
        "ubigeo1" => $rprospecto_ubgmadre,
        "edad1" => $edad_mama,
        "cod_doc2" => $rprospecto_docpadre,
        "num_doc2" => $rprospecto_identpadre,
        "direccion2" => $rprospecto_dirpadre,
        "ubigeo2" => $rprospecto_ubgpadre,
        "edad2" => $edad_papa,
        "medico" => $rprospecto_medico,
        "clinica" => $rprospecto_clinica,
        "moneda" => $moneda,
        "lista_contrato" => $lista_contrato,
        "monto_servicio" => trim(str_replace(',', '', $monto_servicio)),
        "moneda_an" => $moneda_an,
        "monto_an" => trim(str_replace(',', '', $monto_an)),
        "usuario" => $cod_user,
        "vendedor" => $rprospecto_atendido,
        "pais1" => $rprospecto_pais_madre,
        "pais2" => $rprospecto_pais_padre,
        "mama_soltera" => $rprospecto_msoltera,
        "servicio_contrato" => $servicio_contrato,
        "lista_an" => $lista_an,
        "servicio_an" => $servicio_an,
        "seguro" => $seguro,
        "plan" => $plan,
        "cobertura" => trim(str_replace('%', '', $cobertura)),
        "car_seguro" => trim(str_replace(',', '', $car_seguro)),
        "car_cliente" => trim(str_replace(',', '', $car_cliente)),
        "sin_prueba_serologica" => $XSINPRUEBASEROLOGICA,
        "tipo_cambio" => $XTIPO_CAMBIO,
        "cobrador" => $XCOBRADOR,
        "id_cia_seguro" => $seguro,
        'porcentaje' => trim(str_replace('%', '', $cobertura)),
        "sangre" => $XSANGRE,
        "tejidos" => $XTEJIDOS,
        "edita" => $edita

      );

    }

    return $data;
  }



}