<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class SeguimientoCaptador extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
 	protected static function list_seguimiento_captadores($request){

        

        $cia = Auth::user()->empresa;

        $usuario = Auth::user()->codigo;

        $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

   		$end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');





        $contrato = trim($request->vm_contrato) ;
        //$estado_pago= $request->vm_estado ;
                       
        //$estado_analisis =$request->analisis;
        $doctor = $request->doctor ;
        //$filtro_fecha = $request->filtro_fecha ;

        $tipo = $request->tipo;

        $cliente = $request->cliente;

        

        $exacta = $request->exacta;

       

        if(count((array)$request->servicios)>0){

          $servicios = implode(",",$request->servicios);
        }else{

          $servicios = null;

        }


        $captador = $request->captador;


        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_MEDCAPTADORES(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $tipo, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $contrato, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $doctor, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $cliente, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $start, PDO::PARAM_STR);
      
        $stmt->bindParam(':p7', $end, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $usuario, PDO::PARAM_STR);

        $stmt->bindParam(':p9', $exacta, PDO::PARAM_STR);
        $stmt->bindParam(':p10', $captador, PDO::PARAM_STR);
        $stmt->bindParam(':p11', $servicios, PDO::PARAM_STR);
     


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }





protected static function detalle_linea_tiempo_seguimiento_cap($request){

        

        $cia = Auth::user()->empresa;

     

      

        $contrato = trim($request->contrato);

        $stmt = static::$pdo->prepare("begin WEB_CON_PAGOMEDLINETIME(:p1,:p2,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
      
        


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }

protected static function sustentos_pendientes_medico($request){

        

        $cia = Auth::user()->empresa;

     

        $dni = $request->dni;

        $contrato = $request->contrato;

        $stmt = static::$pdo->prepare("begin WEB_SUSTENTOS_MED_PENDIENTES(:p1,:p2,:p3,:c); end;");
        $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $dni, PDO::PARAM_STR);
        


        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    }

    
    protected static function obtener_contratos_str_masiva($list){

            $row = '';


            foreach($list as $value){

                if($value["SELECCIONA"]){

                    $row.=$value["NUMERO_CONTRATO"].',';
                }
                


            }

            $row = rtrim($row,',');


            return $row;
    }
    
    protected static function confirmar_masiva_medicos($request){


      
        $contratos_list= self::obtener_contratos_str_masiva($request->data);

        

        $p1 = Auth::user()->empresa;
        $p2 =  trim($contratos_list);
        $p3 = Auth::user()->codigo;
    
   

    $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_CONFIRMARPAGOS(:p1,:p2,:p3,:rpta); end;");
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR,10000000);
    $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
    
   

    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT); 
    $stmt->execute();

    return $rpta;
     

    }
    protected static function confirmar_solicitud_pagos_masiva_medicos($request){

        if(empty( $request->data)){
            $rpta='2';
            return $rpta;
        }
        $contratos_list = implode(',', $request->data);
        //$contratos_list= self::obtener_contratos_str_masiva($request->data);

        

        $p1 = Auth::user()->empresa;
        $p2 =  trim($contratos_list);
        $p3 = Auth::user()->codigo;
    
   

    $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_SOLI_PAGOMED2(:p1,:p2,:p3,:rpta); end;");
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR,10000000);
    $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
    
   

    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT); 
    $stmt->execute();

    return $rpta;
     

    }

    //confirmar_solicitud_pagos_masiva_medicos


    protected static function confirmar_solicita_pago_medico($request){


      


        $p1 = Auth::user()->empresa;
        $p2 =  trim($request->contrato);
        $p3 = Auth::user()->codigo;
    
   

    $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_SOLICITARPAGOMED(:p1,:p2,:p3,:rpta); end;");
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
    
   

    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT); 
    $stmt->execute();

    return $rpta;
     

    }


	protected static function confirmar_asignar_mpago_doctor($request){


      


        $p1 = Auth::user()->empresa;
    $p2 = trim($request->identificacion);
    $p3 =  $request->recibo;
   
    


   


    
    $stmt = static::$pdo->prepare("begin WEB_VEN_PAGOMED_ASIGNAMEDPAGO(:p1,:p2,:p3,:rpta); end;");
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
    
   

    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT); 
    $stmt->execute();

    return $rpta;
     

    }


   
    protected static function registroAdjunto_modal_captador_tracking($request){


     


        $p1 = Auth::user()->empresa;
    $p2 = $request->contrato_global_f;
    $p3 = null;
    $p4 = Auth::user()->codigo;
    
   

    

    if ($request->file('contrato_file_f')) {

           $directorio      = 'adjuntos_captador_tracking/';

            $ext      = strtolower($request->file('contrato_file_f')->getClientOriginalExtension()); 

            $fileName = str_random() . '.' . $ext;

            $request->file('contrato_file_f')->move($directorio, $fileName);

            $p3   = $fileName;
        }


    
    $p3 = trim($request->comentario_adjunto);

     $p5 = $request->contrato_f_comp;

    $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_SUSTENTOPAGOMED(:p1,:p2,:p3,:p4,:p5,:rpta); end;");
    $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
    $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
    $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
    $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
    $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);

    $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT); 
    $stmt->execute();

    return $rpta;
     

    }

    



  

   	
   	
}
