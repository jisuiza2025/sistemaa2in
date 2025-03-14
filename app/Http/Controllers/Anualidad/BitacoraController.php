<?php


namespace App\Http\Controllers\Anualidad; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\Bitacora;
use App\Contrato;
use Auth;
use PDF;


use App\Http\Controllers\Mantenimiento\CorreoController;
use App\Http\Controllers\Reporte\ReporteController;
use App\Http\Controllers\Mantenimiento\WordController;
use App\Http\Controllers\Mantenimiento\CloudConvertController;

class BitacoraController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
  
    public function index($contratox)
	{      

		
        $empresa = Auth::user()->empresa;

        $contrato = trim($contratox);

        $data = Bitacora::get_detail_bitacora_data($contrato);

        $estado_cuenta = Bitacora::get_detail_estado_cuenta($contrato);

        //$info_contrato = Contrato::get_info_contrato(trim($contrato));
        
        $medios_comunicacion = Maestro::get_medios_comunicacion();

        $files = Bitacora::get_files_bitacora_data($contrato);

        //para modal de cliente
        
        $categorias = Maestro::list_categoria();

        $documentos = Maestro::list_tipo_documento();

        $ecivil     = Maestro::list_estado_civil();

        $ocupacion  = Maestro::list_ocupacion();

        $paises = Maestro::list_paises();

        $departamentos = Maestro::list_departamento();

        
        $btn_informe = $this->botones_usuario('anu_llamadacob_inflab');

        $btn_constancia = $this->botones_usuario('anu_llamadacob_constancia');

        $btn_facturacion = $this->botones_usuario('anu_llamadacob_fact');

        $lista_comentarios_contrato =Maestro::lista_comentarios_contrato($contrato);


        //$lst_info_laboratorio=array();
        //$lst_info_laboratorio=Contrato::get_info_laboratorio($contrato);

        $titular_pago=$this->set_titular_pago($contrato);


        $captacion_prop = Maestro::list_captacion();

        $lsta_servicios = Maestro::cmb_servicios_filtro();
        $xtipo='';
        return View('llamada.bitacora.registro_bitacora',compact('empresa','contrato','data','estado_cuenta','medios_comunicacion','files','categorias','documentos','ecivil','ocupacion','paises','departamentos','btn_informe','btn_facturacion','lista_comentarios_contrato','titular_pago','captacion_prop','lsta_servicios','btn_constancia'));
        
	}






protected function set_titular_pago($contrato){

       $cia = Auth::user()->empresa;

       $query = DB::select("SELECT NVL(TITULAR_PAGO,'M') AS TITULAR_PAGO FROM VEN_CONTRATOS WHERE NO_CIA=? AND NUMERO_CONTRATO=?",array($cia,$contrato));



       $rpta = json_decode(json_encode($query),true);
  
      return $rpta[0]['titular_pago'];



    } 

    protected function get_files_bitacora_registro(Request $request){

        $list = Bitacora::get_files_bitacora_data($request->contrato);

        return response()->json($list);
    } 


    protected function list_contactos_bitacora(Request $request){

        $list = Bitacora::get_contactos($request);

        return response()->json($list);
    } 







protected function registra_nuevo_servicio_nuevo(Request $request){

       

       $rpta = Bitacora::registra_nuevo_servicio_nuevo($request);
         
         if($rpta==1){

            return $this->setRpta("ok","Se registro correctamente el registro");

         }else{

            return $this->setRpta("error","hubo un error SQL.");

         }
    } 

protected function modal_asigna_inubicable(Request $request){

       

       $rpta = Bitacora::modal_asigna_inubicable($request);
         
         if($rpta==1){

            return $this->setRpta("ok","Se actualizó correctamente el registro");

         }else{

            return $this->setRpta("error","hubo un error SQL.");

         }
    } 

