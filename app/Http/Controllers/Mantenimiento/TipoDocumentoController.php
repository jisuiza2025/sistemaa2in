<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\TipoDocumento;

class TipoDocumentoController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function index(){


      $middleRpta = $this->valida_url_permisos(15);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


      $empresa_user = Auth::user()->empresa;

      $grupo = TipoDocumento::list_grupo();

      $impuesto = TipoDocumento::list_impuesto();

      $sunat = TipoDocumento::list_sunat();

      return View('mantenimiento.tipo_documento.index',compact('empresa_user','grupo','impuesto','sunat'));

    }  


    public function list_tipoDocumento()
	{      

		$list = TipoDocumento::list_tipoDocumento();
		
		return response()->json($list);

	}

	protected function desactiva_tipoDocumento(Request $request){      

        $rpta = TipoDocumento::desactiva_tipoDocumento($request);


        if($rpta == 1){

           	return $this->setRpta("ok","Se desactivó sastisfactoriamente");
        }
        else if($rpta == 0){

        	return $this->setRpta("error","Error: el registro es utilizado");
        }
      
        return $this->setRpta("error","Ocurrió un error");
    }

    

	protected function get_item_tipoDocumento(Request $request){      

		$list = TipoDocumento::get_item_tipoDocumento($request);

		if($request->identificacion == 'Nuevo'){
		                    
          //nuevo tipoDocumento
          $list = array(array("REGISTRO_VENTAS"=>'S',
                             "TIPO_MOVIMIENTO"=>'',
                             "TIPO_DOCUMENTO"=>'',
                             "DESCRIPCION"=>'',
                             "CODIGO_IMPUESTO"=>'',
                             "SECUENCIA_FACTURA"=>'',
                             "DOCUMENTO_ANULACION"=>'',
                             "CODIGO_SUNAT"=>'',
                             "CODIGO_GRUPO"=>'',
                           )
                );
        }

        return response()->json($list);    
    }

    protected function valida_documento_existente($request){

      if($request->identificacion == 'Nuevo'){

        $rpta =  TipoDocumento::valida_documento_existente($request);

        if($rpta == 1 ){

          return $this->setRpta("error","El documento ya existe");
        }


        return $this->setRpta("ok","validó correctamente");
      }

         
      return $this->setRpta("ok","validó correctamente");


    }


    protected function save_tipoDocumento(Request $request){      

    	DB::beginTransaction();

        try {

          $valida_documento = $this->valida_documento_existente($request);

          if($valida_documento["status"]=="ok"){

            $rpta = TipoDocumento::save_tipoDocumento($request);
            
            if($rpta == 1){

              DB::commit();

              return $this->setRpta("ok","Se procesó correctamente");

            }


            DB::rollback();

            return $this->setRpta("error","Ocurrió un error");

          }
            
          return  $valida_documento;
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }
  	}
}