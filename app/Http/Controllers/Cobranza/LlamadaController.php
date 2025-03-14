<?php


namespace App\Http\Controllers\Cobranza; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\Llamada;
use Carbon\Carbon;
use Auth;
use App\Exports\ExportGeneral;
use App\Exports\ExportMax;
use Maatwebsite\Excel\Facades\Excel;

class LlamadaController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
	{      

		$middleRpta = $this->valida_url_permisos(25);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

		$usuario = Auth::user()->codigo;

		$responsables = Maestro::get_responsables_llamadas();

		//$indicadores = $this->indicadores_filtros(0);
    	


		$indicadores = $this->set_indicadores_tabla_listado_llamadas(0);

    	$empresa = Auth::user()->empresa;

    	$atencion_bitacora = $this->set_flag_atencion_bitacora();
    	
		return View('llamada.index',compact('usuario','responsables','indicadores','empresa','atencion_bitacora'));
		

	}	


	protected function set_flag_atencion_bitacora(){

        $cia = Auth::user()->empresa;

        $user =Auth::user()->codigo;

        $query = DB::select("SELECT ATENDER FROM VEN_RESP_ANUA WHERE NO_CIA=? AND CODIGO_USUARIO=?",array($cia,$user));

       

        $rpta = json_decode(json_encode($query),true);

        $atencion = (isset($rpta[0]['atender']))?$rpta[0]['atender']:0;

        return $atencion;


    }


   



	// protected function indicadores_filtros($responsable)
	// {      

	// 	$tipos = array("1","2","3","4","6","5");

	// 	$data = array();

	// 	$total = 0;

	// 	foreach ($tipos as  $value) {
			
	// 		$request = new Request();

	// 		$request->responsable = $responsable ;

 //        	$request->tipo = $value;

	// 		$list = Llamada::list_llamadas_filtro1($request);

	// 		$data[] =count($list);

	// 		$total = $total+count($list);

	// 	}
		
	// 	array_push($data,$total);
		
	// 	return $data;
		
	// }


	protected function list_llamadas_filtro1(Request $request)
	{      

		

		$list = Llamada::list_llamadas_filtro1($request);
		
		$responsable = $request->responsable;
		
		//$indicadores = $this->indicadores_filtros($responsable);

		$indicadores = $this->set_indicadores_tabla_listado_llamadas($responsable);

		$data = array($list,$indicadores);

		return response()->json($data);

	}	


	


	protected function list_llamadas_filtro2(Request $request)
	{      

		
		$list = Llamada::list_llamadas_filtro2($request);
		
		return response()->json($list);

	}
	


	protected function set_indicadores_tabla_listado_llamadas($responsable)
	{      

		$list = Llamada::set_indicadores_tabla_listado_llamadas($responsable);
		
		$hoy        	=	0;
		$mes_actual 	=	0;
		$anio_actual	=	0;
		$antiguo		=	0;
		$futuro			=	0;
		$total			=	0;


		foreach($list as $value){

			if($value["CODIGO"] == '1'){

				$hoy = $value["CANTIDAD"];
			}

			if($value["CODIGO"] == '2'){

				$mes_actual = $value["CANTIDAD"];
			}

			
			if($value["CODIGO"] == '3'){

				$anio_actual = $value["CANTIDAD"];
			}

			if($value["CODIGO"] == '4'){

				$antiguo = $value["CANTIDAD"];
			}

			if($value["CODIGO"] == '6'){

				$futuro = $value["CANTIDAD"];
			}

			if($value["CODIGO"] == '5'){

				$total = $value["CANTIDAD"];
			}

		}


		

		$data = array(  $hoy ,
						$mes_actual ,
						$anio_actual,
						$antiguo,
						$futuro,
						$total
					);

		
		

		return $data;
		

	}





	

		protected function list_llamadas_sinnombre_bebe(Request $request)
	{      

		$list = Llamada::list_llamadas_filtro1($request);

		$tipo_busqueda = $request->tipobusqueda;

		$data = json_decode(json_encode($list),true);

		$setData = array();


		if($tipo_busqueda  =='S'){

			//bebes sin nombres
			
			foreach($data as $values){

				if(empty($values['NOMBRE_BEBE'])){

					$setData[] = array(

					"NUMERO_CONTRATO"=>$values['NUMERO_CONTRATO'],
					"NUMERO_PRECONTRATO"=>$values['NUMERO_PRECONTRATO'],
					"MAMA"=>$values['MAMA'],
					"PAPA"=>$values['PAPA'],
					"NOMBRE_BEBE"=>$values['NOMBRE_BEBE'],
					"FECHA_CONTRATO"=>$values['FECHA_CONTRATO'],
					"FECHA_NACIMIENTO"=>$values['FECHA_NACIMIENTO'],
					"FECHA_VENCIMIENTO"=>$values['FECHA_VENCIMIENTO'],
					"EDAD_BEBE"=>$values['EDAD_BEBE'],
					"MONEDA"=>$values['MONEDA'],
					"SALDO"=>$values['SALDO']

					);

				}
				


			
			}
			
		}elseif($tipo_busqueda  =='C'){


			foreach($data as $values){

				$hoy = Carbon::now()->format('d/m');
				
			
				$nac = explode("/",$values['FECHA_NACIMIENTO']);
				
				$nacimiento = $nac[0].'/'.$nac[1];


				if($nacimiento == $hoy){

					$setData[] = array(

					"NUMERO_CONTRATO"=>$values['NUMERO_CONTRATO'],
					"NUMERO_PRECONTRATO"=>$values['NUMERO_PRECONTRATO'],
					"MAMA"=>$values['MAMA'],
					"PAPA"=>$values['PAPA'],
					"NOMBRE_BEBE"=>$values['NOMBRE_BEBE'],
					"FECHA_CONTRATO"=>$values['FECHA_CONTRATO'],
					"FECHA_NACIMIENTO"=>$values['FECHA_NACIMIENTO'],
					"FECHA_VENCIMIENTO"=>$values['FECHA_VENCIMIENTO'],
					"EDAD_BEBE"=>$values['EDAD_BEBE'],
					"MONEDA"=>$values['MONEDA'],
					"SALDO"=>$values['SALDO']

					);

				}
				


			
			}



		}elseif($tipo_busqueda  =='COB'){

			//bebes sin nombres
			
			foreach($data as $values){

				if(trim($values['TIPO_DOCUMENTO'])=='AV'){

					$setData[] = array(

					"NUMERO_CONTRATO"=>$values['NUMERO_CONTRATO'],
					"NUMERO_PRECONTRATO"=>$values['NUMERO_PRECONTRATO'],
					"MAMA"=>$values['MAMA'],
					"PAPA"=>$values['PAPA'],
					"NOMBRE_BEBE"=>$values['NOMBRE_BEBE'],
					"FECHA_CONTRATO"=>$values['FECHA_CONTRATO'],
					"FECHA_NACIMIENTO"=>$values['FECHA_NACIMIENTO'],
					"FECHA_VENCIMIENTO"=>$values['FECHA_VENCIMIENTO'],
					"EDAD_BEBE"=>$values['EDAD_BEBE'],
					"MONEDA"=>$values['MONEDA'],
					"SALDO"=>$values['SALDO']

					);

				}
				


			
			}
			
		}elseif($tipo_busqueda  =='SER'){

			//bebes sin nombres
			
			foreach($data as $values){

				if($values['HEMOCULTIVO']=='P' || $values['SEROLOGIA']=='P'){

					$setData[] = array(

					"NUMERO_CONTRATO"=>$values['NUMERO_CONTRATO'],
					"NUMERO_PRECONTRATO"=>$values['NUMERO_PRECONTRATO'],
					"MAMA"=>$values['MAMA'],
					"PAPA"=>$values['PAPA'],
					"NOMBRE_BEBE"=>$values['NOMBRE_BEBE'],
					"FECHA_CONTRATO"=>$values['FECHA_CONTRATO'],
					"FECHA_NACIMIENTO"=>$values['FECHA_NACIMIENTO'],
					"FECHA_VENCIMIENTO"=>$values['FECHA_VENCIMIENTO'],
					"EDAD_BEBE"=>$values['EDAD_BEBE'],
					"MONEDA"=>$values['MONEDA'],
					"SALDO"=>$values['SALDO']

					);

				}
				


			
			}
			
		}
		
		
		return response()->json($setData);

	}





	protected function export_list_llamadas2($list){

		
		$substring = explode("|", $list);

		$request = new Request();

		$request->contrato = $substring[0];

		$request->busqueda = $substring[1];

		$request->cliente = $substring[2];

		$request->dia_mes_naci = $substring[3];

		$request->periodo_ven = $substring[4];

		$request->anios_ven = $substring[5];

		$request->fec_naci = $substring[6];

		$request->fecha_ven = $substring[7];

		$request->codigo_usuario = $substring[8];

		$request->pre_contrato = $substring[9];


      	$list = Llamada::list_llamadas_filtro2($request);

		$excel = $this->set_filas_excel_export_llamadas($list);

        $export = new ExportGeneral([
            
            $excel

         ]); 

        return Excel::download($export, 'LLAMADAS_COBRANZA2_'.date('Y-m').'.xlsx');

    }


    protected function descargar_todo_cobrabnzas(){



    	try {

			ini_set('max_execution_time', 300);
			
		    $list = Llamada::list_llamadas_filtro_todos(); 
		    
		    $export = new ExportMax(collect($list)); 
   

    		return Excel::download($export, 'LLAMADAS_COBRANZA1_TODOS_' . date('Y-m') . '.xlsx');

		} catch (\Exception $e) {
			
		    return response()->json(['error' => 'Error generando el archivo: ' . $e->getMessage()], 500);
		}




    	


    }


 protected function set_filas_excel_export_llamadas_todos($list){



 	$sub_array = array();

        $i=3;

        $sub_array[0] =array();
        	$sub_array[1] =array();

        $sub_array[2]= array(
					    "NO_CIA",
					    "NUMERO_CONTRATO",
					    "DEUDA_ANNOS",
					    "MONEDA",
					    "SALDO",
					    "PROXIMO_CONTACTO",
					    "CODIGO",
					    "TITULO",
					    "COBRADOR",
					    "NUMERO_BASE",
					    "SERVICIO",
					    "NUMERO_PRECONTRATO",
					    "FECHA_VENCE",
					    "VENC_VALMES",
					    "CODUSU_RESPCOB",
					    "PAR_IMPAR",
					    "FAMILIA",
					    "SITUACION",
					    "CIA_SEGUROS",
					    "FECHA_NACE",
					    "NOMBRE_BEBE",
					    "DNI_MAMA",
					    "NOTIFICA_MAMA",
					    "MAMA",
					    "FIJO_MAMA",
					    "CELULAR_MAMA",
					    "MAIL_MAMA",
					    "LOCALIDAD_MAMA",
					    "INUBICABLE_MAMA",
					    "DNI_PAPA",
					    "NOTIFICA_PAPA",
					    "PAPA",
					    "FIJO_PAPA",
					    "CELULAR_PAPA",
					    "MAIL_PAPA",
					    "LOCALIDAD_PAPA",
					    "INUBICABLE_PAPA",
					    "HEMOCULTIVO",
					    "SEROLOGIA",
					    "DEBITO",
					    "EDAD_BEBE",
					    "RESPONSABLE_CONTRATO",
					    "DIRECCION_MAMA",
					    "DIRECCION_PAPA",
					    "UBIGEO_MAMA",
					    "UBIGEO_PAPA",
					    "MORA",
					    "DSCTO",
					    "SALDOFIN",
					    "CANT_CONTRATOS",
					    "ESTADO_CONTRATO",
					    "FECHA_CONTRATO",
					    "ADN"
  					);

        foreach ($list as $value) {
            
            

					    $NO_CIA = $value["NO_CIA"];
						$NUMERO_CONTRATO = $value["NUMERO_CONTRATO"];
						$DEUDA_ANNOS = $value["DEUDA_ANNOS"];
						$MONEDA = $value["MONEDA"];
						$SALDO = $value["SALDO"];
						$PROXIMO_CONTACTO = $value["PROXIMO_CONTACTO"];
						$CODIGO = $value["CODIGO"];
						$TITULO = $value["TITULO"];
						$COBRADOR = $value["COBRADOR"];
						$NUMERO_BASE = $value["NUMERO_BASE"];
						$SERVICIO = $value["SERVICIO"];
						$NUMERO_PRECONTRATO = $value["NUMERO_PRECONTRATO"];
						$FECHA_VENCE = $value["FECHA_VENCE"];
						$VENC_VALMES = $value["VENC_VALMES"];
						$CODUSU_RESPCOB = $value["CODUSU_RESPCOB"];
						$PAR_IMPAR = $value["PAR_IMPAR"];
						$FAMILIA = $value["FAMILIA"];
						$SITUACION = $value["SITUACION"];
						$CIA_SEGUROS = $value["CIA_SEGUROS"];
						$FECHA_NACE = $value["FECHA_NACE"];
						$NOMBRE_BEBE = $value["NOMBRE_BEBE"];
						$DNI_MAMA = $value["DNI_MAMA"];
						$NOTIFICA_MAMA = $value["NOTIFICA_MAMA"];
						$MAMA = $value["MAMA"];
						$FIJO_MAMA = $value["FIJO_MAMA"];
						$CELULAR_MAMA = $value["CELULAR_MAMA"];
						$MAIL_MAMA = $value["MAIL_MAMA"];
						$LOCALIDAD_MAMA = $value["LOCALIDAD_MAMA"];
						$INUBICABLE_MAMA = $value["INUBICABLE_MAMA"];
						$DNI_PAPA = $value["DNI_PAPA"];
						$NOTIFICA_PAPA = $value["NOTIFICA_PAPA"];
						$PAPA = $value["PAPA"];
						$FIJO_PAPA = $value["FIJO_PAPA"];
						$CELULAR_PAPA = $value["CELULAR_PAPA"];
						$MAIL_PAPA = $value["MAIL_PAPA"];
						$LOCALIDAD_PAPA = $value["LOCALIDAD_PAPA"];
						$INUBICABLE_PAPA = $value["INUBICABLE_PAPA"];
						$HEMOCULTIVO = $value["HEMOCULTIVO"];
						$SEROLOGIA = $value["SEROLOGIA"];
						$DEBITO = $value["DEBITO"];
						$EDAD_BEBE = $value["EDAD_BEBE"];
						$RESPONSABLE_CONTRATO = $value["RESPONSABLE_CONTRATO"];
						$DIRECCION_MAMA = $value["DIRECCION_MAMA"];
						$DIRECCION_PAPA = $value["DIRECCION_PAPA"];
						$UBIGEO_MAMA = $value["UBIGEO_MAMA"];
						$UBIGEO_PAPA = $value["UBIGEO_PAPA"];
						$MORA = $value["MORA"];
						$DSCTO = $value["DSCTO"];
						$SALDOFIN = $value["SALDOFIN"];
						$CANT_CONTRATOS = $value["CANT_CONTRATOS"];
						$ESTADO_CONTRATO = $value["ESTADO_CONTRATO"];
						$FECHA_CONTRATO = $value["FECHA_CONTRATO"];
						$ADN = $value["ADN"];
  					

          
        
            $sub_array[$i]=array(


            		$NO_CIA,
            		 $NUMERO_CONTRATO, 
            		 $DEUDA_ANNOS,
            		  $MONEDA, 
            		  $SALDO, 
            		  $PROXIMO_CONTACTO,
            		   $CODIGO, 
            		   $TITULO,
            		    $COBRADOR,
            		     $NUMERO_BASE, 
						$SERVICIO, 
						$NUMERO_PRECONTRATO, 
						$FECHA_VENCE, 
						$VENC_VALMES,
						 $CODUSU_RESPCOB, 
						 $PAR_IMPAR, 
						 $FAMILIA, 
						 $SITUACION, 
						$CIA_SEGUROS, 
						$FECHA_NACE, 
						$NOMBRE_BEBE,
						 $DNI_MAMA, 
						 $NOTIFICA_MAMA, 
						 $MAMA,
						  $FIJO_MAMA,
						  $CELULAR_MAMA,
						   $MAIL_MAMA, 
						$LOCALIDAD_MAMA, 
						$INUBICABLE_MAMA, 
						$DNI_PAPA, 
						$NOTIFICA_PAPA, 
						$PAPA, $FIJO_PAPA,
						 $CELULAR_PAPA, 
						 $MAIL_PAPA, 
						$LOCALIDAD_PAPA,
						 $INUBICABLE_PAPA,
						  $HEMOCULTIVO, 
						  $SEROLOGIA,
						   $DEBITO, 
						   $EDAD_BEBE,
						    $RESPONSABLE_CONTRATO, 
						$DIRECCION_MAMA, 
						$DIRECCION_PAPA, 
						$UBIGEO_MAMA, 
						$UBIGEO_PAPA,
						 $MORA, 
						 $DSCTO,
						  $SALDOFIN, 
						  $CANT_CONTRATOS, 
						$ESTADO_CONTRATO, 
						$FECHA_CONTRATO, 
						$ADN


            );

            $i++;
        }

        return $sub_array;



 }

	protected function export_list_llamadas($responsable,$tipo){

		$request = new Request();

		$request->responsable = $responsable;

		$request->tipo = $tipo;

      	$list = Llamada::list_llamadas_filtro1($request);

		$excel = $this->set_filas_excel_export_llamadas($list);

        $export = new ExportGeneral([
            
            $excel

         ]); 

        return Excel::download($export, 'LLAMADAS_COBRANZA1_'.date('Y-m').'.xlsx');

    }


    protected function set_filas_excel_export_llamadas($list){

    	

    	$sub_array = array();

        $i=1;

        $sub_array[0]= array(
					    "NO_CIA" ,
					    "CONTRATO_BASE",
					    "NUMERO_CONTRATO" ,
					    "SERVICIO" ,
					    "NUMERO_PRECONTRATO" ,
					    "PARIMPAR" ,
					    "FAMILIA" ,
					    "NOMBRE_BEBE" ,
					    "FECHA_CONTRATO" ,
					    "FECHA_NACIMIENTO" ,
					    "SITUACION" ,
					    "CIA_SEGUROS",
					    "CUMPLE_ANOS_HOY" ,
					    "FECHA_VENCIMIENTO",
					    "TIPO_DOCUMENTO" ,
					    "NUMERO_DOCUMENTO" ,
					    "DEUDA_ANNOS" ,
					    "MONEDA",
					    "SALDO" ,
					    "DNI_MAMA",
					    "MAMA" ,
					    "FIJO_MAMA" ,
					    "CELULAR_MAMA" ,
					    "MAIL_MAMA",
					    "LOCALIDAD_MAMA" ,
					    "INUBICABLE_MAMA",
					    "DNI_PAPA" ,
					    "PAPA" ,
					    "FIJO_PAPA" ,
					    "CELULAR_PAPA" ,
					    "MAIL_PAPA" ,
					    "LOCALIDAD_PAPA" ,
					    "INUBICABLE_PAPA" ,
					    "HEMOCULTIVO" ,
					    "SEROLOGIA" ,
					    "DEBITO" ,
					    "EDAD_BEBE"
  					);

        foreach ($list as $value) {
            
            

					    $NO_CIA = $value["NO_CIA"];
					    $CONTRATO_BASE= $value["CONTRATO_BASE"];
					    $NUMERO_CONTRATO= $value["NUMERO_CONTRATO"];
					    $SERVICIO= $value["SERVICIO"];
					   $NUMERO_PRECONTRATO = $value["NUMERO_PRECONTRATO"];
					    $PARIMPAR= $value["PARIMPAR"];
					    $FAMILIA= $value["FAMILIA"];
					    $NOMBRE_BEBE= $value["NOMBRE_BEBE"];
					    $FECHA_CONTRATO= $value["FECHA_CONTRATO"];
					    $FECHA_NACIMIENTO= $value["FECHA_NACIMIENTO"];
					    $SITUACION= $value["SITUACION"];
					    $CIA_SEGUROS= $value["CIA_SEGUROS"];
					    $CUMPLE_ANOS_HOY= $value["CUMPLE_ANOS_HOY"];
					    $FECHA_VENCIMIENTO= $value["FECHA_VENCIMIENTO"];
					    $TIPO_DOCUMENTO= $value["TIPO_DOCUMENTO"];
					    $NUMERO_DOCUMENTO= $value["NUMERO_DOCUMENTO"];
					    $DEUDA_ANNOS= $value["DEUDA_ANNOS"];
					    $MONEDA= $value["MONEDA"];
					    $SALDO= $value["SALDO"];
					    $DNI_MAMA= $value["DNI_MAMA"];
					    $MAMA= $value["MAMA"];
					    $FIJO_MAMA= $value["FIJO_MAMA"];
					    $CELULAR_MAMA= $value["CELULAR_MAMA"];
					    $MAIL_MAMA= $value["MAIL_MAMA"];
					    $LOCALIDAD_MAMA= $value["LOCALIDAD_MAMA"];
					    $INUBICABLE_MAMA= $value["INUBICABLE_MAMA"];
					    $DNI_PAPA= $value["DNI_PAPA"];
					    $PAPA= $value["PAPA"];
					    $FIJO_PAPA= $value["FIJO_PAPA"];
					    $CELULAR_PAPA= $value["CELULAR_PAPA"];
					    $MAIL_PAPA= $value["MAIL_PAPA"];
					    $LOCALIDAD_PAPA= $value["LOCALIDAD_PAPA"];
					    $INUBICABLE_PAPA= $value["INUBICABLE_PAPA"];
					    $HEMOCULTIVO= $value["HEMOCULTIVO"];
					    $SEROLOGIA= $value["SEROLOGIA"];
					    $DEBITO= $value["DEBITO"];
					    $EDAD_BEBE= $value["EDAD_BEBE"];
  					

          
        
            $sub_array[$i]=array(


            	$NO_CIA,
					    $CONTRATO_BASE,
					    $NUMERO_CONTRATO,
					    $SERVICIO,
					   $NUMERO_PRECONTRATO ,
					    $PARIMPAR,
					    $FAMILIA,
					    $NOMBRE_BEBE,
					    $FECHA_CONTRATO,
					    $FECHA_NACIMIENTO,
					    $SITUACION,
					    $CIA_SEGUROS,
					    $CUMPLE_ANOS_HOY,
					    $FECHA_VENCIMIENTO,
					    $TIPO_DOCUMENTO,
					    $NUMERO_DOCUMENTO,
					    $DEUDA_ANNOS,
					    $MONEDA,
					    $SALDO,
					    $DNI_MAMA,
					    $MAMA,
					    $FIJO_MAMA,
					    $CELULAR_MAMA,
					    $MAIL_MAMA,
					    $LOCALIDAD_MAMA,
					    $INUBICABLE_MAMA,
					    $DNI_PAPA,
					    $PAPA,
					    $FIJO_PAPA,
					    $CELULAR_PAPA,
					    $MAIL_PAPA,
					    $LOCALIDAD_PAPA,
					    $INUBICABLE_PAPA,
					    $HEMOCULTIVO,
					    $SEROLOGIA,
					    $DEBITO,
					    $EDAD_BEBE


            );

            $i++;
        }

        return $sub_array;




    }
    
}
