<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Auth;
use PDO;
use Carbon\Carbon;
use App\Maestro;

class PrecioLista extends Model
{   
    

 	protected  static $pdo;

 	protected function __construct()

	{
    	static::$pdo = DB::getPdo();
	}

    
    protected static function get_item_precio_list($request){

		$p1 = Auth::user()->empresa;

		$p2 = trim($request->codigo);

		
		$stmt = static::$pdo->prepare("begin WEB_LISPRECIO_GETITEM(:p1, :p2, :c); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
		
		$stmt->execute();

		oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        return $list;


	}

	
   	
   	protected static function save_precio_list($list){

   
		$p1 = Auth::user()->empresa;
		$p2 = trim($list['lista_precio']);
		$p3 = trim($list['descripcion']);

		$articulo = $list['articulo'];

		$sub_articulo = explode('-',$articulo);

		$p4 = trim($sub_articulo[0]);

		

		$p5 = $list['moneda'];
		$p6 = $list['plista'];
		$p7 = $list['pnuevo'];
		$p8 = $list['psugerido'];

		$stmt = static::$pdo->prepare("begin WEB_LISTPRECIO_INPUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8 ,:rpta); end;");
		$stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
		$stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
		$stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
		$stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
		$stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
		$stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
		$stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
		$stmt->bindParam(':p8', $p8, PDO::PARAM_STR);

		$stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
		
		$stmt->execute();
        return $rpta;

    
    }

    
}
