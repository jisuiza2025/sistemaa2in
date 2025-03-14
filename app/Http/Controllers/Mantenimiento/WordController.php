<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PDF;
use App\Contrato;
use Carbon\Carbon;


class WordController extends Controller
{	
	
    
   


      public function generacion_contrato($plantilla,$contrato,$variable)
      {      

            
      
            try {
                  
                  

                  

            $file_path = $plantilla;

         
       
            $template = new \PhpOffice\PhpWord\TemplateProcessor($file_path);
 
        
            
            $get_data = Contrato::genera_contrato_impresion($contrato,$variable);

            $json = json_decode(json_encode($get_data),true);


            $NUMERO_CONTRATO = (isset($json[0]['NUMERO_CONTRATO']))?$json[0]['NUMERO_CONTRATO']:'';

            $RAZON_SOCIAL = (isset($json[0]['RAZON_SOCIAL']))?$json[0]['RAZON_SOCIAL']:'';

            $RUC = (isset($json[0]['RUC']))?$json[0]['RUC']:'';

            $REPRESENTANTE_LEGAL = (isset($json[0]['DNI_REP_LEGAL']))?$json[0]['DNI_REP_LEGAL']:'';

            $DIRECCION_EMPRESA = (isset($json[0]['DIRECCION_LEGAL']))?$json[0]['DIRECCION_LEGAL']:'';


            $NOMBRE_MAMA = (isset($json[0]['NOMBRE_MAMA']))?$json[0]['NOMBRE_MAMA']:'';

            $TIPO_DOC_MAMA = (isset($json[0]['TIPO_DOC_MAMA']))?$json[0]['TIPO_DOC_MAMA']:'';

            $DOC_MAMA = (isset($json[0]['DOC_MAMA']))?$json[0]['DOC_MAMA']:'';

            $ESTADO_CIVIL_MAMA = (isset($json[0]['ESTADO_CIVIL_MAMA']))?$json[0]['ESTADO_CIVIL_MAMA']:'';


            $NOMBRE_PAPA = (isset($json[0]['NOMBRE_PAPA']))?$json[0]['NOMBRE_PAPA']:'';

            $TIPO_DOC_PAPA = (isset($json[0]['TIPO_DOC_PAPA']))?$json[0]['TIPO_DOC_PAPA']:'';

            $DOC_PAPA = (isset($json[0]['DOC_PAPA']))?$json[0]['DOC_PAPA']:'';


            $DIRECCION_MAMA = (isset($json[0]['DIRECCION_MAMA']))?$json[0]['DIRECCION_MAMA']:'';


            $MAIL_MAMA = (isset($json[0]['MAIL_MAMA']))?$json[0]['MAIL_MAMA']:'';

            $MAIL_PAPA = (isset($json[0]['MAIL_PAPA']))?$json[0]['MAIL_PAPA']:'';


            $MONEDA_CONTRATO = (isset($json[0]['MONEDA_CONTRATO']))?$json[0]['MONEDA_CONTRATO']:'';


            $MONTO_SOLES = (isset($json[0]['MONTO_SOLES']))?$json[0]['MONTO_SOLES']:'';

            $MONTO_SOLES_TXT = (isset($json[0]['MONTO_SOLES_TXT']))?$json[0]['MONTO_SOLES_TXT']:'';


            $CUOTA_DOLARES = (isset($json[0]['CUOTA_DOLARES']))?$json[0]['CUOTA_DOLARES']:'';

            $CUOTA_DOLARES_TXT = (isset($json[0]['CUOTA_DOLARES_TXT']))?$json[0]['CUOTA_DOLARES_TXT']:'';



            $CUOTA_DOL_CINCO = (isset($json[0]['CUOTA_DOL_CINCO']))?$json[0]['CUOTA_DOL_CINCO']:'';

            $CUOTA_DOL_CINCO_TXT = (isset($json[0]['CUOTA_DOL_CINCO_TXT']))?$json[0]['CUOTA_DOL_CINCO_TXT']:'';


            $CUOTA_DOL_DIEZ = (isset($json[0]['CUOTA_DOL_DIEZ']))?$json[0]['CUOTA_DOL_DIEZ']:'';

            $CUOTA_DOL_DIEZ_TXT = (isset($json[0]['CUOTA_DOL_DIEZ_TXT']))?$json[0]['CUOTA_DOL_DIEZ_TXT']:'';



            $CUOTA_DOL_VEINTE = (isset($json[0]['CUOTA_DOL_VEINTE']))?$json[0]['CUOTA_DOL_VEINTE']:'';

            $CUOTA_DOL_VEINTE_TXT = (isset($json[0]['CUOTA_DOL_VEINTE_TXT']))?$json[0]['CUOTA_DOL_VEINTE_TXT']:'';


            $PENALIDAD_DOL = (isset($json[0]['PENALIDAD_DOL']))?$json[0]['PENALIDAD_DOL']:'';

            $PENALIDAD_DOL_TXT = (isset($json[0]['PENALIDAD_DOL_TXT']))?$json[0]['PENALIDAD_DOL_TXT']:'';



            $DIA = (isset($json[0]['DIA']))?$json[0]['DIA']:'';

            $MES = (isset($json[0]['MES']))?$json[0]['MES']:'';

            $ANIO = (isset($json[0]['ANIO']))?$json[0]['ANIO']:'';

        


            $CONTRATO_BASE = (isset($json[0]['CONTRATO_BASE']))?$json[0]['CONTRATO_BASE']:'';


            $EDAD_MAMA_CON = (isset($json[0]['EDAD_MAMA_CON']))?$json[0]['EDAD_MAMA_CON']:'';


            $EDAD_ACTUAL_MAMA = (isset($json[0]['EDAD_ACTUAL_MAMA']))?$json[0]['EDAD_ACTUAL_MAMA']:'';

            
           
            $FECNACI_BEBE = (isset($json[0]['FECNACI_BEBE']))?$json[0]['FECNACI_BEBE']:'';



            

            $dia_actual = Carbon::now()->format('d');
            $mes_actual = $this->obtener_mes_actual_espanol();
            $año_actual = Carbon::now()->format('Y');


           
      
            //OTROS CAMPOS SP
            

            $ESTADO_CIVIL_PAPA = (isset($json[0]['ESTADO_CIVIL_PAPA']))?$json[0]['ESTADO_CIVIL_PAPA']:'';


            $FECHA_CONTRATO = (isset($json[0]['FECHA_CONTRATO']))?$json[0]['FECHA_CONTRATO']:'';

            $ESTADO_CONTRATO = (isset($json[0]['ESTADO_CONTRATO']))?$json[0]['ESTADO_CONTRATO']:'';

            $PARENTESCO = (isset($json[0]['PARENTESCO']))?$json[0]['PARENTESCO']:'';

            
            $EDAD_PAPA_CON = (isset($json[0]['EDAD_PAPA_CON']))?$json[0]['EDAD_PAPA_CON']:'';

           
            $FECNACI_MAMA = (isset($json[0]['FECNACI_MAMA']))?$json[0]['FECNACI_MAMA']:'';


            $FECNACI_PAPA = (isset($json[0]['FECNACI_PAPA']))?$json[0]['FECNACI_PAPA']:'';

            $EDAD_ACTUAL_PAPA = (isset($json[0]['EDAD_ACTUAL_PAPA']))?$json[0]['EDAD_ACTUAL_PAPA']:'';

            $DIRECCION_PAPA = (isset($json[0]['DIRECCION_PAPA']))?$json[0]['DIRECCION_PAPA']:'';

            $TIPO_PLANTILLA = (isset($json[0]['TIPO_PLANTILLA']))?$json[0]['TIPO_PLANTILLA']:'';

            $PLANTILLA = (isset($json[0]['PLANTILLA']))?$json[0]['PLANTILLA']:'';

            
            $OBS_ACEP_USCU = (isset($json[0]['OBS_ACEP_USCU']))?$json[0]['OBS_ACEP_USCU']:'';

            
            $FAMILIA = (isset($json[0]['FAMILIA']))?$json[0]['FAMILIA']:'';

            $CNTX10 = (isset($json[0]['CNTX10']))?$json[0]['CNTX10']:'';

            $NOMBRE_BEBE = (isset($json[0]['NOMBRE_BEBE']))?$json[0]['NOMBRE_BEBE']:'';

            $FECHA_PARTO = (isset($json[0]['FECHA_PARTO']))?$json[0]['FECHA_PARTO']:'';

            $APELLIDOS_BEBE = (isset($json[0]['APELLIDOS_BEBE']))?$json[0]['APELLIDOS_BEBE']:'';

            $PAIS_MAMA = (isset($json[0]['PAIS_MAMA']))?$json[0]['PAIS_MAMA']:'';

            $UBIGEO_MAMA = (isset($json[0]['UBIGEO_MAMA']))?$json[0]['UBIGEO_MAMA']:'';

            $TELF_MAMA = (isset($json[0]['TELF_MAMA']))?$json[0]['TELF_MAMA']:'';

            $CELULAR_MAMA = (isset($json[0]['CELULAR_MAMA']))?$json[0]['CELULAR_MAMA']:'';

            $PAIS_PAPA = (isset($json[0]['PAIS_PAPA']))?$json[0]['PAIS_PAPA']:'';

            $UBIGEO_PAPA = (isset($json[0]['UBIGEO_PAPA']))?$json[0]['UBIGEO_PAPA']:'';

            $TELF_PAPA = (isset($json[0]['TELF_PAPA']))?$json[0]['TELF_PAPA']:'';

            $CELULAR_PAPA = (isset($json[0]['CELULAR_PAPA']))?$json[0]['CELULAR_PAPA']:'';

            $NOMBRE_MEDICO = (isset($json[0]['NOMBRE_MEDICO']))?$json[0]['NOMBRE_MEDICO']:'';

            $CELULAR_MEDICO = (isset($json[0]['CELULAR_MEDICO']))?$json[0]['CELULAR_MEDICO']:'';

            $NOMBRE_CLINICA = (isset($json[0]['NOMBRE_CLINICA']))?$json[0]['NOMBRE_CLINICA']:'';

            $PLAN_SEGURO = (isset($json[0]['PLAN_SEGURO']))?$json[0]['PLAN_SEGURO']:'';

            $PORCENTAJE_SEGURO = (isset($json[0]['PORCENTAJE_SEGURO']))?$json[0]['PORCENTAJE_SEGURO']:'';
            

            //NUEVAS LLAVES SP MODIFICADO
            

            $NOMBRE_PROPIETARIO = (isset($json[0]['NOMBRE_PROPIETARIO']))?$json[0]['NOMBRE_PROPIETARIO']:'';

            $DOC_PROPIETARIO = (isset($json[0]['DOC_PROPIETARIO']))?$json[0]['DOC_PROPIETARIO']:'';

            $ESTADO_CIVIL_PROPIETARIO = (isset($json[0]['ESTADO_CIVIL_PROPIETARIO']))?$json[0]['ESTADO_CIVIL_PROPIETARIO']:'';

            $FECNACI_PROPIETARIO = (isset($json[0]['FECNACI_PROPIETARIO']))?$json[0]['FECNACI_PROPIETARIO']:'';

            $EDAD_ACTUAL_PROPIETARIO = (isset($json[0]['EDAD_ACTUAL_PROPIETARIO']))?$json[0]['EDAD_ACTUAL_PROPIETARIO']:'';

            $DIRECCION_PROPIETARIO = (isset($json[0]['DIRECCION_PROPIETARIO']))?$json[0]['DIRECCION_PROPIETARIO']:'';

            $MAIL_PROPIETARIO = (isset($json[0]['MAIL_PROPIETARIO']))?$json[0]['MAIL_PROPIETARIO']:'';

            $NOMBRE_TITULAR = (isset($json[0]['NOMBRE_TITULAR']))?$json[0]['NOMBRE_TITULAR']:'';

            $TIPO_DOC_TITULAR = (isset($json[0]['TIPO_DOC_TITULAR']))?$json[0]['TIPO_DOC_TITULAR']:'';

            $DOC_TITULAR = (isset($json[0]['DOC_TITULAR']))?$json[0]['DOC_TITULAR']:'';

            $ESTADO_CIVIL_TITULAR = (isset($json[0]['ESTADO_CIVIL_TITULAR']))?$json[0]['ESTADO_CIVIL_TITULAR']:'';

            $FECNACI_TITULAR = (isset($json[0]['FECNACI_TITULAR']))?$json[0]['FECNACI_TITULAR']:'';

            $EDAD_ACTUAL_TITULAR = (isset($json[0]['EDAD_ACTUAL_TITULAR']))?$json[0]['EDAD_ACTUAL_TITULAR']:'';

            $DIRECCION_TITULAR = (isset($json[0]['DIRECCION_TITULAR']))?$json[0]['DIRECCION_TITULAR']:'';

            $MAIL_TITULAR = (isset($json[0]['MAIL_TITULAR']))?$json[0]['MAIL_TITULAR']:'';



            $NOMBRE_TUTOR = (isset($json[0]['NOMBRE_TUTOR']))?$json[0]['NOMBRE_TUTOR']:'';

            $TIPO_DOC_TUTOR = (isset($json[0]['TIPO_DOC_TUTOR']))?$json[0]['TIPO_DOC_TUTOR']:'';

            $DOC_TUTOR = (isset($json[0]['DOC_TUTOR']))?$json[0]['DOC_TUTOR']:'';

            $ESTADO_CIVIL_TUTOR = (isset($json[0]['ESTADO_CIVIL_TUTOR']))?$json[0]['ESTADO_CIVIL_TUTOR']:'';

            $FECNACI_TUTOR = (isset($json[0]['FECNACI_TUTOR']))?$json[0]['FECNACI_TUTOR']:'';

            $EDAD_ACTUAL_TUTOR = (isset($json[0]['EDAD_ACTUAL_TUTOR']))?$json[0]['EDAD_ACTUAL_TUTOR']:'';

            $DIRECCION_TUTOR = (isset($json[0]['DIRECCION_TUTOR']))?$json[0]['DIRECCION_TUTOR']:'';

            $MAIL_TUTOR = (isset($json[0]['MAIL_TUTOR']))?$json[0]['MAIL_TUTOR']:'';

            

            $TIPO_DOC_PROPIETARIO = (isset($json[0]['TIPO_DOC_PROPIETARIO']))?$json[0]['TIPO_DOC_PROPIETARIO']:'';

		$MONTO_SUMADO = (isset($json[0]['MONTO_SUMADO']))?$json[0]['MONTO_SUMADO']:'';
		 $MONTO_SUMADO_TXT = (isset($json[0]['MONTO_SUMADO_TXT']))?$json[0]['MONTO_SUMADO_TXT']:'';

             $template->setValue('monto_sumado', mb_strtoupper($MONTO_SUMADO));
		$template->setValue('monto_sumado_txt', mb_strtoupper($MONTO_SUMADO_TXT));

            $template->setValue('razon_social', mb_strtoupper($RAZON_SOCIAL));

            $template->setValue('ruc', mb_strtoupper($RUC));

            $template->setValue('rep_legal', mb_strtoupper($REPRESENTANTE_LEGAL));

            $template->setValue('direccion_legal', mb_strtoupper($DIRECCION_EMPRESA));


            $template->setValue('nombre_mama', mb_strtoupper($NOMBRE_MAMA));

            $template->setValue('tipo_identidad_mama', mb_strtoupper($TIPO_DOC_MAMA));

            $template->setValue('identidad_mama', mb_strtoupper($DOC_MAMA));

            $template->setValue('estado_civil_mama', mb_strtoupper($ESTADO_CIVIL_MAMA));


            $template->setValue('nombre_papa', mb_strtoupper($NOMBRE_PAPA));

            $template->setValue('tipo_identidad_papa', mb_strtoupper($TIPO_DOC_PAPA));

            $template->setValue('identidad_papa', mb_strtoupper($DOC_PAPA));


            $template->setValue('direccion_mama', mb_strtoupper($DIRECCION_MAMA));

            $template->setValue('mail_mama', mb_strtoupper($MAIL_MAMA));

            $template->setValue('mail_papa', mb_strtoupper($MAIL_PAPA));



            $template->setValue('moneda_contrato', mb_strtoupper($MONEDA_CONTRATO));

            $template->setValue('monto_contrato', mb_strtoupper($MONTO_SOLES));

            $template->setValue('texto_monto_contrato', mb_strtoupper($MONTO_SOLES_TXT));


            $template->setValue('anualidad_dolares', mb_strtoupper($CUOTA_DOLARES));

            $template->setValue('texto_anualidad_dolares', mb_strtoupper($CUOTA_DOLARES_TXT));

            $template->setValue('anualidad_dol_por_cinco', mb_strtoupper($CUOTA_DOL_CINCO));

            $template->setValue('texto_anualidad_dol_por_cinco', mb_strtoupper($CUOTA_DOL_CINCO_TXT));

            $template->setValue('anualidad_dol_por_diez', mb_strtoupper($CUOTA_DOL_DIEZ));

            $template->setValue('texto_anualidad_dol_por_diez', mb_strtoupper($CUOTA_DOL_DIEZ_TXT));

            $template->setValue('anualidad_dol_por_veinte', mb_strtoupper($CUOTA_DOL_VEINTE));
            
            $template->setValue('texto_anualidad_dol_por_veinte', mb_strtoupper($CUOTA_DOL_VEINTE_TXT));


            $template->setValue('penalidad_dolares', mb_strtoupper($PENALIDAD_DOL));

            $template->setValue('texto_penalidad_dolares', mb_strtoupper($PENALIDAD_DOL_TXT));


            $template->setValue('dia_contrato', mb_strtoupper($DIA));
        
            $template->setValue('mes_contrato', mb_strtoupper($MES));

            $template->setValue('año_contrato', mb_strtoupper($ANIO));



            $template->setValue('contrato_base', mb_strtoupper($CONTRATO_BASE));

            $template->setValue('edad_contrato_mama', mb_strtoupper($EDAD_MAMA_CON));

            $template->setValue('edad_mama', mb_strtoupper($EDAD_ACTUAL_MAMA));


            $template->setValue('numero_contrato', mb_strtoupper($NUMERO_CONTRATO));

            $template->setValue('fecha_nacimiento_bebe', mb_strtoupper($FECNACI_BEBE));


            $template->setValue('dia_actual', mb_strtoupper($dia_actual));
        
            $template->setValue('mes_actual', mb_strtoupper($mes_actual));

            $template->setValue('año_actual', mb_strtoupper($año_actual));
            

            
            
            //NUEVOS CAMPOS

            $template->setValue('familia', mb_strtoupper($FAMILIA));

            $template->setValue('nombre_bebe', mb_strtoupper($NOMBRE_BEBE));


            $template->setValue('estado_civil_papa', mb_strtoupper($ESTADO_CIVIL_PAPA));

            $template->setValue('fecha_contrato', mb_strtoupper($FECHA_CONTRATO));

            $template->setValue('estado_contrato', mb_strtoupper($ESTADO_CONTRATO));

            $template->setValue('parentesco_titular', mb_strtoupper($PARENTESCO));

            $template->setValue('edad_papa', mb_strtoupper($EDAD_ACTUAL_PAPA));

            $template->setValue('fecha_nacimiento_mama', mb_strtoupper($FECNACI_MAMA));

            $template->setValue('fecha_nacimiento_papa', mb_strtoupper($FECNACI_PAPA));


            $template->setValue('edad_contrato_papa', mb_strtoupper($EDAD_PAPA_CON));

            $template->setValue('direccion_papa', mb_strtoupper($DIRECCION_PAPA));

            $template->setValue('tipo_plantilla', mb_strtoupper($TIPO_PLANTILLA));

            $template->setValue('plantilla', mb_strtoupper($PLANTILLA));

            $template->setValue('obs_acepta_uscu', mb_strtoupper($OBS_ACEP_USCU));

            $template->setValue('cntx10', mb_strtoupper($CNTX10));

            $template->setValue('fecha_parto', mb_strtoupper($FECHA_PARTO));

            $template->setValue('apellidos_bebe', mb_strtoupper($APELLIDOS_BEBE));

            $template->setValue('país_mama', mb_strtoupper($PAIS_MAMA));

            $template->setValue('ubigeo_mama', mb_strtoupper($UBIGEO_MAMA));

            $template->setValue('telef_mama', mb_strtoupper($TELF_MAMA));

            $template->setValue('celular_mama', mb_strtoupper($CELULAR_MAMA));

            $template->setValue('país_papa', mb_strtoupper($PAIS_PAPA));

            $template->setValue('ubigeo_papa', mb_strtoupper($UBIGEO_PAPA));

            $template->setValue('telef_papa', mb_strtoupper($TELF_PAPA));

            $template->setValue('celular_papa', mb_strtoupper($CELULAR_PAPA));

            $template->setValue('nombre_medico', mb_strtoupper($NOMBRE_MEDICO));

            $template->setValue('celular_medico', mb_strtoupper($CELULAR_MEDICO));

            $template->setValue('nombre_clinica', mb_strtoupper($NOMBRE_CLINICA));

            $template->setValue('plan_seguro', mb_strtoupper($PLAN_SEGURO));

            $template->setValue('porcentaje_seguro', mb_strtoupper($PORCENTAJE_SEGURO));

            //NUEVOS CAMPOS SP


            $template->setValue('nombre_propietario', mb_strtoupper($NOMBRE_PROPIETARIO));

            $template->setValue('doc_propietario', mb_strtoupper($DOC_PROPIETARIO));

            $template->setValue('tipo_doc_propietario', mb_strtoupper($TIPO_DOC_PROPIETARIO));

            $template->setValue('estado_civil_propietario', mb_strtoupper($ESTADO_CIVIL_PROPIETARIO));

            $template->setValue('fecnaci_propietario', mb_strtoupper($FECNACI_PROPIETARIO));

            $template->setValue('edad_actual_propietario', mb_strtoupper($EDAD_ACTUAL_PROPIETARIO));

            $template->setValue('direccion_propietario', mb_strtoupper($DIRECCION_PROPIETARIO));

            $template->setValue('mail_propietario', mb_strtoupper($MAIL_PROPIETARIO));

            $template->setValue('nombre_titular', mb_strtoupper($NOMBRE_TITULAR));

            $template->setValue('tipo_doc_titular', mb_strtoupper($TIPO_DOC_TITULAR));

            $template->setValue('doc_titular', mb_strtoupper($DOC_TITULAR));

            $template->setValue('estado_civil_titular', mb_strtoupper($ESTADO_CIVIL_TITULAR));

            $template->setValue('fecnaci_titular', mb_strtoupper($FECNACI_TITULAR));

            $template->setValue('edad_actual_titular', mb_strtoupper($EDAD_ACTUAL_TITULAR));

            $template->setValue('direccion_titular', mb_strtoupper($DIRECCION_TITULAR));

            $template->setValue('mail_titular', mb_strtoupper($MAIL_TITULAR));




            $template->setValue('nombre_tutor', mb_strtoupper($NOMBRE_TUTOR));

            $template->setValue('tipo_doc_tutor', mb_strtoupper($TIPO_DOC_TUTOR));

            $template->setValue('doc_tutor', mb_strtoupper($DOC_TUTOR));

            $template->setValue('estado_civil_tutor', mb_strtoupper($ESTADO_CIVIL_TUTOR));

            $template->setValue('fecnaci_tutor', mb_strtoupper($FECNACI_TUTOR));

            $template->setValue('edad_actual_tutor', mb_strtoupper($EDAD_ACTUAL_TUTOR));

            $template->setValue('direccion_tutor', mb_strtoupper($DIRECCION_TUTOR));

            $template->setValue('mail_tutor', mb_strtoupper($MAIL_TUTOR));


            
            //
            $tenpFile = tempnam(sys_get_temp_dir(),'PHPWord');

            $template->saveAs($tenpFile);

         


            $randomName = $this->generaRandomString(10);

            $subDirectory = '/resultadoFormatoWord/'.$randomName.'.docx';

            $file_to = public_path().$subDirectory;



            if(!copy($tenpFile, $file_to)){
                  return  $this->setRpta('error','No se pudo mover el fichero temporal contratacion word ');
            }
                  

            if ( file_exists($tenpFile) ) {

               unlink($tenpFile);

            }

            //


            if(file_exists($file_to)){

                  return $this->setRpta('ok','Se reemplazó existosamente en el documento WORD',$subDirectory);

            }else{


                  return $this->setRpta('error','No se pudo reemplazar las variables en el documento WORD');
            }


            } catch (\Exception $e) {
                  
                  return $this->setRpta('error','OfficeWord: '.$e->getMessage());

            }


            

      }

	
	


