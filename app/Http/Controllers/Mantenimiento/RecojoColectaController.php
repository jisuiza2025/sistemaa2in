<?php

namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\RecojoColecta;
use Auth;
use DB;
use PDF;
use Carbon\Carbon;
use GuzzleHttp\Client;

class RecojoColectaController extends Controller
{
   
   public function __construct()
    {
        $this->middleware('auth');
    }
    
   public function index(){




    $middleRpta = $this->valida_url_permisos(36);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


    $empresa_user = Auth::user()->empresa;

    return view('mantenimiento.recojo_colecta.index', compact('empresa_user'));

   }

   public function list_recojo(){

      $list=RecojoColecta::list_recojo();
 
      return response()->json($list);
   }  


   public function set_tipos_servicio_constancia_recojo(Request $request){

      $list=RecojoColecta::set_tipos_servicio_constancia_recojo($request);
 
      return response()->json($list);
   }  

   

    public function datos_propietario_dental_colectas(Request $request){

      

      $list=RecojoColecta::datos_propietario_dental_colectas($request);
 
      return response()->json($list);
   }  


   public function constancia_recojo(Request $request){



    $middleRpta = $this->valida_url_permisos(36);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }



    //$name = Auth::user()->codigo;
 
    $cargo = Auth::user()->cargo;

    $codigo = Auth::user()->codigo;

    $num_contrato=$request->num_contrato;

    
    $empresa_user = Auth::user()->empresa;


    $fecha_actual = Carbon::now()->format('Y-m-d');

    $hora_actual = Carbon::now()->format('h:i');

    

    $base = $this->obten_numero_base_contrato($num_contrato);


    $dental = $this->obtener_pulpa_dental($base);

    
    
    


    $query = DB::select("SELECT DNI, DNI || ' - '|| UPPER(NOMBRE) NOMBRE,CODIGO_USUARIO FROM COR_USUARIOS WHERE CODIGO_USUARIO=? ",array($codigo));


    $decode = json_decode(json_encode($query),true);

    $dni_prop_ulogeado = (isset($decode[0]['dni']))?$decode[0]['dni']:'';

    $nombre_prop_ulogeado= (isset($decode[0]['nombre']))?$decode[0]['nombre']:'';

    $name= (isset($decode[0]['codigo_usuario']))?$decode[0]['codigo_usuario']:'';
    

