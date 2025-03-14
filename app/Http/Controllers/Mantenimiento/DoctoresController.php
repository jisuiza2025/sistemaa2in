<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\Doctores;

class DoctoresController extends Controller
{	

	public function __construct()
    {
        $this->middleware('auth');
    }
    

    public function index(){


      $middleRpta = $this->valida_url_permisos(13);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

      $empresa_user = Auth::user()->empresa;

      $documentos =  Doctores::list_tipo_documentos();

      $titulo =  Doctores::list_tipo_titulos();

      $especialidad = Doctores::list_tipo_especialidad();

      $bancos = Maestro::get_list_bancos();

      $monedas =Maestro::list_monedas();


      $btn_nuevo = $this->botones_usuario('mant_doctores_nuevo');

      $btn_editar = $this->botones_usuario('mant_doctores_editar');

      $btn_especialidad = $this->botones_usuario('mant_doctores_espe');

      $btn_precios = $this->botones_usuario('mant_doctores_precios');

      $btn_orucs = $this->botones_usuario('mant_doctores_oruc');

      $btn_secretaria = $this->botones_usuario('mant_doctores_secre');

      $btn_mpagos = $this->botones_usuario('mant_doctores_mpago');

      return View('mantenimiento.doctores.index',compact('empresa_user','documentos','titulo','especialidad','bancos','monedas','btn_nuevo','btn_editar','btn_especialidad','btn_precios','btn_orucs','btn_secretaria','btn_mpagos'));

    }  

    
    



 protected function get_info_doctor_mantenimiento(Request $request){      

    $list = Doctores::get_info_doctor_mantenimiento($request);


    return response()->json($list);
    

  }
  
    protected function list_doctores(Request $request){      

    $list = Doctores::list_doctores($request);


    return response()->json($list);
    

  }

 protected function get_data_proveedor_doctores(Request $request){      

    $list = Doctores::get_data_proveedor_doctores($request);


    return response()->json($list);
    

  }

  protected function guardar_nuevo_proveedor_mant_doctores(Request $request){      

    if(empty($request->ruc)){

      return $this->setRpta("error","Ingrese un ruc");

    }

     if(empty($request->descripcion)){

      return $this->setRpta("error","Ingrese una razon social");

    }


    if($request->edita==0){

      //REGISTRO NUEVO VALIDA RUC DUPLICADO
      $cia = Auth::user()->empresa;

      $ruc = trim($request->ruc);


      $count = DB::select("SELECT * FROM cxp_proveedores WHERE NO_CIA=? AND IDENTIFICACION = ? ",array($cia,$ruc));

      if(count($count)>0){

        return $this->setRpta("error","El Ruc ya se encuentra registrado");

      }

    }

    $rpta = Doctores::guardar_nuevo_proveedor_mant_doctores($request);


   
    if($rpta == 1){

      

       return $this->setRpta("ok","Se agregó correctamente");
    }
    

     return $this->setRpta("error","Ocurió un error al registrar");

  }

  

  protected function filter_clinica_doctores(Request $request){      

    $list = Doctores::filter_clinica_doctores($request);


    return response()->json($list);
    

  }

  protected function get_data_mpago_medico(Request $request){      

    $list = Doctores::get_data_mpago_medico($request);


    return response()->json($list);
    

  }



   protected function get_data_secretaria_medico(Request $request){      

    $list = Doctores::get_data_secretaria_medico($request);


    return response()->json($list);
    

  }

  

