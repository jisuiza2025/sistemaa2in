<?php


namespace App\Http\Controllers\Cmr; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\Prospecto;
use App\Captadores\Captador;
use App\Cmr\Cmr;

use Carbon\Carbon;
use App\Imports\ModelExcelCaptacion;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Config;

class CmrController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    



     public function reportes_incentivo_citas()
  {      


     $middleRpta = $this->valida_url_permisos(78);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    $lsta_servicios = Maestro::cmb_servicios_filtro();

    $tipo = Maestro::list_cor_tablas(30);
    
      return View('cmr.reporte_incentivo_citas',compact('lsta_servicios','tipo'));

  }



 protected function get_reporte_incentivo_citas(Request $request)
  {      

    
    $list = Cmr::get_reporte_incentivo_citas($request);
    
   

    return response()->json($list);

  }



    public function listado($params){




  $middleRpta = $this->valida_url_permisos(57);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


      $params  = base64_decode($params);

     
      $params = explode('|', $params);

     $empresa_user = Auth::user()->empresa;

     $usuario =  Auth::user()->codigo;

     
     $dni_filtro = $params[0];

     $indicador_filtro = $params[1];

     

     $medios = Captador::list_captacion();

     $departamentos = Maestro::list_departamento();



     $dni = Auth::user()->identificacion;

     //combos 
     
     $atencion_prop=[];
     $clasificacion_prop= [];
     $medio_cmb_prop =  Cmr::cmb_medios();
     $asignar_prop=[];



    $ver_todos = $this->botones_usuario('crm_bt_ver');
    $botonera_cc = $this->botones_usuario('crm_tarea_directo');

      $atenciones = Cmr::cmb_atenciones();

      return View('cmr.listado',compact('empresa_user','medios','departamentos','atencion_prop','clasificacion_prop','medio_cmb_prop','asignar_prop','dni_filtro','indicador_filtro','atenciones','ver_todos','botonera_cc'));

    }  





    protected function listado_principal(Request $request)
  {      

    
    $list = Cmr::listado_principal($request);
    
   

    $indicadores = Cmr::listado_principal_indicadores($request);

   

    $data = array($list,$indicadores);

    return response()->json($data);

  }





protected function get_asignaciones_prospecto(Request $request)
  {      

    $cia = $request->cia;
    $prospecto = $request->prospecto;

    $list = Cmr::cmb_asignar($cia,$prospecto);
    
   

    return response()->json($list);

  }
  




 protected function list_panel_tareas(Request $request)
  {      

    
    $list = Cmr::list_panel_tareas($request);
    
   

    return response()->json($list);

  }
  

   protected function listado_principal2(Request $request)
  {      

    
    $list = Cmr::listado_principal2($request);
    
   

    return response()->json($list);

  }
  


  protected function filter_medio(Request $request)
  {      

    
    $list = Cmr::cmb_medios();
    
   

    return response()->json($list);

  }
  protected function filter_clasificacion(Request $request)
  {      

    
    $list = Cmr::cmb_clasificacion($request);
    
   

    return response()->json($list);

  }


  

  protected function filter_atencion(Request $request)
  {      

    
    $list = Cmr::cmb_atencion($request);
    
   

    return response()->json($list);

  }





 protected function iniciar_chat_crm(Request $request)
  {      

    
    $list = Cmr::iniciar_chat_crm($request);
    
   

    return response()->json($list);

  }


 protected function iniciar_chat_crm_by_token(Request $request)
  {      

    
    $list = Cmr::iniciar_chat_crm_by_token($request);
    
   

    return response()->json($list);

  }




  
   protected function muestra_botones_configuracion(Request $request)
  {      

    
    $list = Cmr::muestra_botones_configuracion($request);
    
   

    return response()->json($list);

  }

  
  protected function list_atencion_mantenimiento(Request $request)
  {      

    
    $list = Cmr::list_atencion_mantenimiento($request);
    
   

    return response()->json($list);

  }






 protected function registrar_mensaje_chat(Request $request)
  {      


    if(empty($request->mensaje)){

        return $this->setRpta("error","Ingrese un texto");
    }

  $rpta = Cmr::registrar_mensaje_chat($request);

  if($rpta == 1){

         return $this->setRpta("ok","Se procesó correctamente");
  }

   return $this->setRpta("error","ocurrió un error al guardar");

  }



 protected function salvar_clases_mantenimiento(Request $request)
  {      


  $rpta = Cmr::salvar_clases_mantenimiento($request);

  if($rpta == 1){

         return $this->setRpta("ok","Se procesó correctamente");
  }

   return $this->setRpta("error","ocurrió un error al guardar");

  }

