<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\MediosCaptacion;

class MediosCaptacionController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function index(){


      $middleRpta = $this->valida_url_permisos(9);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

      $empresa_user = Auth::user()->empresa;

      
      $btn_nuevo = $this->botones_usuario('mant_mediocapt_nuevo');

      $options_tablas_d1 = Maestro::options_tablas_d1();

      $options_efectividad= Maestro::list_cor_tablas(25);

      return View('mantenimiento.medios_captacion.index',compact('empresa_user','btn_nuevo','options_tablas_d1','options_efectividad'));

    }  


    public function list_mediosCaptacion()
	{      

		$list = MediosCaptacion::list_mediosCaptacion();
		
		return response()->json($list);

	}

	public function list_mediosCaptacion_Detalle(Request $request)
	{   
		
		$list = MediosCaptacion::list_mediosCaptacion_Detalle($request);
		
		return response()->json($list);

	}


  public function get_subdetails_medios_captacion(Request $request)
  {   
    
    $list = MediosCaptacion::get_subdetails_medios_captacion($request);
    
    return response()->json($list);

  }

   public function get_subdetails_medios_captacion_edit(Request $request)
  {   
    
    if($request->codigo_evento=='nuevo'){

      $list = array(array("SE_INFORMO"=>'',
                             "DESCRIPCION"=>'',
                             "CODIGO"=>'',
                             "CODIGO_EVENTO"=>'',
                             "TEMA"=>'',
                             "DIRECCION"=>'',
                             "FECHA_DESDE"=>'',
                             "FECHA_HASTA"=>'',
                             "ASISTENTES"=>'',
                             "ESTADO"=>'A',
                             "OBSERVACIONES"=>'',
                             "EFECTIVIDAD"=>'',
                           )
                );
    }else{

      $list = MediosCaptacion::get_subdetails_medios_captacion_edit($request);

    }
    
    
    return response()->json($list);

  }

	public function get_item_mediosCaptacion_nivel_1(Request $request)
	{   
		
		$list = MediosCaptacion::get_item_mediosCaptacion_nivel_1($request);

		if($request->codigo == 'Nuevo'){
		                    
          //nuevo tipoDocumento
          $list = array(array("CODIGO"=>'',
                             "DESCRIPCION"=>'',
                             "ESTADO"=>'ACT',
                             "TABLAD2"=>null,
                             "EFECTIVIDAD"=>'',
                           )
                );
        }
		
		return response()->json($list);

	}



	protected function get_item_mediosCaptacion(Request $request){      

		$list = MediosCaptacion::get_item_mediosCaptacion($request);

		if($request->identificacion == 'Nuevo'){
		                    
          //nuevo tipoDocumento
          $list = array(array("CODIGO"=>'',
                             "DESCRIPCION"=>'',
                             "ESTADO"=>'ACT',
                           )
                );
        }

        return response()->json($list);    
    }


    protected function save_MediosCaptacion(Request $request){      

    	DB::beginTransaction();

        try {

            $rpta = MediosCaptacion::save_MediosCaptacion($request);
            
            if($rpta == 1){

              	DB::commit();

              	return $this->setRpta("ok","Se procesó correctamente");
            }
            else{
            	DB::rollback();
            	return $this->setRpta("error","Ocurrió un error");
            }

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }
  	}


    protected function save_sub_details_medio_captacion(Request $request){      

      DB::beginTransaction();

        try {

            $rpta = MediosCaptacion::save_sub_details_medio_captacion($request);
            
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

  	protected function save_MediosCaptacion_nivel_1(Request $request){      

    	DB::beginTransaction();

        try {

            $rpta = MediosCaptacion::save_MediosCaptacion_nivel_1($request);
            
            if($rpta == 1){

              	DB::commit();

              	return $this->setRpta("ok","Se procesó correctamente");
            }
            else{
            	DB::rollback();
            	return $this->setRpta("error","Ocurrió un error");
            }

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }
  	}



  	
}