   protected function get_valida_clinica_especialidad(Request $request){      

    $rpta = Doctores::get_valida_clinica_especialidad($request);


    if($rpta == 0){

       $direccion = $this->obten_direccion_clinica($request);

       return $this->setRpta("ok","Se agregó correctamente",$direccion);
    }
    

     return $this->setRpta("error","La clínica ya se encuentra registrada");
  }


protected function valida_nuevo_proveedor_medico(Request $request){      

    $rpta = Doctores::valida_nuevo_proveedor_medico($request);


    if($rpta == 0){

       $dta = $this->obten_direccion_proveedor($request);

       return $this->setRpta("ok","Se agregó correctamente",$dta);
    }
    

     return $this->setRpta("error","El proveedor ya se encuentra registrado");
  }


protected function elimina_proveedor_oruc_medico(Request $request){      

    $rpta = Doctores::elimina_proveedor_oruc_medico($request);


    if($rpta == 1){

      
       return $this->setRpta("ok","Se eliminó correctamente");
    }
    

     return $this->setRpta("error","Ocurrió un error al eliminar");
  }


protected function guardar_nuevos_proveedores_medico(Request $request){      

    $proveedores = $request->proveedores;

    $this->elimina_antiguos_proveedores($request);

    foreach($proveedores as $list){

        $data = array(
                      "MEDICO" => $request->identificacion,
                      "RUC" => $list['IDENTIFICACION'],
                      "ESTADO"=> $list['ESTADO']
                      );


        $rpta = Doctores::guardar_nuevos_proveedores_medico($data);


        if($rpta == 0){

          return $this->setRpta("error","Ocurrió un error al guardar");
        }


    }
    

    return $this->setRpta("ok","Se procesó correctamente");
     
  }


protected function elimina_antiguos_proveedores($request){

  $medico = $request->identificacion;

  $cia = Auth::user()->empresa;


  DB::statement("DELETE FROM VEN_TERAPEUTAS_OTROS_RUC WHERE IDENTIFICACION=? AND NO_CIA=?",array($medico,$cia));



}

protected function obten_direccion_proveedor($request){

    $cia    = Auth::user()->empresa;

    $proveedor = $request->proveedor;

    


    $query = DB::select("SELECT DIRECCION AS DIRECCION, CASE WHEN ESTADO = 'ACT' THEN 'ACTIVO' ELSE 'INACTIVO' END ESTADO FROM cxp_proveedores WHERE NO_CIA = ? AND IDENTIFICACION=?",array($cia,$proveedor));

    

    $json = json_decode(json_encode($query),true);

    $direccion =  (isset($json[0]['direccion']))?$json[0]['direccion']:'';

    $estado =  (isset($json[0]['estado']))?$json[0]['estado']:'';

    return $direccion.'|'.$estado ;


  }

  protected function obten_direccion_clinica($request){

    $cia    = Auth::user()->empresa;

    $clinica = $request->codigo_clinica;

    
    $query = DB::select("SELECT DIRECCION FROM VEN_HOSPITALES WHERE  NO_CIA=? AND IDENTIFICACION=?",array($cia,$clinica));

    

    $json = json_decode(json_encode($query),true);

    $direccion =  (isset($json[0]['direccion']))?$json[0]['direccion']:'';

    return $direccion ;


  }


  protected function get_colectas_por_medico(Request $request){      

    $list = Doctores::get_colectas_por_medico($request);


    return response()->json($list);
    

  }

   protected function filter_terapeuta(Request $request){      

    $list = Doctores::filter_terapeuta($request);


    return response()->json($list);
    

  }

protected function get_full_doctoresq(Request $request){      

    $list = Doctores::get_full_doctoresq($request);


    return response()->json($list);
    

  }

  

  protected function get_cabecera_lprecio_medico(Request $request){      

    $list = Doctores::get_cabecera_lprecio_medico($request);


    return response()->json($list);
    

  }

  

  protected function listar_otros_proveedores_ruc_medicos(Request $request){      

    $list = Doctores::listar_otros_proveedores_ruc_medicos($request);

    return response()->json($list);
    

  }

  protected function filter_proveedor(Request $request){      

    $list = Doctores::filter_proveedor($request);


    return response()->json($list);
    

  }




  
   protected function filter_articulos_doctor(Request $request){      

    $list = Doctores::filter_articulos_doctor($request);


    return response()->json($list);
    

  }


  

     protected function get_info_medico_especialidad(Request $request){      

    $list = Doctores::get_info_medico_especialidad($request);


    return response()->json($list);
    

  }
  
  protected function list_medicos_asociados_lista_precio(Request $request){      

    $list = Doctores::list_medicos_asociados_lista_precio($request);

  
    return response()->json($list);
    

  }
  
  protected function valida_precios_lista_doctor($request){

     //1 es nuevo , 0 ya existe
   
      $flag = $request->flag_nuevo_edita;

      $dni= trim($request->identificacion);

      if($flag == 0){

          $list = Doctores::get_cabecera_lprecio_medico($request);

          if(count($list)>0){

             return $this->setRpta("ok","validó correctamente");
          }

           return $this->setRpta("error","El médico identificado con : ".$dni." no tiene lista de precio");
      }

      return $this->setRpta("ok","validó correctamente");

  }

