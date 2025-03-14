<?php


namespace App\Http\Controllers\Anualidad; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\Facturacion;
use App\Cliente;
use App\TipoCambio;
use App\RegistroPago;
use Carbon\Carbon;
use Auth;


class RegistroPagoController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
  
    public function index($params)
	{      

        $middleRpta = $this->valida_url_permisos(22);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

        $base64 = base64_decode($params);

        $list_params = explode("&",$base64);

        $cliente_init = str_replace("cliente=","",$list_params[0]);

        $contrato_init = str_replace("contrato=","",$list_params[1]);

        $documentos = str_replace("documentos=","",$list_params[2]);
        
        $tipo_doc = str_replace("tdoc=","",$list_params[3]);

        
      

        $list_documentos = array();

        $fullnamecliente = '';

        if($documentos!="0"){

            //proviene desde facturacion 
           

            $list_documentos = $this->set_documentos_liquidar_asistente_facturacion($documentos);
            
        }

        

        if($cliente_init!=="0"){

            $request = new Request();

            $request->documento = $cliente_init;

            $cliente_query = Cliente::search_cliente($request);

            $fullnamecliente = $cliente_query[0]['NOMBRE'];

            
            
        }

       

        $empresa_user  = Auth::user()->empresa;

        $valida_cambio   = Facturacion::valida_tipo_cambio();
        $medio_pago      = Maestro::get_list_medios_pago();
        $factor_cambio   = TipoCambio::get_tipo_cambio();
        $monedas         = Maestro::list_monedas();
        $bancos          = Maestro::get_list_bancos();
        $tipo_documentos = Maestro::get_list_tipo_documentos();
        
        $factor_cambio = $factor_cambio[0]['FACTOR_CAMBIO'];
        
		return View('registro_pago.index',compact('empresa_user','valida_cambio','medio_pago','factor_cambio','monedas','bancos','tipo_documentos','cliente_init','contrato_init','fullnamecliente','list_documentos','tipo_doc'));	

	}

	
    
    

    protected function set_documentos_liquidar_asistente_facturacion($string){

        //ejemplo de cadena BO,00100001125|BO,00200041839|BO,00100020242

        $data = array();

        $string = $string.'|';


        
        $list_documentos = explode("|",$string);

        foreach($list_documentos as $list){

            if(!empty($list)){


                $sub_detail = explode(",",$list);

                $tipo_documento = $sub_detail[0];

                $numero_documento = $sub_detail[1];

                $data[] = RegistroPago::get_documentos_asistente_facturacion($tipo_documento,$numero_documento);


            }
            

        }

        return $data;
    } 

    protected function get_tc_fecha_operacion_liquidacion(Request $request){



        $list = RegistroPago::get_tc_fecha_operacion_liquidacion($request);


        return response()->json($list);
    } 

    protected function get_notas_credito_liquidacion(Request $request){


        $list = RegistroPago::get_notas_credito_liquidacion($request);


        return response()->json($list);
    }



 protected function get_data_titular_cliente_regpag(Request $request){


        $list = RegistroPago::get_data_titular_cliente_regpag($request);


        return response()->json($list);
    }


    protected function get_documentos_liquidar(Request $request){


        $list = RegistroPago::get_documentos_liquidar($request);


        return response()->json($list);
    }


    protected function obtener_numero_liquidacion_result($params){


        $rpta = RegistroPago::obtener_numero_liquidacion_result($params);

        if(empty($rpta)){

           return $this->setRpta("error","No se generó número liquidación"); 
        }

        return $this->setRpta("ok","Se procesó correctamente , N° REFERENCIA: ".$rpta,$rpta);
    }




    protected function registro_pago_liquidacion(Request $request){


        DB::beginTransaction();

        try {

          
            $resultado = $request->resultado;
        
            
             foreach ($resultado as $value) {
                  

                  $set_values = array(

                    'medio' => $value['IDMEDIO'],
                    'descripcion_medio' => $value['MEDIO'],
                    'banco'=> $value['IDBANCO'],
                    'moneda'=> $value['MONEDA'],
                    'monto'=> $value['MONTO'],
                    'tcambio'=> $value['TCAMBIO'],
                    'operacion'=> $value['NOPERACION'],
                    'fecha_operacion'=> $value['FOPERACION'],
                    'contrato'=> $value['CONTRATO'],
                    'cliente'=> $value['CLIENTE'],
                    'num_nc'=>$value['NUM_NC']
                    );


                  $middleRpta = $this->obtener_numero_liquidacion_result($set_values);

                  

                  $details = $value['DETAIL'];

                  if($middleRpta["status"] == "ok"){

                        $nro_referencia = $middleRpta["data"] ;

                        $rpta = RegistroPago::registro_pago_liquidacion($details,$nro_referencia,$set_values);

                        if(empty($rpta)){

                            DB::rollback();

                            return $this->setRpta("error","Ocurrió un error al Procesar");
                        }

                  }

                  DB::rollback();

                  return $middleRpta;
                  
                 

             }

              DB::commit();

              return $this->setRpta("ok","Se procesó correctamente los pagos");
               
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }
       
    }

    

	

    




	

	
	
	






    
}
