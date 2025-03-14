<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Prospecto extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
	
	
   	
  
   	protected static function list_prospecto($request){

        
         

   		$cia    = Auth::user()->empresa;

   		$cliente = $request->cliente;

   		$start = ($request->date[0] =='null' || $request->date[0] ==null )?'':Carbon::parse($request->date[0])->format('d/m/Y');

   		$end  = ($request->date[1] =='null' || $request->date[1] ==null)?'':Carbon::parse($request->date[1])->format('d/m/Y');

   		$prospecto = $request->prospecto ;
            
            //$prospecto = 'HISPROS';
   		
         $usuario = Auth::user()->codigo;

        
         $proximo_contacto = $request->contacto;
         

         
          $correo = $request->correo;
          $fijo =  $request->fijo;
          $celular = $request->celular;
          $vendedor = $request->vendedor;
          $captador = $request->captador;


          $valorfechafiltro = $request->valorfechafiltro;

          //1 FILTRA POR FECHA DE REGISTRO ,2 FILTRA POR FECHA DE PARTO

          //FEREGISTRO


         //$start_registro = ($request->fe_registro[0] =='null' || $request->fe_registro[0] ==null )?'':Carbon::parse($request->fe_registro[0])->format('d/m/Y');

         //$end_registro  = ($request->fe_registro[1] =='null' || $request->fe_registro[1] ==null)?'':Carbon::parse($request->fe_registro[1])->format('d/m/Y');




          //FEPARTO
          

          //$start_parto = ($request->fe_parto[0] =='null' || $request->fe_parto[0] ==null )?'':Carbon::parse($request->fe_parto[0])->format('d/m/Y');

         //$end_parto  = ($request->fe_parto[1] =='null' || $request->fe_parto[1] ==null )?'':Carbon::parse($request->fe_parto[1])->format('d/m/Y');


         
         //$flag_registro = $request->flag_registro;

         
         //$flag_parto = $request->flag_parto;


         $busca_fecha = $request->fecha;

          if($prospecto == 'HISPROS'){

            $proximo_contacto = 5;

         }


         $numero_prospecto = $request->numero;

   		$stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_LISTADO(:p1, :p2, :p3,:p4,:p5,:c,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15); end;");

		  $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		  $stmt->bindParam(':p2', $cliente, PDO::PARAM_STR);
		  $stmt->bindParam(':p3', $start, PDO::PARAM_STR);
		  $stmt->bindParam(':p4', $end, PDO::PARAM_STR);
		  $stmt->bindParam(':p5', $prospecto, PDO::PARAM_STR);
		  $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		 
        $stmt->bindParam(':p6', $usuario, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $proximo_contacto, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $numero_prospecto, PDO::PARAM_STR);

        $stmt->bindParam(':p9', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':p10', $fijo, PDO::PARAM_STR);
        $stmt->bindParam(':p11', $celular, PDO::PARAM_STR);
        $stmt->bindParam(':p12', $vendedor, PDO::PARAM_STR);
        $stmt->bindParam(':p13', $captador, PDO::PARAM_STR);

        $stmt->bindParam(':p14', $busca_fecha, PDO::PARAM_STR);

        $stmt->bindParam(':p15', $valorfechafiltro, PDO::PARAM_STR);


		  $stmt->execute();

		    oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
    
    }

    protected static function save_prospecto($request){

      


      $cia    = Auth::user()->empresa;
      $usuario    = Auth::user()->codigo;
  
      $rprospecto_prospecto   =$request->rprospecto_prospecto ;

      

      

      $rprospecto_registro    =($request->rprospecto_registro==null)?null:Carbon::parse($request->rprospecto_registro)->format('d/m/Y');



     

      $rprospecto_fechaAprox  =($request->rprospecto_fechaAprox==null)?null:Carbon::parse($request->rprospecto_fechaAprox)->format('d/m/Y');

      

      $rprospecto_tipoParto   =$request->rprospecto_tipoParto ;
      $rprospecto_estado      =$request->rprospecto_estado ;
      $rprospecto_contratado  =$request->rprospecto_contratado ;
      $rprospecto_captacion   =$request->rprospecto_captacion;
      $rprospecto_tipo        =$request->rprospecto_tipo ;
      $rprospecto_nroFicha    =$request->rprospecto_nroFicha ;
      $rprospecto_atendido    =$request->rprospecto_atendido ;
      $rprospecto_derivado    =$request->rprospecto_derivado ;
      $rprospecto_medico      =$request->rprospecto_medico ;
      $rprospecto_clinica     =$request->rprospecto_clinica;
      $rprospecto_contrato    =($request->rprospecto_contrato==null)?'NO':'SI' ;

     

      $rprospecto_identmadre  =$request->rprospecto_identmadre ;
      $rprospecto_docmadre    =$request->rprospecto_docmadre;
      $rprospecto_sexomadre   =$request->rprospecto_sexomadre;
      $rprospecto_patmadre    =mb_strtoupper($request->rprospecto_patmadre);
      $rprospecto_matmadre    =mb_strtoupper($request->rprospecto_matmadre) ;
      $rprospecto_nommadre    =mb_strtoupper($request->rprospecto_nommadre) ;
      $rprospecto_dirmadre    =mb_strtoupper($request->rprospecto_dirmadre) ;
      $rprospecto_msoltera    =($request->rprospecto_msoltera==null)?'N':'S';
      $rprospecto_pais_madre  =$request->rprospecto_pais_madre;
      $rprospecto_ubgmadre    =$request->rprospecto_ubgmadre ;
      $rprospecto_civmadre    =$request->rprospecto_civmadre ;
      $rprospecto_nacmadre    =($request->rprospecto_nacmadre==null )?null:Carbon::parse($request->rprospecto_nacmadre)->format('d/m/Y'); 
      $rprospecto_fijo1madre  =$request->rprospecto_fijo1madre ;
      $rprospecto_movil1madre =$request->rprospecto_movil1madre ;
      $rprospecto_email1madre =$request->rprospecto_email1madre ;
      $rprospecto_fijo2madre  =$request->rprospecto_fijo2madre ;
      $rprospecto_movil2madre =$request->rprospecto_movil2madre ;
      $rprospecto_email2madre =$request->rprospecto_email2madre ;
      $rprospecto_fijo3madre  =$request->rprospecto_fijo3madre ;
      $rprospecto_movil3madre =$request->rprospecto_movil3madre ;
      $rprospecto_email3madre =$request->rprospecto_email3madre ;
      
      $rprospecto_identpadre  =$request->rprospecto_identpadre ;
      $rprospecto_docpadre    =$request->rprospecto_docpadre ;
      $rprospecto_sexopadre   =$request->rprospecto_sexopadre ;
      $rprospecto_patpadre    =mb_strtoupper($request->rprospecto_patpadre)  ;
      $rprospecto_matpadre    =mb_strtoupper($request->rprospecto_matpadre)  ;
      $rprospecto_nompadre    =mb_strtoupper($request->rprospecto_nompadre)  ;
      $rprospecto_dirpadre    =mb_strtoupper($request->rprospecto_dirpadre) ;
      $rprospecto_pais_padre  =$request->rprospecto_pais_padre ;
      $rprospecto_ubgpadre    =$request->rprospecto_ubgpadre ;
      $rprospecto_civpadre    =$request->rprospecto_civpadre  ;
      $rprospecto_nacpadre    =($request->rprospecto_nacpadre==null)?null: Carbon::parse($request->rprospecto_nacpadre)->format('d/m/Y'); 
      $rprospecto_fijo1padre  =$request->rprospecto_fijo1padre ;
      $rprospecto_movil1padre =$request->rprospecto_movil1padre ;
      $rprospecto_email1padre =$request->rprospecto_email1padre ;
      $rprospecto_fijo2padre  =$request->rprospecto_fijo2padre ;
      $rprospecto_movil2padre =$request->rprospecto_movil2padre ;
      $rprospecto_email2padre =$request->rprospecto_email2padre ;
      $rprospecto_fijo3padre  =$request->rprospecto_fijo3padre ;
      $rprospecto_movil3padre =$request->rprospecto_movil3padre ;
      $rprospecto_email3padre =$request->rprospecto_email3padre ;
      

      $full_name_madre=mb_strtoupper($rprospecto_nommadre.' '.$rprospecto_patmadre.' '.$rprospecto_matmadre);

      $full_name_padre=mb_strtoupper($rprospecto_nompadre.' '.$rprospecto_patpadre.' '.$rprospecto_matpadre);

      $observaciones = '';

      $edad_madre='';
      $edad_padre='';

      
      //desde bitacora
      
      $desde_bitacora = $request->desde_bitacora;


      if($desde_bitacora==1){

         $contraroref=null;

      }else{

         $contraroref = self::obten_numero_contrato_ref($rprospecto_prospecto);

      }

      


      //DATOS DEL PROPIETARIO
      
      $rprospecto_identpropietario  =$request->rprospecto_identpropietario ;
      $rprospecto_docpropietario    =$request->rprospecto_docpropietario ;
      $rprospecto_sexopropietario   =$request->rprospecto_sexopropietario ;
      $rprospecto_patpropietario    =mb_strtoupper($request->rprospecto_patpropietario)  ;
      $rprospecto_matpropietario    =mb_strtoupper($request->rprospecto_matpropietario)  ;
      $rprospecto_nompropietario    =mb_strtoupper($request->rprospecto_nompropietario)  ;
      $rprospecto_dirpropietario    =mb_strtoupper($request->rprospecto_dirpropietario) ;
      $rprospecto_pais_propietario  =$request->rprospecto_pais_propietario ;
      $rprospecto_ubgpropietario    =$request->rprospecto_ubgpropietario ;
      $rprospecto_civpropietario   =$request->rprospecto_civpropietario  ;
      $rprospecto_nacpropietario    =($request->rprospecto_nacpropietario==null)?null: Carbon::parse($request->rprospecto_nacpropietario)->format('d/m/Y'); 
      $rprospecto_fijo1propietario =$request->rprospecto_fijo1propietario ;
      $rprospecto_movil1propietario =$request->rprospecto_movil1propietario ;
      $rprospecto_email1propietario =$request->rprospecto_email1propietario ;
      $rprospecto_fijo2propietario  =$request->rprospecto_fijo2propietario ;
      $rprospecto_movil2propietario =$request->rprospecto_movil2propietario ;
      $rprospecto_email2propietario =$request->rprospecto_email2propietario ;
      $rprospecto_fijo3propietario  =$request->rprospecto_fijo3propietario ;
      $rprospecto_movil3propietario =$request->rprospecto_movil3propietario ;
      $rprospecto_email3propietario=$request->rprospecto_email3propietario ;

      $full_name_propietario=mb_strtoupper($rprospecto_nompropietario.' '.$rprospecto_patpropietario.' '.$rprospecto_matpropietario);


      $xvalida1= ($request->rprospecto_vbmadre==null)?'0':'1';
      $xvalida2= ($request->rprospecto_vbpadre==null)?'0':'1';
      $xvalida3= ($request->rprospecto_vbtpropietario==null)?'0':'1';


      $multiple_servicios = $request->multipleservicios;

      $stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_INPUPD(:cia,:rprospecto_prospecto,:rprospecto_registro,:rprospecto_fechaAprox,:rprospecto_atendido,:rprospecto_derivado,:rprospecto_tipoParto,:rprospecto_estado,:rprospecto_contratado,:rprospecto_medico,:rprospecto_clinica,:rprospecto_captacion,:rprospecto_tipo,:rprospecto_nroFicha,:rprospecto_contrato,:rprospecto_identmadre,:rprospecto_docmadre,:rprospecto_sexomadre,:rprospecto_patmadre,:rprospecto_matmadre,:rprospecto_nommadre,:rprospecto_dirmadre,:rprospecto_msoltera,:rprospecto_pais_madre,:rprospecto_ubgmadre,:rprospecto_civmadre,:rprospecto_nacmadre,:rprospecto_fijo1madre,:rprospecto_fijo2madre,:rprospecto_fijo3madre,:rprospecto_movil1madre,:rprospecto_movil2madre,:rprospecto_movil3madre,:rprospecto_email1madre,:rprospecto_email2madre,:rprospecto_email3madre,:rprospecto_identpadre,:rprospecto_docpadre,:rprospecto_sexopadre,:rprospecto_patpadre,:rprospecto_matpadre,:rprospecto_nompadre,:rprospecto_dirpadre,:rprospecto_pais_padre,:rprospecto_ubgpadre,:rprospecto_civpadre,:rprospecto_nacpadre,:rprospecto_fijo1padre,:rprospecto_fijo2padre,:rprospecto_fijo3padre,:rprospecto_movil1padre,:rprospecto_movil2padre,:rprospecto_movil3padre,:rprospecto_email1padre,:rprospecto_email2padre,:rprospecto_email3padre,:rprospecto_identpropietario,:rprospecto_docpropietario,:rprospecto_sexopropietario,:rprospecto_patpropietario,:rprospecto_matpropietario,:rprospecto_nompropietario,:rprospecto_dirpropietario,:rprospecto_pais_propietario,:rprospecto_ubgpropietario,:rprospecto_civpropietario,:rprospecto_nacpropietario,:rprospecto_fijo1propietario,:rprospecto_fijo2propietario,:rprospecto_fijo3propietario,:rprospecto_movil1propietario,:rprospecto_movil2propietario,:rprospecto_movil3propietario,:rprospecto_email1propietario,:rprospecto_email2propietario,:rprospecto_email3propietario,:xvalida1,:xvalida2,:xvalida3,:usuario,:contraroref,:multipleservicios,:rpta); end;");


      

$stmt->bindParam(':cia', $cia, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_prospecto', $rprospecto_prospecto, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_registro', $rprospecto_registro, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fechaAprox', $rprospecto_fechaAprox, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_atendido', $rprospecto_atendido, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_derivado', $rprospecto_derivado, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_tipoParto', $rprospecto_tipoParto, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_estado', $rprospecto_estado, PDO::PARAM_STR);

$stmt->bindParam(':rprospecto_contratado', $rprospecto_contratado, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_medico', $rprospecto_medico, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_clinica', $rprospecto_clinica, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_captacion', $rprospecto_captacion, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_tipo', $rprospecto_tipo, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nroFicha', $rprospecto_nroFicha, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_contrato', $rprospecto_contrato, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_identmadre', $rprospecto_identmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_docmadre', $rprospecto_docmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_sexomadre', $rprospecto_sexomadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_patmadre', $rprospecto_patmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_matmadre', $rprospecto_matmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nommadre', $rprospecto_nommadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_dirmadre', $rprospecto_dirmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_msoltera', $rprospecto_msoltera, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_pais_madre', $rprospecto_pais_madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_ubgmadre', $rprospecto_ubgmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_civmadre', $rprospecto_civmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nacmadre', $rprospecto_nacmadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fijo1madre', $rprospecto_fijo1madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fijo2madre', $rprospecto_fijo2madre ,PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fijo3madre', $rprospecto_fijo3madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_movil1madre', $rprospecto_movil1madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_movil2madre', $rprospecto_movil2madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_movil3madre', $rprospecto_movil3madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_email1madre', $rprospecto_email1madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_email2madre', $rprospecto_email2madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_email3madre', $rprospecto_email3madre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_identpadre', $rprospecto_identpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_docpadre', $rprospecto_docpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_sexopadre', $rprospecto_sexopadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_patpadre', $rprospecto_patpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_matpadre', $rprospecto_matpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nompadre', $rprospecto_nompadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_dirpadre', $rprospecto_dirpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_pais_padre', $rprospecto_pais_padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_ubgpadre', $rprospecto_ubgpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_civpadre', $rprospecto_civpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nacpadre', $rprospecto_nacpadre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fijo1padre', $rprospecto_fijo1padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fijo2padre', $rprospecto_fijo2padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fijo3padre', $rprospecto_fijo3padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_movil1padre', $rprospecto_movil1padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_movil2padre', $rprospecto_movil2padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_movil3padre', $rprospecto_movil3padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_email1padre', $rprospecto_email1padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_email2padre', $rprospecto_email2padre, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_email3padre', $rprospecto_email3padre, PDO::PARAM_STR);


$stmt->bindParam(':rprospecto_identpropietario', $rprospecto_identpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_docpropietario', $rprospecto_docpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_sexopropietario', $rprospecto_sexopropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_patpropietario', $rprospecto_patpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_matpropietario', $rprospecto_matpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nompropietario', $rprospecto_nompropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_dirpropietario', $rprospecto_dirpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_pais_propietario', $rprospecto_pais_propietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_ubgpropietario', $rprospecto_ubgpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_civpropietario', $rprospecto_civpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_nacpropietario', $rprospecto_nacpropietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fijo1propietario', $rprospecto_fijo1propietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fijo2propietario', $rprospecto_fijo2propietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_fijo3propietario', $rprospecto_fijo3propietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_movil1propietario', $rprospecto_movil1propietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_movil2propietario', $rprospecto_movil2propietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_movil3propietario', $rprospecto_movil3propietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_email1propietario', $rprospecto_email1propietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_email2propietario', $rprospecto_email2propietario, PDO::PARAM_STR);
$stmt->bindParam(':rprospecto_email3propietario', $rprospecto_email3propietario, PDO::PARAM_STR);

$stmt->bindParam(':xvalida1', $xvalida1, PDO::PARAM_STR);
$stmt->bindParam(':xvalida2', $xvalida2, PDO::PARAM_STR);
$stmt->bindParam(':xvalida3', $xvalida3, PDO::PARAM_STR);


$stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
$stmt->bindParam(':contraroref', $contraroref, PDO::PARAM_STR);

$stmt->bindParam(':multipleservicios', $multiple_servicios, PDO::PARAM_STR);
$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

     $stmt->execute();

    
        return $rpta;
    
    }

    protected static function obten_numero_contrato_ref($rprospecto_prospecto){



      if(empty($rprospecto_prospecto)){

            return null;

      }else{

            $cia    = Auth::user()->empresa;

            $query = DB::select("SELECT NUMERO_CONTRATORF FROM VEN_PROSPECTOS WHERE NO_CIA=? AND NUMERO_PROSPECTO=?",array($cia,$rprospecto_prospecto));

            $json = json_decode(json_encode($query),true);
            
            $contratorf = (isset($json[0]['numero_contratorf']))?$json[0]['numero_contratorf']:null;

            return $contratorf ;


      }

    }




   

   protected static function elimina_detalle_servicio_prospecto($request){

      $items = $request->item;



      $cia        = Auth::user()->empresa;

      $numero_prospecto = $request->prospecto;

      $servicio        = $items["ser"];
      $servicio_anual  = $items["ser_an"];
      $usuario         = Auth::user()->codigo;



      $stmt = static::$pdo->prepare("begin WEB_PROSPECTO_DET_ANULA(:cia,:numero_prospecto,:servicio,:servicio_anual,:usuario,:c); end;");


      $stmt->bindParam(':cia', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':numero_prospecto', $numero_prospecto, PDO::PARAM_STR);
      $stmt->bindParam(':servicio', $servicio, PDO::PARAM_STR);
      $stmt->bindParam(':servicio_anual', $servicio_anual, PDO::PARAM_STR);
      $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
      
   
      $stmt->bindParam(':c', $rpta, PDO::PARAM_STR,1000);
      
      $stmt->execute();

      
       return 1;
        //return $rpta;

    
    }



    protected static function save_prospecto_detalle($list){

      //dd($list);
      //die();

      $cia            = $list["cia"] ;
      $nro_prospecto  = $list["nro_prospecto"] ;
      $moneda         = $list["moneda"] ;
      $servicio_contrato = $list["servicio_contrato"] ;
      $lista_contrato = $list["lista_contrato"] ;
      $monto_servicio = $list["monto_servicio"] ;
      $id_cia_seguro  = $list["id_cia_seguro"] ;
      $porcentaje     = $list["porcentaje"] ;
      $moneda_an      = $list["moneda_an"] ;
      $servicio_an    = $list["servicio_an"] ;
      $monto_an       = $list["monto_an"] ;
      $lista_an       = $list["lista_an"] ;
      $usuario        = Auth::user()->codigo;



      $stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_SERV_INPUPD(:cia,:nro_prospecto,:moneda,:servicio_contrato,:lista_contrato,:monto_servicio,:id_cia_seguro,:porcentaje,:moneda_an,:servicio_an,:monto_an,:lista_an,:usuario,:c); end;");


      $stmt->bindParam(':cia', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':nro_prospecto', $nro_prospecto, PDO::PARAM_STR);
      $stmt->bindParam(':moneda', $moneda, PDO::PARAM_STR);
      $stmt->bindParam(':servicio_contrato', $servicio_contrato, PDO::PARAM_STR);
      $stmt->bindParam(':lista_contrato', $lista_contrato, PDO::PARAM_STR);
      $stmt->bindParam(':monto_servicio', $monto_servicio, PDO::PARAM_STR);
      $stmt->bindParam(':id_cia_seguro', $id_cia_seguro, PDO::PARAM_STR);
      $stmt->bindParam(':porcentaje', $porcentaje, PDO::PARAM_STR);
      $stmt->bindParam(':moneda_an', $moneda_an, PDO::PARAM_STR);
      $stmt->bindParam(':servicio_an', $servicio_an, PDO::PARAM_STR);
      $stmt->bindParam(':monto_an', $monto_an, PDO::PARAM_STR);
      $stmt->bindParam(':lista_an', $lista_an, PDO::PARAM_STR);
      $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
     
     
      $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
      
      $stmt->execute();

      
     
        return $rpta;

    
    }


    protected static function genera_contrato_prospecto2($list){

      
     

      $cia            = $list["cia"] ;
      $nro_prospecto  = $list["nro_prospecto"] ;
      $servicio       = $list["servicio_contrato"] ;
      $servicio_anual = $list["servicio_an"] ;
      $usuario = Auth::user()->codigo; 
      
      
      //$moneda = $list["moneda"] ;
    
      //$moneda_anualidad = $list["moneda_an"] ;



      $stmt = static::$pdo->prepare("begin WEB_GENERAR_CONTRATO(:cia,:nro_prospecto,:servicio,:servicio_anual,:usuario,:c,:msj); end;");


      $stmt->bindParam(':cia', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':nro_prospecto', $nro_prospecto, PDO::PARAM_STR);
      $stmt->bindParam(':servicio', $servicio, PDO::PARAM_STR);
      $stmt->bindParam(':servicio_anual', $servicio_anual, PDO::PARAM_STR);
      $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);

      $stmt->bindParam(':c', $rpta, PDO::PARAM_STR,1000);
      $stmt->bindParam(':msj', $msj, PDO::PARAM_STR,1000);
      
      $stmt->execute();


      if(!empty($rpta)){

         return array(true,$msj);
      }
     
        return array(false,$msj);

    
    }



    protected static function genera_contrato_prospecto($list){

      

      $cia            = $list["cia"] ;
      $nro_contrato   = $list["nro_contrato"] ;
      $fecha_contrato = $list["fecha_contrato"] ;
      $estado         = $list["estado"] ;
      $nro_prospecto  = $list["nro_prospecto"] ;
      $contrato_lp    = $list["contrato_lp"] ;
      $emergencia     = $list["emergencia"] ;
      $cod_doc1       = $list["cod_doc1"] ;
      $num_doc1       = $list["num_doc1"] ;
      $direccion1     = $list["direccion1"] ;
      $ubigeo1        = $list["ubigeo1"] ;
      $edad1          = $list["edad1"] ;
      $cod_doc2       = $list["cod_doc2"] ;
      $num_doc2       = $list["num_doc2"] ;
      $direccion2     = $list["direccion2"] ;
      $ubigeo2        = $list["ubigeo2"] ;
      $edad2          = $list["edad2"] ;
      $medico         = $list["medico"] ;
      $clinica        = $list["clinica"] ;
      $moneda         = $list["moneda"] ;
      $lista_contrato = $list["lista_contrato"] ;
      $monto_servicio = $list["monto_servicio"] ;
      $moneda_an      = $list["moneda_an"] ;
      $monto_an       = $list["monto_an"] ;
      $usuario        = $list["usuario"] ;
      $vendedor       = $list["vendedor"] ;
      $pais1          = $list["pais1"] ;
      $pais2          = $list["pais2"] ;
      $mama_soltera   = $list["mama_soltera"] ;
      $servicio_contrato = $list["servicio_contrato"] ;
      $lista_an          = $list["lista_an"] ;
      $servicio_an       = $list["servicio_an"] ;
      $seguro            = $list["seguro"] ;
      $plan              = $list["plan"] ;
      $cobertura         = $list["cobertura"] ;
      $car_seguro        = $list["car_seguro"] ;
      $car_cliente       = $list["car_cliente"] ;
      $sin_prueba_serologica = $list["sin_prueba_serologica"] ;
      $tipo_cambio           = $list["tipo_cambio"] ;
      $cobrador              = $list["cobrador"] ;
      $id_cia_seguro         = $list["id_cia_seguro"] ;
      $porcentaje            = $list["porcentaje"] ;
      
      //$sangre  = $list["sangre"] ;
     // $tejidos = $list["tejidos"] ;
    
      
   
      $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_GENERAR(:cia,:nro_contrato,:estado,:nro_prospecto,:contrato_lp,:emergencia,:cod_doc1,:num_doc1,:direccion1,:ubigeo1,:edad1,:cod_doc2,:num_doc2,:direccion2,:ubigeo2,:edad2,:medico,:clinica,:moneda,:lista_contrato,:monto_servicio,:moneda_an,:monto_an,:usuario,:vendedor,:pais1,:pais2,:mama_soltera,:servicio_contrato,:lista_an,:servicio_an,:seguro,:plan,:cobertura,:car_seguro,:car_cliente,:sin_prueba_serologica,:tipo_cambio,:cobrador,:id_cia_seguro,:porcentaje,:c); end;");


      $stmt->bindParam(':cia', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':nro_contrato', $nro_contrato, PDO::PARAM_STR);
      //$stmt->bindParam(':fecha_contrato', $fecha_contrato, PDO::PARAM_STR);
      $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
      $stmt->bindParam(':nro_prospecto', $nro_prospecto, PDO::PARAM_STR);
      $stmt->bindParam(':contrato_lp', $contrato_lp, PDO::PARAM_STR);
      $stmt->bindParam(':emergencia', $emergencia, PDO::PARAM_STR);
      $stmt->bindParam(':cod_doc1', $cod_doc1, PDO::PARAM_STR);
      $stmt->bindParam(':num_doc1', $num_doc1, PDO::PARAM_STR);
      $stmt->bindParam(':direccion1', $direccion1, PDO::PARAM_STR);
      $stmt->bindParam(':ubigeo1', $ubigeo1, PDO::PARAM_STR);
      $stmt->bindParam(':edad1', $edad1, PDO::PARAM_STR);
      $stmt->bindParam(':cod_doc2', $cod_doc2, PDO::PARAM_STR);
      $stmt->bindParam(':num_doc2', $num_doc2, PDO::PARAM_STR);
      $stmt->bindParam(':direccion2', $direccion2, PDO::PARAM_STR);
      $stmt->bindParam(':ubigeo2', $ubigeo2, PDO::PARAM_STR);
      $stmt->bindParam(':edad2', $edad2, PDO::PARAM_STR);
      $stmt->bindParam(':medico', $medico, PDO::PARAM_STR);
      $stmt->bindParam(':clinica', $clinica, PDO::PARAM_STR);
      $stmt->bindParam(':moneda', $moneda, PDO::PARAM_STR);
      $stmt->bindParam(':lista_contrato', $lista_contrato, PDO::PARAM_STR);
      $stmt->bindParam(':monto_servicio', $monto_servicio, PDO::PARAM_STR);
      $stmt->bindParam(':moneda_an', $moneda_an, PDO::PARAM_STR);
      $stmt->bindParam(':monto_an', $monto_an, PDO::PARAM_STR);
      $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
      $stmt->bindParam(':vendedor', $vendedor, PDO::PARAM_STR);
      $stmt->bindParam(':pais1', $pais1, PDO::PARAM_STR);
      $stmt->bindParam(':pais2', $pais2, PDO::PARAM_STR);
      $stmt->bindParam(':mama_soltera', $mama_soltera, PDO::PARAM_STR);
      $stmt->bindParam(':servicio_contrato', $servicio_contrato, PDO::PARAM_STR);
      $stmt->bindParam(':lista_an', $lista_an, PDO::PARAM_STR);
      $stmt->bindParam(':servicio_an', $servicio_an, PDO::PARAM_STR);
      $stmt->bindParam(':seguro', $seguro, PDO::PARAM_STR);
      $stmt->bindParam(':plan', $plan, PDO::PARAM_STR);
      $stmt->bindParam(':cobertura', $cobertura, PDO::PARAM_STR);
      $stmt->bindParam(':car_seguro', $car_seguro, PDO::PARAM_STR);
      $stmt->bindParam(':car_cliente', $car_cliente, PDO::PARAM_STR);
      $stmt->bindParam(':sin_prueba_serologica', $sin_prueba_serologica, PDO::PARAM_STR);
      $stmt->bindParam(':tipo_cambio', $tipo_cambio, PDO::PARAM_STR);
      $stmt->bindParam(':cobrador', $cobrador, PDO::PARAM_STR);
      $stmt->bindParam(':id_cia_seguro', $id_cia_seguro, PDO::PARAM_STR);
      $stmt->bindParam(':porcentaje', $porcentaje, PDO::PARAM_STR);
      $stmt->bindParam(':c', $rpta, PDO::PARAM_INT);
      
      $stmt->execute();

      
     
        return $rpta;

    
    }


    

    protected static function get_data_info_bitacora($prospecto){


      $p1 = Auth::user()->empresa;
    
      
      $stmt = static::$pdo->prepare("begin  WEB_VENPROSPECTOS_INFO_CON(:p1,:p2,:rpta); end;");
      $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
      $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
     
        return $list;


   }
   
    protected static function get_data_info($prospecto){


      $p1 = Auth::user()->empresa;
    
      
      $stmt = static::$pdo->prepare("begin  WEB_VENPROSPECTOS_VER_INFO(:p1,:p2,:rpta); end;");
      $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
      $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
     
        return $list;


   }

   protected static function get_data_info_servicios($prospecto){


      $p1 = Auth::user()->empresa;
    
      
      $stmt = static::$pdo->prepare("begin  WEB_VENPROSPECTOSSERV_LIST(:p1,:p2,:rpta); end;");
      $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
      $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
     
        return $list;


   }



    protected static function confirmar_eliminacion_prospecto($request){


      $p1 = Auth::user()->empresa;
    
      $p2 = $request->prospecto;

      $p3 = trim($request->motivo);

      //$p4 = Auth::user()->codigo;


      $stmt = static::$pdo->prepare("begin  WEB_VENPROSPECTOS_ANULAR(:p1,:p2,:p3,:rpta); end;");
      $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
      $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
      //$stmt->bindParam(':p4', $p4, PDO::PARAM_STR);

      $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
      $stmt->execute();

       
     
        return $rpta;


   }
   

   

    protected static function get_data_tipo_ficha($id){

      $p1 = Auth::user()->empresa;

     

      
      $stmt = static::$pdo->prepare("begin WEB_VEN_PROSPECTO_TIPOFICHA(:p1, :p2, :c); end;");
      $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $id, PDO::PARAM_STR);
      $stmt->bindParam(':c', $rpta, PDO::PARAM_STR,1000);
      
      $stmt->execute();

     
        
        return $rpta;


   }


    protected static function set_listado_bitacora_prospectos($request){

      $p1 = Auth::user()->empresa;

      $p2 = $request->prospecto;

      
      $stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_DETALLE_Q01(:p1, :p2, :c); end;");
      $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
      $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
      
      $stmt->execute();

      oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;


   }



    protected static function valida_duplicado_prospecto($request){



      $rprospecto_patmadre    =mb_strtoupper($request->rprospecto_patmadre);
      $rprospecto_matmadre    =mb_strtoupper($request->rprospecto_matmadre) ;
      $rprospecto_nommadre    =mb_strtoupper($request->rprospecto_nommadre) ;


      $rprospecto_patpadre    =mb_strtoupper($request->rprospecto_patpadre)  ;
      $rprospecto_matpadre    =mb_strtoupper($request->rprospecto_matpadre)  ;
      $rprospecto_nompadre    =mb_strtoupper($request->rprospecto_nompadre)  ;


      $rprospecto_fijo1madre  =trim($request->rprospecto_fijo1madre);
      $rprospecto_fijo2madre  =trim($request->rprospecto_fijo2madre);
      $rprospecto_fijo3madre  =trim($request->rprospecto_fijo3madre);

      $rprospecto_fijo1padre  =trim($request->rprospecto_fijo1padre);
      $rprospecto_fijo2padre  =trim($request->rprospecto_fijo2padre);
      $rprospecto_fijo3padre  =trim($request->rprospecto_fijo3padre);


      $rprospecto_movil1madre =trim($request->rprospecto_movil1madre);
      $rprospecto_movil2madre =trim($request->rprospecto_movil2madre);
      $rprospecto_movil3madre =trim($request->rprospecto_movil3madre);
      
      $rprospecto_movil1padre =trim($request->rprospecto_movil1padre);
      $rprospecto_movil2padre =trim($request->rprospecto_movil2padre);
      $rprospecto_movil3padre =trim($request->rprospecto_movil3padre);
    
      
      $stmt = static::$pdo->prepare("begin  WEB_PROSPECTO_VALDUPLICADO(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,:p18,:rpta); end;");
      $stmt->bindParam(':p1', $rprospecto_patmadre, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $rprospecto_matmadre, PDO::PARAM_STR);
      $stmt->bindParam(':p3', $rprospecto_nommadre, PDO::PARAM_STR);
      $stmt->bindParam(':p4', $rprospecto_patpadre, PDO::PARAM_STR);
      $stmt->bindParam(':p5', $rprospecto_matpadre, PDO::PARAM_STR);
      $stmt->bindParam(':p6', $rprospecto_nompadre, PDO::PARAM_STR);
      $stmt->bindParam(':p7', $rprospecto_fijo1madre, PDO::PARAM_STR);
      $stmt->bindParam(':p8', $rprospecto_fijo2madre, PDO::PARAM_STR);
      $stmt->bindParam(':p9', $rprospecto_fijo3madre, PDO::PARAM_STR);
      $stmt->bindParam(':p10', $rprospecto_fijo1padre, PDO::PARAM_STR);
      $stmt->bindParam(':p11', $rprospecto_fijo2padre, PDO::PARAM_STR);
      $stmt->bindParam(':p12', $rprospecto_fijo3padre, PDO::PARAM_STR);
      $stmt->bindParam(':p13', $rprospecto_movil1madre, PDO::PARAM_STR);
      $stmt->bindParam(':p14', $rprospecto_movil2madre, PDO::PARAM_STR);
      $stmt->bindParam(':p15', $rprospecto_movil3madre, PDO::PARAM_STR);
      $stmt->bindParam(':p16', $rprospecto_movil1padre, PDO::PARAM_STR);
      $stmt->bindParam(':p17', $rprospecto_movil2padre, PDO::PARAM_STR);
      $stmt->bindParam(':p18', $rprospecto_movil3padre, PDO::PARAM_STR);
      $stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
     
        return $list;


   }



   


   protected static function insert_update_bitacora_prospecto($request){

       $cia = Auth::user()->empresa;
       $prospecto          = $request->prospecto;
       $fecha_comunicacion = $request->vm_fcomunicacion.' '.$request->hora_actual;;
       $quien_contacto     = $request->vm_qcontacto;
       $respuesta          = ($request->vm_respuesta)?'SI':'NO';
       $detalle            = $request->vm_comentario_bit;
       $proxcontacto       = $request->vm_proxcontacto.' '.$request->hora_actual;
       $medio              = $request->vm_mcomunicacion;
       
       $usuario = Auth::user()->codigo;
       
       
       
      
      $flag = $request->flag;

      //ACTUALIZA
      

      if($flag == 1){

         $stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_DETALLE_UDP(:p1,:p2,:p3,:p4, :p5,:p6,:p7,:p8,:p9,:rpta); end;");

      }else{

         //INSERTA
         
         $stmt = static::$pdo->prepare("begin WEB_PROSPECTO_DETALLE_I01(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:rpta); end;");

      }


      
      $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
      $stmt->bindParam(':p2', $prospecto, PDO::PARAM_STR);
      $stmt->bindParam(':p3', $fecha_comunicacion, PDO::PARAM_STR);
      $stmt->bindParam(':p4', $quien_contacto, PDO::PARAM_STR);
      $stmt->bindParam(':p5', $respuesta, PDO::PARAM_STR);
      $stmt->bindParam(':p6', $detalle, PDO::PARAM_STR);
      $stmt->bindParam(':p7', $proxcontacto, PDO::PARAM_STR);
      $stmt->bindParam(':p8', $medio, PDO::PARAM_STR);
      $stmt->bindParam(':p9', $usuario, PDO::PARAM_STR);


      if($flag == 0){

         $dniusuario = null;
         $ip      = null;
         $dominio = null;
         $pc      = null;

         $stmt->bindParam(':p10', $dniusuario, PDO::PARAM_STR);
         $stmt->bindParam(':p11', $ip, PDO::PARAM_STR);
         $stmt->bindParam(':p12', $dominio, PDO::PARAM_STR);
         $stmt->bindParam(':p13', $pc, PDO::PARAM_STR);

      }

      $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
      
      $stmt->execute();

        
        return $rpta;


   }  

}
