<?php


namespace App\Http\Controllers\Reporte; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use Auth;
use App\Reporte;
use App\Maestro;
use App\Exports\ExportGeneral;
use App\Exports\ExportResumenFacturacion;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
class ReporteController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
   
  protected static function reporte_contrato_estado_cuenta(Request $request){

        $list = Reporte::reporte_contrato_estado_cuenta($request);
 
        $empresa_user = Auth::user()->empresa;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('A4','landscape');
        $pdf->loadView('reporte.contrato.estado_cuenta', compact('empresa_user','list'));
        return $pdf->stream();

    }


    public function analisis_anualidades(){



         $middleRpta = $this->valida_url_permisos(73);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


        $servicios =Maestro::list_cor_tablas(31);


           return View('reporte.anualidades.analisis',compact('servicios'));

    }

    public function get_tabla_reporte_analisis(Request $request){


          $list = Reporte::get_tabla_reporte_analisis($request);

        return response()->json($list);



    }

    public  function reporte_archivo_contrato_estado_cuenta($request){

        $list = Reporte::reporte_contrato_estado_cuenta($request);
 
        $empresa_user = Auth::user()->empresa;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('A4','landscape');
        $pdf->loadView('reporte.contrato.estado_cuenta', compact('empresa_user','list'));


    
        $output = $pdf->output();

        $random = $this->generaRandomString(10);
        $filepath = public_path().'/estados_cuenta/'.$random.'.pdf';


        file_put_contents($filepath, $output);

        return $filepath;

       


    }



   
    protected function reportes_analisis_view(){

        $middleRpta = $this->valida_url_permisos(33);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


        $empresa_user = Auth::user()->empresa;


        $btn_pca = $this->botones_usuario('rep_analsis_pca');
        $btn_dpca = $this->botones_usuario('rep_analsis_dpca');
        $btn_rfap = $this->botones_usuario('rep_analsis_rfap');
        $btn_dfap = $this->botones_usuario('rep_analsis_dfap');
        $btn_icpv = $this->botones_usuario('rep_analsis_icpv');
        $btn_facanu = $this->botones_usuario('rep_analsis_facanu');


        return View('reporte.analisis',compact('empresa_user','btn_pca','btn_dpca','btn_rfap','btn_dfap','btn_icpv','btn_facanu'));


    }


    protected function set_filas_excel_export_report_analisis($list,$btn){



        $sub_array = array();

        for($j=0;$j<2;$j++){

             $sub_array[$j]=array(
            "",
            "",
            "",
            "",
            "",
            "",
             "",
            "",
            "",
            "",
            "",
            "",
            "",
            );
        }
       

        $i=3;


        $sub_array[2]= array(
                        "FECHA_CONTRATO" ,
                        "FECHA_NACIMIENTO",
                        "FECHA_VENCIMIENTO" ,
                        "ANIO_VENCIMIENTO" ,
                        "MES_VENCIMIENTO" ,
                        "MONEDA_ANUALIDAD" ,
                        "MONTO_ANUALIDAD" ,
                        "TIPO_CAMBIO" ,
                        "MONTO_ANUALIDAD_SOL" ,
                        "MONTO_ANUALIDAD_DOL" ,
                        "ULTIMA_FECHA_PAGO" ,
                        "ULTIMO_ANIO_PAGO" ,
                        "ULTIMO_MES_PAGO" 

                        
                    );

        if($btn==3){

            $sub_array[0][]="";
             $sub_array[1][]="";


             $sub_array[2]= array(
                        "FECHA_CONTRATO" ,
                        "FECHA_NACIMIENTO",
                        "FECHA_VENCIMIENTO" ,
                        "ANIO_VENCIMIENTO" ,
                        "MES_VENCIMIENTO" ,
                        "MONEDA_ANUALIDAD" ,
                        "MONTO_ANUALIDAD" ,
                        "TIPO_CAMBIO" ,
                        "MONTO_ANUALIDAD_SOL" ,
                        "MONTO_ANUALIDAD_DOL" ,
                        "PERIODO_ANUALIDAD" ,
                        "FECHA_PAGO" ,
                        "ANIO_ANUALIDAD" ,
                        "MES_ANUALIDAD"

                        
                    );




        }



       

        foreach ($list as $value) {
            
            

                        $FECHA_CONTRATO = $value["FECHA_CONTRATO"];
                        $FECHA_NACIMIENTO= $value["FECHA_NACIMIENTO"];
                        $FECHA_VENCIMIENTO= $value["FECHA_VENCIMIENTO"];
                        $ANIO_VENCIMIENTO= $value["ANIO_VENCIMIENTO"];
                       $MES_VENCIMIENTO = $value["MES_VENCIMIENTO"];
                        $MONEDA_ANUALIDAD= $value["MONEDA_ANUALIDAD"];
                        $MONTO_ANUALIDAD= $value["MONTO_ANUALIDAD"];
                        $TIPO_CAMBIO= $value["TIPO_CAMBIO"];
                         $MONTO_ANUALIDAD_SOL= $value["MONTO_ANUALIDAD_SOL"];
                          $MONTO_ANUALIDAD_DOL= $value["MONTO_ANUALIDAD_DOL"];


                         
                       //27/12/2023
                       
                            if($btn==3){


                           $ULTIMA_FECHA_PAGO= $value["PERIODO_ANUALIDAD"];
                            $ULTIMO_ANIO_PAGO= $value["FECHA_PAGO"];
                            $ULTIMO_MES_PAGO= $value["ANIO_ANUALIDAD"];
                            $MES_ANUALIDAD = $value["MES_ANUALIDAD"];


                            }else{

                                  $ULTIMA_FECHA_PAGO= $value["ULTIMA_FECHA_PAGO"];
                            $ULTIMO_ANIO_PAGO= $value["ULTIMO_ANIO_PAGO"];
                            $ULTIMO_MES_PAGO= $value["ULTIMO_MES_PAGO"];
                            

                            }

          
        
            $sub_array[$i]=array(


                $FECHA_CONTRATO,
                        $FECHA_NACIMIENTO,
                        $FECHA_VENCIMIENTO,
                        $ANIO_VENCIMIENTO,
                       $MES_VENCIMIENTO ,
                        $MONEDA_ANUALIDAD,
                        $MONTO_ANUALIDAD,
                        $TIPO_CAMBIO,
                        $MONTO_ANUALIDAD_SOL,
                        $MONTO_ANUALIDAD_DOL,
                        $ULTIMA_FECHA_PAGO,
                        $ULTIMO_ANIO_PAGO,
                        $ULTIMO_MES_PAGO
                     


            );

            if($btn == 3){

                 $sub_array[$i][] = $MES_ANUALIDAD ;
            }
            $i++;


        }

        return $sub_array;

    }


    protected function export_report_analisis(Request $request){


        $servicio = $request->servicio;
        $tipo= $request->tipo;

        $btn =  $request->btn;

        
         $list = Reporte::export_report_analisis($servicio,$tipo,$btn);

        $excel = $this->set_filas_excel_export_report_analisis($list,$btn);

        $export = new ExportGeneral([
            
            $excel

         ]); 

        if($btn==1){

            $name='Contratos_Reales_';
        }elseif($btn==2){

            $name='Pagos_Adelantados_';
        }elseif($btn==3){

            $name='Cobranzas_Anual_';
        }
        return Excel::download($export, $name.date('Y-m').'.xlsx');



    }



     protected function export_analisis($variable,$desde,$hasta){

       
        
        switch ($variable) {

            case "PCA":
            
                $list = Reporte::proyeccion_cobranzas_anualidad($desde);


                break;

            case "DPCA":
            
                $list = Reporte::detalle_proyeccion_cobranzas_anualidad($desde);

                break;
            
            case "RFAP":
        
                $list = Reporte::resumen_facturacion_anualidad_periodo($desde);

                break;

            case "DFAP":
        
                $list = Reporte::detalle_resumen_facturacion_anualidad_periodo($desde,$hasta);
                
                break;

            case "ICPV":
        
                $list = Reporte::informacion_contratos_periodo_vencimiento($desde);
                
                break;

            case "FACANU":
        
                $list = Reporte::facturacion_anualidades($desde,$hasta);
                
                break;
        }   

        
        $excel = $this->set_filas_excel_export_general($list,$variable,$desde);


        if($variable == 'RFAP'){

             $export = new ExportResumenFacturacion([
            
                    $excel

            ]); 

            return Excel::download($export, 'ANALISIS_'.$variable.'_'.date('Y-m').'.xlsx');


        }else{



            $export = new ExportGeneral([
            
                    $excel

            ]); 

            return Excel::download($export, 'ANALISIS_'.$variable.'_'.date('Y-m').'.xlsx');

        }


       


    }


    protected function set_cabecera_excel_export_general($variable){

        if($variable == 'PCA'){

            return array("REPORTE",
                        "CONCEPTO","MON","MONTO_ANUALIDAD","TIPO_CAMBIO",
                            "MES1","MES1_CANT",
                            "MES2","MES2_CANT",
                            "MES3","MES3_CANT",
                            "MES4","MES4_CANT",
                            "MES5","MES5_CANT",
                            "MES6","MES6_CANT",
                            "MES7","MES7_CANT",
                            "MES8","MES8_CANT",
                            "MES9","MES9_CANT",
                            "MES10","MES10_CANT",
                            "MES11","MES11_CANT",
                            "MES12","MES12_CANT","TOTAL","TOTAL_CANT","MES_ANL1","MES_ANL2","MES_ANL3","MES_ANL4","MES_ANL5","MES_ANL6","MES_ANL7","MES_ANL8","MES_ANL9","MES_ANL10","MES_ANL11","MES_ANL12");

        }elseif($variable == 'DPCA'){

            return array("CIA","CONTRATO_BASE","SERVICIO","COBRADOR","VENCIMIENTO","MONEDA_ANUALIDAD","MONTO_ANUALIDAD","HEMOCULTIVO","SEROLOGIA","MTRA_HIV_PN","MTRA_EPA_B_AUS_PN","MTRA_ANTI_HBC_PN","MTRA_EPA_C_3G_PN","MTRA_RPR_SN","MTRA_HTVL_PN","ESTADO");

        }elseif($variable == 'RFAP'){

            // return array("GRUPO",
            //             "SERVICIO","VENCIDOS_MONTO","VENCIDOS_CANT",
            //                 "MES_M12_MONTO","MES_M12_CANT",
            //                 "MES_M11_MONTO","MES_M11_CANT",
            //                 "MES_M10_MONTO","MES_M10_CANT",
            //                 "MES_M09_MONTO","MES_M09_CANT",
            //                 "MES_M08_MONTO","MES_M08_CANT",
            //                 "MES_M07_MONTO","MES_M07_CANT",
            //                 "MES_M06_MONTO","MES_M06_CANT",
            //                 "MES_M05_MONTO","MES_M05_CANT",
            //                 "MES_M04_MONTO","MES_M04_CANT",
            //                 "MES_M03_MONTO","MES_M03_CANT",
            //                 "MES_M02_MONTO","MES_M02_CANT",
            //                 "MES_M01_MONTO","MES_M01_CANT",
            //                 "MES_00_MONTO","MES_00_CANT",
            //                 "MES_01_MONTO","MES_01_CANT",
            //                 "MES_02_MONTO","MES_02_CANT",
            //                 "MES_03_MONTO","MES_03_CANT",
            //                 "MES_04_MONTO","MES_04_CANT",
            //                 "MES_05_MONTO","MES_05_CANT",
            //                 "MES_06_MONTO","MES_06_CANT",
            //                 "MES_07_MONTO","MES_07_CANT",
            //                 "MES_08_MONTO","MES_08_CANT",
            //                 "MES_09_MONTO","MES_09_CANT",
            //                 "MES_10_MONTO","MES_10_CANT",
            //                 "MES_11_MONTO","MES_11_CANT",
            //                 "ADELANTO_MONTO","ADELANTO_CANTIDAD");



            return array("GRUPO",
                        "SERVICIO",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD",
                            "MONTO S/.","ANUALIDAD");

        }elseif($variable == 'DFAP'){

            return array("NO_CIA","TD","NUMERO","NUMERO_CONTRATO","NUMERO_PRECONTRATO","SERVICIO","FECHA","NOMBRE_MAMA","MAIL_MAMA","TELEFONO_MAMA","CELULAR_MAMA","NOMBRE_PAPA","MAIL_PAPA","TELEFONO_PAPA","CELULAR_PAPA","PERIODO_MINIMO_PAGADO","PAR_IMPAR","TOTAL","PORCENTAJE_DESCUENTO","PERIODO_ASIGNADO","PERIODO_ORIGINAL","HEMOCULTIVO","SEROLOGIA","FECHA_COMUNICACION","USUARIO","DETALLE","RESPONSABLE_CONTRATO");

        }elseif($variable == 'ICPV'){

            return array("NO_CIA","CONTRATO_BASE","NUMERO_CONTRATO","FECHA_CONTRATO","FAMILIA","FECHA_NACIMIENTO","MONEDA_ANUALIDAD","CUOTA_ANUAL","ESTADO_CONTRATO","SITUACION_CONTRATO","INUBICABLE_PAPA","INUBICABLE_MAMA","TIPO_DOCUMENTO_ULTFC","NUMERO_DOCUMENTO_ULTFC","FECHA_ULTFC","CUBRE_PERIODO_DESDE_ULTFC","CUBRE_PERIODO_HASTA_ULTFC");

        }elseif($variable == 'FACANU'){

             return array("NO_CIA","TD","NUMERO","NUMERO_CONTRATO","SERVICIO","FECHA","NOMBRE_MAMA","MAIL_MAMA","TELEFONO_MAMA","CELULAR_MAMA","NOMBRE_PAPA","MAIL_PAPA","TELEFONO_PAPA","CELULAR_PAPA","PERIODO_MINIMO_PAGADO","PAR_IMPAR","PERIODO_ASIGNADO","PERIODO_ORIGINAL","HEMOCULTIVO","SEROLOGIA","FECHA_COMUNICACION","USUARIO","DETALLE","MEDIO_PAGO","MONEDA","MONTO_ANUALIDAD","PORCENTAJE_DESCUENTO","VALOR_DESCUENTO","PENALIDAD","TOTAL");

        }

    }

    protected function set_body_excel_export_general($list,$variable){

        $i = 3 ;

        $data = array();

        if($variable == 'PCA'){

            foreach ($list as $value) {
            
            $REPORTE         = $value['REPORTE'];
          
            $CONCEPTO        = $value['CONCEPTO'];
            $MON             = $value['MONEDA_ANUALIDAD'];
            $MONTO_ANUALIDAD = $value['MONTO_ANUALIDAD'];
            $TIPO_CAMBIO     = $value['TIPO_CAMBIO'];
            $MES1         = $value['MES1'];
            $MES1_CANT    = $value['MES1_CANT'];
            $MES2         = $value['MES2'];
            $MES2_CANT    = $value['MES2_CANT'];
            $MES3         = $value['MES3'];
            $MES3_CANT    = $value['MES3_CANT'];
            $MES4         = $value['MES4'];
            $MES4_CANT    = $value['MES4_CANT'];
            $MES5         = $value['MES5'];
            $MES5_CANT    = $value['MES5_CANT'];
            $MES6         = $value['MES6'];
            $MES6_CANT    = $value['MES6_CANT'];
            $MES7         = $value['MES7'];
            $MES7_CANT    = $value['MES7_CANT'];
            $MES8         = $value['MES8'];
            $MES8_CANT    = $value['MES8_CANT'];
            $MES9         = $value['MES9'];
            $MES9_CANT    = $value['MES9_CANT'];
            $MES10         = $value['MES10'];
            $MES10_CANT    = $value['MES10_CANT'];
            $MES11         = $value['MES11'];
            $MES11_CANT    = $value['MES11_CANT'];
            $MES12        = $value['MES12'];
            $MES12_CANT   = $value['MES12_CANT'];
            $TOTAL        = $value['TOTAL'];
            $TOTAL_CANT   = $value['TOTAL_CANT'];
            $MES_ANL1   = $value['MES_ANL1'];
            $MES_ANL2   = $value['MES_ANL2'];
            $MES_ANL3   = $value['MES_ANL3'];
            $MES_ANL4   = $value['MES_ANL4'];
            $MES_ANL5   = $value['MES_ANL5'];
            $MES_ANL6   = $value['MES_ANL6'];
            $MES_ANL7   = $value['MES_ANL7'];
            $MES_ANL8   = $value['MES_ANL8'];
            $MES_ANL9   = $value['MES_ANL9'];
            $MES_ANL10   = $value['MES_ANL10'];
            $MES_ANL11   = $value['MES_ANL11'];
            $MES_ANL12   = $value['MES_ANL12'];
           
            
            $data[$i]= array($REPORTE,
                        $CONCEPTO,$MON,$MONTO_ANUALIDAD,$TIPO_CAMBIO,
                            $MES1,$MES1_CANT,
                            $MES2,$MES2_CANT,
                            $MES3,$MES3_CANT,
                            $MES4,$MES4_CANT,
                            $MES5,$MES5_CANT,
                            $MES6,$MES6_CANT,
                            $MES7,$MES7_CANT,
                            $MES8,$MES8_CANT,
                            $MES9,$MES9_CANT,
                            $MES10,$MES10_CANT,
                            $MES11,$MES11_CANT,
                            $MES12,$MES12_CANT,$TOTAL,$TOTAL_CANT,$MES_ANL1,$MES_ANL2,$MES_ANL3,$MES_ANL4,$MES_ANL5,$MES_ANL6,$MES_ANL7,$MES_ANL8,$MES_ANL9,$MES_ANL10,$MES_ANL11,$MES_ANL12);
            $i++;
            }




        }elseif($variable == 'DPCA'){

            foreach ($list as $value) {
            
            $NO_CIA              = $value['NO_CIA'];
            $CONTRATO_BASE       = $value['CONTRATO_BASE'];
            $SERVICIO            = $value['SERVICIO'];
            $COBRADOR            = $value['COBRADOR'];
            $VENCIMIENTO         = $value['VENCIMIENTO'];
            $MONEDA_ANUALIDAD    = $value['MONEDA_ANUALIDAD'];
            $MONTO_ANUALIDAD     = $value['MONTO_ANUALIDAD'];
            $HEMOCULTIVO         = $value['HEMOCULTIVO'];
            $SEROLOGIA           = $value['SEROLOGIA'];
            $MTRA_HIV_PN         = $value['MTRA_HIV_PN'];
            $MTRA_EPA_B_AUS_PN   = $value['MTRA_EPA_B_AUS_PN'];
            $MTRA_ANTI_HBC_PN    = $value['MTRA_ANTI_HBC_PN'];
            $MTRA_EPA_C_3G_PN    = $value['MTRA_EPA_C_3G_PN'];
            $MTRA_RPR_SN         = $value['MTRA_RPR_SN'];
            $MTRA_HTVL_PN        = $value['MTRA_HTVL_PN'];
            $ESTADO             = $value['ESTADO'];
           
        
            $data[$i]= array($NO_CIA,
                            $CONTRATO_BASE,
                            $SERVICIO,
                            $COBRADOR,
                            $VENCIMIENTO,
                            $MONEDA_ANUALIDAD,
                            $MONTO_ANUALIDAD,
                            $HEMOCULTIVO,
                            $SEROLOGIA,
                            $MTRA_HIV_PN,
                            $MTRA_EPA_B_AUS_PN,
                            $MTRA_ANTI_HBC_PN,
                            $MTRA_EPA_C_3G_PN,
                            $MTRA_RPR_SN,
                            $MTRA_HTVL_PN,
                            $ESTADO);
            $i++;
            }




        }elseif($variable == 'RFAP'){

            //variables calculadas
                        $SUB_MONTO_TOTAL_1=0;
                        $SUB_MONTO_TOTAL_ANUAL_1=0;


                        $SUB_VENCIDOS_MONTO  =0;
                        $SUB_VENCIDOS_CANT   =0;
                        $SUB_MES_M12_MONTO   =0;
                        $SUB_MES_M12_CANT    =0;
                        $SUB_MES_M11_MONTO   =0;
                        $SUB_MES_M11_CANT    =0;
                        $SUB_MES_M10_MONTO   =0;  
                        $SUB_MES_M10_CANT    =0;
                        $SUB_MES_M09_MONTO   =0; 
                        $SUB_MES_M09_CANT    =0;
                        $SUB_MES_M08_MONTO   =0; 
                        $SUB_MES_M08_CANT    =0;
                        $SUB_MES_M07_MONTO   =0;
                        $SUB_MES_M07_CANT    =0;
                        $SUB_MES_M06_MONTO   =0;
                        $SUB_MES_M06_CANT    =0;
                        $SUB_MES_M05_MONTO   =0;
                        $SUB_MES_M05_CANT    =0; 
                        $SUB_MES_M04_MONTO   =0; 
                        $SUB_MES_M04_CANT    =0; 
                        $SUB_MES_M03_MONTO   =0;
                        $SUB_MES_M03_CANT    =0;
                        $SUB_MES_M02_MONTO   =0;
                        $SUB_MES_M02_CANT    =0;
                        $SUB_MES_M01_MONTO   =0;
                        $SUB_MES_M01_CANT    =0; 
                        $SUB_MES_00_MONTO    =0;
                        $SUB_MES_00_CANT     =0;
                        $SUB_MES_01_MONTO    =0;
                        $SUB_MES_01_CANT     =0;
                        $SUB_MES_02_MONTO    =0;
                        $SUB_MES_02_CANT     =0; 
                        $SUB_MES_03_MONTO    =0;
                        $SUB_MES_03_CANT     =0; 
                        $SUB_MES_04_MONTO    =0;
                        $SUB_MES_04_CANT     =0; 
                        $SUB_MES_05_MONTO    =0;
                        $SUB_MES_05_CANT     =0;
                        $SUB_MES_06_MONTO    =0;
                        $SUB_MES_06_CANT     =0;
                        $SUB_MES_07_MONTO    =0;
                        $SUB_MES_07_CANT     =0;
                        $SUB_MES_08_MONTO    =0;
                        $SUB_MES_08_CANT     =0;
                        $SUB_MES_09_MONTO    =0;
                        $SUB_MES_09_CANT     =0;
                        $SUB_MES_10_MONTO    =0;
                        $SUB_MES_10_CANT     =0;
                        $SUB_MES_11_MONTO    =0;
                        $SUB_MES_11_CANT     =0;
                        $SUB_ADELANTO_MONTO  =0;
                        $SUB_ADELANTO_CANT   =0;


            foreach ($list as $value) {
            
            $GRUPO             = $value['GRUPO'];
            $SERVICIO          = $value['SERVICIO'];
            


            $VENCIDOS_MONTO    = $value['VENCIDOS_MONTO'];
            $VENCIDOS_CANT     = $value['VENCIDOS_CANT'];
            $MES_M12_MONTO     = $value['MES_M12_MONTO'];
            $MES_M12_CANT      = $value['MES_M12_CANT'];
            $MES_M11_MONTO    = $value['MES_M11_MONTO'];
            $MES_M11_CANT     = $value['MES_M11_CANT'];
            $MES_M10_MONTO    = $value['MES_M10_MONTO'];
            $MES_M10_CANT     = $value['MES_M10_CANT'];
            $MES_M09_MONTO    = $value['MES_M09_MONTO'];
            $MES_M09_CANT     = $value['MES_M09_CANT'];
            $MES_M08_MONTO    = $value['MES_M08_MONTO'];
            $MES_M08_CANT     = $value['MES_M08_CANT'];
            $MES_M07_MONTO    = $value['MES_M07_MONTO'];
            $MES_M07_CANT     = $value['MES_M07_CANT'];
            $MES_M06_MONTO    = $value['MES_M06_MONTO'];
            $MES_M06_CANT     = $value['MES_M06_CANT'];
            $MES_M05_MONTO    = $value['MES_M05_MONTO'];
            $MES_M05_CANT     = $value['MES_M05_CANT'];
            $MES_M04_MONTO    = $value['MES_M04_MONTO'];
            $MES_M04_CANT     = $value['MES_M04_CANT'];
            $MES_M03_MONTO    = $value['MES_M03_MONTO'];
            $MES_M03_CANT     = $value['MES_M03_CANT'];
            $MES_M02_MONTO    = $value['MES_M02_MONTO'];
            $MES_M02_CANT     = $value['MES_M02_CANT'];
            $MES_M01_MONTO    = $value['MES_M01_MONTO'];
            $MES_M01_CANT     = $value['MES_M01_CANT'];

            $MES_00_MONTO    = $value['MES_00_MONTO'];
            $MES_00_CANT     = $value['MES_00_CANT'];
            $MES_01_MONTO    = $value['MES_01_MONTO'];
            $MES_01_CANT     = $value['MES_01_CANT'];
            $MES_02_MONTO    = $value['MES_02_MONTO'];
            $MES_02_CANT     = $value['MES_02_CANT'];
            $MES_03_MONTO    = $value['MES_03_MONTO'];
            $MES_03_CANT     = $value['MES_03_CANT'];
            $MES_04_MONTO    = $value['MES_04_MONTO'];
            $MES_04_CANT     = $value['MES_04_CANT'];
            $MES_05_MONTO    = $value['MES_05_MONTO'];
            $MES_05_CANT     = $value['MES_05_CANT'];
            $MES_06_MONTO    = $value['MES_06_MONTO'];
            $MES_06_CANT     = $value['MES_06_CANT'];
            $MES_07_MONTO    = $value['MES_07_MONTO'];
            $MES_07_CANT     = $value['MES_07_CANT'];
            $MES_08_MONTO    = $value['MES_08_MONTO'];
            $MES_08_CANT     = $value['MES_08_CANT'];
            $MES_09_MONTO    = $value['MES_09_MONTO'];
            $MES_09_CANT     = $value['MES_09_CANT'];
            $MES_10_MONTO    = $value['MES_10_MONTO'];
            $MES_10_CANT     = $value['MES_10_CANT'];
            $MES_11_MONTO    = $value['MES_11_MONTO'];
            $MES_11_CANT     = $value['MES_11_CANT'];

            $ADELANTO_MONTO    = $value['ADELANTO_MONTO'];
            $ADELANTO_CANT  = $value['ADELANTO_CANT'];
           


           //sumatoria de todas las columnas montos 
            $MONTO_TOTAL_1      = round(($VENCIDOS_MONTO + 
                                         $MES_M12_MONTO+
                                         $MES_M11_MONTO+
                                         $MES_M10_MONTO+
                                         $MES_M09_MONTO+
                                         $MES_M08_MONTO+
                                         $MES_M07_MONTO+
                                         $MES_M06_MONTO+
                                         $MES_M05_MONTO+
                                         $MES_M04_MONTO+
                                         $MES_M03_MONTO+
                                         $MES_M02_MONTO+
                                         $MES_M01_MONTO+
                                         $MES_00_MONTO+
                                         $MES_01_MONTO+
                                         $MES_02_MONTO+
                                         $MES_03_MONTO+
                                         $MES_04_MONTO+
                                         $MES_05_MONTO+
                                         $MES_06_MONTO+
                                         $MES_07_MONTO+
                                         $MES_08_MONTO+
                                         $MES_09_MONTO+
                                         $MES_10_MONTO+
                                         $MES_11_MONTO+
                                         $ADELANTO_MONTO),2);

            //sumatorias de todas las columnas anualidades
            
            $MONTO_TOTAL_ANUAL_1 =  round(($VENCIDOS_CANT + 
                                         $MES_M12_CANT+
                                         $MES_M11_CANT+
                                         $MES_M10_CANT+
                                         $MES_M09_CANT+
                                         $MES_M08_CANT+
                                         $MES_M07_CANT+
                                         $MES_M06_CANT+
                                         $MES_M05_CANT+
                                         $MES_M04_CANT+
                                         $MES_M03_CANT+
                                         $MES_M02_CANT+
                                         $MES_M01_CANT+
                                         $MES_00_CANT+
                                         $MES_01_CANT+
                                         $MES_02_CANT+
                                         $MES_03_CANT+
                                         $MES_04_CANT+
                                         $MES_05_CANT+
                                         $MES_06_CANT+
                                         $MES_07_CANT+
                                         $MES_08_CANT+
                                         $MES_09_CANT+
                                         $MES_10_CANT+
                                         $MES_11_CANT+
                                         $ADELANTO_CANT),2);
            //
            




            //sumamos montos 
            

            $SUB_MONTO_TOTAL_1       +=$MONTO_TOTAL_1;
            $SUB_MONTO_TOTAL_ANUAL_1 +=$MONTO_TOTAL_ANUAL_1;


                    $SUB_VENCIDOS_MONTO  +=$VENCIDOS_MONTO;
                        $SUB_VENCIDOS_CANT   +=$VENCIDOS_CANT;
                        $SUB_MES_M12_MONTO   +=$MES_M12_MONTO;
                        $SUB_MES_M12_CANT    +=$MES_M12_CANT;
                        $SUB_MES_M11_MONTO   +=$MES_M11_MONTO;
                        $SUB_MES_M11_CANT    +=$MES_M11_CANT;
                        $SUB_MES_M10_MONTO   +=$MES_M10_MONTO;  
                        $SUB_MES_M10_CANT    +=$MES_M10_CANT;
                        $SUB_MES_M09_MONTO   +=$MES_M09_MONTO; 
                        $SUB_MES_M09_CANT    +=$MES_M09_CANT;
                        $SUB_MES_M08_MONTO   +=$MES_M08_MONTO; 
                        $SUB_MES_M08_CANT    +=$MES_M08_CANT;
                        $SUB_MES_M07_MONTO   +=$MES_M07_MONTO;
                        $SUB_MES_M07_CANT    +=$MES_M07_CANT;
                        $SUB_MES_M06_MONTO   +=$MES_M06_MONTO;
                        $SUB_MES_M06_CANT    +=$MES_M06_CANT;
                        $SUB_MES_M05_MONTO   +=$MES_M05_MONTO;
                        $SUB_MES_M05_CANT    +=$MES_M05_CANT; 
                        $SUB_MES_M04_MONTO   +=$MES_M04_MONTO; 
                        $SUB_MES_M04_CANT    +=$MES_M04_CANT; 
                        $SUB_MES_M03_MONTO   +=$MES_M03_MONTO;
                        $SUB_MES_M03_CANT    +=$MES_M03_CANT;
                        $SUB_MES_M02_MONTO   +=$MES_M02_MONTO;
                        $SUB_MES_M02_CANT    +=$MES_M02_CANT;
                        $SUB_MES_M01_MONTO   +=$MES_M01_MONTO;
                        $SUB_MES_M01_CANT    +=$MES_M01_CANT; 
                        $SUB_MES_00_MONTO    +=$MES_00_MONTO;
                        $SUB_MES_00_CANT     +=$MES_00_CANT ;
                        $SUB_MES_01_MONTO    +=$MES_01_MONTO;
                        $SUB_MES_01_CANT     +=$MES_01_CANT;
                        $SUB_MES_02_MONTO    +=$MES_02_MONTO;
                        $SUB_MES_02_CANT     +=$MES_02_CANT; 
                        $SUB_MES_03_MONTO    +=$MES_03_MONTO;
                        $SUB_MES_03_CANT     +=$MES_03_CANT; 
                        $SUB_MES_04_MONTO    +=$MES_04_MONTO ;
                        $SUB_MES_04_CANT     +=$MES_04_CANT; 
                        $SUB_MES_05_MONTO    +=$MES_05_MONTO;
                        $SUB_MES_05_CANT     +=$MES_05_CANT;
                        $SUB_MES_06_MONTO    +=$MES_06_MONTO;
                        $SUB_MES_06_CANT     +=$MES_06_CANT;
                        $SUB_MES_07_MONTO    +=$MES_07_MONTO;
                        $SUB_MES_07_CANT     +=$MES_07_CANT;
                        $SUB_MES_08_MONTO    +=$MES_08_MONTO ;
                        $SUB_MES_08_CANT     +=$MES_08_CANT;
                        $SUB_MES_09_MONTO    +=$MES_09_MONTO;
                        $SUB_MES_09_CANT     +=$MES_09_CANT;
                        $SUB_MES_10_MONTO    +=$MES_10_MONTO;
                        $SUB_MES_10_CANT     +=$MES_10_CANT;
                        $SUB_MES_11_MONTO    +=$MES_11_MONTO;
                        $SUB_MES_11_CANT     +=$MES_11_CANT;
                        $SUB_ADELANTO_MONTO  +=$ADELANTO_MONTO;
                        $SUB_ADELANTO_CANT   +=$ADELANTO_CANT;
            //fin sumar




            $data[$i]= array(
                        $GRUPO ,
                        $SERVICIO             ,
                        number_format($MONTO_TOTAL_1,2),
                        number_format($MONTO_TOTAL_ANUAL_1,2),
                        number_format($VENCIDOS_MONTO,2)    ,
                        number_format($VENCIDOS_CANT,2)     ,
                        number_format($MES_M12_MONTO ,2)    ,
                        number_format($MES_M12_CANT,2)     ,
                        number_format($MES_M11_MONTO ,2)   ,
                        number_format($MES_M11_CANT  ,2)  ,
                        number_format($MES_M10_MONTO ,2)   ,
                        number_format($MES_M10_CANT  ,2)   ,
                        number_format($MES_M09_MONTO  ,2)  ,
                        number_format($MES_M09_CANT   ,2)  ,
                        number_format($MES_M08_MONTO ,2)   ,
                        number_format($MES_M08_CANT  ,2)  ,
                        number_format($MES_M07_MONTO ,2)   ,
                        number_format($MES_M07_CANT  ,2)   ,
                        number_format($MES_M06_MONTO ,2)   ,
                        number_format($MES_M06_CANT  ,2)   ,
                        number_format($MES_M05_MONTO ,2)   ,
                        number_format($MES_M05_CANT  ,2)   ,
                        number_format($MES_M04_MONTO ,2)   ,
                        number_format($MES_M04_CANT  ,2)   ,
                        number_format($MES_M03_MONTO ,2)   ,
                        number_format($MES_M03_CANT  ,2)   ,
                        number_format($MES_M02_MONTO ,2) ,
                        number_format($MES_M02_CANT  ,2)   ,
                        number_format($MES_M01_MONTO ,2)  ,
                        number_format($MES_M01_CANT  ,2)   ,
                        number_format($MES_00_MONTO  ,2)  ,
                        number_format($MES_00_CANT   ,2) ,
                        number_format($MES_01_MONTO  ,2) ,
                        number_format($MES_01_CANT   ,2)  ,
                        number_format($MES_02_MONTO  ,2)  ,
                        number_format($MES_02_CANT   ,2)  ,
                        number_format($MES_03_MONTO  ,2)  ,
                        number_format($MES_03_CANT  ,2)   ,
                        number_format($MES_04_MONTO ,2)   ,
                        number_format($MES_04_CANT  ,2)   ,
                        number_format($MES_05_MONTO  ,2) ,
                        number_format($MES_05_CANT  ,2)   ,
                        number_format($MES_06_MONTO ,2)   ,
                        number_format($MES_06_CANT  ,2)   ,
                        number_format($MES_07_MONTO ,2)  ,
                        number_format($MES_07_CANT  ,2)   ,
                        number_format($MES_08_MONTO ,2)   ,
                        number_format($MES_08_CANT  ,2)   ,
                        number_format($MES_09_MONTO ,2)   ,
                        number_format($MES_09_CANT   ,2)  ,
                        number_format($MES_10_MONTO  ,2)  ,
                        number_format($MES_10_CANT  ,2)  ,
                        number_format($MES_11_MONTO ,2)  ,
                        number_format($MES_11_CANT  ,2) ,
                        number_format($ADELANTO_MONTO,2),
                        number_format($ADELANTO_CANT,2)

                        );
            $i++;

            }


            
            //PINTAMOS TOTALES SI EXISTE DATA
            if(count($data)>0){



                    //AGREGAMOS COLUMNAS CALCULADAS
            
            $igv = number_format(0.18,2);

            $sin_igv=number_format(0,2);

            $sin_anualidad =number_format(0,2);

            $siguiente_columna = $i+1;

            $suma_con_igv=1+$igv;

            //SUBTOTALES
            
            $data[$siguiente_columna]= array(
                        '' ,
                        'SUBTOTAL'             ,
                        number_format($SUB_MONTO_TOTAL_1/$suma_con_igv,2)    ,
                        $sin_anualidad ,
                        number_format($SUB_VENCIDOS_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_M12_MONTO/$suma_con_igv,2)     ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_M11_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad    ,
                        number_format($SUB_MES_M10_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_M09_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_M08_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad    ,
                        number_format($SUB_MES_M07_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_M06_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_M05_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_M04_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_M03_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_M02_MONTO/$suma_con_igv,2)   ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_M01_MONTO/$suma_con_igv,2)   ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_00_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad    ,
                        number_format($SUB_MES_01_MONTO/$suma_con_igv ,2)  ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_02_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_03_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_04_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_05_MONTO/$suma_con_igv,2)   ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_06_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_07_MONTO/$suma_con_igv,2)   ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_08_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_09_MONTO/$suma_con_igv ,2)   ,
                        $sin_anualidad     ,
                        number_format($SUB_MES_10_MONTO/$suma_con_igv,2)    ,
                        $sin_anualidad    ,
                        number_format($SUB_MES_11_MONTO/$suma_con_igv,2)   ,
                        $sin_anualidad   ,
                        number_format($SUB_ADELANTO_MONTO/$suma_con_igv,2),
                        $sin_anualidad

                        );

                //IGV


            $data[$siguiente_columna+1]= array(
                        '' ,
                        'IGV'             ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv     ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv    ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv    ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv   ,
                        $sin_igv     ,
                        $igv   ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv    ,
                        $igv   ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv   ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv   ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv     ,
                        $igv    ,
                        $sin_igv    ,
                        $igv   ,
                        $sin_igv   ,
                        $igv,
                        $sin_igv

                        );

            //TOTALES
            
            


            $data[$siguiente_columna+2]= array(
                        '' ,
                        'TOTAL'             ,
                        number_format($SUB_MONTO_TOTAL_1,2)  ,
                        $SUB_MONTO_TOTAL_ANUAL_1     ,
                        number_format($SUB_VENCIDOS_MONTO,2)  ,
                        $SUB_VENCIDOS_CANT     ,
                        number_format($SUB_MES_M12_MONTO,2)     ,
                        $SUB_MES_M12_CANT     ,
                        number_format($SUB_MES_M11_MONTO,2)    ,
                        $SUB_MES_M11_CANT    ,
                        number_format($SUB_MES_M10_MONTO,2)    ,
                        $SUB_MES_M10_CANT     ,
                        number_format($SUB_MES_M09_MONTO,2)    ,
                        $SUB_MES_M09_CANT     ,
                        number_format($SUB_MES_M08_MONTO,2)    ,
                        $SUB_MES_M08_CANT    ,
                        number_format($SUB_MES_M07_MONTO,2)    ,
                        $SUB_MES_M07_CANT     ,
                        number_format($SUB_MES_M06_MONTO,2)    ,
                        $SUB_MES_M06_CANT     ,
                        number_format($SUB_MES_M05_MONTO,2)    ,
                        $SUB_MES_M05_CANT     ,
                        number_format($SUB_MES_M04_MONTO,2)    ,
                        $SUB_MES_M04_CANT     ,
                        number_format($SUB_MES_M03_MONTO,2)    ,
                        $SUB_MES_M03_CANT     ,
                        number_format($SUB_MES_M02_MONTO,2)   ,
                        $SUB_MES_M02_CANT     ,
                        number_format($SUB_MES_M01_MONTO,2)   ,
                        $SUB_MES_M01_CANT     ,
                        number_format($SUB_MES_00_MONTO,2)    ,
                        $SUB_MES_00_CANT    ,
                        number_format($SUB_MES_01_MONTO,2)   ,
                        $SUB_MES_01_CANT     ,
                        number_format($SUB_MES_02_MONTO,2)    ,
                        $SUB_MES_02_CANT     ,
                        number_format($SUB_MES_03_MONTO,2)    ,
                        $SUB_MES_03_CANT     ,
                        number_format($SUB_MES_04_MONTO,2)    ,
                        $SUB_MES_04_CANT     ,
                        number_format($SUB_MES_05_MONTO,2)   ,
                        $SUB_MES_05_CANT     ,
                        number_format($SUB_MES_06_MONTO,2)    ,
                        $SUB_MES_06_CANT     ,
                        number_format($SUB_MES_07_MONTO,2)   ,
                        $SUB_MES_07_CANT     ,
                        number_format($SUB_MES_08_MONTO ,2)   ,
                        $SUB_MES_08_CANT     ,
                        number_format($SUB_MES_09_MONTO,2)    ,
                        $SUB_MES_09_CANT     ,
                        number_format($SUB_MES_10_MONTO,2)    ,
                        $SUB_MES_10_CANT    ,
                        number_format($SUB_MES_11_MONTO,2)   ,
                        $SUB_MES_11_CANT   ,
                        number_format($SUB_ADELANTO_MONTO,2),
                        $SUB_ADELANTO_CANT

                        );



            }
            


        }elseif($variable == 'DFAP'){

            foreach ($list as $value) {
            
            $NO_CIA                 = $value['NO_CIA'];
            $TD                     = $value['TD'];
            $NUMERO                 = $value['NUMERO'];
            $NUMERO_CONTRATO        = $value['NUMERO_CONTRATO'];
            $NUMERO_PRECONTRATO     = $value['NUMERO_PRECONTRATO'];
            $SERVICIO               = $value['SERVICIO'];
            $FECHA                  = $value['FECHA'];
            $NOMBRE_MAMA            = $value['NOMBRE_MAMA'];
            $MAIL_MAMA              = $value['MAIL_MAMA'];
            $TELEFONO_MAMA          = $value['TELEFONO_MAMA'];
            $CELULAR_MAMA           = $value['CELULAR_MAMA'];
            $NOMBRE_PAPA            = $value['NOMBRE_PAPA'];
            $MAIL_PAPA              = $value['MAIL_PAPA'];
            $TELEFONO_PAPA          = $value['TELEFONO_PAPA'];
            $CELULAR_PAPA          = $value['CELULAR_PAPA'];

            $PERIODO_MINIMO_PAGADO  = $value['PERIODO_MINIMO_PAGADO'];
            $PAR_IMPAR             = $value['PAR_IMPAR'];
            $TOTAL                 = $value['TOTAL'];
            $PORCENTAJE_DESCUENTO  = $value['PORCENTAJE_DESCUENTO'];
            $PERIODO_ASIGNADO      = $value['PERIODO_ASIGNADO'];
            $PERIODO_ORIGINAL      = $value['PERIODO_ORIGINAL'];
            $HEMOCULTIVO           = $value['HEMOCULTIVO'];
            $SEROLOGIA             = $value['SEROLOGIA'];
            $FECHA_COMUNICACION    = $value['FECHA_COMUNICACION'];
            $USUARIO             = $value['USUARIO'];
            $DETALLE             = $value['DETALLE'];
            $RESPONSABLE_CONTRATO  = $value['RESPONSABLE_CONTRATO'];


            $data[$i]= array(

                        $NO_CIA,
                        $TD ,
                        $NUMERO   ,
                        $NUMERO_CONTRATO,
                        $NUMERO_PRECONTRATO ,
                        $SERVICIO ,
                        $FECHA ,
                        $NOMBRE_MAMA ,
                        $MAIL_MAMA ,
                        $TELEFONO_MAMA ,
                        $CELULAR_MAMA ,
                        $NOMBRE_PAPA ,
                        $MAIL_PAPA ,
                        $TELEFONO_PAPA,
                        $CELULAR_PAPA,
                        $PERIODO_MINIMO_PAGADO ,
                        $PAR_IMPAR  ,
                        $TOTAL,
                        $PORCENTAJE_DESCUENTO  ,
                        $PERIODO_ASIGNADO  ,
                        $PERIODO_ORIGINAL    ,
                        $HEMOCULTIVO    ,
                        $SEROLOGIA   ,
                        $FECHA_COMUNICACION  ,
                        $USUARIO ,
                        $DETALLE  ,
                        $RESPONSABLE_CONTRATO


                    );
            $i++;

            }




        }elseif($variable == 'ICPV'){

            foreach ($list as $value) {
            
            $NO_CIA              = $value['NO_CIA'];
            $CONTRATO_BASE       = $value['CONTRATO_BASE'];
            $NUMERO_CONTRATO     = $value['NUMERO_CONTRATO'];
            $FECHA_CONTRATO      = $value['FECHA_CONTRATO'];
            $FAMILIA             = $value['FAMILIA'];
            $FECHA_NACIMIENTO    = $value['FECHA_NACIMIENTO'];
            $MONEDA_ANUALIDAD    = $value['MONEDA_ANUALIDAD'];
            $CUOTA_ANUAL         = $value['CUOTA_ANUAL'];
            $ESTADO_CONTRATO     = $value['ESTADO_CONTRATO'];
            $SITUACION_CONTRATO  = $value['SITUACION_CONTRATO'];
            $INUBICABLE_PAPA     = $value['INUBICABLE_PAPA'];
            $INUBICABLE_MAMA     = $value['INUBICABLE_MAMA'];
            $TIPO_DOCUMENTO_ULTFC    = $value['TIPO_DOCUMENTO_ULTFC'];
            $NUMERO_DOCUMENTO_ULTFC  = $value['NUMERO_DOCUMENTO_ULTFC'];

            $FECHA_ULTFC        = $value['FECHA_ULTFC'];
            $CUBRE_PERIODO_DESDE_ULTFC   = $value['CUBRE_PERIODO_DESDE_ULTFC'];
            $CUBRE_PERIODO_HASTA_ULTFC   = $value['CUBRE_PERIODO_HASTA_ULTFC'];

            
           
        
            $data[$i]= array(


                $NO_CIA,
                $CONTRATO_BASE,
                $NUMERO_CONTRATO,
                $FECHA_CONTRATO,
                $FAMILIA ,
                $FECHA_NACIMIENTO,
                $MONEDA_ANUALIDAD,
                $CUOTA_ANUAL,
                $ESTADO_CONTRATO,
                $SITUACION_CONTRATO ,
                $INUBICABLE_PAPA,
                $INUBICABLE_MAMA ,
                $TIPO_DOCUMENTO_ULTFC ,
                $NUMERO_DOCUMENTO_ULTFC ,
                $FECHA_ULTFC ,
                $CUBRE_PERIODO_DESDE_ULTFC ,
                $CUBRE_PERIODO_HASTA_ULTFC


            );

            $i++;
            }




        }elseif($variable == 'FACANU'){

            

            foreach ($list as $value) {
            
            $NO_CIA              = $value['NO_CIA'];
            $TD                 = $value['TD'];
            $NUMERO             = $value['NUMERO'];
            $NUMERO_CONTRATO      = $value['NUMERO_CONTRATO'];
            $SERVICIO             = $value['SERVICIO'];
            $FECHA              = $value['FECHA'];
            $NOMBRE_MAMA        = $value['NOMBRE_MAMA'];
            $MAIL_MAMA          = $value['MAIL_MAMA'];
            $TELEFONO_MAMA      = $value['TELEFONO_MAMA'];
            $CELULAR_MAMA       = $value['CELULAR_MAMA'];
            $NOMBRE_PAPA        = $value['NOMBRE_PAPA'];
            $MAIL_PAPA          = $value['MAIL_PAPA'];
            $TELEFONO_PAPA      = $value['TELEFONO_PAPA'];
            $CELULAR_PAPA       = $value['CELULAR_PAPA'];

            $PERIODO_MINIMO_PAGADO  = $value['PERIODO_MINIMO_PAGADO'];
            $PAR_IMPAR              = $value['PAR_IMPAR'];
            $PERIODO_ASIGNADO       = $value['PERIODO_ASIGNADO'];
            $PERIODO_ORIGINAL       = $value['PERIODO_ORIGINAL'];

            $HEMOCULTIVO            = $value['HEMOCULTIVO'];
            $SEROLOGIA              = $value['SEROLOGIA'];
            $FECHA_COMUNICACION     = $value['FECHA_COMUNICACION'];
            $USUARIO                = $value['USUARIO'];
            $DETALLE                = $value['DETALLE'];
            $MEDIO_PAGO             = $value['MEDIO_PAGO'];
            $MONEDA                 = $value['MONEDA'];
            $MONTO_ANUALIDAD        = $value['MONTO_ANUALIDAD'];
            $PORCENTAJE_DESCUENTO   = $value['PORCENTAJE_DESCUENTO'];
            $VALOR_DESCUENTO        = $value['VALOR_DESCUENTO'];
            $PENALIDAD   = $value['PENALIDAD'];
            $TOTAL       = $value['TOTAL'];

            
        
            $data[$i]= array(

                $NO_CIA ,
                $TD  ,
                $NUMERO  ,
                $NUMERO_CONTRATO ,
                $SERVICIO ,
                $FECHA  ,
                $NOMBRE_MAMA ,
                $MAIL_MAMA  ,
                $TELEFONO_MAMA  ,
                $CELULAR_MAMA ,
                $NOMBRE_PAPA ,
                $MAIL_PAPA ,
                $TELEFONO_PAPA ,
                $CELULAR_PAPA ,

                $PERIODO_MINIMO_PAGADO,
                $PAR_IMPAR  ,
                $PERIODO_ASIGNADO ,
                $PERIODO_ORIGINAL ,

                $HEMOCULTIVO ,
                $SEROLOGIA ,
                $FECHA_COMUNICACION ,
                $USUARIO  ,
                $DETALLE ,
                $MEDIO_PAGO ,
                $MONEDA ,
                $MONTO_ANUALIDAD    ,
                $PORCENTAJE_DESCUENTO,
                $VALOR_DESCUENTO ,
                $PENALIDAD  ,
                $TOTAL   
               


            );

            $i++;
            }




        }



        return $data;

    }

    protected function set_nombre_cia(){


        $cia = Auth::user()->empresa;

        $query = \DB::select("SELECT RAZON_SOCIAL FROM COR_COMPANIAS WHERE CODIGO_COMPANIA=?",array($cia));


        $rpta = json_decode(json_encode($query),true);
    
        return $rpta[0]['razon_social'];






    }
    protected function set_filas_excel_export_general($list,$variable,$desde){


        $data =array();

        if($variable == 'RFAP'){
            //RESUMEN DE FACTURACION SE COMBINAN CABECERAS
             
             $nombre_cia = $this->set_nombre_cia();


            

             $año = Carbon::parse($desde)->format('Y');

             $mes = Carbon::parse($desde)->format('m');

             $periodo = $this->mes_en_espanol($mes).'-'.$año;

             $data[0] = array("REPORTE DE COBRANZA - PERIDODO : $periodo - $nombre_cia");
             $data[1] = $this->set_nombre_columnas_resumen_facturacion($desde);

            
             

        }else{

            $data[0] = array();
            $data[1] = array();
        }
        
        
        $data[2] = $this->set_cabecera_excel_export_general($variable);

        $body = $this->set_body_excel_export_general($list,$variable);
        
        return array_merge($data,$body);


    }


    protected function mes_en_espanol($mes){


        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        return $meses[$mes-1];


    }


    protected function set_nombre_columnas_resumen_facturacion($inicio){


        $año = (int)Carbon::parse($inicio)->format('Y')-1;

        $mes = (int)Carbon::parse($inicio)->format('m');

        $rows =    24;

        $cabecera = array();

        


        for($i = 0; $i<$rows; $i++){


            if($mes == 13){

                $mes=1;

                $año = $año+1 ;

            }

             $cabecera[] = $this->mes_en_espanol($mes).'-'.$año;

             $cabecera[] = "";

             $mes ++;
        }


       $datos_iniciales = array("","","Total","","Vencidos","");


       $datos_finales = array("Adelanto","");


       $titulos = array_merge($datos_iniciales,$cabecera,$datos_finales);

       

       return $titulos;

        
    }
    
    
}