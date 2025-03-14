<?php


namespace App\Http\Controllers\Contrato;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Laboratorio;
use App\Http\Controllers\Mantenimiento\CorreoController;
use Carbon\Carbon;

class InformeLaboratorioController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }


  public function informe_laboratorio()
  {



    $middleRpta = $this->valida_url_permisos(45);

    if ($middleRpta["status"] != "ok") {

      return $this->redireccion_404();
    }

    $empresa_user = Auth::user()->empresa;

    return View('contrato.informe_laboratorio', compact('empresa_user'));
  }

  protected function list_informes_laboratorio(Request $request)
  {


    $list = Laboratorio::list_informes_laboratorio($request);

    $data = array();

    foreach ($list as $key => $value) {

      $data[] = array(


        "NUMERO_CONTRATO" => $value["NUMERO_CONTRATO"],
        "FAMILIA" => $value["FAMILIA"],
        "CLIENTE" => $value["CLIENTE"],
        "FECHA_COLECTA" => $value["FECHA_COLECTA"],
        "FECHA_AUTORIZA_LAB" => $value["FECHA_AUTORIZA_LAB"],
        "USUARIO_LAB" => $value["USUARIO_LAB"],
        "SELECCIONA" => false,
        "NUMERO_COLECTA" => $value["NUMERO_COLECTA"],
        "SERVICIO" => $value["SERVICIO"],
        "DESTINO" => $value["DESTINO"],
        "COPIA" => $value["COPIA"],
        "FICHERO" => null,
        "CANTIDAD_FICHEROS" => 0,
        "ENVIO_FECHA_CORREO" => $value["ENVIO_FECHA_CORREO"],
        "TOTAL" => $value["TOTAL"],
        "SALDO" => $value["SALDO"],
        "USUARIO_ENVIO" => $value["USUARIO_ENVIO"],
        "FLAG_ENVIO_SEROLOGIA" => $value["FLAG_ENVIO_SEROLOGIA"],
        "CANTIDAD_ENVIO" => $value["CANTIDAD_ENVIO"],
        "VENDEDOR" => $value["VENDEDOR"],
        "TIPO_SEROLOGIA" => $value["TIPO_SEROLOGIA"],
        "ADJUNTO_ADICIONAL" => $value["ADJUNTO_ADICIONAL"],
      );
    }

    return response()->json($data);
  }

  protected function get_documentos_subidos($adjunto, $cantidad)
  {


    $array_doc = explode('|', $adjunto);

    $formato1 = null;

    $formato2 = null;

    //como maximo se pueden tener 2 adjuntos

    //1 adjunto
    $ultimo = $array_doc[count($array_doc) - 1];

    //2 adjuntos
    $penultimo = $array_doc[count($array_doc) - 2];


    if ($cantidad == 1) {

      $documentos1 =  public_path() . '/laboratorio_temporal/' . $ultimo;


      if (file_exists($documentos1)) {

        $formato1 = $documentos1;
      }
    } elseif ($cantidad == 2) {

      $documentos1 =  public_path() . '/laboratorio_temporal/' . $ultimo;


      if (file_exists($documentos1)) {

        $formato1 = $documentos1;
      }


      $documentos2 =  public_path() . '/laboratorio_temporal/' . $penultimo;


      if (file_exists($documentos2)) {

        $formato2 = $documentos2;
      }
    }

    return array($formato1, $formato2);
  }




  protected function enviar_informe_laboratorio_masiva(Request $request)
  {
    $data = $request->data;

    if (count((array)$data) == 0) {

      return $this->setRpta('error', 'No existen contratos seleccionados');
    }

    $selecciona_row = false;

    //VALIDA CORREO
    //
    foreach ($data as $values) {

      //$values = json_decode($values);
      $contrato = $values["NUMERO_CONTRATO"];

      $selecciona = $values["SELECCIONA"];

      $destino = $values["DESTINO"];

      if ($selecciona) {

        $selecciona_row = true;

        if (empty($destino)) {

          return $this->setRpta('error', 'El contrato N°' . $contrato . ' no tiene un correo asociado');
        }
      }
    }


    if (!$selecciona_row) {

      return $this->setRpta('error', 'Seleccione al menos un registro');
    }

    //ENVIA DCORREO

    $cia = Auth::user()->empresa;

    //$config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();

    $config = ($cia == '001') ? $this->mailCriocord_Laboratorio() : $this->mailLazoVida_Laboratorio();

    foreach ($data as $values) {

      //$values = json_decode($values);

      $selecciona = $values["SELECCIONA"];

      if ($selecciona) {
        //return $this->setRpta('error', $values["ADJUNTO_ADICIONAL"]);
        //$formato = null;

        $contrato = $values["NUMERO_CONTRATO"];

        //destinatario2 inormer-lab 1er pdf

        $destino = $values["DESTINO"];
        //$destino = 'miguel_94_14@outlook.com';

        if (!filter_var($destino, FILTER_VALIDATE_EMAIL)) {

          return $this->setRpta("error", "Ingrese un correo válido para el destino :" . $destino . "Contrato N:" . $contrato);
        }

        $copia = $values["COPIA"];

        if (!filter_var($copia, FILTER_VALIDATE_EMAIL)) {

          return $this->setRpta("error", "Ingrese un correo válido para la copia :" . $copia . "Contrato N:" . $contrato);
        }

        $familia = $values["FAMILIA"];

        $colecta = $values["NUMERO_COLECTA"];

        $servicio = $values["SERVICIO"];

        $adjunto = $values["FICHERO"];

        $numero_ficheros = $values["CANTIDAD_FICHEROS"];

        if (!empty($adjunto)) {

          $documentos_explode = $this->get_documentos_subidos($adjunto, $numero_ficheros);

          $formato1 = $documentos_explode[0];

          $formato2 = $documentos_explode[1];

          $tipo_envio = 'adjunto';
        } else {

          $formato1 = $this->genera_documentos_laboratorios($contrato, $colecta, $servicio);

          $formato2 = $this->genera_documentos_laboratorios2($contrato, $servicio);

          $tipo_envio = 'sistema';
        }

        //new mx otros
        $adjunto_adicional = $values["ADJUNTO_ADICIONAL"];
        if($adjunto_adicional == "S") {
          $formato3 = $this->informe_laboratorio_serologia_temp($contrato,"ADI");
        }else {
          $formato3 = null;
        }
        //envia serologia segun flag

        $serologia = $values["FLAG_ENVIO_SEROLOGIA"];

        if ($serologia == 0 && $tipo_envio == 'sistema') {
          $formato2 = null;
        }

        //new mx
        $tipo_serelogia = $values["TIPO_SEROLOGIA"];
        // return $this->setRpta('error', $tipo_serelogia);
        if ($tipo_serelogia == "EXT") {
          $formato2 = $this->informe_laboratorio_serologia_temp($contrato,"RSE");
        }

        //envio de solo informe lab para el correo copia

        $informe_estatico = $this->genera_documentos_laboratorios($contrato, $colecta, $servicio);

        $parametros = array(

          "cia"           => $cia,
          "servicio"      => $servicio,
          "contrato"      => $contrato,
          "config"        => $config,
          "destinatarios" => $destino,
          "formato1"       => $formato1,
          "formato2"       => $formato2,
          "formato3"       => $formato3,
          "cliente"       => strtoupper($familia),
          "copia"         => $copia,
          "informe_estatico" => $informe_estatico,
          "tipo_envio" => $tipo_envio

        );

        //return $this->setRpta('error', $parametros);
        $correo = new CorreoController;

        $rpta =  $correo->envia_informe_laboratorio_masiva($parametros);

        if ($rpta["status"] == "error") {

          return $rpta;
        }

        //actualiza envio y fecha 

        $hoy = Carbon::now()->format('Y-m-d');

        $usuario_envio = Auth::user()->codigo;

        \DB::update("UPDATE LAB_COLECTAS SET ENVIO_FECHA_CORREO=?,FLAG_ENVIO_CORREO=? , USUARIO_ENVIO=? WHERE NO_CIA=? AND NUMERO_CONTRATO=?", array($hoy, 1, $usuario_envio, $cia, $contrato));


        //insertamos historial



        \DB::insert("INSERT INTO LAB_COLECTAS_ADJUNTO_HISTORIAL(NO_CIA,NUMERO_CONTRATO,FECHA_ENVIO,USUARIO_ENVIO) VALUES(?,?,?,?)", array($cia, $contrato, $hoy, $usuario_envio));
      }
    }


    // return $this->setRpta('ok', 'Se enviaron los informes de manera satisfactoria');
    return $this->setRpta('ok', $rpta);
  }

  //new mx
  protected function atender_informe_laboratorio_masiva(Request $request)
  {
    $data = $request->data;

    if (count((array)$data) == 0) {

      return $this->setRpta('error', 'No existen contratos seleccionados');
    }

    $selecciona_row = false;

    //VALIDA CORREO
    //
    foreach ($data as $values) {

      //$values = json_decode($values);
      $contrato = $values["NUMERO_CONTRATO"];

      $selecciona = $values["SELECCIONA"];

      $destino = $values["DESTINO"];

      if ($selecciona) {

        $selecciona_row = true;
        $rpta = Laboratorio::atender_envio_informecom($contrato, 'CANCEL');
        if ($rpta != 1) {
          return $this->setRpta('error', 'El contrato N°' . $contrato . ' cancel');
        }
      }
    }

    return $this->setRpta('ok', 'Se enviaron los informes de manera satisfactoria');
  }



  protected function genera_documentos_laboratorios($contrato, $colecta, $tipo)
  {


    //laboratorio 

    $data = Laboratorio::informe_laboratorio_descarga_laboratorio($contrato, $colecta);




    $empresa_user = Auth::user()->empresa;

    $dia_actual = Carbon::now()->format('d');
    $mes_actual = $this->obtener_mes_actual_espanol();
    $año_actual = Carbon::now()->format('Y');



    $pdf = \App::make('dompdf.wrapper');
    $pdf->setPaper('A4');
    $pdf->loadView('reporte.contrato.laboratorio.laboratorio', compact('empresa_user', 'data', 'tipo', 'dia_actual', 'mes_actual', 'año_actual'));


    $random = $this->generaRandomString(10);

    $file = public_path() . '/laboratorio_temporal/' . $random . '.pdf';

    $pdf->save($file);

    return $file;
  }



  protected function genera_documentos_laboratorios2($contrato, $tipo)
  {



    $data = Laboratorio::informe_laboratorio_descarga_serologia($contrato);
    $data2 = Laboratorio::informe_laboratorio_descarga_serologia2($contrato);




    $empresa_user = Auth::user()->empresa;

    $dia_actual = Carbon::now()->format('d');
    $mes_actual = $this->obtener_mes_actual_espanol();
    $año_actual = Carbon::now()->format('Y');

    //maximo de paginas


    $res =  count(array($data2)) % 6;

    $res = $res + 1;


    $pdf = \App::make('dompdf.wrapper');
    $pdf->setPaper('A4');
    $pdf->loadView('reporte.contrato.laboratorio.serologia', compact('empresa_user', 'data2', 'data', 'tipo', 'dia_actual', 'mes_actual', 'año_actual', 'res'));




    $random = $this->generaRandomString(10);

    $file = public_path() . '/laboratorio_temporal/' . $random . '.pdf';

    $pdf->save($file);

    return $file;
  }

  protected function informe_laboratorio_descarga_laboratorio($contrato, $colecta, $tipo)
  {


    $data = Laboratorio::informe_laboratorio_descarga_laboratorio($contrato, $colecta);




    $empresa_user = Auth::user()->empresa;

    $dia_actual = Carbon::now()->format('d');
    $mes_actual = $this->obtener_mes_actual_espanol();
    $año_actual = Carbon::now()->format('Y');



    $pdf = \App::make('dompdf.wrapper');
    $pdf->setPaper('A4');
    $pdf->loadView('reporte.contrato.laboratorio.laboratorio', compact('empresa_user', 'data', 'tipo', 'dia_actual', 'mes_actual', 'año_actual'));
    return $pdf->stream();
  }



  protected function informe_laboratorio_descarga_serologia($contrato, $tipo)
  {
    if ($tipo == "EXT") {
      $docbyte = Laboratorio::get_doc_byte($contrato, "RSE");
      if (!$docbyte) {
        // Manejar el caso en que el documento no se encuentre
        return response()->json(['error' => 'Documento no encontrado'], 404);
      }

      $filepath2 = public_path() . '/resultadoFormatoWord/' . $contrato . '-blob' . '.pdf';
      file_put_contents($filepath2, $docbyte);

      // Crea la respuesta del archivo
      $response = response()->file($filepath2, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => "inline; filename=\"{$contrato}.pdf\""
      ]);

      // Elimina el archivo después de que se envíe la respuesta
      $response->deleteFileAfterSend(true);

      return $response;
    }

    $data = Laboratorio::informe_laboratorio_descarga_serologia($contrato);
    $data2 = Laboratorio::informe_laboratorio_descarga_serologia2($contrato);

    $empresa_user = Auth::user()->empresa;

    $dia_actual = Carbon::now()->format('d');
    $mes_actual = $this->obtener_mes_actual_espanol();
    $año_actual = Carbon::now()->format('Y');

    $res =  count(array($data2)) % 6;

    $res = $res + 1;

    $pdf = \App::make('dompdf.wrapper');
    $pdf->setPaper('A4');
    // $pdf->loadView('reporte.contrato.laboratorio.serologia', compact('empresa_user','data2','data','tipo','dia_actual','mes_actual','año_actual','res'));
    $pdf->loadView('reporte.contrato.laboratorio.serologia', compact('empresa_user', 'data2', 'data', 'dia_actual', 'mes_actual', 'año_actual', 'res'));
    return $pdf->stream();
  }

  //new mx
  protected function informe_laboratorio_descarga_otros($contrato)
  {
    $docbyte = Laboratorio::get_doc_byte($contrato, "ADI");
    if (!$docbyte) {
      // Manejar el caso en que el documento no se encuentre
      return response()->json(['error' => 'Documento no encontrado'], 404);
    }

    $filepath2 = public_path() . '/resultadoFormatoWord/' . $contrato . '-blob' . '.pdf';
    file_put_contents($filepath2, $docbyte);

    // Crea la respuesta del archivo
    $response = response()->file($filepath2, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => "inline; filename=\"{$contrato}.pdf\""
    ]);

    // Elimina el archivo después de que se envíe la respuesta
    $response->deleteFileAfterSend(true);

    return $response;
  }


  protected function informe_laboratorio_serologia_temp($contrato,$tipo)
  {
    $docbyte = Laboratorio::get_doc_byte($contrato, $tipo);
    if (!$docbyte) {
      // Manejar el caso en que el documento no se encuentre
      return null;
    }

    $random = $this->generaRandomString(10);

    $file = public_path() . '/laboratorio_temporal/' . $random . '_serelogia.pdf';
    file_put_contents($file, $docbyte);

    return $file;
  }




  protected function upload_file_informe_laboratorio(Request $request)
  {

    $indice = $request->indice;


    $dir      = 'laboratorio_temporal/';



    if ($request->file('file')) {

      $ext      = strtolower($request->file('file')->getClientOriginalExtension());
      $fileName = str_random() . '.' . $ext;


      if ($request->file('file')->move($dir, $fileName)) {

        return $this->setRpta('ok', 'se cargo el archivo de manera correcta', $indice . '|' . $fileName);
      } else {

        return $this->setRpta('error', 'no se cargo el archivo');
      }
    }


    //return $this->setRpta('ok','se cargo el archivo de manera correcta');
  }
}
