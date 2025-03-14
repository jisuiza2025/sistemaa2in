<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Botones extends Model
{   
    
    public $table ='BOTONES_USUARIOS';

    public $timestamps = false;

    protected $fillable = ['NO_CIA', 'USUARIO'];

    protected $sequence = null;

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

   

    protected static function salvar_botonera_menu($request){




        $query =  Botones::where([['USUARIO',$request->id_codigo],['NO_CIA',$request->id_cia]])->get();

        //para captacion - servicios
        
        $AUTO = $request->sw_automatico_cap_vendedor;


        //$srv =($AUTO==1)?$request->registroServ:null;


         $srv = $request->registroServ;

        // $srv = null;

        // if(count((array) $sr)>0){

        //     $srv = implode(",",$sr );
        // }

        //ppvencer

         $ppv = $request->registroServxPerder;

        // $ppv = null;

        // if(count((array) $pp)>0){

        //     $ppv = implode(",",$pp );
        // }

       //reporte - pro
       //
       //
       
        $procia11 = $request->procia1;

        $procia11var = null;

        if(count((array) $procia11)>0){

            $procia11var = implode(",",$procia11 );
        }


        $proser1var =$request->proser1;

        // $proser11 = $request->proser1;

        // $proser1var = null;

        // if(count((array) $proser11)>0){

        //     $proser1var = implode(",",$proser11 );
        // }
        


        //reporte-cap
        

        $capser11var=$request->capser1;


        //  $capser11 = $request->capser1;

        // $capser11var = null;

        // if(count((array) $capser11)>0){

        //     $capser11var = implode(",",$capser11 );
        // }

         $capcia12 = $request->capcia1;

        $capcia12var = null;

        if(count((array) $capcia12)>0){

            $capcia12var = implode(",",$capcia12 );
        }


        //reporte-anu
       


         $anuia11 = $request->anuia1;

        $anuia11var = null;

        if(count((array) $anuia11)>0){

            $anuia11var = implode(",",$anuia11 );
        }


        $anuia1servar3=$request->anuia1ser;
        //  $anuia1servar = $request->anuia1ser;

        // $anuia1servar3 = null;

        // if(count((array) $anuia1servar)>0){

        //     $anuia1servar3 = implode(",",$anuia1servar );
        // }

        $values = array(

                "NO_CIA" =>$request->id_cia ,
                "USUARIO" => $request->id_codigo,
                "PROSPECTO_NUEVO" => $request->vm_pros_nuevo,
                "GENERA_CONTRATO" => $request->vm_genera_contrato,
                "AGREGA_SERVICIOS" => $request->vm_pros_servicios,
                "CONTRATO_ACUERDO" => $request->vm_contrato_impacuerdo ,
                "CONTRATO_ECUENTA" => $request->vm_contrato_ecuenta,
                "CONTRATO_FACTURACION" => $request->vm_contrato_facturacion,
                "CONTRATO_DESVINCULA" => $request->vm_contrato_desvinculacion,
                "ANU_HISTBIT_SW" => $request->vm_hisbit_switch ,
                "ANU_HISTBIT_GRABAR" => $request->vm_hisbit_grabar,
                "ANU_DOCVENTA_NC" => $request->vm_docventa_notacred ,
                "ANU_LLAMADACOB_INFLAB" => $request->vm_llamadacob_informlab,
                "ANU_LLAMADACOB_FACT"=> $request->vm_llamadacob_facturacion,
                "MANT_CLIENTES_NUEVO" => $request->vm_mant_cliente_nuevo,
                "MANT_VENDEDOR_NUEVO" => $request->vm_mant_vendedor_nuevo,
                "MANT_VENDEDOR_EDIT" => $request->vm_mant_vendedor_editar,
                "MANT_CAPTADOR_NUEVO" => $request->vm_mant_captador_nuevo,
                "MANT_CAPTADOR_EDIT" => $request->vm_mant_captador_editar,
                "MANT_HOSPITAL_NUEVO" => $request->vm_mant_clinica_nuevo,
                "MANT_HOSPITAL_EDIT" => $request->vm_mant_clinica_editar,
                "MANT_HOSPITAL_ELIMINA" => $request->vm_mant_clinica_desactivar,
                "MANT_MEDIOCAPT_NUEVO" => $request->vm_mant_mediocap_nuevo,
                "MANT_CENFACT_NUEVO" => $request->vm_mant_cent_fact_nuevo,
                "MANT_CENFACT_ASIGUSU"=> $request->vm_mant_cent_fact_asigusu,
                "MANT_CENFACT_ASIGDOC" => $request->vm_mant_cent_fact_asigdoc,
                "MANT_LISTPRECIO_NUEVO"=> $request->vm_mant_list_precio_nuevo ,
                "MANT_SERVICIOS_NUEVO" => $request->vm_mant_servicios_nuevo,
                "MANT_SERVICIOS_EDITAR"=> $request->vm_mant_servicios_editar, 
                "MANT_DOCTORES_NUEVO" => $request->vm_mant_doctor_nuevo, 
                "MANT_DOCTORES_EDITAR" => $request->vm_mant_doctor_editar, 
                "MANT_DOCTORES_ESPE" => $request->vm_mant_doctor_especialidad, 
                "MANT_DOCTORES_PRECIOS" => $request->vm_mant_doctor_precios, 
                "MANT_DOCTORES_ORUC" => $request->vm_mant_doctor_oruc, 
                "MANT_DOCTORES_SECRE" => $request->vm_mant_doctor_secretaria, 
                "MANT_DOCTORES_MPAGO" => $request->vm_mant_doctor_mpago, 
                "MANT_CONFIG_RESP_NUEVO" => $request->vm_mant_configrespo_nuevo, 
                "MANT_CONFIG_RESP_EDIT" => $request->vm_mant_configrespo_editar, 
                "MANT_CONFIG_RESP_ELIM" => $request->vm_mant_configrespo_eliminar, 
                "REP_ANALSIS_PCA" => $request->vm_report_1, 
                "REP_ANALSIS_DPCA" => $request->vm_report_2, 
                "REP_ANALSIS_RFAP" => $request->vm_report_3, 
                "REP_ANALSIS_DFAP" => $request->vm_report_4, 
                "REP_ANALSIS_ICPV" => $request->vm_report_5, 
                "REP_ANALSIS_FACANU" => $request->vm_report_6,
                "VER_LIST_PROSPECTOS" => $request->vm_ver_todos_prospectos_list,
                "CONT_ENV_CORREO" => $request->vm_contrato_correo_envia,
                "CONT_ENV_ECTA" => $request->vm_contrato_ecuenta_correo_envia,
                "CONT_ANULAR" => $request->vm_contrato_anular,

                "CONT_CONFIRMA_DESV" => $request->vm_contrato_desvinculacion_confirma,
                "CONT_IMPRIME_ACU" => $request->vm_contrato_imprimir,
                "CONT_IMPRIME_FIC" => $request->vm_contrato_imprimir_ficha_datos,
                /**BTN-FIRMA-DIGITA**/
                "CON_FIRMA_DIG" => $request->vm_contrato_firma_digital,


                "SEG_SER_SER" => $request->vm_contrato_ser_sol_servicio,
                "SEG_SER_VER" => $request->vm_contrato_ser_sol_ver,
                "SEG_SER_BIT" => $request->vm_contrato_ser_sol_bitacora,
                "SEG_SER_LBIT" => $request->vm_contrato_ser_sol_list_bitacora,
                "SEG_SER_CONS" => $request->vm_contrato_ser_sol_consentimiento,


                "CAP_EDITAR" => $request->vm_contrato_cap_editar,
                "CAP_V_SUS_PEND" => $request->vm_contrato_cap_v_sus_pend,
                "CAP_ASIG_PAGO" => $request->vm_contrato_cap_asig_pago,
                "CAP_ADJUNT_PAGO" => $request->vm_contrato_cap_adjunt_pago,
                "CAP_RETIRA" => $request->vm_contrato_cap_retira,
                "CAP_INCLUYE" => $request->vm_contrato_cap_incluye,
                "CAP_IMPRIME" => $request->vm_contrato_cap_imprime,
                "CAP_ENVIA_CORREO" => $request->vm_contrato_cap_envia_correo,
                "PAGO_MEDICO_GENERAR" => $request->vm_pago_medico_generar,
                "SEG_CONTRATO_EDITAR" => $request->vm_seg_contrato_edit,
                "VM_KITS" => $request->vm_kits,



                "CAP_TRACK_VER" => $request->vm_capt_tra_ver_todos,
                "CRM_BT_VER" => $request->vm_crm_ver_todos,


                "CON_SEG_CAP_PAGO" => $request->vm_contrato_cap_sol_pago,
                "CON_SEG_CAP_MAS" => $request->vm_contrato_cap_pago_mas,


                 "CAPT_TRA_SERVICIOS" => $request->vm_capt_tra_servicios,
                 "CAPT_AV_AUTO_SERVICIOS" => $request->sw_automatico_cap_vendedor,
                 "CAPT_AV_SERVICIOS" => $srv,
                 "CRM_PPV_SERVICIOS" => $ppv,

                

                


                  
                   "REP1_PRO_CIA" => $procia11var,
                    "REP1_PRO_SER" => $proser1var,


                    "REP1_CAP_CIA" => $capcia12var,
                     "REP1_CAP_SER" => $capser11var,
                      

                       "REP1_ANU_CIA" => $anuia11var,
                        "REP1_ANU_SER" => $anuia1servar3,
                        "FAC_BITACORA_CONTRATO" => $request->fac_bitacora_contrato,
                        "ANU_LLAMADACOB_CONSTANCIA"=>$request->vm_llamadacob_imp_constancia,
                        "FAC_ASIGNA_CONTRATO_DOC"=>$request->vm_fac_asigna_contrato_doc,
                        "FAC_ANULAR_DOC"=>$request->vm_fac_anular_doc,

                        "CRM_TAREA_DIRECTO"=>$request->vm_crm_tarea_directo,


                         "CON_COB_VER"=>$request->vm_con_cob_ver,
                          "CON_COB_CAMBIAR_COB"=>$request->vm_con_cob_cambiar_cob


                // "VM_CAPT_INDI" => $request->vm_capt_indi,
                // "VM_CAPT_MASIVA" => $request->vm_capt_masiva,
                // "VM_CAPT_VALDUPLI" => $request->vm_capt_valdupli,
                // "VM_CAPT_TRACKING" => $request->vm_capt_tracking,
                // "VM_CAPT_ASIGNA" => $request->vm_capt_asigna,


                // "VM_CRM_CONTROL" => $request->vm_crm_control,
                // "VM_CRM_CONTRATO_DIRECTO" => $request->vm_crm_contrato_directo,
                // "VM_CRM_BANDEJA" => $request->vm_crm_bandeja,
                // "VM_CRM_TIPO_ATENCION" => $request->vm_crm_tipo_atencion,



            );

        if(count($query) == 0){

            $rpta = DB::table('BOTONES_USUARIOS')->where([['USUARIO',$request->id_codigo],['NO_CIA',$request->id_cia]])->insert($values);


        }else{


           $rpta = DB::table('BOTONES_USUARIOS')->where([['USUARIO',$request->id_codigo],['NO_CIA',$request->id_cia]])->update($values);


        }
       

        return $rpta;



    }







    






   
}
