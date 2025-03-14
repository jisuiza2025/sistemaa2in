<?php


namespace App\Http\Controllers\Contrato; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\Doctores;
use App\Contrato;
use App\SeguimientoCaptador;

use Auth;
use Carbon\Carbon;
use App\Exports\ExportGeneral;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Mantenimiento\CorreoController;

class SeguimientoCaptadorController extends Controller
{	


	public function __construct()
    {
        $this->middleware('auth');
    }
    
  


  
  protected function confirmar_masiva_medicos(Request $request)
  {      

   
  $data = $request->data;

  if(count($data) == 0){


    return $this->setRpta("error","No hay elementos en la lista");
  }


  $selecciona = false;

  foreach($data as $values){

    if($values["SELECCIONA"]){

        $selecciona= true;
    }
    
  }


  if(!$selecciona){


    return $this->setRpta("error","No hay elementos seleccionados de la lista");
  }



    $rpta = SeguimientoCaptador::confirmar_masiva_medicos($request);
    
    
                if($rpta == 1){

                 

                    return $this->setRpta("ok","Se procesó correctamente");

                }
          
             

                return $this->setRpta("error","Ocurrió un error");

  }
  protected function confirmar_solicitud_pagos_masiva_medicos(Request $request)
  { 
    $rpta = SeguimientoCaptador::confirmar_solicitud_pagos_masiva_medicos($request);
    if($rpta == 1){

                 

      return $this->setRpta("ok","Se procesó correctamente");

  }



  return $this->setRpta("error","Ocurrió un error");
    
   }
   protected function confirmar_solicita_pago_medico(Request $request)
  {      

   
  


    $rpta = SeguimientoCaptador::confirmar_solicita_pago_medico($request);
    
    
                if($rpta == 1){

                 

                    return $this->setRpta("ok","Se procesó correctamente");

                }
          
             

                return $this->setRpta("error","Ocurrió un error");

  }

    public function seguimiento_captadores()
    {
        

        $middleRpta = $this->valida_url_permisos(46);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }



        $empresa_user = Auth::user()->empresa;
         $moneda = Maestro::list_monedas();

            $bancos = Maestro::get_list_bancos();

         
       $tipo_reporte = Maestro::list_cor_tablas(17);



       $btn_editar = $this->botones_usuario('cap_editar');
       $btn_ver_pendientes = $this->botones_usuario('cap_v_sus_pend');
       $btn_asigna_pago = $this->botones_usuario('cap_asig_pago');
       $btn_adjunta_pago = $this->botones_usuario('cap_adjunt_pago');
       $btn_retirar= $this->botones_usuario('cap_retira');
       $btn_incluye = $this->botones_usuario('cap_incluye');
       $btn_imprime = $this->botones_usuario('cap_imprime');
       $btn_envia_correo = $this->botones_usuario('cap_envia_correo');


       $btn_pago_masivo = $this->botones_usuario('con_seg_cap_mas');
       $btn_confirma_pago = $this->botones_usuario('con_seg_cap_pago');

