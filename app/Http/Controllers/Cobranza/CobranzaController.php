<?php


namespace App\Http\Controllers\Cobranza; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\Cobranza;
use Carbon\Carbon;
use Auth;
use App\Exports\ExportGeneral;
use Maatwebsite\Excel\Facades\Excel;

class CobranzaController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function contratos_por_cobrar()
	{      

		$middleRpta = $this->valida_url_permisos(70);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

		
         $btn_ver_todo = $this->botones_usuario('con_cob_ver');
          $btn_cambiar_cobrador = $this->botones_usuario('con_cob_cambiar_cob');



		$servicios = Maestro::list_cor_tablas(22);
		$periodos = Maestro::list_cor_tablas(28);

    	$empresa_user = Auth::user()->empresa;

    
    	
		return View('cobranza.contratos_por_cobrar',compact('servicios','periodos','empresa_user','btn_ver_todo','btn_cambiar_cobrador'));
		

	}	



	 public function contratos_cobrados_por_periodo()
	{      

		$middleRpta = $this->valida_url_permisos(71);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

		

        $btn_ver_todo = $this->botones_usuario('con_cob_ver');
          $btn_cambiar_cobrador = $this->botones_usuario('con_cob_cambiar_cob');



		$servicios = Maestro::list_cor_tablas(22);
	

    	$empresa_user = Auth::user()->empresa;

    
    	
		return View('cobranza.contratos_cobrados_por_periodo',compact('btn_ver_todo','btn_cambiar_cobrador','servicios','empresa_user'));
		

	}	


	

	  protected function contratos_por_cobrar_cobradores(Request $request)
  {      


    $list = Cobranza::contratos_por_cobrar_cobradores($request);
    
    return response()->json($list);

  }




  protected function contratos_cobrados_lista(Request $request)
  {      


    $list = Cobranza::contratos_cobrados_lista($request);
    
    return response()->json($list);

  }

    protected function contratos_por_cobrar_lista(Request $request)
  {      


    $list = Cobranza::contratos_por_cobrar_lista($request);
    
    $data = json_decode(json_encode($list),true);

    $setData = array();



   		foreach($data as $values){

				$setData[] = array(

					"SELECCIONA"=>false,
					"NUMERO_CONTRATO"=>$values['NUMERO_CONTRATO'],
					"SERVICIO"=>$values['SERVICIO'],
					"FECHA_CONTRATO"=>$values['FECHA_CONTRATO'],
					"FECHA_COLECTA"=>$values['FECHA_COLECTA'],
					"MONTO_CONTRATO"=>$values['MONTO_CONTRATO'],
					"PORC_CIA"=>$values['PORC_CIA'],
					"SALDO"=>$values['SALDO'],
					"COBRADOR"=>$values['COBRADOR'],
					"SGTECOBRADOR"=>$values['SGTECOBRADOR']
					

					);
				


			
			}



			return response()->json($setData);

  }





protected function confirmar_contratos_por_cobrar(Request $request){   

		

try {
  
DB::beginTransaction();


   $data = $request->data;



        if(count($data)==0){


        	return $this->setRpta("error","Seleccione algún elemento de la lista"); 
        }


  		$split = array_chunk($data,10);

         
           foreach($split as $sub){


              	$rpta = Cobranza::confirmar_contratos_por_cobrar($request,$sub);


	              if($rpta != 1){

	                
	                  DB::rollback();

	                  return $this->setRpta("error",'Ocurrió un error al guardar');
	              }


           
            }


      
          
         DB::commit();
         return $this->setRpta("ok",'Se procesó de manera correcta' ); 

	} catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }


} 


  





}
