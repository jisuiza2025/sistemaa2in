<?php


namespace App\Http\Controllers\Contrato; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Contrato;
use App\Maestro;
use Auth;
use PDF;
use Carbon\Carbon;
use App\Bitacora;

use App\Http\Controllers\Mantenimiento\CorreoController;
use App\Http\Controllers\Reporte\ReporteController;


use App\Http\Controllers\Mantenimiento\WordController;
use App\Http\Controllers\Mantenimiento\CloudConvertController;

class ContratoController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    

      public function reportes_incentivos(){

      $middleRpta = $this->valida_url_permisos(76);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


     $empresa_user = Auth::user()->empresa;

 

     $lsta_servicios = Maestro::cmb_servicios_filtro();

     $lsta_tipos = Maestro::list_cor_tablas(29);

      return View('reporte.contrato.incentivo',compact('empresa_user','lsta_servicios','lsta_tipos'));


    }


    protected function get_reporte_incentivo_contrato(Request $request){


        $list = Contrato::get_reporte_incentivo_contrato($request);

      return response()->json($list);


    }

    

    protected function get_responsable_contrato_incentivo(Request $request){


        $list = Contrato::get_responsable_contrato_incentivo($request);

      return response()->json($list);


    }


    public function index()
	{      

		$middleRpta = $this->valida_url_permisos(20);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    	$responsables = Maestro::filter_responsable();   

    	$empresa_user = Auth::user()->empresa;

    	$situacion = Maestro::situacion_contratos(); 

    	$motivos = Maestro::motivos_contratos(); 

    	$estados = Maestro::estados_contratos(); 
    	
    	$user_codigo =  Auth::user()->codigo;


        $user_dni = Auth::user()->identificacion;


    	$user_fullname = ucwords(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);


        $btn_estado_cuenta = $this->botones_usuario('contrato_ecuenta');
        $btn_sol_desvincula = $this->botones_usuario('contrato_desvincula');
        $btn_facturacion = $this->botones_usuario('contrato_facturacion');





        $btn_envia_correo_ecuenta = $this->botones_usuario('cont_env_ecta');
        $btn_envia_correo_contrato = $this->botones_usuario('cont_env_correo');
        $btn_anula_contrato = $this->botones_usuario('cont_anular');


        $btn_confirma_desv = $this->botones_usuario('cont_confirma_desv');
        $btn_imprime_cont = $this->botones_usuario('cont_imprime_acu');
        $btn_imprime_ficha = $this->botones_usuario('cont_imprime_fic');

    	$btn_firma_dig = $this->botones_usuario('con_firma_dig');

        $request = new Request();

        $request->request->add(['q' =>  $user_dni ]);

        $vendedor_logeado = Maestro::filter_vendedor($request);

        if(count( $vendedor_logeado)>0){

            $dni_vendedor = $vendedor_logeado[0]["id"];

        }else{

            $dni_vendedor='';
        }

        $tipo_rep = Maestro::list_cor_tablas(15); 

		return View('contrato.index',compact('responsables','empresa_user','situacion','motivos','estados','user_codigo','user_fullname','btn_estado_cuenta','btn_sol_desvincula','btn_facturacion','btn_envia_correo_ecuenta','btn_envia_correo_contrato','btn_anula_contrato','vendedor_logeado','dni_vendedor','btn_confirma_desv','btn_imprime_cont','btn_imprime_ficha','tipo_rep','btn_firma_dig'));
		

	}	

	public function editar_contrato($contrato)
	{      

    
    	$empresa_user = Auth::user()->empresa;

    	$estados_contratos = Maestro::estados_contratos();

    	$info = Contrato::get_info_contrato(trim($contrato));

    	$costos = Contrato::get_info_costos(trim($contrato));

     
       //var_dump($info);
        //exit;
    	//para modal de cliente
        
        $categorias = Maestro::list_categoria();

        $documentos = Maestro::list_tipo_documento();

        $ecivil     = Maestro::list_estado_civil();

        $ocupacion  = Maestro::list_ocupacion();

        $paises = Maestro::list_paises();

        $departamentos = Maestro::list_departamento();


        $btn_acuerdo_correo = $this->botones_usuario('contrato_acuerdo');

        $prop_info_laboratorio=Contrato::get_info_laboratorio(trim($contrato));

        //return var_dump($prop_info_laboratorio);
        //exit;

        $medico_prop=Contrato::get_info_medico(trim($contrato));


        $carta_notarial = Contrato::obtener_documento_notarial($contrato);

        
        $situacion_prop = Maestro::situacion_contratos();

        $desvinculados = Contrato::get_detalle_desvinculados($contrato);

		return View('contrato.editar',compact('empresa_user','estados_contratos','contrato','info','costos','categorias','documentos','ecivil','ocupacion','paises','departamentos','btn_acuerdo_correo','prop_info_laboratorio','medico_prop','carta_notarial','situacion_prop','desvinculados'));
		

	}	
	