protected function modal_asigna_propietario(Request $request){

       

       $rpta = Bitacora::modal_asigna_propietario($request);
         
         if($rpta==1){

            return $this->setRpta("ok","Se actualizó correctamente el registro");

         }else{

            return $this->setRpta("error","hubo un error SQL.");

         }
    } 



 protected function confirma_nueva_descripcion_adjunto_bitacora(Request $request){

       

       $rpta = Bitacora::confirma_nueva_descripcion_adjunto_bitacora($request);
         
         if($rpta==1){

            return $this->setRpta("ok","Se creó la nueva descripción");

         }else{

            return $this->setRpta("error","hubo un error SQL.");

         }
    } 


 protected function upload_file_bitacora(Request $request){

       

       $rpta = Bitacora::upload_file_bitacora($request);
         
         if($rpta==1){

            return $this->setRpta("ok","se cargo corrrectamente el archivo.");

         }else{

            return $this->setRpta("error","hubo un error SQL.");

         }
    } 

    protected function modal_activa_notificacion_cliente(Request $request){

       

       $rpta = Bitacora::modal_activa_notificacion_cliente($request);
         
         if($rpta==1){

            return $this->setRpta("ok","se modificó correctamente.");

         }else{

            return $this->setRpta("error","hubo un error SQL.");

         }
    } 


    protected function list_contratos_bitacora(Request $request){

        $list = Bitacora::list_contratos_bitacora($request);

        return response()->json($list);
    } 
    
     protected function list_deuda_familia_bitacora(Request $request){

        $list = Bitacora::list_deuda_familia_bitacora($request);

        return response()->json($list);
    } 


     protected function get_datos_complementario_cliente_bitacora(Request $request){

        $list = Bitacora::get_datos_complementario_cliente_bitacora($request);

        return response()->json($list);
    } 

    protected function list_bitacora_bitacora(Request $request){

        $list = Bitacora::list_bitacora_bitacora($request);

        

        return response()->json($list);
    } 
    protected function anclar_detalle_bitacora(Request $request){
        $rpta = Bitacora::anclar_detalle_bitacora($request);
        /*if($rpta == 1){       
        
        
            return $this->setRpta("ok","Se actualizó correctamente");
        
        }
        
          
        return $this->setRpta("error","Ocurrió un error");*/
        return $rpta;
        
    }

    
      protected function get_detalle_ver_bitocora_historial(Request $request){

        $list = Bitacora::get_detalle_ver_bitocora_historial($request);

        return response()->json($list);
    } 


    protected function actualiza_nombre_bebe_bitacora(Request $request)
    {      

        
         $rpta = Bitacora::actualiza_nombre_bebe_bitacora($request);
            
            if($rpta == 1){

 
              return $this->setRpta("ok","Se actualizó correctamente");

            }
          
            

            return $this->setRpta("error","Ocurrió un error");
        

    }




protected function actualiza_datos_complmentarios_bitacora(Request $request)
    {      

        
         $rpta = Bitacora::actualiza_datos_complmentarios_bitacora($request);
            
            if($rpta == 1){

 
              return $this->setRpta("ok","Se actualizó correctamente");

            }
          
            

            return $this->setRpta("error","Ocurrió un error");
        

    }


