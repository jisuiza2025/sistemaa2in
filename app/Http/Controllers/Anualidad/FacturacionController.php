<?php


namespace App\Http\Controllers\Anualidad; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\Cliente;
use App\Facturacion;
use App\TipoCambio;
use App\DocVenta;
use App\RegistroPago;

use Auth;
use Zipper;
use Carbon\Carbon;
use App\Exports\ExportGeneral;
use Maatwebsite\Excel\Facades\Excel;
class FacturacionController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function facturacion_directa()
	{      

		$middleRpta = $this->valida_url_permisos(26);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

		$empresa_user = Auth::user()->empresa;

		$usuario_facturacion =  ucwords(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);

		$valida_centro       = Facturacion::valida_centro();

		$valida_tipo_cambio  = Facturacion::valida_tipo_cambio();

		$centros_facturacion = Maestro::facturacion_centros();

		$tipo_cambio         = TipoCambio::get_tipo_cambio();

		$factor_cambio = (empty($tipo_cambio[0]['FACTOR_CAMBIO']))?'':$tipo_cambio[0]['FACTOR_CAMBIO'];

		$monedas = Maestro::list_monedas();

		$igv = Maestro::get_igv();

		$dni_usuer_logeado =Auth::user()->identificacion;
		

		$clientes_varios = $this->set_clientes_varios();

		$precio_contabilidad = $this->set_precios_contabilidad();

		return View('facturacion.facturacion_directa',compact('empresa_user','valida_centro','valida_tipo_cambio','usuario_facturacion','centros_facturacion','factor_cambio','monedas','igv','dni_usuer_logeado','clientes_varios','precio_contabilidad'));
		

	}	

	protected function set_clientes_varios(){

		$request = new Request();

		$request->cliente='00000000';

		$list = Facturacion::get_datos_facturacion_cliente($request);

		return $list;



	}

	protected function set_precios_contabilidad(){

		return array("id"=>20,"text"=>"CONTABILIDAD");
	}


	protected function genera_zip_facturas(Request $request){

		$tipo_documento = $request->str;

		$numero_doc 	= $request->str2;
		
		$array_tipo_doc = explode(",",$tipo_documento);

		$array_numero_doc = explode(",",$numero_doc);

		$empresa_user=Auth::user()->empresa;

		$name_pdfs = array();


		for($i = 0; $i < count($array_tipo_doc); $i++){

			$tipo   = $array_tipo_doc[$i];
			$numero = $array_numero_doc[$i];

			$request = new Request();

			$request->tipo_doc = $tipo ;
        	$request->num_contrato = $numero;

			$list = DocVenta::reporte_facturacion($request);



			$cuotas = DocVenta::cuotas_detalle_documento_venta($request);


			$name_pdfs[] = public_path('pdf_factura/')."FACTURA_".$tipo."_".$numero.".pdf";

			$pdf = \App::make('dompdf.wrapper');
        	$pdf->setPaper('A4');
        	$pdf->loadView('anualidad.documento_venta.reporte.reporte_factura', compact('empresa_user','list','cuotas'));

       		$pdf->save(public_path('pdf_factura/')."FACTURA_".$tipo."_".$numero.".pdf");


		}


		if (file_exists(public_path('zip_factura/facturas.zip'))) {
    		
    		unlink(public_path('zip_factura/facturas.zip'));
		}

    	Zipper::make('zip_factura/facturas.zip')->add($name_pdfs)->close();

    	//eliminamos pdfs
    	

    	foreach($name_pdfs as $values){

    		if (file_exists($values)) {
    		
    			unlink($values);
			}


    	}
    	return response()->download(public_path('zip_factura/facturas.zip'));


	}

	protected function successPdfFactura($tipo,$numero,$cliente){

		//validar que existan los documentos con los tipos

		$documentos = $this->set_documentos_success_asistente($tipo,$numero);

		$string_tipo_numero = $this->set_string_success_tiponumero($tipo,$numero);


		return View('facturacion.success_factura',compact('tipo','numero','documentos','cliente','string_tipo_numero'));
	}


	protected function successPdfFacturaDirecta(){

		
		return View('facturacion.success_factura_directa');
	}


	
	protected function set_string_success_tiponumero($tipo,$numero){


		$array_tipo_doc = explode(",",$tipo);

		$array_numero_doc = explode(",",$numero);

		$row = "";


		for($i = 0; $i < count($array_tipo_doc); $i++){

			$tipox   = $array_tipo_doc[$i];
			$numerox = $array_numero_doc[$i];

			
			$row.=$tipox.','.$numerox.'|';

		}

		return rtrim($row,'|');
		


	}

	protected function set_documentos_success_asistente($tipo,$numero){


		$tipo_documento = $tipo;

		$numero_doc 	= $numero;
		
		$array_tipo_doc = explode(",",$tipo_documento);

		$array_numero_doc = explode(",",$numero_doc);

		$data = array();


		for($i = 0; $i < count($array_tipo_doc); $i++){

			$tipo   = $array_tipo_doc[$i];
			$numero = $array_numero_doc[$i];

			$data[]   = array('TIPO' => $tipo ,'NUMERO'=> $numero ,'NOMBRE'=> $tipo.'-'.$numero) ;

		}

		return $data;


	}


	protected function get_documentos_centros_facturacion(Request $request){


		$list = Facturacion::get_documentos_centros_facturacion($request);

		return response()->json($list);
	}


	protected function get_data_titular_cliente(Request $request){


		$list = Facturacion::get_data_titular_cliente($request);

		return response()->json($list);
	}
	

	protected function filter_vendedor_facturacion(Request $request){


		$list = Facturacion::filter_vendedor_facturacion($request);

		return response()->json($list);
	}
	
	protected function filter_cliente_facturacion(Request $request){


		$list = Facturacion::filter_cliente_facturacion($request);

		return response()->json($list);
	}


	protected function get_datos_facturacion_cliente(Request $request){


		$list = Facturacion::get_datos_facturacion_cliente($request);

		return response()->json($list);
	}
	
	protected function filter_lista_precio_facturacion(Request $request){


		$list = Facturacion::filter_lista_precio_facturacion($request);

		return response()->json($list);
	}
		

	protected function get_articulos_facturacion_precio(Request $request){


		$list = Facturacion::get_articulos_facturacion_precio($request);

		return response()->json($list);
	}




	protected function actualiza_titular_registro_bitacora($request){


		$nocia = Auth::user()->empresa;

		$titular= $request->titular;

		foreach($request->cadena as $values){

        	$contrato = $values['NUMERO_CONTRATO'];


        	DB::update("UPDATE VEN_CONTRATOS SET TITULAR_PAGO=? WHERE NO_CIA=? AND NUMERO_CONTRATO=?",array($titular,$nocia,$contrato));
        	
        	$rpta = Facturacion::actualiza_titular_registro_bitacora($contrato,$request->identificacion);
        	
        }



		

		return $rpta;




	}
	
	protected function valida_asistente_factura_generar_documentos($request){

		$lista = $request->cadena;

		if(count($lista) == 0 ){

			return $this->setRpta("error","No hay documentos a facturar");

		}else{

			return $this->setRpta("ok","");

		}





	}

	protected function guarda_cuotas_facturacion($request){


		
		foreach($request->cadena as $values){

			$contrato   = $values["NUMERO_CONTRATO"];
			$tipo_doc   = $values["TIPO_DOCUMENTO"];
			$numero_doc = $values["NUMERO_DOCUMENTO"];

			$cuenta_detalle = count($values["DETAILS_CUOTAS"]);

			if($cuenta_detalle>0){


				foreach($values["DETAILS_CUOTAS"] as $key =>$list){

					$cuota = ($key+1);

					$vence = Carbon::parse($list['FECHA'])->format('Y-m-d');
				
					$moneda = $list["MONEDA"];

					$monto = $list["MONTO"];

					$observacion = $list["NCUOTA"];


					$middleRpta = Facturacion::guarda_cuotas_facturacion($contrato,$tipo_doc,$numero_doc,$cuota,$vence,$moneda ,$monto,$observacion);


					if($middleRpta == 0){

						return $this->setRpta("error","Ocurrió un error al guardar las cuotas");

					}
				}


			}else{

				$cuotas = $values["CUOTAS"];

				$tipo_facturacion = ($request->tipo)?'c':'a';

				$condicion_pago = $values["CONDICION_PAGO"];

				//inserta 1 cuota automatico
				if($cuotas==1 && $condicion_pago ==1 && $tipo_facturacion=='c'){


					$vence_cuota = Carbon::now()->addMonth()->format('Y-m-d');

					

					$moneda_cuota= $values["MONEDA"];


					$monto_cuota = str_replace(',','',$values['ANUALIDAD']);

					

					$middleRpta = Facturacion::guarda_cuotas_facturacion($contrato,$tipo_doc,$numero_doc,1,$vence_cuota,$moneda_cuota ,$monto_cuota,'Cuota 1 de 1');


					if($middleRpta == 0){

						return $this->setRpta("error","Ocurrió un error al guardar las cuotas");

					}
				}

			}

				

		}

		return $this->setRpta("ok","Se guardaron cuotas");
		

	

	}


	protected function save_factura_asistente(Request $request){      

		
		DB::beginTransaction();

        try {

          	 $valida = $this->valida_asistente_factura_generar_documentos($request);

          	 

          	 if($valida["status"] == "error"){

          	 	return $valida ;

          	 }

             $rpta = Facturacion::save_factura_asistente($request);
            
                if($rpta == 1){


                	$cuotas_rpta = $this->guarda_cuotas_facturacion($request);

                	if($cuotas_rpta["status"]=="ok"){


                	
                   		//actualiza titular en registro de bitacora
                   

                    	$this->actualiza_titular_registro_bitacora($request);


                    	DB::commit();


                    	return $this->setRpta("ok","Se generó factura correctamente");


                	}

                	 	DB::rollback();

                		return $cuotas_rpta;
                    

                }
          
                DB::rollback();

                return $this->setRpta("error","Ocurrió un error");
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }

		

	}



	protected function guarda_cuotas_facturacion_directa($request,$numero_doc){


		$detalle = $request->detalle_cuotas;

		$forma_pago = $request->forma_pago;

		$tipo_comprobante = $request->documento;

		$contrato= null;

		if($forma_pago == 1){

			//variaas cuotas credito
			

			foreach($detalle as $key =>$list){

					

					$cuota = ($key+1);

					$vence = Carbon::parse($list['FECHA'])->format('Y-m-d');
				
					$moneda = $list["MONEDA"];

					$monto = $list["MONTO"];

					$observacion = $list["NCUOTA"];



					$middleRpta = Facturacion::guarda_cuotas_facturacion($contrato,$tipo_comprobante,$numero_doc,$cuota,$vence,$moneda ,$monto,$observacion);


					if($middleRpta == 0){

						return $this->setRpta("error","Ocurrió un error al guardar las cuotas");

					}
				}
		}else{

			return $this->setRpta("ok","guardo cuotas ");

			//1 cuota que es al contado
			
			// $vence = Carbon::now()->addMonth()->format('Y-m-d');
			// //primer registro de la tabla detalle
			// $articulos = $request->articulos;

			// $moneda = $articulos[0]['MONEDA'];
			// //monto final
			// //
			// $monto = $request->total;
			
			// $middleRpta = Facturacion::guarda_cuotas_facturacion($contrato,$tipo_comprobante,$numero_doc,1,$vence,$moneda ,$monto,'');


			// 		if($middleRpta == 0){

			// 			return $this->setRpta("error","Ocurrió un error al guardar la unica cuota");

			// 		}

		}

			
			return $this->setRpta("ok","guardo cuotas ");	

	}

	protected function genera_factura_directa(Request $request){      

		
		DB::beginTransaction();

        try {

          
             $rpta = Facturacion::genera_factura_directa($request);
            
                if(!empty($rpta)){

                	//guarda cuotas 
                	
                	$rptaCuotas = $this->guarda_cuotas_facturacion_directa($request,$rpta);

                	if($rptaCuotas["status"]=="ok"){

                		 DB::commit();

                   

                    	return $this->setRpta("ok","Se generó factura correctamente ",$rpta);

                	}
                   
                   DB::rollback();

                return $this->setRpta("error","Ocurrió un error al guardar las cuotas");


                }
          
                DB::rollback();

                return $this->setRpta("error","Ocurrió un error");
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }

		

	}


	//asistente de facturacion 
	//
	
	protected function obtener_vendedor_contrato_asisfact(Request $request){


		$contrato = $request->contrato;

		$cia= Auth::user()->empresa;


		$query = DB::select("SELECT U.NOMBRE,U.DNI FROM VEN_CONTRATOS C INNER JOIN COR_USUARIOS U ON U.DNI = C.VENDEDOR WHERE (C.NUMERO_CONTRATO=? OR C.NUMERO_BASE=?)AND C.NO_CIA=?",array($contrato,$contrato,$cia));

		return $query;



	}
	

	protected function asistente_facturacion($cliente,$contrato){

		$middleRpta = $this->valida_url_permisos(27);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }
        

		$btn_bitacora_contrato=($this->botones_usuario('fac_bitacora_contrato')==1)?true:false;
	

        $flag_facturacion = Auth::user()->flag_facturacion;

        //si usuario tiene permiso de criopreservacion
		 
        if($flag_facturacion=="CONT" || $flag_facturacion=="ANUCONT" ){

        	$contratos = $this->set_contratos_facturacion($cliente,$contrato);
			//var_dump($contratos);
			//exit;
        }else{

        	$contratos = $this->set_contratos_facturacion_anualidad($cliente,$contrato);
			 
        }
		

		$usuario_facturacion =  ucwords(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);

		$valida_centro       = Facturacion::valida_centro();

		$valida_tipo_cambio  = Facturacion::valida_tipo_cambio();

		$centros_facturacion = Maestro::facturacion_centros();

		$tipo_cambio         = TipoCambio::get_tipo_cambio();

		$factor_cambio = (empty($tipo_cambio[0]['FACTOR_CAMBIO']))?'':$tipo_cambio[0]['FACTOR_CAMBIO'];

		$fullname_cliente = Cliente::get_full_name($cliente);

		


		$persona_dni_logeado=Auth::user()->identificacion;

		$persona_nombre_logeado=mb_strtoupper(Auth::user()->apepat.' '.Auth::user()->apemat.' '.Auth::user()->nombres);



		$aseguradoras_list = Facturacion::lista_aseguradoras();

		return View('facturacion.asistente_facturacion',compact('contratos','usuario_facturacion','valida_centro','valida_tipo_cambio','centros_facturacion','factor_cambio','fullname_cliente','flag_facturacion','persona_dni_logeado','persona_nombre_logeado','aseguradoras_list','btn_bitacora_contrato'));
		
	}
	protected function list_bitacora_contratos(Request $request){
		$contrato=$request->contrato;
		$list_bitacora_contratos_prop = Facturacion::list_bitacora_bitacora($contrato);
		return $list_bitacora_contratos_prop;
	}
	
	protected function set_contratos_facturacion_anualidad($cliente,$contrato){


		$list = Facturacion::asistente_facturacion_tabla_anualidad($cliente,$contrato);

		$data = array();

		foreach ($list as $value) {
			
			$boolean = ($value['FLAG']==1)?true:false;

			$data[] = array("FLAG"=>$boolean,
						 "NUMERO_BASE"=>$value['NUMERO_BASE'],
						 "CONTRATO"=>$value['CONTRATO'],
						 "SERVICIO"=>$value['SERVICIO'],
						 "ESTADO"=>$value['ESTADO'],
						 "FAMILIA"=>$value['FAMILIA'],
						 "SITUACION"=>$value['SITUACION'],
						 "FCONTRATO"=>$value['FCONTRATO'],
						 "FVENCIMIENTO"=>$value['FVENCIMIENTO'],
						 "MONEDA"=>$value['MONEDA'],
						 "SALDO"=>$value['SALDO_MONTO'],
						 "CANTIDAD"=>$value['CANTIDAD']);
		}
		

		return $data;

	}

	protected function set_contratos_facturacion($cliente,$contrato){

		$list = Facturacion::set_contratos_facturacion($cliente,$contrato);

		$data = array();

		foreach ($list as $value) {
			
			$boolean = ($value['FLAG']==1)?true:false;

			$data[] = array("FLAG"=>$boolean,
						 "NUMERO_BASE"=>$value['NUMERO_BASE'],
						 "CONTRATO"=>$value['CONTRATO'],
						 "SERVICIO"=>$value['SERVICIO'],
						 "ESTADO"=>$value['ESTADO'],
						 "FAMILIA"=>$value['FAMILIA'],
						 "SITUACION"=>$value['SITUACION'],
						 "FCONTRATO"=>$value['FCONTRATO'],
						 "FVENCIMIENTO"=>$value['FVENCIMIENTO'],
						 "MONEDA"=>$value['MONEDA'],
						 "SALDO"=>$value['SALDO_MONTO'],
						 "CANTIDAD"=>$value['CANTIDAD']
						);

		}
		
 
		return $data;


	}

	protected function asistente_facturacion_tabla_contratacion(Request $request){

		$list = $this->set_contratos_facturacion($request->cliente,$request->contrato);

		return response()->json($list);
	}

	protected function genera_detalle_facturacion_asistente(Request $request){

		
		

		$list = Facturacion::set_rows_detalle_facturacion($request);

		return response()->json($list);
	}

	

	




	
	protected function agregaFacturaAseguradoraCorrelativo(Request $request){

		
		

		$documento_inicial = Facturacion::genera_detalle_facturacion_asistente($request);



		$num_doc = intval($documento_inicial)+1;

		$numero =str_pad($num_doc, 11, 0, STR_PAD_LEFT);


		return response()->json($numero);
	}

