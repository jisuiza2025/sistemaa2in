<?php

namespace App\Http\Controllers\Anualidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Bitacora;
use App\Maestro;
use App\NotaCredito;
use DB;


class NotaCreditoController extends Controller
{
    protected function nota_credito(){

        $empresa_user = Auth::user()->empresa;

        $tipo_cambio=NotaCredito::tipo_cambio(); 


        $centros_facturacion=Maestro::facturacion_centros();

        $motivo_nota_credito=Maestro::motivo_nota_credito();


        $user_logeado=Auth::user()->codigo;

        return view('nota_credito.index', compact('empresa_user','centros_facturacion','tipo_cambio','motivo_nota_credito'));
    }   

    protected function obtener_info_nota_cred(Request $request){

        $list_info=NotaCredito::obtener_info_nota_cred( $request);

        return response()->json($list_info);
    } 

     protected function list_table_anulacion(Request $request){

        $list=NotaCredito::list_table_anulacion($request);

        return response()->json($list);
    }

    protected function save_nota_credito(Request $request){

      

        $rpta = NotaCredito::save_nota_credito($request);

        DB::beginTransaction();

        try {


            if($rpta == 1){ 

                DB::commit();

                return $this->setRpta("ok","Se procesó correctamente"); 

            }
            else if($rpta == 0){

                DB::rollback();

                return $this->setRpta("error","Ocurrió un error");

            }

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }
    } 
    
  

   
    protected function validar_modal(){

     

        $rpta = NotaCredito::validar_modal();

        if($rpta[0]["TIPO_CAMBIO"] == "1"){           

            return $this->setRpta("ok",""); 

        }
        else if($rpta[0]["TIPO_CAMBIO"] == "0"){

            return $this->setRpta("error","No se ha registrado el TIPO DE CAMBIO del dia de hoy");

        }

        return $this->setRpta("error","Ocurrió un error");
    } 

     protected function verificar_nota_credito(Request $request){

        $list=NotaCredito::verificar_nota_credito($request);

        return response()->json($list);
    }

    protected function anular_nota_credito(Request $request){

        $rpta = NotaCredito::anular_nota_credito($request);

        DB::beginTransaction();

        try {

            if($rpta == 1){ 

                DB::commit();

                return $this->setRpta("ok","Anulado correctamente"); 

            }
            else if($rpta == 0){

                DB::rollback();

                return $this->setRpta("error","No se pudo anular la Nota de credito");

            }

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }
    } 

    protected function campo_tipo_doc(Request $request){

        $list=NotaCredito::campo_tipo_doc($request);

        return response()->json($list);
    }

}