protected function salvar_nueva_bitacora(Request $request)
    {      

        //DEPENDE DE APLICAR CONTRATOS -DEUDA FAMILIAR
        
        

        if($request->vm_respuesta_contratos_aplica=='true'){

          //RECORRE TABLA DEUDA FAMILIAR
          
          $deuda = json_decode($request->deudas,true);

          
          Bitacora::salvar_nueva_bitacora($request,$request->contrato);

          Bitacora::update_titular_bitacora($request,$request->contrato);

          foreach($deuda as $list){

               if($request->contrato != $list["NUMERO_CONTRATO"]){

                

                   $rpta = Bitacora::salvar_nueva_bitacora($request,$list["NUMERO_CONTRATO"]);



                   Bitacora::update_titular_bitacora($request,$list["NUMERO_CONTRATO"]);


                   if($rpta == 0){

                      return $this->setRpta("error","Ocurrió un error");
                   }


               } 



          }
          
           return $this->setRpta("ok","Se agregó correctamente");
        
        
        }else{

             $contrato = $request->contrato;


             $rpta = Bitacora::salvar_nueva_bitacora($request,$contrato);
              
              Bitacora::update_titular_bitacora($request,$contrato);
              
              if($rpta == 1){

 
                  return $this->setRpta("ok","Se agregó correctamente");

              }
          
            

              return $this->setRpta("error","Ocurrió un error");



        }
        
        
        

    }

    
    
    //HISTORIALO BITACORAC
    //
    

    protected function historial_bitacora(){

      $middleRpta = $this->valida_url_permisos(23);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

        $empresa_user = Auth::user()->empresa;

        $lista_responsable=Maestro::filter_responsable();

        $lista_medios=Maestro::filter_comunicacion();

        $user_logeado=Auth::user()->codigo;

        $atencion_bitacora = $this->set_flag_atencion_bitacora();


        $btn_switch_bitacora = $this->botones_usuario('anu_histbit_sw');


        $btn_grabar_bitacora = $this->botones_usuario('anu_histbit_grabar');


        return view('llamada.bitacora_historial.historial_bitacora', compact('empresa_user','lista_responsable','lista_medios', 'user_logeado','atencion_bitacora','btn_switch_bitacora','btn_grabar_bitacora'));
    } 

    protected function set_flag_atencion_bitacora(){

        $cia = Auth::user()->empresa;

        $user =Auth::user()->codigo;

        $query = DB::select("SELECT ATENDER FROM VEN_RESP_ANUA WHERE NO_CIA=? AND CODIGO_USUARIO=?",array($cia,$user));

       

        $rpta = json_decode(json_encode($query),true);

        $atencion = (isset($rpta[0]['atender']))?$rpta[0]['atender']:0;

        return $atencion;


    }
  
    protected function list_historial_bitacora(Request $request){

        $lista=Bitacora::list_historial_bitacora($request);

        return response()->json($lista);
    } 

    protected function save_historial_bitacora(Request $request){

        $rpta = Bitacora::save_historial_bitacora($request);

            if($rpta == 1){

                return $this->setRpta("ok","Se procesó correctamente");

            }
      
            return $this->setRpta("error","Ocurrió un error");


        return response()->json($lista);
    }
	



    protected function registra_cliente_nuevo_bitacora(Request $request){

       

       $rpta = Bitacora::registra_cliente_nuevo_bitacora($request);
         
         if($rpta==1){

            return $this->setRpta("ok","Se procesó correctamente");

         }else{

            return $this->setRpta("error","hubo un error al crear.");

         }
    } 


    

     protected function get_data_envio_lab_correo(Request $request){

        $list = Bitacora::get_data_envio_lab_correo($request);

        return response()->json($list);
    } 

    protected function elabora_informe_lab_pdf($request){



        //obtener plantilla
        

        
      $servicio = $request->servicio;
      $mama_soltera = $request->mama_soltera;
      $contrato = $request->contrato;
      $numero_colecta = $request->numero_colecta;


        try {


           $file_name = Bitacora::get_filename_plantilla_colectas($servicio,$mama_soltera);

           

           if(count($file_name) == 0){

              return $this->setRpta("error","No se encontro nombre de plantilla");

           }


           $documento = $file_name[0]['NOMBRE_ARCHIVO'];

           

        
           $llaves = Bitacora::get_llaves_plantilla_colectas($contrato,$numero_colecta);

    

           $plantilla = (Auth::user()->empresa == '001')? public_path().'/formatos_colectas/ICTC/'.$documento: public_path().'/formatos_colectas/LAZO_DE_VIDA/'.$documento;


            $word = new WordController;

           
            $middleRpta = $word->generacion_plantilla_colectas($plantilla,$llaves);


            if($middleRpta["status"]=="ok"){


                $CloudConvert = new CloudConvertController;

                $filePath = $middleRpta["data"];

                return $CloudConvert->convert($filePath);

            
            }



            return $middleRpta;




        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
            

           return $this->setRpta("error",$e->getCode());
           
        }

    }


    protected function informe_laboratorio_pdf(Request $request){



      return $this->elabora_informe_lab_pdf($request);

        //$empresa_user = Auth::user()->empresa;

        //$pdf = \App::make('dompdf.wrapper');
        //$pdf->setPaper('A4','landscape');
        //$pdf->loadView('reporte.bitacora.informe_laboratorio', compact('empresa_user'));
        //return $pdf->stream();

    }



    public function descargar_informe_laboratorio_pdf($file,$contrato){



    $pathtoFilePago = public_path().'/cloudConvert/'.$file;
    
        if (file_exists($pathtoFilePago)) {
            

        
            $word = new WordController;

            $word->deleteFileWordTemporal($file);
                
            
            $outName ="IMPRESION_LABORATORIO_".$contrato.".pdf";


             $headers = [

                'Content-Type' => 'application/pdf',

            ];

            return response()->download($pathtoFilePago, $outName, $headers)->deleteFileAfterSend(true);



        } else {
        
            return $this->redireccion_404();
        }



}

    protected function set_contactos_informe_bitacora_correo($request){


        $contactos = $request->destinatarios;

       
        $destinatarios = array();

        foreach($contactos as $list){

          $decode = json_decode($list);

          if(!empty($decode->MAIL_CONTACTO)){

              $destinatarios[$decode->MAIL_CONTACTO] = $decode->NOMBRE;


          }



        }


       return $destinatarios;

    }

    protected function envia_notificacion_registro_bitacora(Request $request){

       $cia = Auth::user()->empresa;

       $config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();

       
      

       $rpta_elabora = $this->elabora_informe_lab_pdf($request);

       
       
       if($rpta_elabora!="ok"){

            return $rpta_elabora;

       }else{

          $formato = public_path().'/cloudConvert/'.$rpta_elabora["data"];

          
       }


       
       
       //DATOS DEL CORREO PARA ENVIO
       
       
       $destinatarios_cadena = $request->destinatarios;

       //ARMAMOS DESTINATARIOS
       
       $destinatarios_list = explode(";", $destinatarios_cadena);

       $destinatarios_array = array();

       foreach($destinatarios_list as $list){


          if(!empty($list)){

             $destinatarios_array[] = $list;
          }

       }

       if(count($destinatarios_array) == 0){


          return $this->setRpta("error","no hay destinatarios en la lista");

       }

       
       

       $asunto        = (empty($request->asunto))?'ENVIO DE INFORME LABORATORIO':$request->asunto;
       

       $comentario    = $request->comentario;



       $parametros = array(

                      "cia"          => $cia,
                      "config"       => $config,
                      "destinatarios"=> $destinatarios_array,
                      "formato"      => $formato,
                      "mensaje"      => $comentario,
                      "asunto"       => $asunto,
                      "contrato"    => $request->contrato

                  );


       $correo = new CorreoController;

       return $correo->envia_informe_laboratorio_bitacora($parametros);


        
        
      
    } 

    


	

	

    public function descargar_adjunto_bitacora_nuevo($file){


    

    $pathtoFilePago = public_path().'/adjuntos_nueva_bitacora/'.$file;
    
        if (file_exists($pathtoFilePago)) {
            

            

            return response()->download($pathtoFilePago);

           


        } else {
        
            return $this->redireccion_404();
        }



}

protected function actualiza_debito_automatico(Request $request){

       

    $rpta = Bitacora::actualiza_debito_automatico($request);
      
      if($rpta==1){

         return $this->setRpta("ok","Se actualizó correctamente el registro");

      }else{

         return $this->setRpta("error","hubo un error SQL.");

      }
 } 

	
   





	

	
	
	






    
}