  protected function guardar_nuevo_doctor(Request $request){      

    $valida = $this->valida_nuevo_doctor($request);

    

    if($valida['status']=="ok"){


      $valida_precios = $this->valida_precios_lista_doctor($request);

      if($valida_precios["status"]=="ok"){


          $rpta = Doctores::guardar_nuevo_doctor($request);

          if($rpta==1){

              return $this->setRpta("ok","Se procesó correctamente");

          }
      
          return $this->setRpta("error","Ocurrió un error al procesar");

      }

      return $valida_precios;

        

    }

    return $valida;

  
  }


  protected function valida_nuevo_doctor($request){

   //1 es nuevo , 0 ya existe
   
    $flag = $request->flag_nuevo_edita;

    if($flag == 0 ){

        return $this->setRpta("ok","valido correctamente");

    }else{

        $rpta = Doctores::valida_duplicado_doctor($request);


        if($rpta == 1 ){

          return $this->setRpta("error","El N° de documento ya se encuentra registrado");

        }else{

          return $this->setRpta("ok","valido correctamente");

        }

    }

  }


   protected function elimina_clinica_hospital(Request $request){      

    $rpta = Doctores::elimina_clinica_hospital($request);


    if($rpta == 1){

       
       return $this->setRpta("ok","Se eliminó correctamente");
    }
    

     return $this->setRpta("error","No se pudo eliminar el registro");
  }

   protected function guardar_nuevo_mpago_medico(Request $request){      

    $rpta = Doctores::guardar_nuevo_mpago_medico($request);


    if($rpta == 1){

       
       return $this->setRpta("ok","Se procesó correctamente");
    }
    

     return $this->setRpta("error","No se pudo grabar el registro");
  }



   protected function guardar_especialidad_medico(Request $request){      

    $tabla = $request->tabla_clinicas;

    $rows = count($tabla);

    if($rows > 0){


        foreach($tabla as $list){

        $data = array("IDENTIFICACION"=>$request->identificacion,
                    "CLINICA"=>$list['CODIGO'],
                    "DIRECCION"=>$list['DIRECCION'],
                    "ESPECIALIDAD"=>$request->especialidad,
                    "NACIMIENTO"=>$request->nacimiento,
                    "ACTIVIDAD"=>$request->actividad,
                    "CELULAR"=>$request->celular,
                    "COLECTA"=>$request->calidad,
                    "CLASIFICACION"=>$request->clasificacion,
                    "CAPTADOR"=>$request->captador
                    );

        $rpta = Doctores::guardar_especialidad_medico($data);

        if($rpta==0){

            return $this->setRpta("error","No se pudo guardar el registro");

        }

      }

    
      return $this->setRpta("ok","Se procesó correctamente");


    }else{


        $data = array("IDENTIFICACION"=>$request->identificacion,
                    "CLINICA"=>null,
                    "DIRECCION"=>null,
                    "ESPECIALIDAD"=>$request->especialidad,
                    "NACIMIENTO"=>$request->nacimiento,
                    "ACTIVIDAD"=>$request->actividad,
                    "CELULAR"=>$request->celular,
                    "COLECTA"=>$request->calidad,
                    "CLASIFICACION"=>$request->clasificacion,
                    "CAPTADOR"=>$request->captador
                    );

        $rpta = Doctores::guardar_especialidad_medico($data);

         if($rpta==0){

            return $this->setRpta("error","No se pudo guardar el registro unitario");

        }else{

          return $this->setRpta("ok","Se procesó correctamente");
        }


    }
    

     
  }


protected function eliminaDoctorAsociado(Request $request){      

    $rpta = Doctores::eliminaDoctorAsociado($request);


    if($rpta == 1){

  
       return $this->setRpta("ok","Se elimió correctamente");
    }
    

     return $this->setRpta("error","Ocurrió un error al eliminar");
  }

  protected function guardar_nueva_secretaria_medico(Request $request){      

    $rpta = Doctores::guardar_nueva_secretaria_medico($request);


    if($rpta == 1){

  
       return $this->setRpta("ok","Se procesó correctamente");
    }
    

     return $this->setRpta("error","Ocurrió un error al registrar");
  }





