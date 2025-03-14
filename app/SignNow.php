<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;


class SignNow extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()
	{
    	static::$pdo = DB::getPdo();
	}

    protected static function salvar_signnow($request)
    {
    
        $usuarioreg= Auth::user()->codigo;

        $cia = Auth::user()->empresa;
        $p1 = Auth::user()->empresa;

		$p2 = $request->url;
		$p3 = $request->usuario;
		$p4 = $request->pass;
        $p5 = $request->tokenbasic;
        $p6 = $request->tokenacceso;
        $p7 = $request->tokenvigencia;
        $p8 = Auth::user()->codigo;

        //$stmt =  DB::getPdo()->prepare("begin WEB_COR_SIGNNOW_INSUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:rpta); end;");
        $stmt = static::$pdo->prepare("begin WEB_COR_SIGNNOW_INSUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $p7, PDO::PARAM_INT);
        $stmt->bindParam(':p8', $p8, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;

    }

    protected static function get_signnow(){

        $p1 = Auth::user()->empresa;
        
        $stmt = static::$pdo->prepare("begin WEB_COR_SIGNNOW_GET(:p1,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}
   	
    //plantilla
    protected static function save_sigmail($request)
    {
		$p1 = $request->idplantilla;
        $p2 = Auth::user()->empresa;
		$p3 = $request->asunto;
		$p4 = $request->cuerpo;
        $p5 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin WEB_PLANT_CONT_SIGMAIL_IU(:p1,:p2,:p3,:p4,:p5,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_INT);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;

    }

    protected static function get_sigmail($idplantilla){

        $p1 = $idplantilla;
        $p2 = Auth::user()->empresa;
        
        $stmt = static::$pdo->prepare("begin WEB_PLANT_CONT_SIGMAIL_GET(:p1,:p2,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_INT);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function save_sigdoc($request)
    {
		$p1 = $request->idplantilla;
        $p2 = Auth::user()->empresa;
		$p3 = $request->iddoc;
		$p4 = $request->nombredoc;
        $p5 = Auth::user()->codigo;
        
        $stmt = static::$pdo->prepare("begin WEB_PLANT_CONT_SIGDOC_IU(:p1,:p2,:p3,:p4,:p5,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_INT);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;

    }

    protected static function get_sigdoc($idplantilla){

        $p1 = $idplantilla;
        $p2 = Auth::user()->empresa;
        
        $stmt = static::$pdo->prepare("begin WEB_PLANT_CONT_SIGDOC_GET(:p1,:p2,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_INT);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function save_sigrol($request)
    {
		$p1 = $request->idplantilla;
        $p2 = Auth::user()->empresa;
		$p3 = $request->cadenaroles;
        $p4 = Auth::user()->codigo;
        
        $stmt = static::$pdo->prepare("begin WEB_PLANT_CONT_SIGROL_IU(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_INT);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;

    }

    protected static function get_sigrol($idplantilla){

        $p1 = $idplantilla;
        $p2 = Auth::user()->empresa;
        
        $stmt = static::$pdo->prepare("begin WEB_PLANT_CONT_SIGROL_LIST(:p1,:p2,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_INT);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function save_sigcampos($request)
    {
		$p1 = $request->idplantilla;
        $p2 = Auth::user()->empresa;
		$p3 = $request->cadenacampos;
        $p4 = Auth::user()->codigo;
        
        $stmt = static::$pdo->prepare("begin WEB_PLANT_CONT_SIGCAMPOS_IU(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_INT);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;

    }

    protected static function get_sigcampos($idplantilla){

        $p1 = $idplantilla;
        $p2 = Auth::user()->empresa;
        
        $stmt = static::$pdo->prepare("begin WEB_PLANT_CONT_SIGCAMPOS_LIST(:p1,:p2,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_INT);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function save_sigmod($request)
    {
        $p1 = $request->idplantilla;
        $p2 = Auth::user()->empresa;
        $p3 = $request->modo;
        $p4 = $request->correoPruebas;
        
        $stmt = static::$pdo->prepare("begin WEB_PLANT_CONT_SIGDOC_UMOD(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_INT);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;

    }

    //contrato

    protected static function validate_sigcontrato($numcontrato){

        $p1 = Auth::user()->empresa;
        $p2 = $numcontrato;
        $p3 = "CON";

        $stmt = static::$pdo->prepare("begin WEB_VALIDA_PLANTILLA_SIGGNOW(:p1,:p2,:p3,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function get_sigcontrato_token(){

        $p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_COR_SIGNNOW_TOKENACCESO(:p1,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function get_sigcontrato_parametros(){

        $p1 = Auth::user()->empresa;

        $stmt = static::$pdo->prepare("begin WEB_COR_SIGNNOW_GET(:p1,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function save_sigcontrato_token($request)
    {
        $p1 = Auth::user()->empresa;
        $p2 = $request->token;
        $p3 = $request->vigencia;
        $p4 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin WEB_COR_SIGNNOW_ACTTOKEN(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_INT);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;
    }

    protected static function save_sigcontrato($numcontrato,$idcontrato,$idplantilla)
    {
        $p1 = Auth::user()->empresa;
        $p2 = $numcontrato;
        $p3 = $idcontrato;
        $p4 = "CONTRATO";
        $p5 = $idplantilla;
        $p6 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATOS_SIGNNOW_INS(:p1,:p2,:p3,:p4,:p5,:p6,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_INT);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;
    }

    protected static function get_sigcontrato_campos($idcontrato){

        $p1 = Auth::user()->empresa;
        $p2 = $idcontrato;

        $stmt = static::$pdo->prepare("begin WEB_VEN_SIGNNOW_LISTCAMPOS(:p1,:p2,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function get_sigcontrato_roles($idcontrato){

        $p1 = Auth::user()->empresa;
        $p2 = $idcontrato;
        $p3 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin WEB_VEN_SIGNNOW_LISTROLES(:p1,:p2,:p3,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function get_contrato_firmantes($numcontrato){

        $p1 = Auth::user()->empresa;
        $p2 = $numcontrato;

        $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATO_FIRMANTES(:p1,:p2,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function get_contrato_campos($numcontrato){

        $p1 = Auth::user()->empresa;
        $p2 = $numcontrato;

        $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATO_CAMPOS(:p1,:p2,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function get_contrato_historial($numcontrato){

        $p1 = Auth::user()->empresa;
        $p2 = $numcontrato;

        $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATOS_SIGN_HIST(:p1,:p2,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function get_contrato_botones($numcontrato){

        $p1 = Auth::user()->empresa;
        $p2 = $numcontrato;

        $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATOS_SIG_STATUS(:p1,:p2,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function get_contrato_reenviar($numcontrato,$idcontrato,$firmante){

        $p1 = Auth::user()->empresa;
        $p2 = $numcontrato;
        $p3 = $idcontrato;
        $p4 = $firmante;
        $p5 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_SIGNNOW_REENVIA(:p1,:p2,:p3,:p4,:p5,:c); end;");
      
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;
   	}

    protected static function save_sigcontrato_campos($request)
    {
        $p1 = Auth::user()->empresa;
        $p2 = $request->id_doc;
        $p3 = $request->cadenaCampos;
        $p4 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin VEN_SIGNNOW_INSERT_CAMPOS(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;
    }

    protected static function save_sigcontrato_envios($request)
    {
        $p1 = Auth::user()->empresa;
        $p2 = $request->id_doc;
        $p3 = $request->cadenaEnvios;
        $p4 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin VEN_SIGNNOW_INSERT_ENVIOS(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;
    }

    protected static function save_sigcontrato_atencion($request)
    {
        $p1 = Auth::user()->empresa;
        $p2 = $request->id_doc;
        $p3 = $request->cadenaAtencion;
        $p4 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin VEN_SIGNNOW_INSERT_ATENCION(:p1,:p2,:p3,:p4,:rpta); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        return $rpta;
    }

    protected static function save_sigcontrato_historial($numcontrato,$idcontrato,$tipo)
    {
        $p1 = Auth::user()->empresa;
        $p2 = $numcontrato;
        $p3 = $idcontrato;
        $p4 = $tipo;
        $p5 = Auth::user()->codigo;

        $stmt = static::$pdo->prepare("begin VEN_CONTRATOS_SIGN_HIST_IN(:p1,:p2,:p3,:p4,:p5); end;");

        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p4, PDO::PARAM_STR);
        $stmt->execute();

        return $rpta;
    }

                    protected static function save_byte($numcontrato,$idcontrato,$byte)
                    {
                        $p1 = Auth::user()->empresa;
                        $p2 = $numcontrato;
                        $p3 = $idcontrato;
                        $p4 = $byte;

                        $stmt = static::$pdo->prepare("begin WEB_CONTRATO_SIGNNOW_INSDOC(:p1,:p2,:p3,:p4,:rpta); end;");

                        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
                        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
                        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
                        $stmt->bindParam(':p4', $p4, PDO::PARAM_LOB);
                        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);

                        $stmt->execute();

                        return $rpta;
                    }

                    protected static function get_byte($numcontrato,$idcontrato){

                        $p1 = Auth::user()->empresa;
                        $p2 = $numcontrato;
                        $p3 = $idcontrato;

                        $stmt = static::$pdo->prepare("begin WEB_CONTRATO_SIGNNOW_GETPDF(:p1,:p2,:p3,:c); end;");

                        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
                        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
                        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
                        $stmt->bindParam(':c', $cursor,PDO::PARAM_STMT);
                        
                        $stmt->execute();

                        oci_execute($cursor, OCI_DEFAULT);
                        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
                        oci_free_cursor($cursor);
                        
                        return $list[0];               
                    }

                    protected static function get_byte_status($numcontrato){

                        $p1 = Auth::user()->empresa;
                        $p2 = $numcontrato;
                        $stmt = static::$pdo->prepare("begin WEB_VEN_CONTRATOS_SIG_STATUS(:p1,:p2,:c); end;");

                        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
                        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
                        $stmt->bindParam(':c', $cursor,PDO::PARAM_STMT);
                        
                        $stmt->execute();

                        oci_execute($cursor, OCI_DEFAULT);
                        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
                        oci_free_cursor($cursor);
                        
                        return $list[0];               
                    }

    //
}