protected function list_atencion_mantenimiento_detalle(Request $request)
  {      

    
    $list = Cmr::list_atencion_mantenimiento_detalle($request);
    
    $data = array();


    foreach ($list as  $value) {
      
      $data[] = array(


         "CODIGO" => $value["CODIGO"],
          "DESCRIPCION" => $value["DESCRIPCION"],
           "ACTIVO" => $value["ACTIVO"],
            "DESC_READONLY" => true,
             "ESTADO_READONLY" => true,
             "CHECK_READONLY"=>true,
             "BTN_GRABAR"=>false,

             "FLAGTAREA"=>($value["FLAGTAREA"]==1)?true:false,
             "FLAGCONTRATO"=>($value["FLAGCONTRATO"]==1)?true:false,
             "FLAGNOCALIFICA"=>($value["FLAGNOCALIFICA"]==1)?true:false,

      );
    }

    return response()->json($data);

  }



  
  protected function confirmar_nco_submit(Request $request){

      $rpta = Cmr::confirmar_nco_submit($request);

            if( $rpta == 1){

              return $this->setRpta("ok","Se procesó correctamente");
            }

            return $this->setRpta("error","ocurrió un error");
    }


 protected function registrar_no_califica_tarea(Request $request){

      $rpta = Cmr::registrar_no_califica_tarea($request);

            if( $rpta == 1){

              return $this->setRpta("ok","Se procesó correctamente");
            }

            return $this->setRpta("error","ocurrió un error");
    }

 protected function salvar_tarea_nueva(Request $request){

      //valida
      
      $contacto = Carbon::parse($request->contactar)->format('Y-m-d');



      $hoy = Carbon::now()->format('Y-m-d');

      if(date_create($contacto)<date_create($hoy)){

        return $this->setRpta("error","La fecha de contacto tiene que ser mayor o igual a : ".$hoy);

      }

      $rpta = Cmr::salvar_tarea_nueva($request);

            if( $rpta == 1){

              return $this->setRpta("ok","Se procesó correctamente");
            }

            return $this->setRpta("error","ocurrió un error ");
    }

   protected function get_list_item_tarea(Request $request)
  {      

    
    $list = Cmr::get_list_item_tarea($request);
    
   

    return response()->json($list);

  }


  protected function modal_atencion_historial_tarea(Request $request)
  {      

    
    $list = Cmr::modal_atencion_historial_tarea($request);
    
   

    return response()->json($list);

  }
  
  
  
   protected function modal_otros_contratos(Request $request)
  {      

    
    $list = Cmr::modal_otros_contratos($request);
    
   

    return response()->json($list);

  }

   protected function get_list_item_tarea_tareas(Request $request)
  {      

    
    $list = Cmr::get_list_item_tarea_tareas($request);
    
   

    return response()->json($list);

  }

   protected function verPanel()
  {      


     $middleRpta = $this->valida_url_permisos(55);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    $empresa_user = Auth::user()->empresa;
    
      return View('cmr.panel',compact('empresa_user'));

  }

   protected function verAtencion()
  {      


      $middleRpta = $this->valida_url_permisos(58);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    $empresa_user = Auth::user()->empresa;
    
      return View('cmr.atencion',compact('empresa_user'));

  }

  protected function verContratoDirecto()
  {      


     $middleRpta = $this->valida_url_permisos(56);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    $empresa_user = Auth::user()->empresa;
    
    $departamentos = Maestro::list_departamento();

     $captacion = Maestro::list_captacion();

     //$vendedores = Captador::filter_vendedor();

     $vendedores= Cmr::vendendoresppv();
     
     $alerta_cap_ven = Cmr::alerta_cap_ven();

     $automaticox = Cmr::automatico_servicios_contratodirecto();
     
     $automatico = (isset($automaticox[0]["AUTOMATICO"]))?$automaticox[0]["AUTOMATICO"]:'N';

     $servicios = (isset($automaticox[0]["SERVICIOS"]))?$automaticox[0]["SERVICIOS"]:null;

     $alerta_vendedor= $alerta_cap_ven[0]["DNIVENDEDOR"];

     //$alerta_vendedor= '107532356';
     
     $alerta_captador=  $alerta_cap_ven[0]["DNICAPTADOR"];

    

     //$alerta_captador=  '002178842';
     
     $service_list = Maestro::cmb_servicios_filtro();

      return View('cmr.contrato_directo',compact('empresa_user','departamentos','captacion','vendedores',"alerta_vendedor","alerta_captador","automatico","servicios","service_list"));

  }


