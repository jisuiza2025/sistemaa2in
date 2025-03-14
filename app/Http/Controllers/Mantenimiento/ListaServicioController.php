<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\ServicioLista;

class ListaServicioController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
   
   

    public function index(){


      $middleRpta = $this->valida_url_permisos(12);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

      $list_servicios = Maestro::get_list_servicio();

      $empresa_user = Auth::user()->empresa;

      $servicios_prop = Maestro::get_list_servicio2();


      $btn_nuevo = $this->botones_usuario('mant_servicios_nuevo');

      $btn_editar = $this->botones_usuario('mant_servicios_editar');

      return View('mantenimiento.servicio_lista.index',compact('list_servicios','empresa_user','servicios_prop','btn_nuevo','btn_editar'));

    }

    
    protected function save_servicio(Request $request){      

    DB::beginTransaction();

        try {

            $rpta = ServicioLista::save_servicio($request);
            
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
   
 
    
}