<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\TablaMaestra;

class TablaMaestraController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function index(){

      $middleRpta = $this->valida_url_permisos(3);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


      $empresa_user = Auth::user()->empresa;

      return View('mantenimiento.tabla_maestra.index',compact('empresa_user'));

    }  


    public function list_tablaMaestra()
	{      

		$list = TablaMaestra::list_tablaMaestra();
		
		return response()->json($list);

	}


	public function list_tablaMaestra_adm(Request $request)
	{      

		$list = TablaMaestra::list_tablaMaestra_adm($request);
		
		return response()->json($list);

	}


	protected function anular_tablaMaestra_adm(Request $request){      

        $rpta = TablaMaestra::anular_tablaMaestra_adm($request);


        if($rpta == 1){

           	return $this->setRpta("ok","Se desactivó sastisfactoriamente");
        }
        else if($rpta == 0){

        	return $this->setRpta("error","Error: el registro es utilizado");
        }
      
        return $this->setRpta("error","Ocurrió un error");
    }





    protected function valida_nuevo_registro($request){      

        
        if($request->modo=='editar'){


           return $this->setRpta("ok","valido sastisfactoriamente");

        }else{


          $rpta = TablaMaestra::valida_nuevo_registro($request);


          if($rpta == 1){

              return $this->setRpta("error","El código ya se encuentra en uso");
          }
        
      
           return $this->setRpta("ok","valido sastisfactoriamente");


        }

       
    }



     protected function save_edit_tablamaestra(Request $request){      

    	DB::beginTransaction();

        try {

            $valida = $this->valida_nuevo_registro($request);


            if($valida["status"] == "ok"){


                $rpta = TablaMaestra::save_edit_tablamaestra($request);
            
                if($rpta == 1){

                    DB::commit();

                    return $this->setRpta("ok","Se procesó correctamente");
                }
            

                DB::rollback();
              
                return $this->setRpta("error","Ocurrió un error");



            }

            return $valida;


        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }
  	}

	
}