protected function completa_datos_contacto_aseguradora(Request $request){

		
		

		$list = Facturacion::completa_datos_contacto_aseguradora($request);

		return response()->json($list);
	}

	protected function llenaPlanesAseguradoraFacturacion(Request $request){

		
		

		$list = Facturacion::llenaPlanesAseguradoraFacturacion($request);

		return response()->json($list);
	}



protected function obtener_datos_aseguradoraFacturacion(Request $request){

		
		

		$list = Facturacion::obtener_datos_aseguradoraFacturacion($request);

		return response()->json($list);
	}


	protected function llenaCoberturaSeguroFacturacion(Request $request){

		
		

		$list = Facturacion::llenaCoberturaSeguroFacturacion($request);

		return response()->json($list);
	}




	protected function cambiaTitularFacturacionAsistente(Request $request){

		
		

		$list = Facturacion::cambiaTitularFacturacionAsistente($request);

		return response()->json($list);
	}

	protected function set_datos_personales_asis_factura(Request $request){

		$list = Facturacion::set_datos_personales_asis_factura($request);

		return response()->json($list);
	}


	protected function asistente_facturacion_tabla_anualidad(Request $request){

		$list = Facturacion::asistente_facturacion_tabla_anualidad($request->cliente,$request->contrato,$request->precontrato,$request->vacio);

		$data = array();

		foreach ($list as $value) {
			
			$boolean = ($value['FLAG']==1)?true:false;

			$data[] = array("FLAG"=>$boolean,
						 "NUMERO_BASE"=>$value['NUMERO_BASE'],
						 "CONTRATO"=>$value['CONTRATO'],
						 "SERVICIO"=>$value['SERVICIO'],
						 "ESTADO"=>$value['ESTADO'],
						 "FAMILIA"=>$value['FAMILIA'],
						 "SITUACION"=>$value['SITUACION'],
						 "FCONTRATO"=>$value['FCONTRATO'],
						 "FVENCIMIENTO"=>$value['FVENCIMIENTO'],
						 "MONEDA"=>$value['MONEDA'],
						 "SALDO"=>$value['SALDO_MONTO'],
						 "CANTIDAD"=>$value['CANTIDAD']);

		}
		

		return $data;

	}
	protected function get_numero_contrato(Request $request){
		$list = Facturacion::get_numero_contrato($request);
		return  response()->json($list);
	}

	//facturacion masiva e historial
	


	protected function facturacion_masiva(){



		$middleRpta = $this->valida_url_permisos(41);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

		$empresa= Auth::user()->empresa;

		//centro de facturacion
		

		$centro = Facturacion::set_centro_facturacion_nombre();

		$tipo_cambio=Facturacion::valida_tipo_cambio();


		return View('facturacion.facturacion_masiva',compact('empresa','centro','tipo_cambio'));
	



	}

	protected function lista_tabla_masiva_facturacion(Request $request){

		$list = Facturacion::lista_tabla_masiva_facturacion($request);

		$data=array();

		foreach ($list as $value) {
			
			$data[]=array(

				"SELECCIONA"=>false,
				"NUMERO_CONTRATO"=>$value["NUMERO_CONTRATO"],
				"NUMERO_BASE"=>$value["NUMERO_BASE"],
				"SERVICIO"=>$value["SERVICIO"],
				"FECHA_CONTRATO"=>$value["FECHA_CONTRATO"],
				"TITULAR"=>$value["TITULAR"],
				"MONEDA_CONTRATO"=>$value["MONEDA_CONTRATO"],
				"MONTO_CONTRATO"=>$value["MONTO_CONTRATO"],
				"SALDO_FACTURAR"=>$value["SALDO_FACTURAR"],
				"VENDEDOR"=>$value["VENDEDOR"],
				"MEDICO"=>$value["MEDICO"],
				"ASEGURADORA"=>$value["ASEGURADORA"],
				"PORCENTAJE_SEGURO"=>$value["PORCENTAJE_SEGURO"],
				"ESTADO"=>$value["ESTADO"]

				

			);

		}
		return response()->json($data);
	}
	
	

	protected function historial_facturación_masiva(){




		$middleRpta = $this->valida_url_permisos(42);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

		$empresa= Auth::user()->empresa;

		

	


		return View('facturacion.historial_facturacion_masiva',compact('empresa'));
	



	}

	protected function lista_tabla_masiva_facturacion_historial(Request $request){

		$list = Facturacion::lista_tabla_masiva_facturacion_historial($request);

		return response()->json($list);
	}


	protected function exportar_detalle_masivo_facturas($guia){

		$list = Facturacion::exportar_detalle_masivo_facturas($guia);


			$excel = $this->set_filas_exportar_detalle_masivo_facturas($list);

            $export = new ExportGeneral([
            
                    $excel

            ]); 

            return Excel::download($export, 'HISTORIAL_DETALLE_FACTURAS_'.$guia.'.xlsx');
		
	}



	protected function ConfirmaMasivaFactura(Request $request){





		DB::beginTransaction();

        try {

          		

          		$rpta = Facturacion::ConfirmaMasivaFactura($request);

				if($rpta == 1 ){

					DB::commit();

					return $this->setRpta("ok","Se procesó correctamente");
			
				}


		 		DB::rollback();

				return $this->setRpta("error","No se pudo generar");



               

              
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }




		


	}

	
	protected function set_ventana_registro_pago_contrato(Request $request){

		$cia = Auth::user()->empresa;

		$contrato = $request->contrato;

		$query = DB::select("SELECT TITULAR_PAGO FROM VEN_CONTRATOS WHERE NO_CIA=? AND NUMERO_CONTRATO=?",array($cia,$contrato));

		$decode = json_decode(json_encode($query),true);

		$titular = $decode[0]["titular_pago"];

		$identificaciondb = Facturacion::set_ventana_registro_pago_contrato($contrato,$titular);

		$dni_titular = $identificaciondb[0]["IDENTIFICACION"];

		//documentos de pago del contrato
		

		$documentos = $this->get_documentos_liquidar_contrato($contrato);
		
		return array($dni_titular,$documentos);

		

	}
	protected function get_documentos_liquidar_contrato($contrato){

		$request = new Request();

		$request->contrato = $contrato;

		$request->tdocumento = 'BO';

		$documentos = RegistroPago::get_documentos_liquidar($request);

		$cadena='';

		foreach($documentos as $values){

			$tipo_documento = $values["TIPO_DOCUMENTO"];

			$numero_documento = $values["NUMERO_DOCUMENTO"];


			$cadena.=$tipo_documento.",".$numero_documento."|";


		}

		return rtrim($cadena,'|');


		




	}
	protected function set_filas_exportar_detalle_masivo_facturas($list){


			

		   $sub_array =array();

        	$i=3;

        	$sub_array[0] =array();
        	$sub_array[1] =array();
        	
        	$sub_array[2]=array("NRO_GUIA","FECHA_PROCESO","USUARIO","TIPO_DOC","NUMERO_DOC","FECHA_FC","CONDICION","CONTRATO","VENDEDOR","MEDICO","CONDICION_PAGO","CUOTAS","SUBTOTAL","IGV","TOTAL");

        	
        	foreach ($list as $value) {
            
            	$NRO_GUIA      	= $value["NRO_GUIA"];
            	$FECHA_PROCESO  = $value["FECHA"];
            	$USUARIO 		= $value["USUARIO"];
            	$TIPO_DOC 		= $value["TIPO_DOCUMENTO"];
            	$NUMERO_DOC 	= $value["NUMERO_DOCUMENTO"];
            	$FECHA_FC 		= $value["FECHA_FACTURACION"];
            	$CONDICION 		= $value["CONDICION_PAGO"];
            	$CONTRATO 		= $value["NUMERO_CONTRATO"];
            	$VENDEDOR 		= $value["VENDEDOR"];
            	$MEDICO 		= $value["MEDICO"];

            	$CONDICION_PAGO 		= $value["CONDICION_PAGO"];
            	$CUOTAS 		= $value["CUOTAS"];


            	$SUBTOTAL 		= $value["SUBTOTAL"];
            	$IGV 			= $value["IGV"];
            	$TOTAL 			= $value["TOTAL"];

           
        
            	$sub_array[$i]=array(


            		$NRO_GUIA   ,
            		$FECHA_PROCESO ,
            		$USUARIO 		,
            		$TIPO_DOC 		,
            		$NUMERO_DOC 	,
            		$FECHA_FC 		,
            		$CONDICION 		,
            		$CONTRATO 		,
            		$VENDEDOR 		,
            		$MEDICO 		,
            		$CONDICION_PAGO,
            		$CUOTAS,
            		$SUBTOTAL ,
            		$IGV,
            		$TOTAL 			
            	);

            	$i++;
        	}

        	return $sub_array;



		}
	
		protected function list_contratos_bitacora(Request $request){

			$list = Facturacion::list_contratos_bitacora($request);
	
			return response()->json($list);
		} 
		
		public static function get_btn_permisos($campo){

			$usuario= Auth::user()->codigo;

	 		$cia = Auth::user()->empresa;

	 		$list = DB::select("SELECT ". $campo ." FROM BOTONES_USUARIOS WHERE USUARIO=? AND NO_CIA=?",array($usuario,$cia));


	 		$val = 0;
	 
			if(count($list)>0){

		 		$list=json_decode(json_encode($list),true);
				$val = ($list[0][strtolower($campo)]==1)?1:0;
			}
	 		return $val;
 		}
    
}