  public function generacion_desvinculacion($plantilla,$contrato)
  {      

    
  
    try {
      
      

     

            $file_path = $plantilla;

      
            $template = new \PhpOffice\PhpWord\TemplateProcessor($file_path);
 
          
            $get_data = Contrato::solicitar_desvinculacion_contrato_get_data($contrato);


           
            $json = json_decode(json_encode($get_data),true);

            $dia_contrato = (isset($json[0]['DIA']))?$json[0]['DIA']:'';

            $mes_contrato = (isset($json[0]['MES']))?$json[0]['MES']:'';

            $año_contrato = (isset($json[0]['ANIO']))?$json[0]['ANIO']:'';

      

          $numero_contrato = (isset($json[0]['NUMERO_CONTRATO']))?$json[0]['NUMERO_CONTRATO']:'';




            $FECHA_CONTRATO = (isset($json[0]['FECHA_CONTRATO']))?$json[0]['FECHA_CONTRATO']:'';
            $ESTADO_CONTRATO = (isset($json[0]['ESTADO_CONTRATO']))?$json[0]['ESTADO_CONTRATO']:'';
            $PARENTESCO = (isset($json[0]['PARENTESCO']))?$json[0]['PARENTESCO']:'';
            $EDAD_MAMA_CON = (isset($json[0]['EDAD_MAMA_CON']))?$json[0]['EDAD_MAMA_CON']:'';
            $EDAD_PAPA_CON = (isset($json[0]['EDAD_PAPA_CON']))?$json[0]['EDAD_PAPA_CON']:'';
            $MONEDA_CONTRATO = (isset($json[0]['MONEDA_CONTRATO']))?$json[0]['MONEDA_CONTRATO']:'';
            $MONTO_SOLES = (isset($json[0]['MONTO_SOLES']))?$json[0]['MONTO_SOLES']:'';
            $MONTO_SOLES_TXT = (isset($json[0]['MONTO_SOLES_TXT']))?$json[0]['MONTO_SOLES_TXT']:'';
            $CUOTA_DOLARES = (isset($json[0]['CUOTA_DOLARES']))?$json[0]['CUOTA_DOLARES']:'';
            $CUOTA_DOLARES_TXT = (isset($json[0]['CUOTA_DOLARES_TXT']))?$json[0]['CUOTA_DOLARES_TXT']:'';
            $CUOTA_DOL_CINCO = (isset($json[0]['CUOTA_DOL_CINCO']))?$json[0]['CUOTA_DOL_CINCO']:'';
            $CUOTA_DOL_CINCO_TXT = (isset($json[0]['CUOTA_DOL_CINCO_TXT']))?$json[0]['CUOTA_DOL_CINCO_TXT']:'';
            $CUOTA_DOL_DIEZ = (isset($json[0]['CUOTA_DOL_DIEZ']))?$json[0]['CUOTA_DOL_DIEZ']:'';
            $CUOTA_DOL_DIEZ_TXT = (isset($json[0]['CUOTA_DOL_DIEZ_TXT']))?$json[0]['CUOTA_DOL_DIEZ_TXT']:'';
            $CUOTA_DOL_VEINTE = (isset($json[0]['CUOTA_DOL_VEINTE']))?$json[0]['CUOTA_DOL_VEINTE']:'';
            $CUOTA_DOL_VEINTE_TXT = (isset($json[0]['CUOTA_DOL_VEINTE_TXT']))?$json[0]['CUOTA_DOL_VEINTE_TXT']:'';
            $PENALIDAD_DOL = (isset($json[0]['PENALIDAD_DOL']))?$json[0]['PENALIDAD_DOL']:'';
            $PENALIDAD_DOL_TXT = (isset($json[0]['PENALIDAD_DOL_TXT']))?$json[0]['PENALIDAD_DOL_TXT']:'';
            $NOMBRE_MAMA = (isset($json[0]['NOMBRE_MAMA']))?$json[0]['NOMBRE_MAMA']:'';
            $TIPO_DOC_MAMA = (isset($json[0]['TIPO_DOC_MAMA']))?$json[0]['TIPO_DOC_MAMA']:'';
            $DOC_MAMA = (isset($json[0]['DOC_MAMA']))?$json[0]['DOC_MAMA']:'';
            $ESTADO_CIVIL_MAMA = (isset($json[0]['ESTADO_CIVIL_MAMA']))?$json[0]['ESTADO_CIVIL_MAMA']:'';
            $FECNACI_MAMA = (isset($json[0]['FECNACI_MAMA']))?$json[0]['FECNACI_MAMA']:'';
            $EDAD_ACTUAL_MAMA = (isset($json[0]['EDAD_ACTUAL_MAMA']))?$json[0]['EDAD_ACTUAL_MAMA']:'';
            $DIRECCION_MAMA = (isset($json[0]['DIRECCION_MAMA']))?$json[0]['DIRECCION_MAMA']:'';
            $NOMBRE_PAPA = (isset($json[0]['NOMBRE_PAPA']))?$json[0]['NOMBRE_PAPA']:'';
            $TIPO_DOC_PAPA = (isset($json[0]['TIPO_DOC_PAPA']))?$json[0]['TIPO_DOC_PAPA']:'';
            $DOC_PAPA = (isset($json[0]['DOC_PAPA']))?$json[0]['DOC_PAPA']:'';
            $ESTADO_CIVIL_PAPA = (isset($json[0]['ESTADO_CIVIL_PAPA']))?$json[0]['ESTADO_CIVIL_PAPA']:'';
            $FECNACI_PAPA = (isset($json[0]['FECNACI_PAPA']))?$json[0]['FECNACI_PAPA']:'';
            


            $EDAD_ACTUAL_PAPA = (isset($json[0]['EDAD_ACTUAL_PAPA']))?$json[0]['EDAD_ACTUAL_PAPA']:'';
            $DIRECCION_PAPA = (isset($json[0]['DIRECCION_PAPA']))?$json[0]['DIRECCION_PAPA']:'';
            $FECNACI_BEBE = (isset($json[0]['FECNACI_BEBE']))?$json[0]['FECNACI_BEBE']:'';
            $MAIL_MAMA = (isset($json[0]['MAIL_MAMA']))?$json[0]['MAIL_MAMA']:'';
            $MAIL_PAPA = (isset($json[0]['MAIL_PAPA']))?$json[0]['MAIL_PAPA']:'';
            $OBS_ACEP_USCU = (isset($json[0]['OBS_ACEP_USCU']))?$json[0]['OBS_ACEP_USCU']:'';
            
            $dia_actual = Carbon::now()->format('d');
            $mes_actual = $this->obtener_mes_actual_espanol();
            $año_actual = Carbon::now()->format('Y');


            $template->setValue('numero_contrato', $numero_contrato);

            $template->setValue('dia_contrato', $dia_contrato);

            $template->setValue('mes_contrato', $mes_contrato);

            $template->setValue('año_contrato', $año_contrato);

            $template->setValue('dia_actual', $dia_actual);

            $template->setValue('mes_actual', $mes_actual);

            $template->setValue('año_actual', $año_actual);
            
            
            $template->setValue('fecha_contrato', mb_strtoupper($FECHA_CONTRATO));
            $template->setValue('estado_contrato', mb_strtoupper($ESTADO_CONTRATO));
            $template->setValue('parentesco_titular', mb_strtoupper($PARENTESCO));
            $template->setValue('edad_contrato_mama', mb_strtoupper($EDAD_MAMA_CON));
            $template->setValue('edad_contrato_papa', mb_strtoupper($EDAD_PAPA_CON));
            $template->setValue('moneda_contrato', mb_strtoupper($MONEDA_CONTRATO));
            $template->setValue('monto_contrato', mb_strtoupper($MONTO_SOLES));
            $template->setValue('texto_monto_contrato', mb_strtoupper($MONTO_SOLES_TXT));
            $template->setValue('anualidad_dolares', mb_strtoupper($CUOTA_DOLARES));

            $template->setValue('texto_anualidad_dolares', mb_strtoupper($CUOTA_DOLARES_TXT));


             $template->setValue('anualidad_dol_por_cinco', mb_strtoupper($CUOTA_DOL_CINCO));

            $template->setValue('texto_anualidad_dol_por_cinco', mb_strtoupper($CUOTA_DOL_CINCO_TXT));

            $template->setValue('anualidad_dol_por_diez', mb_strtoupper($CUOTA_DOL_DIEZ));

            $template->setValue('texto_anualidad_dol_por_diez', mb_strtoupper($CUOTA_DOL_DIEZ_TXT));

            $template->setValue('anualidad_dol_por_veinte', mb_strtoupper($CUOTA_DOL_VEINTE));
            
            $template->setValue('texto_anualidad_dol_por_veinte', mb_strtoupper($CUOTA_DOL_VEINTE_TXT));

            $template->setValue('penalidad_dolares', mb_strtoupper($PENALIDAD_DOL));

            $template->setValue('texto_penalidad_dolares', mb_strtoupper($PENALIDAD_DOL_TXT));


             $template->setValue('nombre_mama', mb_strtoupper($NOMBRE_MAMA));

            $template->setValue('tipo_identidad_mama', mb_strtoupper($TIPO_DOC_MAMA));

            $template->setValue('identidad_mama', mb_strtoupper($DOC_MAMA));

            $template->setValue('estado_civil_mama', mb_strtoupper($ESTADO_CIVIL_MAMA));

            $template->setValue('fecha_nacimiento_mama', mb_strtoupper($FECNACI_MAMA));

            $template->setValue('edad_mama', mb_strtoupper($EDAD_ACTUAL_MAMA));

            $template->setValue('direccion_mama', mb_strtoupper($DIRECCION_MAMA));

            $template->setValue('nombre_papa', mb_strtoupper($NOMBRE_PAPA));

            $template->setValue('tipo_identidad_papa', mb_strtoupper($TIPO_DOC_PAPA));

            $template->setValue('identidad_papa', mb_strtoupper($DOC_PAPA));

            $template->setValue('estado_civil_papa', mb_strtoupper($ESTADO_CIVIL_PAPA));

            $template->setValue('fecha_nacimiento_papa', mb_strtoupper($FECNACI_PAPA));

            $template->setValue('edad_papa', mb_strtoupper($EDAD_ACTUAL_PAPA));

            $template->setValue('direccion_papa', mb_strtoupper($DIRECCION_PAPA));

            $template->setValue('fecha_nacimiento_bebe', mb_strtoupper($FECNACI_BEBE));

            $template->setValue('mail_mama', mb_strtoupper($MAIL_MAMA));

            $template->setValue('mail_papa', mb_strtoupper($MAIL_PAPA));

            $template->setValue('obs_acepta_uscu', mb_strtoupper($OBS_ACEP_USCU));

            //
            

            $tenpFile = tempnam(sys_get_temp_dir(),'PHPWord');

            $template->saveAs($tenpFile);

         


            $randomName = $this->generaRandomString(10);

            $subDirectory = '/resultadoFormatoWord/'.$randomName.'.docx';

            $file_to = public_path().$subDirectory;



            if(!copy($tenpFile, $file_to)){

                  return  $this->setRpta('error','No se pudo mover el fichero temporal desvinculación word ');
            }
                  

            if ( file_exists($tenpFile) ) {

               unlink($tenpFile);

            }

            //
            

       
            if(file_exists($file_to)){

              return $this->setRpta('ok','Se reemplazó existosamente en el documento WORD',$subDirectory);

            }else{


              return $this->setRpta('error','No se pudo reemplazar las variables en el documento WORD');
            }


    } catch (\Exception $e) {
      
      return $this->setRpta('error','OfficeWord: '.$e->getMessage());

    }


    

  }

//plantillas colectas




public function generacion_plantilla_colectas($plantilla,$llaves)
  {      

    
  
    try {
      
      

     

            $file_path = $plantilla;

      
            $template = new \PhpOffice\PhpWord\TemplateProcessor($file_path);
 
          
                $NUMERO_CONTRATO = $llaves[0]["NUMERO_CONTRATO"]; 
                $FEC_CONTRATO = $llaves[0]["FEC_CONTRATO"];
                $FEC_NACI = $llaves[0]["FEC_NACI"]; 
                $HORA_NACI = $llaves[0]["HORA_NACI"]; 
                $DIA_NACI = $llaves[0]["DIA_NACI"];
                $MES_NACI = $llaves[0]["MES_NACI"]; 
                $ANIO_NACI = $llaves[0]["ANIO_NACI"]; 
                $NOMBRES_PAPA = $llaves[0]["NOMBRES_PAPA"]; 
                $CORREO_PAPA = $llaves[0]["CORREO_PAPA"]; 
                $NOMBRES_MAMA = $llaves[0]["NOMBRES_MAMA"]; 
                $CORREO_MAMA = $llaves[0]["CORREO_MAMA"]; 
                $FAMILIA = $llaves[0]["FAMILIA"];
                $SEXO_BEBE = $llaves[0]["SEXO_BEBE"]; 
                $NUMERO_EVALUA = $llaves[0]["NUMERO_EVALUA"]; 
                $FEC_COLECTA = $llaves[0]["FEC_COLECTA"]; 
                $HORA_COLECTA = $llaves[0]["HORA_COLECTA"]; 
                $FEC_INGLAB = $llaves[0]["FEC_INGLAB"]; 
                $HORA_INGLAB = $llaves[0]["HORA_INGLAB"];
                $FEC_EVALUA = $llaves[0]["FEC_EVALUA"];
                $HORA_EVALUA = $llaves[0]["HORA_EVALUA"];
                $ESTADO_EVALUA =$llaves[0]["ESTADO_EVALUA"];
                $FEC_REGEVALUA = $llaves[0]["FEC_REGEVALUA"];
                $HORA_REGEVALUA = $llaves[0]["HORA_REGEVALUA"];
                $DNI_TECNOLOGO_EVA = $llaves[0]["DNI_TECNOLOGO_EVA"];
                $NOMBRES_TECNOLOGO_EVA = $llaves[0]["NOMBRES_TECNOLOGO_EVA"];
                $FEC_CIERREVALUA = $llaves[0]["FEC_CIERREVALUA"]; 
                $HORA_CIERREVALUA = $llaves[0]["HORA_CIERREVALUA"]; 
                $COND_USCU_ADECUADA = $llaves[0]["COND_USCU_ADECUADA"]; 
                $COND_USCU_INADECUADA = $llaves[0]["COND_USCU_INADECUADA"]; 
                $COND_USCU_INA_COAGULOS = $llaves[0]["COND_USCU_INA_COAGULOS"];
                $COND_USCU_INA_AVERIADA = $llaves[0]["COND_USCU_INA_AVERIADA"]; 
                $COND_USCU_INA_MALSELLADA = $llaves[0]["COND_USCU_INA_MALSELLADA"];
                $COND_USCU_INA_OTROS = $llaves[0]["COND_USCU_INA_OTROS"];
                $OBS_COND_EVALUA = $llaves[0]["OBS_COND_EVALUA"]; 
                $CODIGO_BOLSA = $llaves[0]["CODIGO_BOLSA"]; 
                $DESC_BOLSA = $llaves[0]["DESC_BOLSA"]; 
                $PESO_BOLSA = $llaves[0]["PESO_BOLSA"]; 
                $ANTI_BOLSA = $llaves[0]["ANTI_BOLSA"];
                $PESO_USCU = $llaves[0]["PESO_USCU"];
                $VOLUMEN_USCU = $llaves[0]["VOLUMEN_USCU"];
                $VOLUMEN_LIQ_USCU = $llaves[0]["VOLUMEN_LIQ_USCU"];
                $ACEP_USCU_ADECUADA = $llaves[0]["ACEP_USCU_ADECUADA"]; 
                $ACEP_USCU_INADECUADA = $llaves[0]["ACEP_USCU_INADECUADA"];
                $ACEP_USCU_COND_INADECUADA = $llaves[0]["ACEP_USCU_COND_INADECUADA"];
                $ACEP_USCU_VOLUMENBAJO = $llaves[0]["ACEP_USCU_VOLUMENBAJO"];
                $ACEP_USCU_OTRO = $llaves[0]["ACEP_USCU_OTRO"];
                $OBS_ACEPTA_EVALUA = $llaves[0]["OBS_ACEPTA_EVALUA"];
                $FEC_PROCESAMIENTO = $llaves[0]["FEC_PROCESAMIENTO"];
                $HOR_PROCESAMIENTO = $llaves[0]["HOR_PROCESAMIENTO"];
                $ESTADO_PROC = $llaves[0]["ESTADO_PROC"];
                $FEC_REGPROC = $llaves[0]["FEC_REGPROC"];
                $HOR_REGPROC = $llaves[0]["HOR_REGPROC"];
                $FEC_CIERREPROC = $llaves[0]["FEC_CIERREPROC"];
                $HOR_CIERREPROC = $llaves[0]["HOR_CIERREPROC"];
                $DNI_TECNOLOGO_PROC = $llaves[0]["DNI_TECNOLOGO_PROC"];
                $NOMBRES_TECNOLOGO_PROC = $llaves[0]["NOMBRES_TECNOLOGO_PROC"]; 
                $ROT_SET_PRO = $llaves[0]["ROT_SET_PRO"];
                $ROT_FRASCOS_HEMO = $llaves[0]["ROT_FRASCOS_HEMO"]; 
                $ROT_CRIOVIALES = $llaves[0]["ROT_CRIOVIALES"]; 
                $HES_UTILIZADO = $llaves[0]["HES_UTILIZADO"]; 
                $HES_VOLUMEN = $llaves[0]["HES_VOLUMEN"]; 
                $TOMA_MUESTRA_INICIAL = $llaves[0]["TOMA_MUESTRA_INICIAL"]; 
                $CELULAS_NUCLEADAS_INI = $llaves[0]["CELULAS_NUCLEADAS_INI"]; 
                $INI_PMN = $llaves[0]["INI_PMN"]; 
                $INI_CMN = $llaves[0]["INI_CMN"]; 
                $INI_CNT = $llaves[0]["INI_CNT"]; 
                $INI_CMNT = $llaves[0]["INI_CMNT"];
                $GRUPO_SANGUINEO = $llaves[0]["GRUPO_SANGUINEO"]; 
                $TIPO_GR_SANGUINEO = $llaves[0]["TIPO_GR_SANGUINEO"]; 
                $FACTOR_GR_SANGUINEO = $llaves[0]["FACTOR_GR_SANGUINEO"]; 
                $GR_VERIFICACION = $llaves[0]["GR_VERIFICACION"]; 
                $GR_DNI_TECNOLOGO = $llaves[0]["GR_DNI_TECNOLOGO"]; 
                $GR_NOMBRES_TECNOLOGO = $llaves[0]["GR_NOMBRES_TECNOLOGO"]; 
                $GR_METODO_SEDIMENTACION = $llaves[0]["GR_METODO_SEDIMENTACION"]; 
                $GR_FECINI_DEPLECION = $llaves[0]["GR_FECINI_DEPLECION"]; 
                $GR_HORINI_DEPLECION = $llaves[0]["GR_HORINI_DEPLECION"]; 
                $GR_FECFIN_DEPLECION = $llaves[0]["GR_FECFIN_DEPLECION"]; 
                $GR_HORFIN_DEPLECION = $llaves[0]["GR_HORFIN_DEPLECION"]; 
                $GR_HS_DEPLECION = $llaves[0]["GR_HS_DEPLECION"]; 
                $GR_FECINI_PLASMA = $llaves[0]["GR_FECINI_PLASMA"]; 
                $GR_HORINI_PLASMA = $llaves[0]["GR_HORINI_PLASMA"];
                $DNI_TEC_GRSEDIMENTACION = $llaves[0]["DNI_TEC_GRSEDIMENTACION"]; 
                $NOMBRES_TEC_GRSEDIMENTACION = $llaves[0]["NOMBRES_TEC_GRSEDIMENTACION"];
                $PESO_SEDIMENTACION_PRL = $llaves[0]["PESO_SEDIMENTACION_PRL"];
                $PESO_SEDIMENTACION_GRD = $llaves[0]["PESO_SEDIMENTACION_GRD"]; 
                $PESO_SEDIMENTACION_PPL = $llaves[0]["PESO_SEDIMENTACION_PPL"];
                $VOLUMEN_ALMACENAR = $llaves[0]["VOLUMEN_ALMACENAR"];
                $TOMA_MUESTRA_HEMO = $llaves[0]["TOMA_MUESTRA_HEMO"];
                $TOMA_MUESTRA_CRIOVIALES = $llaves[0]["TOMA_MUESTRA_CRIOVIALES"];
                $CELULAS_NUCLEADAS_FIN = $llaves[0]["CELULAS_NUCLEADAS_FIN"];
                $FIN_PMN = $llaves[0]["FIN_PMN"];
                $FIN_CMN = $llaves[0]["FIN_CMN"];
                $FIN_CNT = $llaves[0]["FIN_CNT"];
                $FIN_CMNT = $llaves[0]["FIN_CMNT"];
                $RECUENTO_CD34 = $llaves[0]["RECUENTO_CD34"];
                $RECUENTO_CD34X10 = $llaves[0]["RECUENTO_CD34X10"];
                $RECUENTO_CELL_CD34 = $llaves[0]["RECUENTO_CELL_CD34"];
                $RECUENTO_CELL_CD34X10 = $llaves[0]["RECUENTO_CELL_CD34X10"];
                $VIABILIDAD_CD34 = $llaves[0]["VIABILIDAD_CD34"];
                $VIABILIDAD_CD45 = $llaves[0]["VIABILIDAD_CD45"];
                $RECUPERACION_CNT = $llaves[0]["RECUPERACION_CNT"];
                $RECUPERACION_CMN = $llaves[0]["RECUPERACION_CMN"];
                $CD90_TJ = $llaves[0]["CD90_TJ"];
                $RECUENTO_TOTAL_TJ = $llaves[0]["RECUENTO_TOTAL_TJ"];
                $TOTAL_VIVAS = $llaves[0]["TOTAL_VIVAS"];
                $FECHA_CRIOPRESERVACION = $llaves[0]["FECHA_CRIOPRESERVACION"];
                $HOR_CRIOPRESERVACION = $llaves[0]["HOR_CRIOPRESERVACION"];
                $ESTADO_CRIO = $llaves[0]["ESTADO_CRIO"];
                $FEC_CRIO_REG = $llaves[0]["FEC_CRIO_REG"];
                $HOR_CRIO_REG = $llaves[0]["HOR_CRIO_REG"];
                $FEC_CRIO_CIERRE = $llaves[0]["FEC_CRIO_CIERRE"];
                $HOR_CRIO_CIERRE = $llaves[0]["HOR_CRIO_CIERRE"];
                $DNI_TEC_CRIO = $llaves[0]["DNI_TEC_CRIO"];
                $NOMBRES_TEC_CRIO = $llaves[0]["NOMBRES_TEC_CRIO"]; 
                $SOL_CRIO_DMSO = $llaves[0]["SOL_CRIO_DMSO"];
                $SOL_CRIO_DEXTRAN40 = $llaves[0]["SOL_CRIO_DEXTRAN40"]; 
                $SOL_CRIO_OTRO = $llaves[0]["SOL_CRIO_OTRO"]; 
                $CONGELAMIENTO_PROG = $llaves[0]["CONGELAMIENTO_PROG"];
                $CONGELAMIENTO_PROG_MOTIVO = $llaves[0]["CONGELAMIENTO_PROG_MOTIVO"];
                $CONG_GRAFICA = $llaves[0]["CONG_GRAFICA"];
                $CONG_GRAFICA_MOTIVO = $llaves[0]["CONG_GRAFICA_MOTIVO"];
                $FEC_CONGELAMIENTO = $llaves[0]["FEC_CONGELAMIENTO"];
                $HOR_CONGELAMIENTO = $llaves[0]["HOR_CONGELAMIENTO"];
                $CRIO_OBSFIN = $llaves[0]["CRIO_OBSFIN"];
                $TANQUE = $llaves[0]["TANQUE"];
                $CUADRANTE = $llaves[0]["CUADRANTE"];
                $RACKS = $llaves[0]["RACKS"];
                $NIVEL = $llaves[0]["NIVEL"];
                $POSICION = $llaves[0]["POSICION"];
                $SANGRE = $llaves[0]["SANGRE"];
                $TEJIDOS = $llaves[0]["TEJIDOS"];

           

            
            
            
            $dia_actual = Carbon::now()->format('d');
            $mes_actual = $this->obtener_mes_actual_espanol();
            $año_actual = Carbon::now()->format('Y');


            $template->setValue('numero_contrato', mb_strtoupper($NUMERO_CONTRATO));

            $template->setValue('fec_contrato', mb_strtoupper($FEC_CONTRATO));

            
            $template->setValue('fec_naci', mb_strtoupper($FEC_NACI));
            
            $template->setValue('hora_naci', mb_strtoupper($HORA_NACI));

            $template->setValue('dia_naci', mb_strtoupper($DIA_NACI));

            $template->setValue('mes_naci', mb_strtoupper($MES_NACI));

            $template->setValue('anio_naci', mb_strtoupper($ANIO_NACI));


            $template->setValue('nombres_papa', mb_strtoupper($NOMBRES_PAPA));
            $template->setValue('correo_papa', mb_strtoupper($CORREO_PAPA));

            $template->setValue('nombres_mama', mb_strtoupper($NOMBRES_MAMA));
            $template->setValue('correo_mama', mb_strtoupper($CORREO_MAMA));



            $template->setValue('dia_actual', mb_strtoupper($dia_actual));

            $template->setValue('mes_actual', mb_strtoupper($mes_actual));

            $template->setValue('año_actual', mb_strtoupper($año_actual));
            
            $template->setValue('familia', mb_strtoupper($FAMILIA));
                  
            $template->setValue('sexo_bebe', mb_strtoupper($SEXO_BEBE));

            $template->setValue('numero_evalua', mb_strtoupper($NUMERO_EVALUA));
            
            $template->setValue('fec_colecta', mb_strtoupper($FEC_COLECTA));

            $template->setValue('hora_colecta', mb_strtoupper($HORA_COLECTA));

            $template->setValue('fec_inglab', mb_strtoupper($FEC_INGLAB));
            
            $template->setValue('hora_inglab', mb_strtoupper($HORA_INGLAB));

            $template->setValue('fec_evalua', mb_strtoupper($FEC_EVALUA));

            $template->setValue('hora_evalua', mb_strtoupper($HORA_EVALUA));

            $template->setValue('estado_evalua', mb_strtoupper($ESTADO_EVALUA));

            $template->setValue('fec_regevalua', mb_strtoupper($FEC_REGEVALUA));

            $template->setValue('hora_regevalua', mb_strtoupper($HORA_REGEVALUA));

            $template->setValue('dni_tecnologo_eva', mb_strtoupper($DNI_TECNOLOGO_EVA));

            $template->setValue('nombres_tecnologo_eva', mb_strtoupper($NOMBRES_TECNOLOGO_EVA));

            $template->setValue('fec_cierrevalua', mb_strtoupper($FEC_CIERREVALUA));

            $template->setValue('hora_cierrevalua', mb_strtoupper($HORA_CIERREVALUA));

            $template->setValue('cond_uscu_adecuada', $COND_USCU_ADECUADA);

            $template->setValue('cond_uscu_inadecuada', $COND_USCU_INADECUADA);

            $template->setValue('cond_uscu_ina_coagulos', $COND_USCU_INA_COAGULOS);

            $template->setValue('cond_uscu_ina_averiada', $COND_USCU_INA_AVERIADA);

            $template->setValue('cond_uscu_ina_malsellada', $COND_USCU_INA_MALSELLADA);

            $template->setValue('cond_uscu_ina_otros', $COND_USCU_INA_OTROS);

            $template->setValue('obs_cond_evalua', $OBS_COND_EVALUA);

            $template->setValue('codigo_bolsa', $CODIGO_BOLSA);

            $template->setValue('desc_bolsa', $DESC_BOLSA);
            
            $template->setValue('peso_bolsa', $PESO_BOLSA);

            $template->setValue('anti_bolsa', $ANTI_BOLSA);

            $template->setValue('peso_USCU', $PESO_USCU);

            $template->setValue('volumen_USCU', $VOLUMEN_USCU);

            $template->setValue('volumen_liq_USCU', $VOLUMEN_LIQ_USCU);

            $template->setValue('acep_uscu_adecuada', $ACEP_USCU_ADECUADA);

            $template->setValue('acep_uscu_inadecuada', $ACEP_USCU_INADECUADA);

            $template->setValue('acep_uscu_cond_inadecuada', $ACEP_USCU_COND_INADECUADA);

            $template->setValue('acep_uscu_volumenbajo', $ACEP_USCU_VOLUMENBAJO);

            $template->setValue('acep_uscu_otro', $ACEP_USCU_OTRO);

            $template->setValue('obs_acepta_evalua', $OBS_ACEPTA_EVALUA);

            $template->setValue('fec_proc', $FEC_PROCESAMIENTO);

            $template->setValue('hor_proc', $HOR_PROCESAMIENTO);

            $template->setValue('estado_proc', $ESTADO_PROC);

            $template->setValue('fec_regproc', $FEC_REGPROC);

            $template->setValue('hor_regproc', $HOR_REGPROC);

            $template->setValue('fec_cierreproc', $FEC_CIERREPROC);

            $template->setValue('hor_cierreproc', $HOR_CIERREPROC);

            $template->setValue('dni_tecnologo_proc', $DNI_TECNOLOGO_PROC);

            $template->setValue('nombres_tecnologo_proc', $NOMBRES_TECNOLOGO_PROC);

            $template->setValue('rot_set_pro', $ROT_SET_PRO);

            $template->setValue('rot_frascos_hemo', $ROT_FRASCOS_HEMO);

            $template->setValue('rot_crioviales', $ROT_CRIOVIALES);

            $template->setValue('hes_utilizado', $HES_UTILIZADO);

            $template->setValue('hes_volumen', $HES_VOLUMEN);

            $template->setValue('toma_muestra_inicial', $TOMA_MUESTRA_INICIAL);

            $template->setValue('celulas_nucleadas_ini', $CELULAS_NUCLEADAS_INI);

            $template->setValue('ini_pnm', $INI_PMN);

            $template->setValue('ini_cmn', $INI_CMN);

            $template->setValue('ini_cnt', $INI_CNT);

            $template->setValue('ini_cmnt', $INI_CMNT);

            $template->setValue('grupo_sanguineo', $GRUPO_SANGUINEO);

            $template->setValue('tipo_sanguineo', $TIPO_GR_SANGUINEO);

            $template->setValue('factor_sanguineo', $FACTOR_GR_SANGUINEO);

            $template->setValue('gr_verificacion', $GR_VERIFICACION);

            $template->setValue('gr_dni_tecnologico', $GR_DNI_TECNOLOGO);

            $template->setValue('gr_nombres_tecnologico', $GR_NOMBRES_TECNOLOGO);

            $template->setValue('gr_metodo_sedimentacion', $GR_METODO_SEDIMENTACION);

            $template->setValue('gr_fecini_dpl', $GR_FECINI_DEPLECION);

            $template->setValue('gr_horini_dpl', $GR_HORINI_DEPLECION);


            $template->setValue('gr_fecfin_dpl', $GR_FECFIN_DEPLECION);

            $template->setValue('gr_horfin_dpl', $GR_HORFIN_DEPLECION);

            $template->setValue('gr_hs_dpl', $GR_HS_DEPLECION);

            $template->setValue('gr_fecini_pl', $GR_FECINI_PLASMA);

            $template->setValue('gr_horini_pl', $GR_HORINI_PLASMA);

            $template->setValue('dni_tec_grsedimentacion', $DNI_TEC_GRSEDIMENTACION);

            $template->setValue('nombres_tec_grsedimentacion', $NOMBRES_TEC_GRSEDIMENTACION);

            $template->setValue('peso_prl', $PESO_SEDIMENTACION_PRL);

            $template->setValue('peso_grd', $PESO_SEDIMENTACION_GRD);

            $template->setValue('peso_ppl', $PESO_SEDIMENTACION_PPL);

            $template->setValue('volumen_alm', $VOLUMEN_ALMACENAR);

            $template->setValue('toma_muestra_hemo', $TOMA_MUESTRA_HEMO);

            $template->setValue('toma_muestra_crioviales', $TOMA_MUESTRA_CRIOVIALES);
            
            $template->setValue('celulas_nucleadas_fin', $CELULAS_NUCLEADAS_FIN);

            $template->setValue('fin_pmn', $FIN_PMN);

            $template->setValue('fin_cmn', $FIN_CMN);

            $template->setValue('fin_cnt', $FIN_CNT);

            $template->setValue('fin_cmnt', $FIN_CMNT);

            $template->setValue('cd34', $RECUENTO_CD34);

            $template->setValue('cd34x10', $RECUENTO_CD34X10);

            $template->setValue('cell_cd34', $RECUENTO_CELL_CD34);

            $template->setValue('cell_cd34x10', $RECUENTO_CELL_CD34X10);

            $template->setValue('via_cd34', $VIABILIDAD_CD34);

            $template->setValue('via_cd45', $VIABILIDAD_CD45);

            $template->setValue('recu_cnt', $RECUPERACION_CNT);

            $template->setValue('recu_cmn', $RECUPERACION_CMN);
            
            $template->setValue('cd90_tj', $CD90_TJ);

            $template->setValue('recuento_total_tj', $RECUENTO_TOTAL_TJ);

            $template->setValue('total_vivas_tj', $TOTAL_VIVAS);

            $template->setValue('fec_crio', $FECHA_CRIOPRESERVACION);

            $template->setValue('hor_crio', $HOR_CRIOPRESERVACION);

            $template->setValue('estado_crio', $ESTADO_CRIO);

            $template->setValue('fec_crio_reg', $FEC_CRIO_REG);

            $template->setValue('hor_crio_reg', $HOR_CRIO_REG);

            $template->setValue('fec_crio_cierre', $FEC_CRIO_CIERRE);

            $template->setValue('hor_crio_cierre', $HOR_CRIO_CIERRE);

            $template->setValue('dni_tec_crio', $DNI_TEC_CRIO);

            $template->setValue('nombres_tec_crio', $NOMBRES_TEC_CRIO);

            $template->setValue('x18', $SOL_CRIO_DMSO);

            $template->setValue('x19', $SOL_CRIO_DEXTRAN40);

            $template->setValue('sol_crio_otro', $SOL_CRIO_OTRO);

            $template->setValue('cong_prog', $CONGELAMIENTO_PROG);

            $template->setValue('cong_prog_mot', $CONGELAMIENTO_PROG_MOTIVO);

            $template->setValue('cong_graf', $CONG_GRAFICA);

            $template->setValue('cong_graf_mot', $CONG_GRAFICA_MOTIVO);

            $template->setValue('fec_cong', $FEC_CONGELAMIENTO);

            $template->setValue('hor_cong', $HOR_CONGELAMIENTO);

            $template->setValue('crio_obsfin', $CRIO_OBSFIN);

            $template->setValue('tanque', $TANQUE);

            $template->setValue('cuadrante', $CUADRANTE);

            $template->setValue('racks', $RACKS);

            $template->setValue('nivel', $NIVEL);

            $template->setValue('posicion', $POSICION);

            $template->setValue('sangre', $SANGRE);

            $template->setValue('tejidos', $TEJIDOS);
            
            //
            

            $tenpFile = tempnam(sys_get_temp_dir(),'PHPWord');

            $template->saveAs($tenpFile);

         


            $randomName = $this->generaRandomString(10);

            $subDirectory = '/resultadoFormatoWord/'.$randomName.'.docx';

            $file_to = public_path().$subDirectory;



            if(!copy($tenpFile, $file_to)){

                  return  $this->setRpta('error','No se pudo mover el fichero temporal desvinculación word ');
            }
                  

            if ( file_exists($tenpFile) ) {

               unlink($tenpFile);

            }

            //
            

       
            if(file_exists($file_to)){

              return $this->setRpta('ok','Se reemplazó existosamente en el documento WORD',$subDirectory);

            }else{


              return $this->setRpta('error','No se pudo reemplazar las variables en el documento WORD');
            }


    } catch (\Exception $e) {
      
      return $this->setRpta('error','OfficeWord: '.$e->getMessage());

    }


    

}