protected function imprimir_carta_notarial($documento){

$path = public_path().'/cartas_notarial/'.$documento;


            if(file_exists($path)){



                return response()->download($path);

                

            }
 

}



protected function valida_impresion_acuerdo_desv(Request $request){


    $contrato = $request->contrato;

    $variable = $request->variable;

    $middleRpta =  $this->valida_impresion_contrato($variable,$contrato);

    if($middleRpta["status"]=="ok"){

        return $this->imprimir_acuerdo_solicitud_desv($contrato,$variable);
    }

    return $middleRpta;

}

public function descargar_plantilla_pdf($file,$contrato){



    $pathtoFilePago = public_path().'/cloudConvert/'.$file;
    
        if (file_exists($pathtoFilePago)) {
            

        
            $word = new WordController;

            $word->deleteFileWordTemporal($file);
                
            
            $outName ="IMPRESION_PLANTILLA_".$contrato.".pdf";


             $headers = [

                'Content-Type' => 'application/pdf',

            ];

            return response()->download($pathtoFilePago, $outName, $headers)->deleteFileAfterSend(true);



        } else {
        
            return $this->redireccion_404();
        }



}

public function descargar_archivo_desvinculacion_pdf(Request $request)
{
    
    // Descargar archivo PDF
    return response()->download(storage_path($request->ruta));
}

protected function valida_impresion_contrato_imprime(Request $request){


    $contrato = $request->contrato;

    
    $middleRpta =  $this->valida_impresion_contrato('CON',$contrato);

    if($middleRpta["status"]=="ok"){

        $genera = $this->genera_contrato_impresion($contrato,'CON');

        return $genera;
    }

    return $middleRpta;


}
protected function valida_constancia_impresion(Request $request){
    $contrato = $request->contrato;

    
    $middleRpta =  $this->valida_impresion_contrato('CONS',$contrato);

    if($middleRpta["status"]=="ok"){

        $genera = $this->genera_constancia_impresion($contrato,'CONS');

        return $genera;
    }

    return $middleRpta;

} 
 

protected function get_correo_estado_cuenta_cliente(Request $request){

    $cia = Auth::user()->empresa;
    $config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();

    $destinatarios = $this->set_contactos_acuerdo_desv_pdf($request->num_contrato);

    if($destinatarios["status"] == "error"){

        return "error";
    }
    $correos=array_keys($destinatarios["data"]);
   
    return  $this->setRpta("ok","Valido correos contrato",$correos);
}

protected function envia_correo_estado_cuenta_cliente(Request $request){


        $cia = Auth::user()->empresa;

        $config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();

    
        $destinatarios = $this->set_contactos_acuerdo_desv_pdf($request->num_contrato);


        if($destinatarios["status"] == "error"){

            return $destinatarios;
        }
        

       

        $reporte = new ReporteController;

        $formato = $reporte->reporte_archivo_contrato_estado_cuenta($request);



        $mensaje = 'Estimada Familia :'.$request->familia;


        $parametros = array(

                      "cia"          => $cia,
                      "config"       => $config,
                      "destinatarios"=> $destinatarios["data"],
                      "formato"      => $formato,
                      "mensaje_sp"   => $mensaje
                  );


       $correo = new CorreoController;

       return $correo->envia_correo_estado_cuenta_cliente($parametros);

    



}


