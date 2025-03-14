<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Peru\Jne\DniFactory;
use Auth;
use Carbon\Carbon;

class DniController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
   
   public function search_dni($request) 
   {	

    

   		$dni = trim($request->dni);

   		require '../vendor/autoload.php';
		
		  $factory = new DniFactory();

		  $cs = $factory->create();

		  $person = $cs->get($dni);

      

		  if (!$person) {
    		
    		  return $this->setRpta("error","No se encontraron registros");
		  }

		
			return $this->setRpta("ok","Consulta Exitosa Reniec",$person);	

        
    }


   
    public function get_ubigeo_text($id_ubigeo){


      $list = DB::select("SELECT DEPARTAMENTO||' / '||PROVINCIA||' / '||DISTRITO AS FULLUBIGEO FROM cor_ubicaciones_geograficas WHERE CODIGO_UBIGEO=?",array($id_ubigeo));


      $descripcion = $list[0]->fullubigeo;

      return $descripcion;

    }
    //busqueda en prospecto
    

    public function search_bd_cliente(Request $request){


      $documento = trim($request->dni);

      $no_cia = Auth::user()->empresa;

      $list = DB::select('SELECT * FROM VEN_CLIENTES WHERE IDENTIFICACION=? and NO_CIA=?',array($documento,$no_cia));

      if(count($list)==0){

          
          return $this->search_dni($request);

      }else{

          $data  = array('dni'=>$list[0]->identificacion ,
                        'correo'=>$list[0]->mail_contacto ,
                        'celular'=>$list[0]->celular_contacto ,
                        'nombres'=>$list[0]->nombre_corto,
                        'apellidoPaterno'=>$list[0]->apaterno,
                        'apellidoMaterno'=>$list[0]->amaterno,
                        'tipoDocumento'=>$list[0]->codigo_documento,
                        'direccion'=>$list[0]->direccion,
                        'pais'=>$list[0]->codigo_pais,
                        'Idubigeo'=>$list[0]->ubigeo,
                        'ubigeo'=>$this->get_ubigeo_text($list[0]->ubigeo),
                        'estadoCivil'=>$list[0]->estado_civil,
                        'nacimiento'=>(!empty($list[0]->fecha_nacimiento))?Carbon::parse($list[0]->fecha_nacimiento)->format('Y-m-d'):null

                      );


          return $this->setRpta("ok","Consulta Exitosa DB",$data); 
      }


    }
 
    
}