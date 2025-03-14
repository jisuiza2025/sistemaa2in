<?php

namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Responsable;
use App\Maestro;
use Auth; 

class ResponsableController extends Controller
{
    public function index(){

        $middleRpta = $this->valida_url_permisos(14);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    	$empresa_user = Auth::user()->empresa;


        $btn_nuevo = $this->botones_usuario('mant_config_resp_nuevo');

        $btn_editar = $this->botones_usuario('mant_config_resp_edit');

        $btn_eliminar = $this->botones_usuario('mant_config_resp_elim');

    	return view('mantenimiento.responsable.index', compact('empresa_user','btn_nuevo','btn_editar','btn_eliminar'));
    }

    protected function list_responsable(){

    	$list=Responsable::list_responsable();

    	return response()->json($list);
    } 

     protected function get_obtener_datos(Request $request){

        if($request->cod_user == ""){

            $list = array(array("USUARIO"=>'',"DESDE"=>'0.0',"HASTA"=>'0.0',"PAR"=>'S',"IMPAR"=>'S',"AVISO_COBRANZA"=>'S',"ATENDER"=>'1'));


        }else{

            $list=Responsable::get_obtener_datos($request);
        }
    	

    	return response()->json($list);
    } 

    protected function eliminar_responsable(Request $request){

    	$rpta=Responsable::eliminar_responsable($request);

    	if($rpta == 1){

           return $this->setRpta("ok","Se eliminó sastisfactoriamente");

        }
      
         return $this->setRpta("error","Ocurrió un error");
    } 

    protected function validar_responsable($request){

        if($request->accion == 0){

            $rpta = Responsable::validar_responsable($request);

            if($rpta == 0){

                return $this->setRpta("ok","Usuario no tiene registro");

            }
      
            return $this->setRpta("error","Usuario ya tiene registro");

        }else{

            return $this->setRpta("ok","Usuario no tiene registro");

            
        }

    	
    } 

        protected function save_responsable(Request $request){


        $valida = $this->validar_responsable($request);

        if($valida["status"] == "ok"){

            $rpta = Responsable::save_responsable($request);

            if($rpta[0] == 0){

                  return $this->setRpta("error",$rpta[1]);
                  
               

            }
      
          
             return $this->setRpta("ok","Se procesó correctamente");
        }

        return $valida;
        
    }
}