protected function valida_registro_contrato_directo($request){
       
        


        

        $rules = [
            
           
           'registroFullName'=> 'required',

           'vm_registro_captacion'=> 'required',
           'vm_registro_ficha'=> 'required',
           'vm_ultima_ficha'=> 'required',
           'vm_vendedor'=>'required',
           
           //'registroCelular'=> 'required',
           
           //'registroCorreo'=> 'email'
           
          

           
                      
            
        ];

        $messages = [

         
             'registroFullName.required' => 'Ingrese un Nombre.',
             'registroCelular.required' => 'Ingrese un Celular.',
             'registroCorreo.required' => 'Ingrese un Correo.',
             'registroCorreo.email' => 'Ingrese un Correo Valido.',

              'vm_registro_captacion.required' => 'Seleccione un medio.',
              'vm_registro_ficha.required' => 'Seleccione un tipo.',  
              'vm_ultima_ficha.required' => 'Seleccione una ficha.',
                'vm_vendedor.required' => 'Seleccione un vendedor.'
            

            
                 
            
        ];

        
        





         $validate = \Validator::make($request->all(),$rules,$messages);

         $validate->sometimes('registroCelular', 'required', function($input)
          {
            return empty($input->registroCorreo);
          });

         $validate->sometimes('registroCorreo', 'required|email', function($input)
          {
            return empty($input->registroCelular);
          });



         if ($validate->fails())
         {   
            
          

            return $this->setRpta("warning","Complete o Corrija los inputs marcados",$validate->messages() );

         }
        

       return $this->setRpta("ok",'valido inputs correctamente' ); 


    }

protected function salvar_registro_conrato_directo(Request $request){


      DB::beginTransaction();

      try {
        

        $valida_registro = $this->valida_registro_contrato_directo($request);

        
        if($valida_registro["status"]=="ok"){

            
            

           $rpta = Cmr::salvar_registro_conrato_directo($request);

                  if($rpta == 1){

                      DB::commit();

                      return $this->setRpta("ok",'Se procesó de manera correcta' ); 

                  }


                  DB::rollback();
          
                  return $this->setRpta("error",'No se pudo guardar el registro');

        }

        return $valida_registro;



      } catch (\Exception $e) {
          

          DB::rollback();

          return $this->setRpta("error",$e->getMessage());
      }
      
     
    
       

    } 


protected function get_planes_aseguradora($aseguradora){


$request = new Request();

$request->identificacion = $aseguradora;

//$request->identificacion = '20100041953';

$list = Maestro::list_planes($request);

return $list;


}


protected function get_servicios_crm_list($numero){


     $data_servicios =Cmr::get_data_info_servicios($numero);

     $data = array();

     foreach ($data_servicios as  $values) {


        $data[] =  array(
          "FLAGCONTRATADO"=>$values['FLAGCONTRATADO'],
          "SELECCIONA"=>false,
          "ACTIVO"=>$values['ACTIVO'],
          "CODIGO_SERVICIO"=>$values['CODIGO_SERVICIO'],
          "CODIGO_SERVICIO_ANL"=>$values['CODIGO_SERVICIO_ANL'],
         
          "COD_SERVICIO"=>$values['COD_SERVICIO'],
          "ID_COMPANIA_SEGURO"=>$values['ID_COMPANIA_SEGURO'],
          "LISTA_PRECIO"=>$values['LISTA_PRECIO'],
          "LISTA_PRECIO_ANL"=>$values['LISTA_PRECIO_ANL'],
          "LLAVE_ANUALIDAD"=>$values['LLAVE_ANUALIDAD'],
          "LLAVE_CONTRATACION"=>$values['LLAVE_CONTRATACION'],
          "MONEDA"=>$values['MONEDA'],
          "MONEDA_ANL"=>$values['MONEDA_ANL'],
          "NOM_SERVICIO"=>$values['NOM_SERVICIO'],
          "PORC_COBERTURA"=>$values['PORC_COBERTURA'],
          "PRECIO"=>$values['PRECIO'],
          "PRECIO_ANL"=>$values['PRECIO_ANL'],
          "PLAN"=>$values['CODIGO_PLAN'],
          "PLANES"=>$this->get_planes_aseguradora($values['ID_COMPANIA_SEGURO']),
          "ARRAY_CONTRATACION"=>Cmr::contratacion_combo_st($values['COD_SERVICIO']),
          "ARRAY_ANUALIDAD"=>Cmr::anualidad_combo_st($values['COD_SERVICIO']),

          "ARRAY_PRECIO_CON"=>Cmr::contratacion_combo_st_montos($values['COD_SERVICIO']),
          "ARRAY_PRECIO_ANUA"=>Cmr::anualidad_combo_st_montos($values['COD_SERVICIO']),
         

          );
        
       
     }


    return $data;


}


