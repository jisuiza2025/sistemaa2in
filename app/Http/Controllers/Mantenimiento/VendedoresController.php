<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\User;
use Auth;

class VendedoresController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
  	
	
	 public function index()
	{      

    $middleRpta = $this->valida_url_permisos(6);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

		$empresa_user = Auth::user()->empresa;

	 
   $btn_nuevo = $this->botones_usuario('mant_vendedor_nuevo');

   $btn_editar = $this->botones_usuario('mant_vendedor_edit');


   $tipos = Maestro::get_cad_listado(18,'TIPOVEND');

   $categorias= Maestro::get_cad_listado(19,'CLASVEND');

   $servicios_list=Maestro::cmb_servicios_filtro_all();

		return View('mantenimiento.vendedores.index',compact('empresa_user','btn_nuevo','btn_editar','tipos','categorias','servicios_list'));

	}


  protected function valida_nuevo_usuario_mant_vendedor(Request $request){      

    $empresa = Auth::user()->empresa;

    $vendedor = $request->usuario;

    $query = DB::select("SELECT * FROM VEN_VENDEDORES WHERE NO_CIA=? AND IDENTIFICACION=?",array($empresa,$vendedor));

    if(count($query)==0){

      return $this->setRpta("ok","validó correctamente");

    }

    return $this->setRpta("error","El vendedor ya se encuentra registrado");
  }


  protected function valida_nuevo_usuario_mant_captador(Request $request){      

    $empresa = Auth::user()->empresa;

    $captador = $request->captador;

    $query = DB::select("SELECT * FROM VEN_CAPTADORES WHERE NO_CIA=? AND IDENTIFICACION=?",array($empresa,$captador));

    if(count($query)==0){

      return $this->setRpta("ok","validó correctamente");

    }

    return $this->setRpta("error","El captador ya se encuentra registrado");
  }

	 protected function save_vendedor(Request $request){      

    DB::beginTransaction();

        try {

            $rpta = User::save_vendedor($request);
            
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
	 

	//captadores
  

   public function captadores()
  {      


    $middleRpta = $this->valida_url_permisos(7);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    $empresa_user = Auth::user()->empresa;


    $btn_nuevo = $this->botones_usuario('mant_captador_nuevo');

    $btn_editar = $this->botones_usuario('mant_captador_edit');

   $cmb_categoria = Maestro::list_categorizacion_cmb();

   $cmb_nuevo_servicios = Maestro::cmb_servicios_filtro();

    return View('mantenimiento.captadores.index',compact('empresa_user','btn_nuevo','btn_editar','cmb_categoria','cmb_nuevo_servicios'));

  }

  protected function save_captador(Request $request){      

    DB::beginTransaction();

        try {

            $rpta = User::save_captador($request);
            
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

  //clinicas
  
   public function clinicas(){      


    $middleRpta = $this->valida_url_permisos(8);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

    $empresa_user = Auth::user()->empresa;


    $btn_nuevo = $this->botones_usuario('mant_hospital_nuevo');

    $btn_editar = $this->botones_usuario('mant_hospital_edit');

    $btn_delete = $this->botones_usuario('mant_hospital_elimina');

    return View('mantenimiento.clinicas.index',compact('empresa_user','btn_nuevo','btn_editar','btn_delete'));

  }
  

   protected static function get_item_clinica(Request $request){      

      
      

      if($request->clinica==='0')  {

        $list = array(array('ALIAS'=>'','CELULAR'=>'','CIUDAD'=>'','DIRECCION'=>'','DISTRITO'=>'','EMAIL'=>'','ESTADO'=>'ACT','IDENTIFICACION'=>'','OBSERVACIONES'=>'','RAZON_SOCIAL'=>'','TELEFONO'=>'','TIPO'=>'POLICLINICO','UBIGEO'=>'','DESUBIGEO'=>'','WEB'=>''));

      }else{

          $list = Maestro::get_item_clinica($request);
      }

      return response()->json($list); 

  }

  protected function valida_ruc_clinica($request){

    
      $edicion    = $request->tipo_edicion;
       
        
      $cia = Auth::user()->empresa;

      $ruc = trim($request->identificacion_clinica);
      
        $query = DB::select("SELECT IDENTIFICACION FROM VEN_HOSPITALES WHERE NO_CIA = ? AND IDENTIFICACION = ?",array($cia,$ruc));
        
        if($edicion==0 && count($query)>0){

          
            return $this->setRpta("error","El RUC ya se encuentra registrado");

        }elseif($edicion!=0 && count($query)>0){

            $ruc_json=json_decode(json_encode($query),true);
            
            if($edicion==trim($ruc_json[0]['identificacion'])){
                
                return $this->setRpta("ok","valido ruc");

            }else{

                return $this->setRpta("error","El RUC ya se encuentra registrado");
            }
            
        }

        return $this->setRpta("ok","valido ruc");
    


  }

  protected function save_clinica(Request $request){      

    DB::beginTransaction();

        try {

            $valida = $this->valida_ruc_clinica($request);

            if($valida['status']=="ok"){

              $rpta = User::save_clinica($request);
            
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



  protected function desactiva_clinica(Request $request){      

        $rpta = User::desactiva_clinica($request);


        if($rpta == 1){

           return $this->setRpta("ok","Se desactivó sastisfactoriamente");

        }
      
         return $this->setRpta("error","Ocurrió un error");
    }
}
