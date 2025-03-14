<?php


namespace App\Http\Controllers\Contrato; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;

use App\Contrato;


use Auth;
use Carbon\Carbon;


class SeguimientoControlController extends Controller
{	


	public function __construct()
    {
        $this->middleware('auth');
    }
    
  

    public function seguimiento_control()
    {
        

        $middleRpta = $this->valida_url_permisos(47);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }



        $empresa_user = Auth::user()->empresa;
        
        $responsables_prop =  Maestro::filter_responsable(); 

        
         $btn_editar = $this->botones_usuario('seg_contrato_editar');
       

        return View('contrato.seguimiento_contratos',compact('empresa_user','responsables_prop','btn_editar'));
    }


    protected function list_contratos_seguimiento(Request $request)
  {      


    $list = Contrato::list_contratos_seguimiento($request);
    
    return response()->json($list);

  }


    protected function confirmar_edicion_control_contrato(Request $request)
  {      

    //valida fechas 
    

    // if(empty($request->entrega)){

    //       return $this->setRpta("error","Fecha de entrega es obligatorio");
    // }

    //  if(empty($request->firma)){

    //       return $this->setRpta("error","Fecha de firma es obligatorio");
    // }

    //  if(empty($request->comision)){

    //       return $this->setRpta("error","Fecha de pago de comisión es obligatorio");
    // }

    $rpta = Contrato::confirmar_edicion_control_contrato($request);
    
   

                if($rpta == 1){

                 

                    return $this->setRpta("ok","Se procesó correctamente");

                }
          
             

                return $this->setRpta("error","Ocurrió un error");


  }




}




  