protected function valida_prospecto_cmr($request){


  //validamos contrato
  

    $tipo = $request->accion;

    if($tipo == 'G'){

        if(empty($request->rprospecto_identmadre)){

            return $this->setRpta("error","N° de identidad obligatorio para la madre");
        }

        if(empty($request->rprospecto_docmadre)){

          return $this->setRpta("error","Tipo de documento obligatorio para la madre");
        }

        if(empty($request->rprospecto_sexomadre)){

            return $this->setRpta("error","Sexo obligatorio para la madre");
        }

        if(empty($request->rprospecto_patmadre)){

           return $this->setRpta("error","Apellido Paterno obligatorio para la madre");
        }

        // if(empty($request->rprospecto_matmadre)){

        //   return $this->setRpta("error","Apellido Materno obligatorio para la madre");
        // }

        if(empty($request->rprospecto_nommadre)){

           return $this->setRpta("error","Nombres obligatorio para la madre");
        }

        if(empty($request->rprospecto_dirmadre)){

           return $this->setRpta("error","Dirección obligatoria para la madre");
        }

        if(empty($request->rprospecto_pais_madre)){

           return $this->setRpta("error","Pais obligatorio para la madre");
        }

        if(empty($request->rprospecto_ubgmadre)){

           return $this->setRpta("error","Ubigeo obligatorio para la madre");
        }

        if(is_null($request->rprospecto_civmadre)){

           return $this->setRpta("error","Estado Civil obligatorio para la madre");
        }

         if(empty($request->rprospecto_nacmadre)){

           return $this->setRpta("error","Fecha Nacimiento obligatoria para la madre");
        }

         if(empty($request->rprospecto_movil1madre) && empty($request->rprospecto_email1madre) ){

           return $this->setRpta("error","Correo o Celular obligatorio para la madre");
        }


        $mama_soltera = $request->rprospecto_msoltera;


        if($mama_soltera == 0){
          //padre 
          //
            $valida_dental = Cmr::get_data_dental_servicio($request->rprospecto_prospecto);

              if($valida_dental == 0 ){


                  if(empty($request->rprospecto_identpadre)){

                    return $this->setRpta("error","N° de identidad obligatorio para el padre");
                  }

                  if(empty($request->rprospecto_docpadre)){

                    return $this->setRpta("error","Tipo de documento obligatorio para el padre");
                  }

                  if(empty($request->rprospecto_sexopadre)){

                      return $this->setRpta("error","Sexo obligatorio para el padre");
                  }

                  if(empty($request->rprospecto_patpadre)){

                     return $this->setRpta("error","Apellido Paterno obligatorio para el padre");
                  }

                 

                  if(empty($request->rprospecto_nompadre)){

                     return $this->setRpta("error","Nombres obligatorio para el padre");
                  }

                  if(empty($request->rprospecto_dirpadre)){

                     return $this->setRpta("error","Dirección obligatoria para el padre");
                  }

                  if(empty($request->rprospecto_pais_padre)){

                     return $this->setRpta("error","Pais obligatorio para el padre");
                  }

                  if(empty($request->rprospecto_ubgpadre)){

                     return $this->setRpta("error","Ubigeo obligatorio para el padre");
                  }

                  if(is_null($request->rprospecto_civpadre)){

                     return $this->setRpta("error","Estado Civil obligatorio para el padre");
                  }

                   if(empty($request->rprospecto_nacpadre)){

                     return $this->setRpta("error","Fecha Nacimiento obligatoria para el padre");
                  }

                   if(empty($request->rprospecto_movil1padre) && empty($request->rprospecto_email1padre) ){

                     return $this->setRpta("error","Correo o Celular obligatorio para el padre");
                  }

              }

            



        }

        //datos cabecera
        
            if(empty($request->rprospecto_captacion)){

               return $this->setRpta("error","Seleccione un medio de captación");
            }

            if(empty($request->rprospecto_tipo)){

               return $this->setRpta("error","Seleccione un tipo de captación");
            }

            if(empty($request->rprospecto_nroFicha)){

               return $this->setRpta("error","Seleccione una ficha de captación");
            }

            if(empty($request->rprospecto_fechaAprox)){

               return $this->setRpta("error","Ingrese una fecha de parto");
            }

            if(empty($request->rprospecto_clinica)){

               return $this->setRpta("error","Seleccione una institución");
            }

              if(empty($request->rprospecto_medico)){

               return $this->setRpta("error","Seleccione un médico");
            }

            if(empty($request->rprospecto_contratado)){

               return $this->setRpta("error","Seleccione una ciudad");
            }

             if($request->rprospecto_tipoParto<=0 || !is_numeric($request->rprospecto_tipoParto)){

               return $this->setRpta("error","Cantidad de contratos min 1 ");
            }



    }


 return $this->setRpta("ok","Se procesó correctamente");


}

 protected function save_prospecto_detalle_cmr($request,$num_prospecto){



   

    $data = $request->servicios;

    


   

    foreach($data as $list){

        if($list["CODIGO_SERVICIO"]!=null || $list["CODIGO_SERVICIO_ANL"]!=null){


            $rpta = Cmr::save_prospecto_detalle_cmr($list,$num_prospecto);

            if($rpta ==0 ){

              return $this->setRpta("error","Error al guardar servicios");
            }


        }
        
                  
      }


    return $this->setRpta("ok","");


  }

