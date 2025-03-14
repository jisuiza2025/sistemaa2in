<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\TipoCambio;
use Carbon\Carbon;

class TipoCambioController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function index(){

      $middleRpta = $this->valida_url_permisos(31);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

      $empresa_user = Auth::user()->empresa;

      $clase_cambio = Maestro::list_clase_cambio();

      $monedas = Maestro::list_monedas();

      return View('mantenimiento.tipo_cambio.index',compact('clase_cambio','monedas','empresa_user'));

    }  


    public function list_tipoCambio(Request $request)
	{      

		$list = TipoCambio::list_tipoCambio($request);
		
		return response()->json($list);

	}


    protected function valida_nuevo_tipo_cambio($request){

        if($request->codigo == 0){

            $rpta = TipoCambio::valida_nuevo_tipo_cambio($request);

            if( $rpta == 1){

              return $this->setRpta("error","El tipo de cambio ya existe");
            }

            return $this->setRpta("ok","validó correctamente");

        }

        return $this->setRpta("ok","validó correctamente");
    }


    public function save_tipoCambio(Request $request){      

      

    	DB::beginTransaction();

        try {

            $validate_tcambio = $this->valida_nuevo_tipo_cambio($request);

            if($validate_tcambio["status"] == "ok"){

               $rpta = TipoCambio::save_tipoCambio($request);
            
               if($rpta == 1){

                  DB::commit();

                  return $this->setRpta("ok","Se procesó correctamente");

               }

                DB::rollback();

                return $this->setRpta("error","Ocurrió un error");

            }

            return  $validate_tcambio;
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }
  	}


    
}