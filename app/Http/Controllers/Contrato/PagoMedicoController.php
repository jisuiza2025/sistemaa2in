<?php


namespace App\Http\Controllers\Contrato; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\Doctores;
use App\Contrato;
use Auth;
use Carbon\Carbon;
use App\Exports\ExportGeneral;
use Maatwebsite\Excel\Facades\Excel;

class PagoMedicoController extends Controller
{	


	public function __construct()
    {
        $this->middleware('auth');
    }
    
  

    public function pago_medico()
    {
        

        $middleRpta = $this->valida_url_permisos(40);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


        $empresa_user = Auth::user()->empresa;

        $moneda = Maestro::list_monedas();

         $btn_generar = $this->botones_usuario('pago_medico_generar');

        return View('contrato.pago',compact('empresa_user','moneda','btn_generar'));
    }


    protected function list_pagos_medicos(Request $request)
  {      


    $list = Doctores::list_pagos_medicos($request);
    
    return response()->json($list);

  }

  

   protected function get_cuenta_Cargo_pago_medico(Request $request)
  {      


    $list = Doctores::get_cuenta_Cargo_pago_medico($request);
    
    return response()->json($list);

  }
  

    protected function descargar_generar_pagos_medicos($contrato,$estado,$desde,$hasta)
  {      

        $request = new Request();

            $request->vm_contrato = ($contrato=='0')?'':$contrato;
        
          $request->vm_estado  = ($estado=='0')?'':$estado;

          $request->vm_desde = ($desde=='0')?'':$desde;

          $request->vm_hasta = ($hasta=='0')?'':$hasta;

     

        
        $list = Doctores::list_pagos_medicos($request);



        $excel = $this->set_filas_excel_pago_medico($list);

        $export = new ExportGeneral([
            
            $excel

         ]); 

        return Excel::download($export, 'PAGO_A_MEDICO'.date('Y-m').'.xlsx');

  }

  protected function set_filas_excel_pago_medico($list){

      
        

        $sub_array = array();

        $i=1;

        $sub_array[0]= array(
                        "NUMERO_CONTRATO" ,
                        "MEDICO",
                        "FECHA_COLECTA" ,
                        "TOTAL" ,
                        "SALDO" ,
                        "TARIFA" ,
                        "BANCO" ,
                        "NUMERO_CUENTA" ,
                        "ESTADO" ,
                        "FECHA_CONFIRMACION" ,
                        "RESPONSABLE" 
                     
                    );

        foreach ($list as $value) {
            
            
            if($value["PERMISO"] == 1){


                $NUMERO_CONTRATO = $value["NUMERO_CONTRATO"];
                        $MEDICO= $value["MEDICO"];
                        $FECHA_COLECTA= $value["FECHA_COLECTA"];
                        $MONTO_CONTRATO= $value["MONTO_CONTRATO"];
                        $SALDO = $value["SALDO"];
                        $TARIFA= $value["TARIFA"];
                        $BANCO= $value["BANCO"];
                        $CODIGO_CUENTA_BANCO= $value["CODIGO_CUENTA_BANCO"];
                        $ESTADO= $value["ESTADO"];
                        $FECHA_CONFIRMACION= $value["FECHA_CONFIRMACION"];
                        $RESPONSABLE= $value["RESPONSABLE"];
                        
                       
               
                    

        
                        $sub_array[$i]=array(


                                $NUMERO_CONTRATO,
                                $MEDICO,
                                $FECHA_COLECTA,
                                $MONTO_CONTRATO,
                                $SALDO ,
                                $TARIFA,
                                $BANCO,
                                $CODIGO_CUENTA_BANCO,
                                $ESTADO,
                                $FECHA_CONFIRMACION,
                                $RESPONSABLE
                       
                 


                        );

                        $i++;
            }



                        


        }

        return $sub_array;




    }



  

  protected function genera_pago_medico_pago(Request $request){      

    
    
    $list = $request->contratos_list;

    $cadena =array();

    foreach($list as $values){

        if($values["PERMISO"] == 1){

            $cadena[] = $values["NUMERO_CONTRATO"] ;

        }


    }

    if(count($cadena) == 0){

        return $this->setRpta("error","No hay contratos habilitados para generar pago");

    }else{



            $contratos = implode(",", $cadena);

            

            $rpta = Doctores::genera_pago_medico_pago($contratos,$request);


            if($rpta == 1 ){

                return $this->setRpta("ok","Se procesó correctamente");

            }
    
            return $this->setRpta("error","Ocurrió un error");


    }

    

  }

  

    public function pago_realizado_medico()
    {
        

        

        $middleRpta = $this->valida_url_permisos(43);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


        $empresa_user = Auth::user()->empresa;

       

        return View('contrato.pago_medico_realizado',compact('empresa_user'));
    }

    

     protected function list_pagos_realizados_medicos(Request $request)
  {      


    $list = Doctores::list_pagos_realizados_medicos($request);
    
    return response()->json($list);

  }

   protected function ver_detalle_list_pago_guias_medicos(Request $request)
  {      


    $list = Doctores::ver_detalle_list_pago_guias_medicos($request);
    
    return response()->json($list);

  }




protected function confirmar_eliminacion_agregacion_pago(Request $request){


        if(empty($request->comentario)&& $request->tipo==1){

            return $this->setRpta("error","Ingrese un comentario");

        }
      $rpta = Doctores::confirmar_eliminacion_agregacion_pago($request);

            if( $rpta == 1){

              return $this->setRpta("ok","Se procesó correctamente");
            }

            return $this->setRpta("error","ocurrió un error al confirmar");
    }

    
     protected function retirar_detalle_guia_pago_realizado_medico(Request $request){

      $rpta = Doctores::retirar_detalle_guia_pago_realizado_medico($request);

            if( $rpta == 1){

              return $this->setRpta("ok","Se procesó correctamente");
            }

            return $this->setRpta("error","ocurrió un error al generar solicitud");
    }


    protected function confirmar_guia_pago_realizado_medico(Request $request){

      $rpta = Doctores::confirmar_guia_pago_realizado_medico($request);

            if( $rpta == 1){

              return $this->setRpta("ok","Se procesó correctamente");
            }

            return $this->setRpta("error","ocurrió un error al generar solicitud");
    }


  protected function eliminar_guia_pago_realizado_medico(Request $request){

      $rpta = Doctores::eliminar_guia_pago_realizado_medico($request);

            if( $rpta == 1){

              return $this->setRpta("ok","Se procesó correctamente");
            }

            return $this->setRpta("error","ocurrió un error al generar solicitud");
    }







 protected function imprime_pdf_pago_medico($contrato){


     $list = Contrato::get_info_medico($contrato);
 
        $empresa_user = Auth::user()->empresa;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('A5','landscape');
        $pdf->loadView('reporte.contrato.pago_medico', compact('empresa_user','list'));
        return $pdf->stream();


    


    }


protected function genera_txt_pago_medico($guia){

    
    $txt ="txt_bancos/$guia.txt";

    $file=fopen($txt,'w');

    $string =  Doctores::genera_txt_pago_medico($guia);

    

    $rows="";

    
   

    foreach($string as $values){

        foreach($values as $key=>$list){

            
            
            $cadena = $list.PHP_EOL;
            $rows.=$cadena;
        }
        


    }
   

    

    fwrite($file,$rows);

    fclose($file); 


    return response()->download($txt)->deleteFileAfterSend(true);

    
    }

  


}