        return View('contrato.seguimiento_captador',compact('empresa_user','moneda','bancos','tipo_reporte','btn_editar','btn_ver_pendientes','btn_asigna_pago','btn_adjunta_pago','btn_retirar','btn_incluye','btn_imprime','btn_envia_correo','btn_pago_masivo','btn_confirma_pago'));
    }




  
  protected function detalle_linea_tiempo_seguimiento_cap(Request $request)
  {      

   

    $list = SeguimientoCaptador::detalle_linea_tiempo_seguimiento_cap($request);
    
    return response()->json($list);

  }
  
   protected function sustentos_pendientes_medico(Request $request)
  {      

   

    $list = SeguimientoCaptador::sustentos_pendientes_medico($request);
    
    return response()->json($list);

  }

    protected function list_seguimiento_captadores(Request $request)
  {      

   

    $list = SeguimientoCaptador::list_seguimiento_captadores($request);
    
    $data = array();

    foreach($list as $value){

      $data[] = array(
        "SELECCIONA"=>false,
        "NO_CIA"=>$value["NO_CIA"],
        "FECHA_PAGO"=>$value["FECHA_PAGO"],
        "FECHA_PAGO_ORDEN"=>$value["FECHA_PAGO_ORDEN"],
        "CLIENTE"=>$value["CLIENTE"],
        "FECHA_COLECTA"=>$value["FECHA_COLECTA"],
        "NUMERO_CONTRATO"=>$value["NUMERO_CONTRATO"],
        "MONTO_CONTRATO"=>$value["MONTO_CONTRATO"],
        "SALDO"=>$value["SALDO"],
        "IDEMEDICO"=>$value["IDEMEDICO"],
        "MEDICO"=>$value["MEDICO"],
        "TARIFA"=>$value["TARIFA"],
        "SUSTENTOPAGOMED"=>$value["SUSTENTOPAGOMED"],
        "COMP_PAGO"=>$value["COMP_PAGO"],
        "DATOSMEDICO"=>$value["DATOSMEDICO"],
        "SUST_PENDIENTES"=>$value["SUST_PENDIENTES"],
        "LABORATORIO"=>$value["LABORATORIO"],
        "BTNASIGNA_MEDIO_PAGO"=>$value["BTNASIGNA_MEDIO_PAGO"],
        "BTNHABILITA_PAGO"=>$value["BTNHABILITA_PAGO"],
        "BTNRETIRA_PAGO"=>$value["BTNRETIRA_PAGO"],
        "CONSTANCIA_PAGO"=>$value["CONSTANCIA_PAGO"],
        "BTNSUSTENTOPAGO"=>$value["BTNSUSTENTOPAGO"],
        "COMENTARIO"=>$value["COMENTARIO"],
        "COMPANIA"=>$value["COMPANIA"],
        "CORREO_MEDICO"=>$value["CORREO_MEDICO"],
        "BTNENVIOPDF"=>$value["BTNENVIOPDF"],
        
        "BTNCOLORMEDIO"=>$value["BTNCOLORMEDIO"],
        "BTNHABILITA_SOLICITUD"=>$value["BTNHABILITA_SOLICITUD"],
        "TIPO_DOCUMENTO"=>$value["TIPO_DOCUMENTO"],
        "NUMERO_RUC"=>$value["NUMERO_RUC"],
        "RAZON_SOCIAL"=>$value["RAZON_SOCIAL"],
        "NOMBRE_BANCO"=>$value["NOMBRE_BANCO"],


      );

    }
    return response()->json($data);

  }

     protected function confirmar_asignar_mpago_doctor(Request $request)
  {      

   
   if(empty($request->recibo)){

                 

                    return $this->setRpta("error","Seleccione una opción");

                }


    $rpta = SeguimientoCaptador::confirmar_asignar_mpago_doctor($request);
    
    
                if($rpta == 1){

                 

                    return $this->setRpta("ok","Se procesó correctamente");

                }
          
             

                return $this->setRpta("error","Ocurrió un error");

  }



    protected function registroAdjunto_modal_captador_tracking(Request $request)
  {      

    

    $rpta = SeguimientoCaptador::registroAdjunto_modal_captador_tracking($request);
    
   

                if($rpta == 1){

                 

                    return $this->setRpta("ok","Se procesó correctamente");

                }
          
             

                return $this->setRpta("error","Ocurrió un error");


  }



protected function envia_correo_constancia_medico_seg_captador(Request $request)
  {      

    
    $contrato = trim($request->contrato);

    $destino = trim($request->correo);

    $medico = trim($request->medico);

    if(!filter_var($destino, FILTER_VALIDATE_EMAIL)) {
        
        return $this->setRpta("error","Ingrese un correo válido");
    }

    $cia = Auth::user()->empresa;

    $list = Contrato::get_info_medico($contrato);
 
        $empresa_user = Auth::user()->empresa;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('A5','landscape');
        $pdf->loadView('reporte.contrato.pago_medico', compact('empresa_user','list'));
        

        $random ='CONSTANCIA_N'.$contrato;

        $file = public_path().'/pago_medico_temporal/'.$random.'.pdf';

        $pdf->save($file);




       $config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();


       $parametros = array(

                      "cia"          => $cia,
                      "config"       => $config,
                      "cliente"       => $medico,
                      "contrato"       => $contrato,
                      "destinatarios" =>$destino,
                      "formato" =>$file
                  );


       $correo = new CorreoController;

       return $correo->envia_correo_constancia_medico_seg_captador($parametros);


  }
  



  

   
  

 



  

  
  

    
    

   




  

  


}
