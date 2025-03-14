<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\Contrato;
use App\User;


class PlantillasController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
  	
	
	 public function index()
	{      

		$middleRpta = $this->valida_url_permisos(34);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

		$empresa_user = Auth::user()->empresa;

		
		

		$tipo_plantillas_tipo = Maestro::get_tipos_plantillas_tipo_mantenimiento();

		$aseguradoras = Maestro::get_aseguradoras_plantillas_mantenimiento();

		
		



		return view('mantenimiento.plantillas.index', compact('empresa_user','tipo_plantillas_tipo','aseguradoras'));

	}

	
	
	protected function list_tabla_plantillas(Request $request)
	{      

		$list = Contrato::list_tabla_plantillas($request);
		
		return response()->json($list);

	}
	


	protected function get_planes_by_aseguradora(Request $request)
	{      

		$list = Maestro::get_planes_by_aseguradora($request);
		
		return response()->json($list);

	}

	

	protected function get_data_plantilla(Request $request)
	{      

		$list = Contrato::get_data_plantilla($request);
		
		return response()->json($list);

	}



	protected function get_parametros_plantillas(Request $request)
	{      

		$list = Contrato::get_parametros_plantillas($request);
		
		return response()->json($list);

	}
	
	
	

	protected function descargar_plantillas_contratos($file)
	{      


		$pathtoFilePago = (Auth::user()->empresa=='001') ? public_path().'/formatos_nuevos/ICTC/'.$file : public_path().'/formatos_nuevos/LAZO_DE_VIDA/'.$file;


	
        if (file_exists($pathtoFilePago)) {
            
            return response()->download($pathtoFilePago);

        } else {
        
            return $this->redireccion_404();
        }

		

	}

	protected function valida_existencia_plantilla($request){

		

		$flag = ($request->flag=='EDITAR')?1:0;


		$variable=$request->vm_tipo_plantilla;

		$cia = Auth::user()->empresa;
		$tipo = $request->vm_tipo_sangre;
		$aseguradora = (empty($request->vm_aseguradora))?0:$request->vm_aseguradora;;
		$plan = (empty($request->vm_plan))?0:$request->vm_plan;;
		$mama_soltera = $request->mama_soltera;


		$id_plantilla = $request->id_plantilla;


		$query=DB::select("SELECT * FROM VEN_PLANTILLA_CONTRATOS WHERE TIPO_PLANTILLA=? AND NO_CIA=? and TIPO=? AND RUC_ASEGURADORA=? AND PLAN_ASEGURADORA=? AND MAMA_SOLTERA=?",array($variable,$cia,$tipo,$aseguradora,$plan,$mama_soltera));



		

		if($flag == 1){



			if(count($query)>0){


					$decode = json_decode(json_encode($query),true);
			
					$rpta = $decode[0]['id_plantilla'];

					if($rpta == $id_plantilla){

						return $this->setRpta("ok","valido correctamente"); 

					}else{

						return $this->setRpta("error","Esta plantilla ya se encuentra registrada");

					}


			}else{

				return $this->setRpta("ok","valido correctamente");


			}




			


		}else{

			$rows = count($query);

			if($rows>0){

				return $this->setRpta("error","Esta plantilla ya se encuentra registrada"); 
			}else{

				return $this->setRpta("ok","valido correctamente"); 

			}



		}


		



		



	}

	protected function salvar_nueva_plantilla(Request $request){   

		$valida_existencia = $this->valida_existencia_plantilla($request);

		if($valida_existencia["status"]=="ok"){

			$rpta = Contrato::salvar_nueva_plantilla($request);

		 	if($rpta == 1){ 

            	return $this->setRpta("ok","Se procesó correctamente"); 

        	}else if($rpta == 3){

        		return $this->setRpta("error","Seleccione un archivo con extensión :.docx"); 

        	}else if($rpta == 4){

        		return $this->setRpta("error","Adjunte un archivo"); 

        	}
     
			return $this->setRpta("error","Ocurrió un error"); 


		}

		return $valida_existencia;

	} 




	 public function plantillas_colectas()
	{      



		$middleRpta = $this->valida_url_permisos(37);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


		$empresa_user = Auth::user()->empresa;
		
		$tipos_plantilla_tipo = Contrato::tipos_plantilla_colectas();


		return view('mantenimiento.plantillas.colectas', compact('empresa_user','tipos_plantilla_tipo'));

	}
    
    

    protected function list_tabla_plantillas_colectas(Request $request)
	{      

		$list = Contrato::list_tabla_plantillas_colectas($request);
		
		return response()->json($list);

	}



	protected function valida_existencia_colecta($request){

		//para colectas


		$formato = $request->vm_formato_plantilla;

        $tipo = $request->vm_tipo_plantilla;

        $mama_soltera = ($request->mama_soltera)?'S':'N';

        $id_plantilla = trim($request->id_plantilla);

		$cia = Auth::user()->empresa;
		


		$query=DB::select("SELECT * FROM LAB_PLANTILLA_COLECTAS WHERE NO_CIA=? and TIPO=? AND MAMA_SOLTERA=?  AND MAIL_PLANTILLA=?",array($cia,$tipo,$mama_soltera,$formato));




		if($id_plantilla != '0'){


			if(count($query)>0){


				$decode = json_decode(json_encode($query),true);
			
				$rpta = $decode[0]['id_plantilla'];

				if($rpta == $id_plantilla){

					return $this->setRpta("ok","valido correctamente"); 

				}else{

					return $this->setRpta("error","Esta plantilla ya se encuentra registrada");

				}


			}else{

				return $this->setRpta("ok","valido correctamente");

			}





		}else{

			$rows = count($query);

			if($rows>0){

				return $this->setRpta("error","Esta plantilla ya se encuentra registrada"); 
			}else{

				return $this->setRpta("ok","valido correctamente"); 

			}



		}



	}



	protected function salvar_nueva_plantilla_colectas(Request $request)
	{      

		$valida_existencia_colecta = $this->valida_existencia_colecta($request);

		if($valida_existencia_colecta["status"]=="ok"){


			$rpta = Contrato::salvar_nueva_plantilla_colectas($request);

		 	if($rpta == 1){ 

            	return $this->setRpta("ok","Se procesó correctamente"); 

        	}else if($rpta == 3){

        		return $this->setRpta("error","Seleccione un archivo con extensión :.docx"); 

        	}else if($rpta == 4){

        		return $this->setRpta("error","Adjunte un archivo"); 

        	}else if($rpta == 5){

        		return $this->setRpta("error","El archivo sobrepasa el límite de 12 MB"); 

        	}
     
			return $this->setRpta("error","Ocurrió un error");

		}else{


			return $valida_existencia_colecta;

		}

		 

	}

	protected function descargar_plantillas_colectas($id)
	{      

		  $cia = Auth::user()->empresa;

		  $query =  DB::select("SELECT NOMBRE_ARCHIVO FROM LAB_PLANTILLA_COLECTAS WHERE NO_CIA = ? AND ID_PLANTILLA = ? ",array($cia,$id));


              $decode = json_decode(json_encode($query),true);
           
              $file  = $decode[0]['nombre_archivo'];

		
		$pathtoFilePago = (Auth::user()->empresa=='001') ? public_path().'/formatos_colectas/ICTC/'.$file : public_path().'/formatos_colectas/LAZO_DE_VIDA/'.$file;


	
        if (file_exists($pathtoFilePago)) {
            
            return response()->download($pathtoFilePago);

        } else {
        
            return $this->redireccion_404();
        }


	}

	protected function get_data_plantilla_colectas(Request $request)
	{      

		
		$list = Contrato::get_data_plantilla_colectas($request);
		
		return response()->json($list);

	}


	protected function get_parametros_plantillas_colectas(Request $request)
	{      

		$list = Contrato::get_parametros_plantillas_colectas($request);
		
		return response()->json($list);

	}
	

	
}