 protected function valida_nuevo_articulo_medico(Request $request){      

    $rpta = Doctores::valida_nuevo_articulo_medico($request);


    if($rpta == 0){

       $data=$this->set_moneda_monto_articulo_medico($request);

       return $this->setRpta("ok","valido correctamente",$data);
    }
    

     return $this->setRpta("error","El articulo ya existe en la tabla");
  }


protected function valida_nuevo_medico_medico(Request $request){      

    $rpta = Doctores::valida_nuevo_medico_medico($request);


    if($rpta == 0){

      

       return $this->setRpta("ok","valido correctamente");
    }
    

     return $this->setRpta("error","El Médico ya existe en la tabla");
  }



  protected function set_moneda_monto_articulo_medico($request){



    $cia    = Auth::user()->empresa;

    $articulo = $request->articulo;


    $query = DB::select("SELECT  T0.MONEDA, T0.COSTO_INDIVIDUAL MONTO FROM VEN_TERAPEUTAS_ESPECIALIDADES T0 INNER JOIN INV_ARTICULOS T1 ON T0.NO_CIA = T1.NO_CIA AND T0.CODIGO_ARTICULO = T1.CODIGO_ARTICULO WHERE T0.NO_CIA = ? AND  T0.CODIGO_ARTICULO = ?",array($cia,$articulo));



    $json = json_decode(json_encode($query),true);

    $moneda =  (isset($json[0]['moneda']))?$json[0]['moneda']:'';

    $monto =  (isset($json[0]['monto']))?$json[0]['monto']:'';

    return $moneda.'/'.$monto  ;


  }



    protected function guarda_nuevo_sublistaprecio(Request $request){      

    
    $cia = Auth::user()->empresa;

    $doctor = $request->doctor;

    $articulo = $request->articulo;

    $comparte_array = $request->lista;

    $comparte = $comparte_array[0]['COMPARTE_CON'];

    $costo = $comparte_array[0]['COSTO_INDIVIDUAL'];

     $rpta = \DB::update("UPDATE VEN_TERAPEUTAS_ESPECIALIDADES SET COMPARTE_CON=? ,COSTO_COMPARTIDO=? WHERE NO_CIA=? AND CODIGO_ARTICULO=? AND IDENTIFICACION=?",array($comparte,$costo,$cia,$articulo,$doctor));

     if($rpta ==1){


         return $this->setRpta("ok","Se actualizó correctamente");
     }

      return $this->setRpta("error","No se pudo guardar el registro");
  }


  protected function guarda_nuevo_lista_cabecera_precios(Request $request){      

    

    $list = $request->items;

    if(count($list) ==0 ){

      return $this->setRpta("error","No hay registros a guardar");

    }else{

      

      $descripcion_list ='';

      foreach($list as $values){

        if(empty($values['MONTO'])){

         

          $descripcion_list = $values["ARTICULO"];

        }
        

      }

      if(!empty($descripcion_list)){

        return $this->setRpta("error","El monto en la lista ".$descripcion_list." es oglitatorio.");

      }
    }
   
    $cia = Auth::user()->empresa;

    

    foreach($list as $values){

      if($values["BASEDATOS"]==1){
        //actualizamos
        

        $rpta = \DB::update("UPDATE VEN_TERAPEUTAS_ESPECIALIDADES SET MONEDA=? ,COSTO_INDIVIDUAL=? WHERE NO_CIA=? AND CODIGO_ARTICULO=? AND IDENTIFICACION=?",array($values["MONEDA"],$values["MONTO"],$cia,$values["CODIGO"],$request->doctor));

      }else{

        //insertamos
        
        $rpta = \DB::insert("INSERT INTO VEN_TERAPEUTAS_ESPECIALIDADES(NO_CIA,IDENTIFICACION,CODIGO_ARTICULO,MONEDA,COSTO_INDIVIDUAL)VALUES(?,?,?,?,?)",array($cia,$request->doctor,$values["CODIGO"],$values["MONEDA"],$values["MONTO"]));

       
      }


        if($rpta == 0){

            return $this->setRpta("error","No se pudo guardar el registro");

        }

      

    }

    
  
     return $this->setRpta("ok","Se agregó correctamente");

     
  }


  

}