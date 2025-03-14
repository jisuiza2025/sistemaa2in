<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\CentrosFacturacion;
use Auth;
use PDO;

class CentrosFactController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
  	
	
	 public function index()
	{      


		$middleRpta = $this->valida_url_permisos(10);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

		$empresa_user = Auth::user()->empresa;

		$documentos = Maestro::documentos_centros_facturacion();

		$usuarios = Maestro::usuarios_autocompletar_cf();


		$btn_nuevo = $this->botones_usuario('mant_cenfact_nuevo');

		$btn_asigna_usuario = $this->botones_usuario('mant_cenfact_asigusu');

		$btn_asigna_documento = $this->botones_usuario('mant_cenfact_asigdoc');


		return View('mantenimiento.centros_facturacion.index',compact('empresa_user','documentos','usuarios','btn_nuevo','btn_asigna_usuario','btn_asigna_documento'));

	}

	 
	 
	 protected  function get_asignaciones_centro(Request $request){

	  	$list = CentrosFacturacion::get_asignaciones_centro($request);

	  	return response()->json($list);

	  }


	  protected  function get_item_series_centrofact(Request $request){

	  	$list = CentrosFacturacion::get_item_series_centrofact($request);

	  	return response()->json($list);

	  }


	 protected  function save_centros_facturacion(Request $request)
	{      
		
		DB::beginTransaction();

        try {

        	

        	$rpta = CentrosFacturacion::save_centros_facturacion($request);

            if($rpta == 1){

                  DB::commit();

                  return $this->setRpta("ok","Se procesó correctamente");

             }
          
              DB::rollback();

              return $this->setRpta("error","Ocurrió un error");
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }


	}



protected function valida_insertar_usuario_fact($request){

	$rpta = CentrosFacturacion::valida_insertar_usuario_fact($request);

	return $rpta;

}


protected  function inserta_usuario_centro_fact(Request $request)
	{     

		$valida = $this->valida_insertar_usuario_fact($request);

		if($valida == 1){

			$rpta = CentrosFacturacion::inserta_usuario_centro_fact($request);

         	if($rpta == 1){

            
             	return $this->setRpta("ok","Se Insertó correctamente");


         	}


         	return $this->setRpta("error","Ocurrió un error");

		}

		return $this->setRpta("error","El Centro no tiene documentos");

	}



protected  function delete_usuario_asignado(Request $request)
	{      
		$rpta = CentrosFacturacion::delete_usuario_asignado($request);

         if($rpta == 1){

            
             return $this->setRpta("ok","Se eliminó correctamente");


         }


         return $this->setRpta("error","Ocurrió un error");

	}



	protected  function delete_documento_centro(Request $request)
	{      
		$rpta = CentrosFacturacion::delete_documento_centro($request);

         if($rpta == 1){

            
             return $this->setRpta("ok","Se eliminó correctamente");


         }else if($rpta == 0){

            
            return $this->setRpta("error","No se puede eliminar porque existen documentos con el correlativo");
         }


         return $this->setRpta("error","Ocurrió un error");

	}
	

	protected  function inserta_documento_centrofact(Request $request)
	{      
		$rpta = CentrosFacturacion::inserta_documento_centrofact($request);

         if($rpta == 1){

            
             return $this->setRpta("ok","Se procesó correctamente");


         }


         return $this->setRpta("error","Ocurrió un error");

	}
	

    
}