	public function deleteFileWordTemporal($file){



      $file = explode(".",$file);

      $file = $file[0];

      $file = $file.".docx";

      $wordTemporal = public_path().'/resultadoFormatoWord/'.$file;


		 if(file_exists($wordTemporal)){

		 	  unlink($wordTemporal);
            

        }

	}

  public function generacion_plantilla_constancia_viabilidad($plantilla,$contrato,$variable) {
    try {

      $file_path = $plantilla;
      $template = new \PhpOffice\PhpWord\TemplateProcessor($file_path);
      $get_data = Contrato::genera_contrato_impresion($contrato,$variable);

      $json = json_decode(json_encode($get_data),true);



      $NUMERO_CONTRATO = (isset($json[0]['CONTRATO_BASE']))?$json[0]['CONTRATO_BASE']:'';
      
      $FAMILIA = (isset($json[0]['FAMILIA']))?$json[0]['FAMILIA']:'';
      $NOMBRE_BEBE = (isset($json[0]['NOMBRE_BEBE']))?$json[0]['NOMBRE_BEBE']:'';
      $FECNACI_BEBE = (isset($json[0]['FECNACI_BEBE']))?$json[0]['FECNACI_BEBE']:'';
      //nombre_bebe
      //fecha_nacimiento_bebe


      $dia_actual = Carbon::now()->format('d');
      $mes_actual = $this->obtener_mes_actual_espanol();
      $año_actual = Carbon::now()->format('Y');

      $template->setValue('mes_actual', mb_strtoupper($mes_actual));
      $template->setValue('año_actual', mb_strtoupper($año_actual));
      $template->setValue('familia', mb_strtoupper($FAMILIA));
      $template->setValue('numero_contrato', mb_strtoupper($NUMERO_CONTRATO));
      
      $template->setValue('nombre_bebe', mb_strtoupper($NOMBRE_BEBE));
      $template->setValue('fecha_nacimiento_bebe', mb_strtoupper($FECNACI_BEBE));
      $tenpFile = tempnam(sys_get_temp_dir(),'PHPWord');


      $template->saveAs($tenpFile);

   


      $randomName = $this->generaRandomString(10);
      $subDirectory = '/resultadoFormatoWord/'.$randomName.'.docx';
      $file_to = public_path().$subDirectory;



      if(!copy($tenpFile, $file_to)){
            return  $this->setRpta('error','No se pudo mover el fichero temporal contratacion word ');
      }
            

      if ( file_exists($tenpFile) ) {

         unlink($tenpFile);

      }

      //


      if(file_exists($file_to)){

            return $this->setRpta('ok','Se reemplazó existosamente en el documento WORD',$subDirectory);

      }else{


            return $this->setRpta('error','No se pudo reemplazar las variables en el documento WORD');
      }
    }
    catch (\Exception $e) {
                  
      return $this->setRpta('error','OfficeWord: '.$e->getMessage());

    }
  }   
}


