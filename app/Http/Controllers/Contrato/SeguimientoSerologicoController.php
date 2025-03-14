<?php


namespace App\Http\Controllers\Contrato; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Serologia;


use Auth;
use Carbon\Carbon;
use App\Exports\ExportGeneral;
use Maatwebsite\Excel\Facades\Excel;

class SeguimientoSerologicoController extends Controller
{	


	public function __construct()
    {
        $this->middleware('auth');
    }
    
  
    protected function seguimiento_serologico(){




        $middleRpta = $this->valida_url_permisos(44);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

        $empresa_user = Auth::user()->empresa;

        $laboratorios  = Serologia::list_laboratorios();

        $responsable = strtoupper(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);


        $btn_servicio = $this->botones_usuario('seg_ser_ser');
        $btn_ver = $this->botones_usuario('seg_ser_ver');
        $btn_bitacora = $this->botones_usuario('seg_ser_bit');
        $btn_bitacora_list = $this->botones_usuario('seg_ser_lbit');
        $btn_consentimiento = $this->botones_usuario('seg_ser_cons');


        return View('contrato.seguimiento_serologico',compact('empresa_user','laboratorios','responsable','btn_servicio','btn_ver','btn_bitacora','btn_bitacora_list','btn_consentimiento'));

    }


     protected function list_seguimiento_serologico(Request $request)
  {      


        $list = Serologia::list_seguimiento_serologico($request);
    
        return response()->json($list);

  }


     protected function list_seguimiento_serologico_servicios(Request $request)
  {      


        $list = Serologia::list_seguimiento_serologico_servicios($request);
    
        return response()->json($list);

  }


     protected function ver_servicio_informacion_serologia(Request $request)
  {      


        $list = Serologia::ver_servicio_informacion_serologia($request);
    
        return response()->json($list);

  }
  

     protected function set_listado_bitacora_serologia(Request $request)
  {      


        $list = Serologia::set_listado_bitacora_serologia($request);
    
        return response()->json($list);

  }

   protected function pdf_consentimiento($contrato)
  {      



      $list = Serologia::pdf_consentimiento($contrato);
     
 
        $empresa_user = Auth::user()->empresa;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('A4');
        $pdf->loadView('reporte.serologia.consentimiento', compact('empresa_user','list'));
        return $pdf->stream();



      

  }

  
  
  


  protected function salvar_generacion_solicitud_serologia(Request $request){

      $rpta = Serologia::salvar_generacion_solicitud_serologia($request);

            if( $rpta == 1){

              return $this->setRpta("ok","Se procesó correctamente");
            }

            return $this->setRpta("error","ocurrió un error al generar solicitud");
    }



    protected function guarda_nueva_bitacora_serologia(Request $request){

      $rpta = Serologia::guarda_nueva_bitacora_serologia($request);

            if( $rpta == 1){

              return $this->setRpta("ok","Se procesó correctamente");
            }

            return $this->setRpta("error","ocurrió un error al generar solicitud");
    }



  




  


}
