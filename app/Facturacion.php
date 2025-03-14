<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class Facturacion extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
 	
	protected static function valida_centro(){


		$p1 = Auth::user()->empresa;	
		$p2 = Auth::user()->codigo;	
		

		$stmt = static::$pdo->prepare("begin WEB_COR_USUARIO_CENTRO_VAL(:p1,:p2,:rpta); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);	
		$stmt->execute();

		return $rpta;



	}

	protected static function valida_tipo_cambio(){



		$stmt = static::$pdo->prepare("begin WEB_COR_TIPO_CAMBIO_VAL (:rpta); end;");

		$stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list[0]['TIPO_CAMBIO'];



	}


	protected static function get_datos_facturacion_cliente($request){

		$cia = Auth::user()->empresa;
		$dni = $request->cliente ;

		$query = DB::select("SELECT nombre,IDENTIFICACION, celular_contacto, mail_contacto, direccion FROM VEN_CLIENTES WHERE NO_CIA = ? AND IDENTIFICACION=?",array($cia,$dni));

		return $query;



	}

	protected static function get_documentos_centros_facturacion($request){


		$p1 = Auth::user()->empresa;
		$p2 = $request->centro;
		
		$stmt = static::$pdo->prepare("begin WEB_TIPO_DOCUMENTO_FACT (:p1,:p2,:rpta); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
      
        $result = array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["TIPO_DOCUMENTO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;



	}


	protected static function get_data_titular_cliente($request){


		$p1 = Auth::user()->empresa;
		$p2 = $request->contrato;
		
		$stmt = static::$pdo->prepare("begin  WEB_DATITULAR_CONTRATO_FACT(:p1,:p2,:rpta); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
     
        return $list;


	}

	protected static function filter_vendedor_facturacion($request){


		$p1 = Auth::user()->empresa;
		$p2 = strtoupper($request->get('q'));
		
		$stmt = static::$pdo->prepare("begin WEB_VENDEDORES_FACT_AUTOCOM(:p1,:p2,:rpta); end;");

		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		
		$stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
      
        $result = array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;



	}
   

   protected static function filter_cliente_facturacion($request){


		$p1 = Auth::user()->empresa;
		$p2 = strtoupper($request->get('q'));
		
		$stmt = static::$pdo->prepare("begin WEB_CLIENTES_AUTOCOMPLETAR_Q2(:p1,:p2,:rpta); end;");

		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		
		$stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
      
        $result = array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["NOMBRE"]);
        }
        
        return $result;



	}
   
    protected static function filter_lista_precio_facturacion($request){


		$p1 = Auth::user()->empresa;
		$p2 = strtoupper($request->get('q'));
		
		$stmt = static::$pdo->prepare("begin WEB_LISTAPRECIO_AUTOCOMPLETAR(:p1,:p2,:rpta); end;");

		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		
		$stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
      
        $result = array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["LISTA_PRECIO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;



	}



protected static function get_articulos_facturacion_precio($request){


		$p1 = Auth::user()->empresa;
		$p2 = $request->lista_precio;
		$p3 = $request->moneda;
		
		$stmt = static::$pdo->prepare("begin WEB_LISTAPRECIO_DETALLE_LIST(:p1,:p2,:p3,:rpta); end;");

		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		$stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
      
        $result = array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["CODIGO_ARTICULO"],"text"=>$value["DESCRIPCION"].' / '.$value["PRECIO"]);
        }
        
        return $result;



	}


	protected static function genera_factura_directa($request){

	  
		$cia = Auth::user()->empresa;
		$usuario = Auth::user()->codigo;
		$centro     = $request->centro;
		$documento  = $request->documento;
		$vendedor   = $request->vendedor;
		$tipo_cambio  = $request->tipo_cambio;
		$cliente      = $request->cliente;

		$descripcion_cliente = $request->descripcion_cliente;
		$dni_cliente       = trim($request->dni_cliente);
		$correo_cliente    = $request->correo_cliente;
		$celular_cliente   = $request->celular_cliente;
		$direccion_cliente = $request->direccion_cliente;

		$sub_total = $request->sub_total;
		$impuesto = $request->impuesto;
		$total = $request->total;

		$igv = $request->igv;

		$articulos = $request->articulos;
		
		$descuento = 0 ;

		$str_articulos = '';

		$lista_precio = $articulos[0]['LISTA_PRECIO'];

		$moneda = $articulos[0]['MONEDA'];

		$numero_contrato=$request->numero_contrato;
		$fecha_desde=$request->fecha_desde;
		$fecha_hasta=$request->fecha_hasta;
		//$fecha_desde=($request->fecha_desde != null )?Carbon::parse($request->fecha_desde)->format('d/m/Y'):null;
		//$fecha_hasta=($request->fecha_hasta != null )?Carbon::parse($request->fecha_hasta)->format('d/m/Y'):null;

		
		foreach($articulos as $list){

			$descuento+=str_replace(',','',$list['MONTO_DESCUENTO']);


			$dscto_detalle = str_replace(',','',$list['MONTO_DESCUENTO']);

			$total_detalle = str_replace(',','',$list['TOTAL']);


			$str_articulos.= $list['CODIGO_ARTICULO'].','.$list['MONEDA'].','.$list['PRECIO'].','.$list['DESCUENTO'].','.$dscto_detalle.','.$total_detalle.'|';
		}




		$result_descuento = $descuento/(1+($igv/100));

		$observaciones = $request->observacion;

		$titular ='';

		

		$apl = 'APL';

		$forma_pago = $request->forma_pago;

		$numero_cuotas = ($forma_pago==1)?$request->numero_cuotas:0;


		
		$stmt = static::$pdo->prepare("begin WEB_GENERAR_FACTURA_DIRECTA(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,:p18,:p19,:p20,:p21,:p22,:p23,:p24,:p25,:p26,:rpta); end;");

		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $centro, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $documento, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $tipo_cambio, PDO::PARAM_STR);
		$stmt->bindParam(':p5', $vendedor, PDO::PARAM_STR);
		$stmt->bindParam(':p6', $descripcion_cliente, PDO::PARAM_STR);
		$stmt->bindParam(':p7', $dni_cliente, PDO::PARAM_STR);
		$stmt->bindParam(':p8', $direccion_cliente, PDO::PARAM_STR);
		$stmt->bindParam(':p9', $correo_cliente, PDO::PARAM_STR);
		$stmt->bindParam(':p10', $celular_cliente, PDO::PARAM_STR);
		$stmt->bindParam(':p11', $lista_precio, PDO::PARAM_STR);
		$stmt->bindParam(':p12', $moneda, PDO::PARAM_STR);
		$stmt->bindParam(':p13', $sub_total, PDO::PARAM_STR);
		$stmt->bindParam(':p14', $result_descuento, PDO::PARAM_STR);
		$stmt->bindParam(':p15', $impuesto, PDO::PARAM_STR);
		$stmt->bindParam(':p16', $total, PDO::PARAM_STR);
		$stmt->bindParam(':p17', $apl, PDO::PARAM_STR);
		$stmt->bindParam(':p18', $usuario, PDO::PARAM_STR);
		$stmt->bindParam(':p19', $observaciones, PDO::PARAM_STR);
		$stmt->bindParam(':p20', $titular, PDO::PARAM_STR);
		$stmt->bindParam(':p21', $str_articulos, PDO::PARAM_STR);
		$stmt->bindParam(':p22', $forma_pago, PDO::PARAM_STR);
		$stmt->bindParam(':p23', $numero_cuotas, PDO::PARAM_STR);
		$stmt->bindParam(':p24', $numero_contrato, PDO::PARAM_STR);
		$stmt->bindParam(':p25', $fecha_desde, PDO::PARAM_STR);
		$stmt->bindParam(':p26', $fecha_hasta, PDO::PARAM_STR);

		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_STR,1000);
		$stmt->execute();

		
        return $rpta;
        

	}

	

	protected static function set_contratos_facturacion($cliente,$contrato){

		//LISTADO DE SW CONTRATACION

		$cia =  Auth::user()->empresa;


		if(empty($cliente)){

			$cliente  = '';
		}

		if(empty($contrato)){

			$contrato  = '';
		}


		if(empty($contrato) && empty($cliente)){

			$contrato = 0;
			$cliente  = 0;

		}

		

		$contrato = trim($contrato);

		$stmt = static::$pdo->prepare("begin WEB_ASISTENTE_FACT_CONTRATA(:p1,:p2,:p3,:rpta); end;");

		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $cliente, PDO::PARAM_STR);
		$stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;



	}



	protected static function asistente_facturacion_tabla_anualidad($cliente,$contrato,$precontrato){

		//valida existencia contrato
		
		$cia =  Auth::user()->empresa;


		$queryx =\DB::select("SELECT COUNT(*) AS TOTAL FROM VEN_CONTRATOS WHERE NO_CIA = ? AND (NUMERO_CONTRATO = ? OR to_char(NUMERO_BASE) =  ?)",array($cia,$contrato,$contrato));

		$decode= json_decode(json_encode($queryx),true);
		
		

		if($decode[0]["total"] == 0){

			return array();

		}




		//LISTADO DE SW ANUALIDAD

		

		if(empty($cliente)){

			$cliente  = '';
		}

		if(empty($contrato)){

			$contrato  = '';
		}


		if(empty($contrato) && empty($cliente)){

			$contrato = 0;
			$cliente  = 0;

		}
		if(empty($precontrato)){

			$precontrato  = '';
		}
		 

		$contrato = trim($contrato);
		
		$stmt = static::$pdo->prepare("begin WEB_ASISTENTE_FACT_ANUALIDAD (:p1,:p2,:p3,:p4,:rpta); end;");

		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $cliente, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $precontrato, PDO::PARAM_STR);
		$stmt->bindParam(':rpta', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

 
        
        return $list;



	}