protected function valida_impresion_contrato($variable,$contrato){


    

    $filespnamex = Contrato::solicitar_desvinculacion_contrato_get_file($variable,$contrato);

           

    if(empty($filespnamex)){

                
         return $this->setRpta('error','No se encontro la plantilla para el contrato : '.$contrato);

    }else{


        $file_path = (Auth::user()->empresa == '001')? public_path().'/formatos_nuevos/ICTC/'.$filespnamex: public_path().'/formatos_nuevos/LAZO_DE_VIDA/'.$filespnamex;


        if(file_exists($file_path)){

         return $this->setRpta('ok','');


        }else{

            return $this->setRpta('error','No se encontro la ruta de la plantilla  : '.$filespnamex);

        }

    }


}


protected function envia_correo_contrato_cliente(Request $request){

  

  

        $cia = Auth::user()->empresa;

        $config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();

        


        $existencia_plantilla = $this->valida_impresion_contrato('CON',$request->contrato);



        if($existencia_plantilla["status"] == "error"){

            return $existencia_plantilla;
        }


        $formato_rpta = $this->genera_contrato_impresion($request->contrato,'CON');



        if($formato_rpta["status"] == "error"){

            return $formato_rpta;
        }
       
        
        $formato = public_path().'/cloudConvert/'.$formato_rpta["data"];


        //ELIMINACION FILE WORD TEMPORAL
        

        $word = new WordController;

        $word->deleteFileWordTemporal($formato_rpta["data"]);



        $destinatarios = $this->set_contactos_acuerdo_desv_pdf($request->contrato);



        if($destinatarios["status"] == "error"){

            return $destinatarios;
        }

        

        $mensaje = 'Estimada Familia :'.$request->familia;


        $parametros = array(

                      "cia"          => $cia,
                      "config"       => $config,
                      "destinatarios"=> $destinatarios["data"],
                      "formato"      => $formato,
                      "mensaje_sp"   => $mensaje
                  );


       $correo = new CorreoController;

       return $correo->envia_acuerdo_contrato_cliente($parametros);




}
    

    protected function genera_contrato_impresion($contrato,$variable){   

      
        
         

        try {




           $documento = Contrato::solicitar_desvinculacion_contrato_get_file($variable,$contrato);

           
           $plantilla = (Auth::user()->empresa == '001')? public_path().'/formatos_nuevos/ICTC/'.$documento: public_path().'/formatos_nuevos/LAZO_DE_VIDA/'.$documento;


            $word = new WordController;

        
            $middleRpta = $word->generacion_contrato($plantilla,$contrato,$variable);
            

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


    protected function genera_constancia_impresion($contrato,$variable){   

      
        
         

        try {




           $documento = Contrato::solicitar_desvinculacion_contrato_get_file($variable,$contrato);

           
           $plantilla = (Auth::user()->empresa == '001')? public_path().'/formatos_nuevos/ICTC/'.$documento: public_path().'/formatos_nuevos/LAZO_DE_VIDA/'.$documento;


            $word = new WordController;

        
            $middleRpta = $word->generacion_plantilla_constancia_viabilidad($plantilla,$contrato,$variable);
            

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
    

	protected function list_ubicacion_almacenaje(Request $request){   


		$list = Contrato::list_ubicacion_almacenaje($request);

		return response()->json($list);
		

	} 

	protected function list_contratos(Request $request){   

		$list = Contrato::list_contratos($request);

        //indicadores del mes
        
        $indicadores =$this->list_contratos_indicadores();



		return response()->json(array($list,$indicadores));
		

	} 


    protected function list_contratos_indicadores(){   

        
        $list = Contrato::list_contratos_indicadores();


        $json = json_decode(json_encode($list),true);

      

        $contrato_mes_sangre = $json[0]['CONTRATO_MES_SANGRE'];
        $contrato_mes_tejido = $json[0]['CONTRATO_MES_TEJIDO'];
        $contrato_mes_dental = $json[0]['CONTRATO_MES_DENTAL'];


        $contrato_con_mes_sangre = $json[0]['CONTRATO_CON_MES_SANGRE'];
        $contrato_con_mes_tejido = $json[0]['CONTRATO_CON_MES_TEJIDO'];
        $contrato_con_mes_dental = $json[0]['CONTRATO_CON_MES_DENTAL'];


        $contrato_pro_sangre = $json[0]['CONTRATO_PRO_SANGRE'];
        $contrato_pro_tejido = $json[0]['CONTRATO_PRO_TEJIDO'];
        $contrato_pro_dental = $json[0]['CONTRATO_PRO_DENTAL'];


                    $contrato_mes_adn = $json[0]['CONTRATO_MES_ADN'];
                    $contrato_mes_tamizaje = $json[0]['CONTRATO_MES_TAMIZAJE'];
                    $contrato_mes_fibroplastos = $json[0]['CONTRATO_MES_FIBROPLASTOS'];
                    $contrato_mes_nip = $json[0]['CONTRATO_MES_NIP'];
                    $contrato_mes_pnatal = $json[0]['CONTRATO_MES_PNATAL'];



                     $contrato_pro_adn = $json[0]['CONTRATO_PRO_ADN'];
                     $contrato_pro_tamizaje = $json[0]['CONTRATO_PRO_TAMIZAJE'];
                     $contrato_pro_fibroplastos = $json[0]['CONTRATO_PRO_FIBROPLASTOS'];
                     $contrato_pro_nip= $json[0]['CONTRATO_PRO_NIP'];
                     $contrato_pro_pnatal= $json[0]['CONTRATO_PRO_PNATAL'];



                     $contrato_con_mes_adn = $json[0]['CONTRATO_CON_MES_ADN'];
                     $contrato_con_mes_tamizaje =$json[0]['CONTRATO_CON_MES_TAMIZAJE'];
                     $contrato_con_mes_fibroplastos = $json[0]['CONTRATO_CON_MES_FIBROPLASTOS'];
                     $contrato_con_mes_nip =$json[0]['CONTRATO_CON_MES_NIP'];
                     $contrato_con_mes_pnatal= $json[0]['CONTRATO_CON_MES_PNATAL'];




        return array(
                      $contrato_mes_sangre,
                      $contrato_mes_tejido,
                      $contrato_mes_dental,
                      
                      $contrato_con_mes_sangre,
                      $contrato_con_mes_tejido,
                      $contrato_con_mes_dental,

                      $contrato_pro_sangre,
                      $contrato_pro_tejido,
                      $contrato_pro_dental,


                       $contrato_mes_adn ,
                    $contrato_mes_tamizaje ,
                    $contrato_mes_fibroplastos ,
                    $contrato_mes_nip ,
                    $contrato_mes_pnatal ,



                     $contrato_pro_adn ,
                     $contrato_pro_tamizaje ,
                     $contrato_pro_fibroplastos ,
                     $contrato_pro_nip,
                     $contrato_pro_pnatal,



                     $contrato_con_mes_adn,
                     $contrato_con_mes_tamizaje,
                     $contrato_con_mes_fibroplastos,
                     $contrato_con_mes_nip,
                     $contrato_con_mes_pnatal,





                    );



    } 


	protected function confirmar_desvinculacion(Request $request){   

		$rpta = Contrato::confirmar_desvinculacion($request);

		 if($rpta == 1){ 

            return $this->setRpta("ok","Se procesó correctamente"); 

        }
     
		return $this->setRpta("error","Ocurrió un error"); 

	} 

	
	protected function save_edit_contrato(Request $request){   

		$rpta = Contrato::save_edit_contrato($request);

		 if($rpta == 1){ 

            return $this->setRpta("ok","Se procesó correctamente"); 

        }
     
		return $this->setRpta("error","Ocurrió un error"); 

	} 


	protected function solicitar_desvinculacion_contrato(Request $request){   

		$rpta = Contrato::solicitar_desvinculacion_contrato($request);

		 if($rpta == 1){ 

            return $this->setRpta("ok","Se procesó correctamente"); 

        }
     
		return $this->setRpta("error","Ocurrió un error"); 

	} 


	protected function generateRandomString($length) {


        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }

    protected function enviar_acuerdo_desv_df_correo(Request $request){

    	
    	$cia = Auth::user()->empresa;

        


        $config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();


        //valida exisstencia de plantilla
        


        $existencia_plantilla = $this->valida_impresion_contrato($request->variable,$request->contrato);



        if($existencia_plantilla["status"] == "error"){

            return $existencia_plantilla;
        }



        $destinatarios = $this->set_contactos_acuerdo_desv_pdf($request->contrato);

        if($destinatarios["status"] == "error"){

        	return $destinatarios;
        }

        $formato_rpta = $this->imprimir_acuerdo_solicitud_desv($request->contrato,$request->variable);



        if($formato_rpta["status"] == "error"){

            return $formato_rpta;
        }
       
        
        $formato = public_path().'/cloudConvert/'.$formato_rpta["data"];


        //ELIMINACION FILE WORD TEMPORAL
        

        $word = new WordController;

        $word->deleteFileWordTemporal($formato_rpta["data"]);
        


        $mensaje = $this->set_mensaje_correo_solicitud_desv($request->contrato);


    	$parametros = array(

                      "cia"          => $cia,
                      "config"       => $config,
                      "destinatarios"=> $destinatarios["data"],
                      "formato"      => $formato,
                      "mensaje_sp"   => $mensaje
                  );


       $correo = new CorreoController;

       return $correo->envia_acuerdo_solicitud_desv($parametros);
    }

    protected function set_mensaje_correo_solicitud_desv($contrato){

    	$cia = Auth::user()->empresa;

    	$query = DB::select("SELECT CASE WHEN T0.MAMA_SOLTERA ='N' THEN  T2.APATERNO || ' ' ||T1.APATERNO ELSE T1.APATERNO || ' ' ||T1.AMATERNO END AS FAMILIA FROM VEN_CONTRATOS T0
    INNER JOIN VEN_CLIENTES T1 ON T0.NO_CIA = T1.NO_CIA AND T0.IDENTIFICACION1 = T1.IDENTIFICACION
    LEFT JOIN VEN_CLIENTES T2 ON T0.NO_CIA = T2.NO_CIA AND T0.IDENTIFICACION2 = T2.IDENTIFICACION WHERE T0.NO_CIA=? AND T0.NUMERO_CONTRATO=?" ,array($cia,$contrato));


    	$json = json_decode(json_encode($query),true);

    	$familia = (isset($json[0]['familia'])) ? $json[0]['familia']:'';

    	return 'Estimada familia : '.strtoupper($familia);

    }


    protected function set_contactos_acuerdo_desv_pdf($contrato){

    	$request = new Request();

    	$request->contrato = $contrato;

    	$list = Bitacora::get_contactos($request);

    	$data = array();


    	foreach ($list as $value) {
    		
    		if(!empty($value["MAIL_CONTACTO"])){

    			$data[$value["MAIL_CONTACTO"]] = $value["NOMBRE"];
    		}
    	}

    	if(count($data) == 0){

    		return $this->setRpta("error","El contrato no tiene correos asociados"); 
    	}

    	return $this->setRpta("ok","Valido correos contrato",$data);
    	 

    }

	protected function imprimir_acuerdo_solicitud_desv($contrato,$variable){   

	
		
		try {


           //$variable = 'DESV';

            
           $documento = Contrato::solicitar_desvinculacion_contrato_get_file($variable,$contrato);

           
           $plantilla = (Auth::user()->empresa == '001')? public_path().'/formatos_nuevos/ICTC/'.$documento: public_path().'/formatos_nuevos/LAZO_DE_VIDA/'.$documento;


            $word = new WordController;

        
            $middleRpta = $word->generacion_desvinculacion($plantilla,$contrato);


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

	



protected function confirmar_eliminacion_contrato(Request $request){   

        $rpta = Contrato::confirmar_eliminacion_contrato($request);

         if($rpta == 1){ 

            return $this->setRpta("ok","Se eliminó correctamente"); 

        }
     
        return $this->setRpta("error","Ocurrió un error"); 

    } 

	


	
	



    
}