protected function genera_contrato_cmr($request,$num_prospecto){




 $data = $request->servicios;


  
  //valida existencia de codigos en caso exista tabla
    

    if(count($data)>0){

      $selecciona = false;

      $middlerpta = false;
      



      foreach ($data as  $value) {

          if($value["SELECCIONA"]){

              $selecciona = true;
          }
      }


      if(!$selecciona){

        return $this->setRpta("error","Marque algun servicio de la lista");
      }



      foreach ($data as  $value) {


          if($value["SELECCIONA"]){


            if($value["CODIGO_SERVICIO"]==null || $value["CODIGO_SERVICIO_ANL"]==null){

                $middlerpta = true;
            }

          }
          
      }


      if($middlerpta){

        return $this->setRpta("error","Seleccione un servicio de contratacion y/o anualidad");
      }



    }else{


        return $this->setRpta("error","No hay registros seleccionados");

    }

    //fin valida


    foreach($data as $list){


        if($list["SELECCIONA"]){



              $rpta = Cmr::genera_contrato_cmr($list,$num_prospecto,$request);

              $mensaje = $rpta[0];

             

                 if($mensaje!=null){

                      return $this->setRpta("error",$mensaje);

                }
         }

        
                  
      }



      return $this->setRpta("ok","Se generó contrato exitosamente");

    

}


protected function save_prospecto_cmr(Request $request){      

    DB::beginTransaction();

        try {

           $tipo = $request->accion;

           $valida_prospecto = $this->valida_prospecto_cmr($request);
           
           if($valida_prospecto["status"] == "ok"){

               
                $rpta = Cmr::save_prospecto_cmr($request);
            
                    if($rpta){



                        $detail =  $this->save_prospecto_detalle_cmr($request,$rpta);

                        if($detail["status"] == "ok"){

                          if($tipo == 'G'){

                              //genera contrato
                              
                              $generaContrato = $this->genera_contrato_cmr($request,$rpta);

                              if($generaContrato["status"]=="ok"){

                                 DB::commit();

                                    return $this->setRpta("ok","Se generó contrato exitosamente",array($rpta));



                              }else{

                                 DB::rollback();

                                return $generaContrato;


                              }

                          }



                          DB::commit();

                          return $this->setRpta("ok","Se procesó correctamente : ".$rpta,array($rpta));


                        }

                        DB::rollback();

                        return $detail;
                    

                      }
          
                      DB::rollback();

                      return $this->setRpta("error","Ocurrió un error al guardar");

           }

           return $valida_prospecto;
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }

    

  }


