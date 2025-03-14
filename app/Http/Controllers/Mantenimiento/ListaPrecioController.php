<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\PrecioLista;

class ListaPrecioController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
   
   

    public function index(){


      $middleRpta = $this->valida_url_permisos(11);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

      $list_precios = Maestro::get_lista_precios();

      $empresa_user = Auth::user()->empresa;

      $btn_nuevo = $this->botones_usuario('mant_listprecio_nuevo');


      return View('mantenimiento.lista_precio.index',compact('list_precios','empresa_user','btn_nuevo'));

    }

    


   protected function get_item_precio_list(Request $request){      

    
      // if($request->codigo === '0'){

      //     $list = array(array("LISTA_PRECIO"=>'',
      //                        "DESCRIPCION"=>'',
      //                        "CODIGO_ARTICULO"=>'',
      //                        "MONEDA"=>'',
      //                        "PLISTA"=>'',
      //                        "PNUEVO"=>'',
      //                        "PSUGERIDO"=>'',
      //                        "ARTICULO"=>''));

      //   }else{

      //     $list = PrecioLista::get_item_precio_list($request);

      //   }
        
        $list = PrecioLista::get_item_precio_list($request);

        return response()->json($list);

    
   }

   protected function delete_tabla_items($request){

      $precio_lista = $request->lprecio_codigo;

      $cia = Auth::user()->empresa;

      $rpta = DB::statement("DELETE FROM ven_listas_precios_detalle WHERE LISTA_PRECIO=? AND NO_CIA=?",array($precio_lista,$cia));

      return $rpta;

   }


    protected function valida_codigo_lista_precio($request){

      if($request->flag_accion=='REGISTRO'){

        $cia = Auth::user()->empresa;

        $codigo = $request->lprecio_codigo;

        $query = DB::select("SELECT * FROM ven_listas_precios_detalle WHERE NO_CIA=? AND LISTA_PRECIO=?",array($cia,$codigo));

        if(count($query)==0){

            return $this->setRpta("ok","validó correctamente");
        }

        return $this->setRpta("error","El código : ".$codigo." ya se encuentra registrado");

      }else{

        return $this->setRpta("ok","validó correctamente");

      }
      

    }

     protected function save_precio_list(Request $request){



      DB::beginTransaction();

        try {
          
          $valida_codigos = $this->valida_codigo_lista_precio($request);

            if($valida_codigos["status"]=="ok"){

              $delete_items = $this->delete_tabla_items($request);

              if($delete_items){

                $array = $this->setListArticulos($request);

                foreach($array as $list){

                  $rpta = PrecioLista::save_precio_list($list);

                }
           
            
                if($rpta == 1){

                  DB::commit();

                  return $this->setRpta("ok","Se procesó correctamente");

                }
          
                DB::rollback();

                return $this->setRpta("error","Ocurrió un error al insertar");


              }else{

                DB::rollback();

                return $this->setRpta("error","Ocurrió un error al eliminar");

              }
           
          }else{


            return $valida_codigos;

          }
           
     

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }

    }

    protected function setListArticulos($request){

       $codigo_lista = $request->lprecio_codigo;
       $descripcion_lista = $request->lprecio_name;


       $articulo = $request->articulo_item;
       $moneda = $request->moneda_item;
       $plista = $request->plista_item;
       $pnuevo = $request->pnuevo_item;
       $psugerido = $request->psugerido_item;

       $data = array();

       foreach($articulo as $key =>$list){

          $sub_nombre = $list;
          $sub_moneda = $moneda[$key];
          $sub_plista = $plista[$key];
          $sub_pnuevo = $pnuevo[$key];
          $sub_psugerido = $psugerido[$key];


          $data[] = array('lista_precio'=>$codigo_lista,
                          'descripcion'=> $descripcion_lista,
                          'articulo'=>$sub_nombre,
                          'moneda'=>$sub_moneda,
                          'plista'=>$sub_plista,
                          'pnuevo'=>$sub_pnuevo,
                          'psugerido'=> $sub_psugerido);

       }


        return $data;


    }
 
    
}