    return view('mantenimiento.recojo_colecta.constancia_recojo', compact('num_contrato', 'name', 'cargo', 'fecha_actual','empresa_user','dni_prop_ulogeado','nombre_prop_ulogeado','hora_actual','dental'));

   } 


   protected function obten_numero_base_contrato($numero_contrato){



        $request = new Request;

        $request->num_contrato = $numero_contrato;

        $data = RecojoColecta::lista_datos_contrato($request);

        $datax = json_decode(json_encode($data),true);

        

        $rpta = $datax[0]['NUMERO_BASE'];

        return $rpta;


   }


   protected function obtener_pulpa_dental($base){

        $request = new Request;

        $request->numero_base = $base;

        $dental = RecojoColecta::set_tipos_servicio_constancia_recojo($request);

        return $dental;

   }

   protected function eliminar_recojo_colecta(Request $request)
  {      

    $rpta = RecojoColecta::eliminar_recojo_colecta($request);
    
    
     if($rpta == 1){
              
              return $this->setRpta("ok","Se eliminó de correctamente");
          }

        return $this->setRpta("error","Ocurrió un error al eliminar");

  }


   protected function registro_colecta(Request $request)
  {      

    $rpta = RecojoColecta::registro_colecta($request);
    
    
     if($rpta == 1){
              
              return $this->setRpta("ok","Se procesoó de correctamente");
      }

        return $this->setRpta("error","Ocurrió un error");
        
        
    
   

  }


   protected function envio_mensaje_sms_celular(Request $request)
  {      

      
      $movil = array();


      foreach ($request->contactos as $key => $list) {

        $decode = json_decode($list,true);

        
        if($decode['TIPO']=='MAMÁ' || $decode['TIPO']=='PAPÁ'){

          $multiple_celular = explode(";",$decode['CELULAR_CONTACTO']);

            foreach($multiple_celular as $list){

                if(!empty($list)){

                  $movil[] = $this->quitar_caracteres($list);

                }
            }

          //$movil[] = $this->quitar_caracteres($decode['CELULAR_CONTACTO']);
          

        }
        


      }
        
        if(count($movil) == 0 ){

            return $this->setRpta("error","No hay números de celular de contactos");
        }


        $movil_unico = array_unique($movil);

        

        foreach($movil_unico as $numero){


          $cuerpo = $this->set_cuerpo_sms_notificacion($request->contrato);

          $rptaEnvio =  $this->api_envia_mensaje_celular($numero,$cuerpo);



        }


        //envio a vendedores
        
        $vendedores = RecojoColecta::set_cuerpo_sms_notificacion_vendedores($request->contrato);


        foreach($vendedores as $list){


          $numerox = $this->quitar_caracteres($list['CELULAR']);

          $rptaEnvio =  $this->api_envia_mensaje_celular($numerox,$list['MENSAJE']);
        }
        

        return $rptaEnvio;

        

        
    
    

  }

  protected function set_cuerpo_sms_notificacion($contrato){


    


    $mensaje = RecojoColecta::set_cuerpo_sms_notificacion($contrato);



    return $mensaje[0]['MENSAJE'];
    



  }


  protected function api_envia_mensaje_celular($movil,$cuerpo){

    try {
      
      

      $token = (Auth::user()->empresa=='001')?'FAA379F2-6A03-4F63-A08F-FA6291B425EB':'8D37CDB5-758F-4B74-9D12-69A48C149CB8';

     
      

      

      $url = 'https://app.wachatbot.com:12345/api/message/send';

     

      $data = array(
              'targetPhone' => $movil,
              
              'message' => $cuerpo,
              'token' => $token);
                          
              $options = array(
                  'http' => array(
                  'header' =>
                  "Content-type:application/x-www-form-urlencoded\r\n",
                  'method' => 'POST',
                  'content' => http_build_query($data)
              ),
                      
              'ssl'=>array(
                  'verify_peer'=>false,
                  'verify_peer_name'=>false,
              )
          );
                      
          $context = stream_context_create($options);
                      
          $result = file_get_contents($url, false, $context);
                     
          $rpta = json_decode($result,true);


      if($rpta["status"]=="success"){

        return $this->setRpta("ok","Se pudo enviar el mensaje de manera satisfactoria - ID DEL MENSAJE: ".$rpta["message_id"]);

      }

      return $this->setRpta("error","No se pudo enviar el mensaje de texto");


    } catch (\Exception $e) {
      

      return $this->setRpta("error",$e->getMessage());

    }

      


      // $parametros= array(

      //     'cia' => $cia,
      //     'destinatarios' =>$movil,
      //     'function' =>'EnviarNotificacion',
      //     'token' =>$token,
      //     'cuerpo' =>$cuerpo
      // );



      // $data = json_encode($parametros);


      

      //   $client = new \GuzzleHttp\Client();

      //   $response = $client->request('POST', 'http://localhost/smsCriocord/SmsService.php', [
  
      //       'headers' => ['Content-Type' => 'application/json'],
      //       'body' => $data

      //   ]);


     
      //   $body = json_decode($response->getBody());

      //   $asoc = (array)$body;

        

      //   return $asoc;



  }

  protected function quitar_caracteres($string){


    $texto = preg_replace('([^0-9])', '', $string);


    $prefijo= substr($texto, 0, 2);

    $celular = ($prefijo=='51')?$texto:'51'.trim($texto);
    

    //return '51'.trim($texto);
    
    return $celular;

 }


  
  

  protected function set_datos_complementarios_contrato_edit_colecta(Request $request){

      $list=RecojoColecta::set_datos_complementarios_contrato_edit_colecta($request);

      return response()->json($list);
   } 
   
   protected function list_contactos_recojo_colecta(Request $request){

      $list=RecojoColecta::list_contactos_recojo_colecta($request);

      return response()->json($list);
   } 


   

   protected function filter_responsable_constancia_colecta(Request $request){

      $list=RecojoColecta::filter_responsable_constancia_colecta($request);

      return response()->json($list);
   }


   protected function list_rec_modal(Request $request){

      $list=RecojoColecta::list_rec_modal($request);

      return response()->json($list);
   }   

   protected function lista_datos_contrato(Request $request){

      $list=RecojoColecta::lista_datos_contrato($request);

      return response()->json($list);
   }

   protected function list_contactos_tab(Request $request){

      $list=RecojoColecta::list_contactos_tab($request);

      return response()->json($list);
   } 

  

   protected function lista_clientes(Request $request){

      $list=RecojoColecta::lista_clientes($request);

      return response()->json($list);
   } 



     protected function informe_laboratorio_pdf_colectas(Request $request){



      return $this->elabora_informe_lab_pdf($request);

       

    }



