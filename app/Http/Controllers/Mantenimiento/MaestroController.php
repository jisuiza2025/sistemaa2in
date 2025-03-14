<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;


class MaestroController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
  	
	 public function filter_medio_concatenado(Request $request)
	{      

		$list = Maestro::filter_medio_concatenado($request);
		
		return response()->json($list);

	}

	
	 public function filter_ubigeo(Request $request)
	{      

		$list = Maestro::filter_ubigeo($request);
		
		return response()->json($list);

	}

	 public function filter_cliente(Request $request)
	{      

		$list = Maestro::filter_cliente($request);
		
		return response()->json($list);

	}



	

	 public function filter_vendedor(Request $request)
	{      

		$list = Maestro::filter_vendedor($request);
		
		return response()->json($list);

	}

	 public function filter_terapeuta(Request $request)
	{      

		$list = Maestro::filter_terapeuta($request);
		
		return response()->json($list);

	}
	

	 public function filter_clinica(Request $request)
	{      

		$list = Maestro::filter_clinica($request);
		
		return response()->json($list);

	}

	 public function filter_captador(Request $request)
	{      

		$list = Maestro::filter_captador($request);
		
		return response()->json($list);

	}

	 public function filter_medico(Request $request)
	{      

		$list = Maestro::filter_medico($request);
		
		return response()->json($list);

	}

	 public function list_captacion()
	{      

		$list = Maestro::list_captacion();
		
		return response()->json($list);

	}

	 public function list_captacion_tipo(Request $request)
	{      

		$list = Maestro::list_captacion_tipo($request);
		
		return response()->json($list);

	}

	 public function list_captacion_ficha_tabla(Request $request)
	{      

		$list = Maestro::list_captacion_ficha_tabla($request);
		
		return response()->json($list);

	}

	
	 public function list_captacion_ficha(Request $request)
	{      

		$list = Maestro::list_captacion_ficha($request);
		
		return response()->json($list);

	}


	 public function list_estado_prospecto(Request $request)
	{      

		$list = Maestro::list_estado_prospecto($request);
		
		return response()->json($list);

	}

	 public function list_precio(Request $request)
	{      

		$list = Maestro::list_precio($request);
		
		return response()->json($list);

	}

	 public function list_servicio(Request $request)
	{      

		$list = Maestro::list_servicio($request);
		
		return response()->json($list);

	}
    
    

   

	public function list_planes(Request $request)
	{      

		$list = Maestro::list_planes($request);
		
		return response()->json($list);

	}



	public function list_articulos(Request $request)
	{      

		$list = Maestro::list_articulos($request);
		
		return response()->json($list);

	}



	public function list_vendedores(Request $request)
	{      

		$list = Maestro::list_vendedores($request);
		
		return response()->json($list);

	}

	public function list_captadores(Request $request)
	{      

		$list = Maestro::list_captadores($request);
		
		return response()->json($list);

	}

	
	public function filter_cor_user(Request $request)
	{      

		$list = Maestro::filter_cor_user($request);
		
		return response()->json($list);

	}

	public function filter_cor_user2(Request $request)
	{      

		$list = Maestro::filter_cor_user2($request);
		
		return response()->json($list);

	}

	public function list_clinicas(Request $request)
	{      

		$list = Maestro::list_clinicas($request);
		
		return response()->json($list);

	}


	public function list_centros(Request $request)
	{      

		$list = Maestro::list_centros($request);
		
		return response()->json($list);

	}

}