protected function enviar_correo_actualiza_ficha(Request $request){

    $prospecto = $request->prospecto;

    $cia = Auth::user()->empresa;

    $vendedor = Auth::user()->email;

    $madre = trim($request->madre);

    $padre = trim($request->padre);

    $familia =  trim($request->familia);

    $soltera = $request->madre_soltera;

    //valida correos
    
    


    // if(!filter_var($madre, FILTER_VALIDATE_EMAIL)) {
       
    //    return $this->setRpta('error','Ingrese un correo valido para la madre');
    // }

    // if($soltera == 0){

    //    if(!filter_var($padre, FILTER_VALIDATE_EMAIL)) {
       
    //     return $this->setRpta('error','Ingrese un correo valido para el padre');
    //    }

    // }else{

    //   $padre = $madre;

    // }
    


    $empresa = ($cia=='001')?'CRIOCORD':'LAZO DE VIDA';

    $token = base64_encode($prospecto.'|'.$cia);

    $parametros = array(

      'empresa'=>$empresa,
      'cia'=>$cia,
      'familia'=> $familia,
      'vendedor'=>$vendedor,
      'correo_madre'=>$madre,
      'correo_padre'=>$padre,
      'token'=>$token,
      'prospecto'=>$prospecto

    );



    if( config("global.production") ){

              
        $client = new \GuzzleHttp\Client();


          $response = $client->post('http://sistemas.criocord.com.pe:8088/confirma_datos_ictc/ServiceLead.php',
              ['body' => json_encode(
                  [
                      'data' => $parametros
                  ]
              )]
          );
              
  
          $response = $response->getBody()->getContents();

          $response = json_decode($response,true);

      
          return $this->setRpta($response["success"],$response["message"],$response["aaData"]);




        }else{


           return $this->setRpta('ok','enviado satisfactoriamente','');

        }

    

  
}



 protected function verProspecto($numero,$str)
  {      


    $str = base64_decode($str);

    $str = explode('|', $str);

    

    $item_get = $str[0];
    $atencion_get = $str[1];
    $clasificacion_get = $str[2];
    $comentario_get = $str[3];


    $boton_generar = $str[4];
    
    $mostrar_botonera = $str[5];

    $empresa_user = Auth::user()->empresa;

    $dni_usuario = Auth::user()->identificacion;
    //$list = Cmr::get_data_prospecto($numero);
    
    $estado_civil = Maestro::list_estado_civil();

    $paises = Maestro::list_paises();

   

    $departamentos = Maestro::list_departamento();

    $tipo_documento = Maestro::list_tipo_documento();

 

    $aseguradora = Maestro::list_aseguradora();

    $medios_prop= Maestro::list_captacion();
    

     if(!empty($numero)){

      $data_prospecto =Prospecto::get_data_info($numero);

      //$data_servicios =Prospecto::get_data_info_servicios($numero);

      //$data_servicios =Cmr::get_data_info_servicios($numero);

      $data_servicios = $this->get_servicios_crm_list($numero);
    }
    


    //combos servicios sangre y tejido anualidad
    

    $contratacion_sangre = Cmr::contratacion_combo_st('S');
    $contratacion_tejido = Cmr::contratacion_combo_st('T');
    $anualidad_sangre = Cmr::anualidad_combo_st('S');
    $anualidad_tejido = Cmr::anualidad_combo_st('T');

    $dental = Cmr::get_data_dental_servicio($numero);

   

      return View('cmr.ver_prospecto',compact('empresa_user','estado_civil','paises','departamentos','tipo_documento','aseguradora','medios_prop','numero','data_prospecto','data_servicios','item_get','atencion_get','clasificacion_get','comentario_get','contratacion_sangre','contratacion_tejido','anualidad_sangre','anualidad_tejido','dni_usuario','boton_generar','mostrar_botonera','dental'));
   

  }
    
  
    

 protected function confirma_asignacion_submit(Request $request){      

        $rpta = Cmr::confirma_asignacion_submit($request);

        if( $rpta == 1){

           return $this->setRpta("ok","Procesado exitosamente");

        }

        return $this->setRpta("error","Ocurrió un error al guardar");
    

    }

     protected function vendedor_list(Request $request)
  {      

    $data  = array();


    $list = Cmr::vendedor_list($request);
    
    foreach ($list as  $value) {
        
        $data[] = array(

            "SELECCIONA"=>false,
            "NO_CIA"=> $value["NO_CIA"],
            "NUMERO_PROSPECTO"=> $value["NUMERO_PROSPECTO"],
            "FECHA_NOC"=> $value["FECHA_NOC"],
            "NOMBRES"=> $value["NOMBRES"],
            "MEDIO_DE_CAPTACION"=> $value["MEDIO_DE_CAPTACION"],
            "MEDIO_CAPTACION_MSG"=> $value["MEDIO_CAPTACION_MSG"],
            "TIPO_ATENCION"=> $value["TIPO_ATENCION"],
            "TIPO_ATENCION_MSG"=> $value["TIPO_ATENCION_MSG"],
            "VENDEDOR"=> $value["VENDEDOR"],
            "TAREAS"=> $value["TAREAS"],
            "FECHA_ASIGNADO"=> $value["FECHA_ASIGNADO"]





        );



    }

    return response()->json($list);

  }


       protected function reasignarView()
  {      


     $middleRpta = $this->valida_url_permisos(59);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    $empresa_user = Auth::user()->empresa;
      

       $request = new Request();

     $request->q="";


     $captadores = Maestro::filter_captador($request);

     

     $medios = Cmr::medios_crm();

    
     $vendedores = Captador::filter_vendedor();
     
     
    

      return View('cmr.reasignar',compact('empresa_user','captadores','medios','vendedores'));

  }

  


  protected function mis_prospectos()
  {      


     $middleRpta = $this->valida_url_permisos(60);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    $empresa_user = Auth::user()->empresa;
      

      $tipo_prospecto = 'HISPROS';

      $btn_prospecto_nuevo =false;

     

   
     
     
    

      return View('cmr.mis_prospectos',compact('tipo_prospecto','btn_prospecto_nuevo','empresa_user'));

  }



   protected function vencidos_por_perder()
  {      


     $middleRpta = $this->valida_url_permisos(65);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    $empresa_user = Auth::user()->empresa;
    
    $vendedores= Cmr::vendendoresppv();

    $captadores= Cmr::captadoresppv();


      $vendedores_modal = Captador::filter_vendedor();

      return View('cmr.vencidos_por_perder',compact('empresa_user','vendedores','captadores','vendedores_modal'));

  }


  protected function prospectos_vencidos_por_perder(Request $request){


      $list = Cmr::prospectos_vencidos_por_perder($request);
    
   

   $data = array();

    foreach ($list as $key => $value) {
     
      $data[] = array(


                "SELECCIONA"=>false,
                "TOKEN"=>$value["TOKEN"],
                "NO_CIA"=>$value["NO_CIA"],
                "NUMERO_PROSPECTO"=>$value["NUMERO_PROSPECTO"],
                "ITEM"=>$value["ITEM"],
               
                "FECHA_CONTACTO_DATE "=>$value["FECHA_CONTACTO_DATE"],
                "FECHA_CONTACTO "=>$value["FECHA_CONTACTO"],
                "TAREAS"=>$value["TAREAS"],
                "FECHA_ASIGNADO"=>$value["FECHA_ASIGNADO"],
                "CONTACTO"=>$value["CONTACTO"],
                "GRUPO" =>$value["GRUPO"],
                "FECHA_PARTO"=>$value["FECHA_PARTO"],
                //"MEDIO_CAPTACION"=>$value["MEDIO_CAPTACION"],
                "MEDIOCAPTACIONMSG"=>$value["MEDIOCAPTACIONMSG"],
                "CAPTADOR"=>$value["CAPTADOR"],
                "VENDEDOR"=>$value["VENDEDOR"]


              );

    }

    return response()->json($data);
    

  }

  protected function confirma_asignacion_submit_ppperder(Request $request){


    $rpta = Cmr::confirma_asignacion_submit_ppperder($request);

    if($rpta == 1){

          return $this->setRpta("ok","Se procesó correctamente");
    }

    return $this->setRpta("error","ocurrió un error al guardar");

  }

  


  


}