protected static function set_ventana_registro_pago_contrato($contrato,$titular){


	$p1 = Auth::user()->empresa;

		
		
	
		$stmt = static::$pdo->prepare("begin WEB_TITULAR_CONTRATO_ASFACT(:p1,:p2,:p3,:rpta); end;");


		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $titular, PDO::PARAM_STR);
		
		$stmt->bindParam(':rpta',$cursor,PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
      
        oci_free_cursor($cursor);
      
       
       return $list;



}

protected static function cambiaTitularFacturacionAsistente($request){


		$p1 = Auth::user()->empresa;

		$p2 = $request->contrato;

		$p3 = $request->titular;
		
	
		$stmt = static::$pdo->prepare("begin WEB_TITULAR_CONTRATO_ASFACT(:p1,:p2,:p3,:rpta); end;");


		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		
		$stmt->bindParam(':rpta',$cursor,PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
      
        oci_free_cursor($cursor);
      
       
       return $list;


	}


	protected static function genera_detalle_facturacion_asistente($request){


		$p1 = Auth::user()->empresa;
		$p2 = $request->documento;
		$p3 = $request->centro;
		
	
		$stmt = static::$pdo->prepare("begin WEB_DETALLEFACT_LLENAR(:p1,:p2,:p3,:rpta); end;");


		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		
		$stmt->bindParam(':rpta',$cursor,PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
      
        oci_free_cursor($cursor);
      
       
        
        $documento = (isset($list[0]['DOCUMENTO']))?$list[0]['DOCUMENTO']:'';

        return $documento;


	}

	protected static function trae_aseguradora_contrato($contrato){

		$request = new \Request();

		$request->contrato = $contrato;

		$list = self::obtener_datos_aseguradoraFacturacion($request);

		

		$rpta = 'NO';

		if(!empty($list[0]["RUC_CIA"])){

			$rpta = 'SI';
		}
		

		return $rpta;

	}



	protected static function set_rows_detalle_facturacion($request){

		$tipo = $request->tipo;

		$list = $request->tabla;

		

		$rows = array();
		
		$details = array();

		$documento_inicial = self::genera_detalle_facturacion_asistente($request);
		
		$cuenta = 1;

		$suma_sol=0;

		$suma_dol=0;

		$cantidad_sol=0;

		$cantidad_dol=0;


		$titular = $request->titular;

		$nocia = Auth::user()->empresa;

		foreach($list as $value){

			$value = json_decode($value);

			if($value->FLAG){

				//actualizamos titular
				
				//NUMERO BASE EJEM 3O4-T , CONTRATO EJMP=200 PARA ESTE CASO

				

				//DB::update("UPDATE VEN_CONTRATOS SET TITULAR_PAGO=? WHERE NO_CIA=? AND NUMERO_CONTRATO=?",array($titular,$nocia,$value->NUMERO_BASE));

				
				//ACTUALIZAMOS PARA BITACORA
				
				


				$edita_fecha = 'NO';

				$finicial = Carbon::parse($value->FVENCIMIENTO)->format('Y-m-d');
				
				for($i = 0;$i<$value->CANTIDAD;$i++){


					


					//$vencimiento = Carbon::parse($value->FVENCIMIENTO)->format('Y-m-d');

					$vencimiento = $finicial;


					

					$periodo_hasta = Carbon::createFromFormat('Y-m-d', $vencimiento)->addYear()->format('Y-m-d');
				
				
					$saldo =  str_replace(',','',$value->SALDO);

					$num_doc = intval($documento_inicial)+$cuenta;

					$numero =str_pad($num_doc, 11, 0, STR_PAD_LEFT);


					$edita_fecha = ($i == 0)?'SI':'NO';

					
					$cuotax = ($tipo=='c')?1:0;


					$aseguradora = self::trae_aseguradora_contrato($value->NUMERO_BASE);


					$rows[]= array('TIPO_DOCUMENTO'=>$request->documento,'NUMERO_DOCUMENTO'=>$numero,'NUMERO_CONTRATO'=>$value->NUMERO_BASE,'MONEDA'=>$value->MONEDA,'PERIODO_DESDE'=>$vencimiento,'PERIODO_HASTA'=>$periodo_hasta,'ANUALIDAD'=>$saldo,'PORCENTAJE_DESCUENTO'=>0,'VALOR_DESCUENTO'=>0,'PENALIDAD'=>0,'TOTAL'=>$saldo,'CUOTAS'=>$cuotax,'EDITA_FECHA'=>$edita_fecha,'CONDICION_PAGO'=>1,'BLOQUEA_CUOTA'=>false,'MUESTRA_DETALLE'=>false,"ASEG_FLAG"=>0,"DETAILS_CUOTAS"=>array(),'ASEGURADORA'=>$aseguradora);

					$cuenta++;	

					$ultimo_contrato = $value->NUMERO_BASE;

					if($value->MONEDA=='SOL'){

						$suma_sol+=$saldo;

						$cantidad_sol++;

					}else{

						$suma_dol+=$saldo;

						$cantidad_dol++;
					
					}


					$finicial = Carbon::createFromFormat('Y-m-d', $finicial)->addYear()->format('Y-m-d');

				}


			}
			
			


		}

		$details = array("SALDO_DOL"=>$suma_dol,"SALDO_SOL"=>$suma_sol,"CANTIDAD_SOL"=>$cantidad_sol,"CANTIDAD_DOL"=>$cantidad_dol,"CONTRATO_MONTO"=>$saldo,"ULTIMO_CONTRATO"=>$ultimo_contrato);


		return array($rows,$details);

	}



	

	protected static function save_factura_asistente($request){


		
		$p1 = Auth::user()->empresa;
		$p2 = $request->documento;
		$p3 = trim($request->identificacion);
		$p4 = $request->razon;
		$p5 = $request->direccion;
		$p6 = $request->celular;
		$p7 = $request->email;
		$p8 = $request->tipo_cambio;
		$p9 = Auth::user()->codigo;
		$p10 = $request->centro;
		$p11 = $request->anombre_de;
		$p12 = $request->vendedor;
		$p13 = trim($request->detalle);
		$p14 = self::set_cadena_detalle_factura_asistente($request);
		$p15 = ($request->tipo)?0:1;
		
		
		

		

		//identificac asegura
		$p16 = trim($request->aseguradora_global);

		

		//obtener demas datos aseguradora
		
		$subquery = \DB::select("SELECT NOMBRE,DIRECCION,CELULAR_CONTACTO,MAIL_CONTACTO FROM VEN_CLIENTES WHERE NO_CIA=? AND IDENTIFICACION=?",array($p1,$p16));

		$sub = json_decode(json_encode($subquery),true);
		
		//razon asegu
		$p17=(isset($sub[0]['nombre']))?trim($sub[0]['nombre']):null;

		//direccion asegu
		$p18=(isset($sub[0]['direccion']))?trim($sub[0]['direccion']):null;


		//anula operacion
		
		$p19  = ($request->anula_operacion)?'Anulación de la Operación':null;

		
		$stmt = static::$pdo->prepare("begin WEB_GENERAR_FACTURA(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:p17,:p18,:p19,:rpta); end;");

		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
		$stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
		$stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
		$stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
		$stmt->bindParam(':p8', $p8, PDO::PARAM_STR);
		$stmt->bindParam(':p9', $p9, PDO::PARAM_STR);
		$stmt->bindParam(':p10', $p10, PDO::PARAM_STR);
		$stmt->bindParam(':p11', $p11, PDO::PARAM_STR);
		$stmt->bindParam(':p12', $p12, PDO::PARAM_STR);
		$stmt->bindParam(':p13', $p13, PDO::PARAM_STR);
		$stmt->bindParam(':p14', $p14, PDO::PARAM_STR);
		$stmt->bindParam(':p15', $p15, PDO::PARAM_STR);

		$stmt->bindParam(':p16', $p16, PDO::PARAM_STR);
		$stmt->bindParam(':p17', $p17, PDO::PARAM_STR);
		$stmt->bindParam(':p18', $p18, PDO::PARAM_STR);
		$stmt->bindParam(':p19', $p19, PDO::PARAM_STR);

		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();

       
        
        return $rpta;



	}


	protected static function set_cadena_detalle_factura_asistente($request){


		$rows = '';


		
		

		foreach($request->cadena as $values){


			if($request->tipo){

				//contratacion
				
				//$desde = Carbon::parse($values['PERIODO_DESDE'])->format('d/m/Y');
				//$hasta = Carbon::parse($values['PERIODO_HASTA'])->format('d/m/Y');

				$desde=-1;
				$hasta =-1;

				$anualidad = str_replace(',','',$values['ANUALIDAD']);

				//$rows.= $values['NUMERO_DOCUMENTO'].','.$values['NUMERO_CONTRATO'].','.$values['MONEDA'].','.$desde.','.$hasta.','.$anualidad.','.$values['PORCENTAJE_DESCUENTO'].','.$values['VALOR_DESCUENTO'].','.$values['PENALIDAD'].','.'1'.'|';


				$cuotas =($values['CONDICION_PAGO']==0)?null:$values['CUOTAS'];

				

				

				$aseguradora_flag = ($values['ASEG_FLAG'] == 0)?0:1;

				

				$rows.= $values['TIPO_DOCUMENTO'].','.$values['NUMERO_DOCUMENTO'].','.$values['NUMERO_CONTRATO'].','.$values['MONEDA'].','.$desde.','.$hasta.','.$anualidad.','.$values['PORCENTAJE_DESCUENTO'].','.$values['VALOR_DESCUENTO'].','.$values['PENALIDAD'].','.$values['CONDICION_PAGO'].','.$cuotas.','.$aseguradora_flag.'|';
			}else{

				//anualidad
				
				$medio_pago_combo = $request->medio_pago_anualidad;


				$cuotas =($medio_pago_combo==0)?0:$values['CUOTAS'];

				$desde = Carbon::parse($values['PERIODO_DESDE'])->format('d/m/Y');
				$hasta = Carbon::parse($values['PERIODO_HASTA'])->format('d/m/Y');

				$anualidad = str_replace(',','',$values['ANUALIDAD']);

				$aseguradora_flag=0;


				$rows.= $values['TIPO_DOCUMENTO'].','.$values['NUMERO_DOCUMENTO'].','.$values['NUMERO_CONTRATO'].','.$values['MONEDA'].','.$desde.','.$hasta.','.$anualidad.','.$values['PORCENTAJE_DESCUENTO'].','.$values['VALOR_DESCUENTO'].','.$values['PENALIDAD'].','.$medio_pago_combo.','.$cuotas.','.$aseguradora_flag.'|';

			}

		}


		return $rows;
	}


	protected  static function set_datos_personales_asis_factura($request){

    	$cliente = $request->cliente;

    	$list= DB::select("SELECT IDENTIFICACION,NOMBRE,DIRECCION,CELULAR_CONTACTO,MAIL_CONTACTO FROM VEN_CLIENTES WHERE IDENTIFICACION=?",array($cliente));

    	return $list ;

    }





    protected static function actualiza_titular_registro_bitacora($contrato,$identificacion){



        $cia = Auth::user()->empresa;


        $variable = 2;

        $stmt = static::$pdo->prepare("begin WEB_CONTRATOS_BITA_IDENT_UPD(:p1,:p2,:p3,:p4,:rpta); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':p3', $variable, PDO::PARAM_STR);
          $stmt->bindParam(':p4', $identificacion, PDO::PARAM_STR);
          $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
          $stmt->execute();
          return $rpta;

     

    }



    



protected static function llenaCoberturaSeguroFacturacion($request){



       $p1 = Auth::user()->empresa;

        
		$p2= $request->cia;

		$p3= $request->plan;

		$stmt = static::$pdo->prepare("begin WEB_VENASEGURA_PLANSEGURO_POR(:p1,:p2,:p3,:c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        // $result = array();

        // foreach ($list as $value) {
        	
        // 	$result[] = array("id"=>$value["CODIGO_PLAN"],"text"=>$value["DESCRIPCION"]);
        // }
        
        return $list;

     

    }


    protected static function llenaPlanesAseguradoraFacturacion($request){



       $p1 = Auth::user()->empresa;

        
		$p2= $request->cia;

		$stmt = static::$pdo->prepare("begin WEB_VENASEGURADORA_PLANSEGURO(:p1,:p2,:c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
	
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["CODIGO_PLAN"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

     

    }


    protected static function lista_aseguradoras(){



       $p1 = Auth::user()->empresa;

        
	
		$stmt = static::$pdo->prepare("begin WEB_VENPLANESSEGURO_LISTA(:p1,:c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
	
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

        $result = array();

        foreach ($list as $value) {
        	
        	$result[] = array("id"=>$value["IDENTIFICACION"],"text"=>$value["RAZONSOCIAL"]);
        }
        
        return $result;

     

    }



	

	 protected static function completa_datos_contacto_aseguradora($request){



       $p1 = Auth::user()->empresa;

        $p2=$request->cia;

	
		$stmt = static::$pdo->prepare("begin WEB_VENPLANESSEGURO_DATOS(:p1,:p2,:c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

      
        return $list;

     

    }

    protected static function obtener_datos_aseguradoraFacturacion($request){



       $p1 = Auth::user()->empresa;

        $p2=$request->contrato;

	
		$stmt = static::$pdo->prepare("begin WEB_OBTENER_DATOS_ASEG_FACTURA(:p1,:p2,:c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

      
        return $list;

     

    }



protected static function guarda_cuotas_facturacion($contrato,$tipo_doc,$numero_doc,$cuota,$vence,$moneda ,$monto,$observacion){



       $cia = Auth::user()->empresa;

       $creado = Carbon::now()->format('Y-m-d');
        
       $creado_por = Auth::user()->codigo;
	



		$stmt = static::$pdo->prepare("begin WEB_VENFACTURACUOTASALL_INPUT(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:rpta); end;");

		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $tipo_doc, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $numero_doc, PDO::PARAM_STR);
		$stmt->bindParam(':p5', $cuota, PDO::PARAM_STR);
		$stmt->bindParam(':p6', $vence, PDO::PARAM_STR);
		$stmt->bindParam(':p7', $moneda, PDO::PARAM_STR);
		$stmt->bindParam(':p8', $monto, PDO::PARAM_STR);
		$stmt->bindParam(':p9', $observacion, PDO::PARAM_STR);
		$stmt->bindParam(':p10',$creado, PDO::PARAM_STR);
		$stmt->bindParam(':p11',$creado_por, PDO::PARAM_STR);

		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();


        $rpta = ($rpta == 1)?1:0;
        
        return $rpta;

     

    }






protected static function ConfirmaMasivaFactura($request){


		

        $p1 = Auth::user()->empresa;

        $p2 = rtrim($request->contratos,',');

        $p3 = Auth::user()->codigo;

        $p4 = $request->centro;

        $p5 = $request->cantidad;

        $p6 = $request->soles;

        $p7 = $request->dolares;
		
		

		$stmt = static::$pdo->prepare("begin WEB_GENERAR_FACTURA_MASIVA(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:rpta); end;");
		
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
		$stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
		$stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
		$stmt->bindParam(':p7', $p7, PDO::PARAM_STR);

		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
		
		$stmt->execute();

		
        return $rpta;

     

    }


    protected static function set_centro_facturacion_nombre(){



       $p1 = Auth::user()->empresa;

        $p2=Auth::user()->codigo;

	
		$stmt = static::$pdo->prepare("begin WEB_CORUSUARIO_CENTRO_NOM(:p1,:p2,:c); end;");
		
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);

		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

      
        return $list;

     

    }

    

    protected static function lista_tabla_masiva_facturacion_historial($request){



      
    	$cia    = Auth::user()->empresa;
   		

   		$start = ($request->date[0] =='null' || $request->date[0] ==null )?'':Carbon::parse($request->date[0])->format('d/m/Y');

   		$end  = ($request->date[1] =='null' || $request->date[1] ==null)?'':Carbon::parse($request->date[1])->format('d/m/Y');

   		


		$stmt = static::$pdo->prepare("begin WEB_GUIAFACTURACIONMASICA_LIST(:p1,:p2,:p3,:c); end;");
		
		
		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		
		$stmt->bindParam(':p2', $start, PDO::PARAM_STR);

		$stmt->bindParam(':p3', $end, PDO::PARAM_STR);
		

		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

      
        return $list;

     

    }


    	protected static function exportar_detalle_masivo_facturas($guia){



      

	
		$stmt = static::$pdo->prepare("begin WEB_GUIAFACTURACIONMASICA_EXP(:p1,:c); end;");
		
		$stmt->bindParam(':p1', $guia, PDO::PARAM_STR);
		
	

		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

      
        return $list;

     

    }

    protected static function lista_tabla_masiva_facturacion($request){



       $cia    = Auth::user()->empresa;

   		

   		$start = ($request->date[0] =='null' || $request->date[0] ==null )?'':Carbon::parse($request->date[0])->format('d/m/Y');

   		$end  = ($request->date[1] =='null' || $request->date[1] ==null)?'':Carbon::parse($request->date[1])->format('d/m/Y');

   		$vendedor = $request->vendedor ;

		$medico = $request->medico;

		$servicio =  (empty($request->servicio))?'':$request->servicio;

		


		$stmt = static::$pdo->prepare("begin WEB_VENCONTRATO_GENMASIVA_LIS(:p1,:p2,:p3,:p4,:p5,:p6,:c); end;");
		
		$stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
		
		$stmt->bindParam(':p2', $start, PDO::PARAM_STR);

		$stmt->bindParam(':p3', $end, PDO::PARAM_STR);

		$stmt->bindParam(':p4', $vendedor, PDO::PARAM_STR);

		$stmt->bindParam(':p5', $medico, PDO::PARAM_STR);

		$stmt->bindParam(':p6', $servicio, PDO::PARAM_STR);

		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        

      
        return $list;

     

    }

	protected static function list_bitacora_bitacora($contrato){

        $cia = Auth::user()->empresa;

          $contrato = $contrato;


          $stmt = static::$pdo->prepare("begin WEB_CXCDOCUMENTOS_DETALLE_Q01(:p1,:p2, :c); end;");

          $stmt->bindParam(':p1', $cia, PDO::PARAM_STR);
          $stmt->bindParam(':p2', $contrato, PDO::PARAM_STR);
          $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
          $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

      
      

        //return $list;

        usort($list, function ($a, $b) {

            //return strcmp($a["FECHA_COMUNICACION"], $b["FECHA_COMUNICACION"]);

            //return strtotime(trim($a['FECHA_COMUNICACION'])) < strtotime(trim($b['FECHA_COMUNICACION']));

            return strtotime($b["FCOMPARSE"]) - strtotime($a["FCOMPARSE"]);
        
        });


      
        return $list;



    }
	protected static function get_numero_contrato($request){
		$cia = Auth::user()->empresa;

       $precontrato = $request->precontrato;

       $list = DB::select("SELECT NUMERO_CONTRATO FROM VEN_CONTRATOS WHERE NO_CIA=? AND NUMERO_PRECONTRATO=?",array($cia,$precontrato));


        return $list;
	}
}