protected function envia_notificacion_registro_bitacora_colecta(Request $request){

       $cia = Auth::user()->empresa;

       $config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();

       
      
       $formato = $this->elabora_informe_lab_pdf($request);

       $formato = public_path().'/plantillas_estaticas/'.$formato["data"];


        if (!file_exists($formato)) {
            
          return $this->setRpta("error","no se pudo generar el formato de colecta");
           
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

       
       

       $asunto        = (empty($request->asunto))?'ENVIO DE CONSTANCIA DE RECOJO':$request->asunto;
       

       $comentario    = $request->comentario;



       $parametros = array(

                      "cia"          => $cia,
                      "config"       => $config,
                      "destinatarios"=> $destinatarios_array,
                      "formato"      => $formato,
                      "mensaje"      => $comentario,
                      "asunto"       => $asunto
                  );


       $correo = new CorreoController;

       
       return $correo->envia_informe_constancia_recojo($parametros);


        
        
      
    } 

    

   protected function get_data_envio_lab_correo_colectas(Request $request){

      $list=RecojoColecta::get_data_envio_lab_correo_colectas($request);

      return response()->json($list);
   } 

    

    public function descargar_informe_laboratorio_pdf_colectas($file,$contrato){



    $pathtoFilePago = public_path().'/plantillas_estaticas/'.$file;
    
        if (file_exists($pathtoFilePago)) {
                  
            
            $outName ="CONSTANCIA_RECOJO".$contrato.".pdf";


             $headers = [

                'Content-Type' => 'application/pdf',

            ];

            return response()->download($pathtoFilePago, $outName, $headers)->deleteFileAfterSend(true);



        } else {
        
            return $this->redireccion_404();
        }



}

    protected function elabora_informe_lab_pdf($request){



      
    
      
      $numero_colecta = $request->numero_colecta;

      $numero_base = $request->numero_base;
     


        try {


          

        
            $llaves = RecojoColecta::get_llaves_plantilla_colectas($numero_colecta,$numero_base);

            
            $dia_actual = Carbon::now()->format('d');
            $mes_actual = $this->obtener_mes_actual_espanol();
            $año_actual = Carbon::now()->format('Y');

            $fecha_texto_hoy = $dia_actual.' de '. $mes_actual.' del '.$año_actual;


            $empresa_user = Auth::user()->empresa;

            $pdf = \App::make('dompdf.wrapper');
      
            $path = public_path('plantillas_estaticas/');

            $pdf->setPaper('A4');

            $pdf->loadView('plantillas.constancia_recojo',compact('llaves','empresa_user','fecha_texto_hoy'));

            $fileName = $this->generaRandomString();

            $fileName = $fileName.'.pdf';

            $pdf->save($path . '/' . $fileName);


            return $this->setRpta("ok",'se generó el pdf',$fileName);





        } catch (\Exception $e) {
            

           return $this->setRpta("error",$e->getCode());
           
        }

    }




    

}
