<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\User;
use App\Botones;

class RolController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
  	
	
	 public function index()
	{      

		$middleRpta = $this->valida_url_permisos(30);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

		$empresa_user = Auth::user()->empresa;

		$request = new Request();

		$request->flag_activo=1;

		$usuarios = User::list_usuarios($request);

		$servicios = Maestro::cmb_servicios_filtro_all();

		$onlyservicios = Maestro::cmb_servicios_filtro();

		return view('mantenimiento.permisos.index', compact('empresa_user','usuarios','servicios','onlyservicios'));

	}

	
	protected function get_usuarios_por_compania(Request $request)
	{      

		$list = User::get_usuarios_por_compania($request);
		
		return response()->json($list);

	}


	protected function salvar_opciones_usuario(Request $request)
	{      

		$rpta = User::salvar_opciones_usuario($request);
		
		
		 if($rpta == 1){
              
              return $this->setRpta("ok","Se procesó de correctamente");
          }

        return $this->setRpta("error","Ocurrió un error al guardar");

	}




	protected function get_menu_usuario_por_compania(Request $request)
	{      

		$list = User::get_menu_usuario_por_compania($request);
		
		$opciones = array();

		for($i=1;$i<=78;$i++){

			$opciones[$i]=false;

		}
		

		foreach ($list as $value) {

			$opciones[intval($value["CODIGO_MENU"])] = true;

			
		}

		$menu = array(

			array("id"=>1,"text"=>"Mantenimientos","selected"=>$opciones[1],
				"opened"=>true,
				"children"=>

				array(

					array("id"=>34,"text"=>"Plantillas Contrato","selected"=>$opciones[34]),
					array("id"=>37,"text"=>"Plantillas Colecta","selected"=>$opciones[37]),
					array("id"=>2,"text"=>"Clientes","selected"=>$opciones[2]),
					array("id"=>3,"text"=>"Tabla Mestra","selected"=>$opciones[3]),
					array("id"=>4,"text"=>"Contratación","selected"=>$opciones[4],"opened"=>true,"children"=>


						array(


							array("id"=>6,"text"=>"Vendedores","selected"=>$opciones[6]),
							array("id"=>7,"text"=>"Captadores","selected"=>$opciones[7]),
							array("id"=>8,"text"=>"Clinicas/Hospitales","selected"=>$opciones[8]),

							array("id"=>9,"text"=>"Medios de Captación","selected"=>$opciones[9]),

							array("id"=>10,"text"=>"Centros de Facturación","selected"=>$opciones[10]),

							array("id"=>11,"text"=>"Lista de Precios","selected"=>$opciones[11]),

							array("id"=>12,"text"=>"Servicios","selected"=>$opciones[12]),

							array("id"=>13,"text"=>"Doctores/Médicos","selected"=>$opciones[13])




						)


					),
					array("id"=>5,"text"=>"Anualidad","selected"=>$opciones[5],"opened"=>true,"children"=>

						array(

							array("id"=>14,"text"=>"Configuracion Responsables","selected"=>$opciones[14]),
							array("id"=>15,"text"=>"Configuracion de Documentos","selected"=>$opciones[15])


						)



					)
					


				)

			),

			array("id"=>48,"text"=>"Captación","selected"=>$opciones[48],"opened"=>true,"children"=>

				array(


					array("id"=>49,"text"=>"Captación Individual","selected"=>$opciones[49]),
					array("id"=>50,"text"=>"Captación Masiva","selected"=>$opciones[50]),
					array("id"=>51,"text"=>"Validar Duplicados","selected"=>$opciones[51]),
					array("id"=>52,"text"=>"Tracking","selected"=>$opciones[52]),
					array("id"=>53,"text"=>"Asignar Vendedor","selected"=>$opciones[53]),
					array("id"=>68,"text"=>"Validacion Masiva de Captaciones","selected"=>$opciones[68]),


					array("id"=>61,"text"=>"Reportes","selected"=>$opciones[61],"opened"=>true,"children"=>

						array(

							array("id"=>62,"text"=>"Grafico por periodo y compañia","selected"=>$opciones[62]),
							array("id"=>63,"text"=>"Grafico por compañia y estado","selected"=>$opciones[63]),
							array("id"=>64,"text"=>"Grafico por compañia y medio de captación","selected"=>$opciones[64]),
							array("id"=>66,"text"=>"Grafico por compañia y vendedor","selected"=>$opciones[66]),
							array("id"=>67,"text"=>"Grafico por compañia y captador","selected"=>$opciones[67]),
							array("id"=>74,"text"=>"Reporte de Incentivos","selected"=>$opciones[74])
							


						)



					)



				)

		),


			array("id"=>54,"text"=>"CRM","selected"=>$opciones[54],"opened"=>true,"children"=>

				array(


					array("id"=>55,"text"=>"Control de Tareas","selected"=>$opciones[55]),
					array("id"=>56,"text"=>"Contrato Directo","selected"=>$opciones[56]),
					array("id"=>57,"text"=>"Bandeja de Tareas","selected"=>$opciones[57]),
					array("id"=>58,"text"=>"Tipo de Atención","selected"=>$opciones[58]),
					array("id"=>59,"text"=>"Reasignar Vendedores","selected"=>$opciones[59]),
					array("id"=>60,"text"=>"Mis Prospectos Atendidos","selected"=>$opciones[60]),
					array("id"=>65,"text"=>"Prospectos Vencidos por Perder","selected"=>$opciones[65]),
					array("id"=>77,"text"=>"Reportes","selected"=>$opciones[77],"opened"=>true,"children"=>

						array(

							array("id"=>78,"text"=>"Reporte Incentivo - Citas","selected"=>$opciones[78]),
							
							


						)



					)
					



				)

		)
			,
			array("id"=>16,"text"=>"Prospectos","selected"=>$opciones[16],"opened"=>true,"children"=>


				array(

					array("id"=>17,"text"=>"Prospectos Pendientes","selected"=>$opciones[17]),

					array("id"=>18,"text"=>"Prospectos Histórico","selected"=>$opciones[18]),

				)
			),
			array("id"=>19,"text"=>"Contratos","selected"=>$opciones[19],"opened"=>true,"children"=>


				array(

					array("id"=>20,"text"=>"Contratos","selected"=>$opciones[20]),
					array("id"=>47,"text"=>"Seguimiento Contratos","selected"=>$opciones[47]),
					array("id"=>40,"text"=>"Pago a Médicos","selected"=>$opciones[40]),

					array("id"=>43,"text"=>"Pago Realizado a Médicos","selected"=>$opciones[43]),
					array("id"=>44,"text"=>"Seguimiento Exámen Serologico","selected"=>$opciones[44]),
					array("id"=>45,"text"=>"Informe Laboratorio","selected"=>$opciones[45]),
					array("id"=>46,"text"=>"Seguimiento Captador","selected"=>$opciones[46]),
					array("id"=>69,"text"=>"Cobranzas","selected"=>$opciones[69],"opened"=>true,"children"=>


						array(


							array("id"=>70,"text"=>"Contratos por Cobrar","selected"=>$opciones[70]),
							array("id"=>71,"text"=>"Contratos Cobrados por Periodo","selected"=>$opciones[71]),
							




						)


					),
					array("id"=>75,"text"=>"Reportes","selected"=>$opciones[75],"opened"=>true,"children"=>


						array(


							array("id"=>76,"text"=>"Control de Incentivos","selected"=>$opciones[76])
							
							




						)


					)

					

				)
			)




			,array("id"=>35,"text"=>"Colectas","selected"=>$opciones[35],"opened"=>true,"children"=>


				array(

					array("id"=>36,"text"=>"Constancia de Recojo","selected"=>$opciones[36])

					

				)
			),array("id"=>39,"text"=>"Anualidad","selected"=>$opciones[39],"opened"=>true,"children"=>


				array(

					array("id"=>23,"text"=>"Historial de Bitácora","selected"=>$opciones[23]),
					array("id"=>25,"text"=>"Llamada de Cobranza","selected"=>$opciones[25]),
						array("id"=>72,"text"=>"Reportes","selected"=>$opciones[72],"opened"=>true,"children"=>

						array(

							array("id"=>73,"text"=>"Reporte de Resultado de Anualidad","selected"=>$opciones[73]),
						


						)



					)

					

				)
			),
			array("id"=>21,"text"=>"Facturación","selected"=>$opciones[21],"opened"=>true,"children"=>

				array(

					array("id"=>22,"text"=>"Registro de Pago","selected"=>$opciones[22]),
					
					array("id"=>24,"text"=>"Documento de Venta","selected"=>$opciones[24]),
					
					array("id"=>26,"text"=>"Facturación Directa","selected"=>$opciones[26]),
					
					array("id"=>27,"text"=>"Asistente de Facturación","selected"=>$opciones[27]),

					array("id"=>41,"text"=>"Facturación Masiva","selected"=>$opciones[41]),

					array("id"=>42,"text"=>"Historial Facturación Masiva","selected"=>$opciones[42])


				)

			),
			array("id"=>32,"text"=>"Reporte Analisis","selected"=>$opciones[32],"opened"=>true,"children"=>

				array(


					array("id"=>33,"text"=>"Reportes","selected"=>$opciones[33])



				)

		),array("id"=>28,"text"=>"Seguridad","selected"=>$opciones[28],"opened"=>true,"children"=>

				array(


					array("id"=>29,"text"=>"Usuarios","selected"=>$opciones[29]),
					array("id"=>30,"text"=>"Permisos","selected"=>$opciones[30]),
					array("id"=>31,"text"=>"Tipo de Cambio","selected"=>$opciones[31]),
					array("id"=>38,"text"=>"Correos","selected"=>$opciones[38])



				)

		)


		);

		return response()->json($menu);

	}

	
	

	


	protected function salvar_botonera_menu(Request $request)
	{      

		//valida captacion servicios automatico
		 
        $AUTO = $request->sw_automatico_cap_vendedor;

        $ser = $request->registroServ;

        if($AUTO==1 && empty($ser)){

        	 return $this->setRpta("error","Seleccione algun servicio si elige configuracion automatica en Captación - asignar vendedor");
        }


		$rpta = Botones::salvar_botonera_menu($request);
		
		
		 if($rpta){
              
              return $this->setRpta("ok","Se procesó de correctamente");
          }

        return $this->setRpta("error","Ocurrió un error al guardar");

	}


	protected function get_botones_usuario_por_compania(Request $request){

		

		$list =  Botones::where([['USUARIO',$request->codigo],['NO_CIA',$request->cia]])->first();

		

		return response()->json($list);
	}


    
}

