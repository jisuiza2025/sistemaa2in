<?php


namespace App\Http\Controllers\Captadores; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Maestro;
use App\Captadores\Captador;
use Carbon\Carbon;
use App\Imports\ModelExcelCaptacion;

use App\Imports\ModelExcelCaptacionValida;

use Maatwebsite\Excel\Facades\Excel;


class CaptadoresController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    


    public function reportes_incentivos(){

      $middleRpta = $this->valida_url_permisos(74);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


     $empresa_user = Auth::user()->empresa;

 

     $lsta_servicios = Maestro::cmb_servicios_filtro();

      return View('captadores.reports.incentivo',compact('empresa_user','lsta_servicios'));


    }


    protected function get_reporte_incentivo(Request $request){


        $list = Captador::get_reporte_incentivo($request);

      return response()->json($list);


    }

      public function validar_captaciones(){

     
     $middleRpta = $this->valida_url_permisos(68);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


     $empresa_user = Auth::user()->empresa;

 

     $lsta_servicios = Maestro::cmb_servicios_filtro();

      return View('captadores.registro.validar',compact('empresa_user','lsta_servicios'));

    }

    public function viewIndivual(){

     
     $middleRpta = $this->valida_url_permisos(49);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


     $empresa_user = Auth::user()->empresa;

     $usuario =  Auth::user()->codigo;

     $ultimos_registros = Captador::top_10_registros_captacion_usuario($usuario);


     $departamentos = Maestro::list_departamento();

     $captacion = Maestro::list_captacion();

     $automatico = Captador::get_servicios_captador();

     

     $lsta_servicios = Maestro::cmb_servicios_filtro();

      return View('captadores.registro.individual',compact('empresa_user','ultimos_registros','captacion','departamentos','automatico','lsta_servicios'));

    }  


    protected function listar_top_10(Request $request){


         $usuario =  Auth::user()->codigo;

      $list = Captador::top_10_registros_captacion_usuario($usuario);

      return response()->json($list);
    }

    public function viewMasiva(){


      $middleRpta = $this->valida_url_permisos(50);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }
     
     $empresa_user = Auth::user()->empresa;

     $captacion = Maestro::list_captacion();

     $departamentos = Maestro::list_departamento();


     $automatico = Captador::get_servicios_captador();


  

     $lsta_servicios = Maestro::cmb_servicios_filtro();


      return View('captadores.registro.masiva',compact('empresa_user','captacion','departamentos','automatico','lsta_servicios'));

    }


    

    public function viewMarketing(){

     
     $empresa_user = Auth::user()->empresa;

      return View('captadores.registro.individual',compact('empresa_user'));

    } 

    

    protected function salvar_carga_validaciones_captaciones($array,$cia,$servicio,$token){

      $rows='';

       for($i = 0; $i < count($array); $i++){


            $dni = trim($array[$i][0]);

            $nombres = trim($array[$i][1]);

             $apellidos = trim($array[$i][2]);
 
            $celular = trim($array[$i][3]);

             $correo = trim($array[$i][4]);


             $dni= (empty($dni))?' ':$dni;
             $nombres= (empty($nombres))?' ':$nombres;
             $apellidos= (empty($apellidos))?' ':$apellidos;
             $celular= (empty($celular))?' ':$celular;
             $correo= (empty($correo))?' ':$correo;

         $rows.= $dni.','.$nombres.','.$apellidos.','.$celular.','.$correo.'|';


       }


            $rpta = Captador::salvar_carga_validaciones_captaciones($rows,$cia,$servicio,$token);

            return $rpta ;


    }


    protected function upload_file_excel_valida_captaciones(Request $request){

         DB::beginTransaction();

        try {

         
            if ($request->file('file')) {

           

              $ext  = strtolower($request->file('file')->getClientOriginalExtension()); 

              

              if($ext == "xlsx"){

                 $dir = 'captacion_temporal_excel';

                 $fileName = str_random() . '.' . $ext;

                 $request->file('file')->move($dir, $fileName);

                 $file_path = public_path().'/captacion_temporal_excel/'.$fileName;
      
                 

                  $import = new ModelExcelCaptacionValida;

                  Excel::import($import, $file_path);
                  
                  $array = $import->getArray();




                  $errores = '';

                  $ciaselect = $request->ciaselect ;
                 
                 $servicio = $request->servicio ;


                  if(count($array) == 0){

                     return $this->setRpta("error","No hay elementos a importar" );

                  }


                    if(empty($ciaselect)){

                     return $this->setRpta("error","Seleccione una compañia" );

                  }


                    if(empty($servicio)){

                     return $this->setRpta("error","Seleccione un servicio" );

                  }

                 for($i = 0; $i < count($array); $i++){

                      
                      
                      $nombre = trim($array[$i][1]);

                    

                      $celular = trim($array[$i][3]);

                      $correo = trim($array[$i][4]);
                      




                    

                      if(empty($nombre)){

                           $errores.= 'EL CAMPO NOMBRE EN LA FILA N°'.($i+3).' DEBE SER OBLIGATORIO|';
                        

                      }



                     

                      if(empty($celular) && empty($correo)){

                         $errores.= 'LOS CAMPOS CELULAR Y CORREO EN LA FILA N°'.($i+3).' ALGUNO ES OBLIGATORIO|';
                      }


                    
                   

                 }



                 if (file_exists($file_path)) {
          
                    unlink($file_path);
                  }




                 if($errores!=''){

                    $data = explode('|',$errores);

                    return $this->setRpta("warning","Existen las siguientes observaciones" ,$data);

                 }
                

                $now = Carbon::now()->format('Y-m-d H:i:s');

                $token =sha1( md5( uniqid( $now, true ) ) );

                $split = array_chunk($array,20);

                 foreach($split as $sub){

                     

                        $rpta = $this->salvar_carga_validaciones_captaciones($sub,$ciaselect,$servicio,$token);

                        if($rpta != 1){

                           DB::rollback();

                          return $this->setRpta("error",'Ocurrió un error al guardar');


                          

                        }

                       




                   }


                //$middleLoad = $this->salvar_carga_validaciones_captaciones($array,$ciaselect,$servicio);

                 DB::commit();

                 $middleLoad = $this->get_list_carga_masiva_by_token($token);

                return $this->setRpta("ok","Cargo correctamente el archivo" ,$middleLoad);

              }else{

                  DB::rollback();

                  return $this->setRpta("error","No es un archivo excel" );
              }

          }else{

              DB::rollback();
              return $this->setRpta("error","No hay un archivo cargado" );
          }

        } catch (\Exception $e) {
          

          DB::rollback();

          return $this->setRpta("error",$e->getMessage());
      }

        

     }


     protected function get_list_carga_masiva_by_token($token){


     return Captador::get_list_carga_masiva_by_token($token);


     }

    protected function descargar_ficheros_captacion($fichero){

      if($fichero =='masiva'){

         $pathto = public_path().'/captacion_temporal_excel/plantilla_captacion_masiva.xlsx';
        
        if (file_exists($pathto)) {
            
            return response()->download($pathto);

        } else {
        
            return $this->redireccion_404();
        }

      }elseif($fichero =='validacion'){


         $pathto = public_path().'/captacion_temporal_excel/plantilla_captacion_validaciones.xlsx';
        
        if (file_exists($pathto)) {
            
            return response()->download($pathto);

        } else {
        
            return $this->redireccion_404();
        }


      }

    }

    protected function valida_medico_clinica($list){

      $rows = '';

      

      foreach($list as $value){

       

         $institucion = (empty($value[0]))?'0':trim($value[0]);
         $medico = (empty($value[1]))?'0':trim($value[1]);

        
         $rows .= $institucion.','.$medico.'|';

        
        

      }


      $rows = rtrim($rows,'|');

     
    

      $rpta = Captador::valida_medico_clinica($rows);


      if(!empty($rpta)){

        return $this->setRpta("error",$rpta);
      }

      return $this->setRpta("ok",'validó correctamente');
    }


    protected function registro_captacion_masiva_guardar(Request $request){

      DB::beginTransaction();

        try {
            

            $list = $request->data;

            //validamos todo la lista para terapeutas y hospitales
            
            $valida_med_clinica = $this->valida_medico_clinica($list);



            if($valida_med_clinica["status"] == "ok"){


                //enviamos x grupo 20
            
                $split = array_chunk($list,20);

                

                 foreach($split as $sub){

                     $rpta = Captador::registro_captacion_masiva_guardar($request,$sub);

                      if($rpta != 1){

                         DB::rollback();

                        return $this->setRpta("error",'Ocurrió un error al guardar');


                        

                      }

                     




                 }


                  DB::commit();

                  return $this->setRpta("ok",'Se procesó de manera correcta' ); 


            }else{

              return $valida_med_clinica;

            }
            

           
           

        }catch (\Exception $e) {
          

          DB::rollback();

          return $this->setRpta("error",$e->getMessage());
      }
    }


     protected function upload_file_excel_carga_masiva_captacion(Request $request){


        if ($request->file('file')) {

         

            $ext  = strtolower($request->file('file')->getClientOriginalExtension()); 

            

            if($ext == "xlsx"){

               $dir = 'captacion_temporal_excel';

               $fileName = str_random() . '.' . $ext;

               $request->file('file')->move($dir, $fileName);

               $file_path = public_path().'/captacion_temporal_excel/'.$fileName;
    
               

                $import = new ModelExcelCaptacion;

                Excel::import($import, $file_path);
                
                $array = $import->getArray();




                $errores = '';




               for($i = 0; $i < count($array); $i++){

                    
                    $institucion = trim($array[$i][0]);
                    
                    $dni_doc = trim($array[$i][1]);

                    $nombre = trim($array[$i][2]);

                    $dni = trim($array[$i][5]);

                    $celular = trim($array[$i][6]);

                    $correo = trim($array[$i][7]);
                    

                    $fecha_parto = trim($array[$i][8]);



                    
                    // if(!empty($institucion)){

                    //   //valida numero y guion
                      

                    //   if(!is_numeric($institucion)){

                    //       $errores.= 'EL CAMPO RUC_INSTITUCION EN LA FILA N°'.($i+3).' DEBE SER UN RUC VALIDO|';
                    //   }
                      

                    // }

                    if(!empty($dni_doc)){

                      if(!is_numeric($dni_doc)){

                          $errores.= 'EL CAMPO DNI_MEDICO EN LA FILA N°'.($i+3).' DEBE SER UN DNI VALIDO|';
                      }
                      

                    }



                    if(empty($nombre)){

                      $errores.= 'EL CAMPO NOMBRE EN LA FILA N°'.($i+3).' ES OBLIGATORIO|';

                    }

                    if(empty($celular) && empty($correo)){

                       $errores.= 'EL CAMPO CELULAR Y CORREO EN LA FILA N°'.($i+3).' ALGUNO ES OBLIGATORIO|';
                    }


                    $duplicidad = Captador::valida_duplicidad_registro_individual($celular,$dni,$correo);

                    if(!empty($duplicidad)){

                         $errores.= $duplicidad.' ,EN LA FILA N°'.($i+3).'|';

                    }
                 

               }



               if (file_exists($file_path)) {
        
                  unlink($file_path);
                }




               if($errores!=''){

                  $data = explode('|',$errores);

                  return $this->setRpta("warning","Existen las siguientes observaciones" ,$data);

               }
         
              return $this->setRpta("ok","Cargo correctamente el archivo" ,$array);

            }else{

                return $this->setRpta("error","No es un archivo excel" );
            }

        }else{


            return $this->setRpta("error","No hay un archivo cargado" );
        }

     }



    protected function valida_registro_captacion($request){
       
        $anualidad = $request->flag_anualidad;


        

        $rules = [
            
           
           'registroFullName'=> 'required',

           //'vm_registro_captacion'=> 'required',
           //'vm_registro_ficha'=> 'required',
           //'vm_ultima_ficha'=> 'required',

           
           //'registroCelular'=> 'required',
           
           //'registroCorreo'=> 'email'
           
          

           
                      
            
        ];

        $messages = [

         
             'registroFullName.required' => 'Ingrese un Nombre.',
             'registroCelular.required' => 'Ingrese un Celular.',
             'registroCorreo.required' => 'Ingrese un Correo.',
             'registroCorreo.email' => 'Ingrese un Correo Valido.',

               //'vm_registro_captacion.required' => 'Seleccione un medio.',
              //'vm_registro_ficha.required' => 'Seleccione un tipo.',  'vm_ultima_ficha.required' => 'Seleccione una ficha.'
            

            
                 
            
        ];

        
         if($anualidad==0){

          $rules = array_merge($rules,[
            'vm_registro_captacion'=> 'required',
            'vm_registro_ficha'=> 'required',
            'vm_ultima_ficha'=> 'required'



            ]);

            $messages = array_merge($messages,

              ['vm_registro_captacion.required' => 'Seleccione un medio.',
                'vm_registro_ficha.required' => 'Seleccione un tipo.',
                'vm_ultima_ficha.required' => 'Seleccione una ficha.',



              ]);

        }



        if($anualidad==1){

          $rules = array_merge($rules,['cliente_ref'=> 'required']);

          $messages = array_merge($messages,

            ['cliente_ref.required' => 'Seleccione un Cliente.']);

        }


         $validate = \Validator::make($request->all(),$rules,$messages);

         $validate->sometimes('registroCelular', 'required', function($input)
          {
            return empty($input->registroCorreo);
          });

         $validate->sometimes('registroCorreo', 'required|email', function($input)
          {
            return empty($input->registroCelular);
          });



         if ($validate->fails())
         {   
            
          

            return $this->setRpta("warning","Complete o Corrija los inputs marcados",$validate->messages() );

         }
        

       return $this->setRpta("ok",'valido inputs correctamente' ); 


    }



    protected function set_contactos_modal_captacion(Request $request)
  {      

    
    $list = Captador::set_contactos_modal_captacion($request);
    
    return response()->json($list);

  }
  

    protected function valida_duplicidad_registro_individual($request){


      $celular = trim($request->registroCelular);
      $dni     = trim($request->registroDNI);
      $correo  = trim($request->registroCorreo);


      $rpta = Captador::valida_duplicidad_registro_individual($celular,$dni,$correo);


       if(empty($rpta)){

           return $this->setRpta("ok",'validó correctamente' ); 

        }else{

           return $this->setRpta("error",$rpta ); 
        }


    }

    protected function salvar_registro_individual(Request $request){


      DB::beginTransaction();

      try {
        

        $valida_registro = $this->valida_registro_captacion($request);

        
        if($valida_registro["status"]=="ok"){

            
            

            $duplicidad = $this->valida_duplicidad_registro_individual($request);

            if($duplicidad["status"]=="ok"){


                  $rpta = Captador::salvar_registro_individual($request);

                  if($rpta == 1){

                      DB::commit();

                      return $this->setRpta("ok",'Se procesó de manera correcta' ); 

                  }


                  DB::rollback();
          
                  return $this->setRpta("error",'No se pudo guardar el registro');


            }
             
            return $duplicidad;

        }

        return $valida_registro;



      } catch (\Exception $e) {
          

          DB::rollback();

          return $this->setRpta("error",$e->getMessage());
      }
      
     
    
       

    } 



    

   
   




public function verCaptacion($token){

     
     $empresa_user = Auth::user()->empresa;

     $ultimos_registros =  Captador::datos_captacion_token_top_10($token);

     

     $data_captacion = Captador::datos_captacion_token($token);

      return View('captadores.registro.ver',compact('empresa_user','ultimos_registros','data_captacion'));

    } 
    


 public function viewDuplicados(){


  $middleRpta = $this->valida_url_permisos(51);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }
     
     $empresa_user = Auth::user()->empresa;

    

      return View('captadores.registro.duplicados',compact('empresa_user'));

    } 
    



 public function viewTracking(){


  $middleRpta = $this->valida_url_permisos(52);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

     
     $empresa_user = Auth::user()->empresa;

     //en produccion cambiar a 20
     
     
     $tipo_list = Maestro::get_cad_listado(20,'CAPTACIONTRK');


     

     $ver_todos = $this->botones_usuario('cap_track_ver');

      $ver_servicios = $this->botones_usuario('capt_tra_servicios');

      $departamentos = Maestro::list_departamento();

      $servicios_list = Maestro::cmb_servicios_filtro_all();

      return View('captadores.registro.tracking',compact('empresa_user','tipo_list','departamentos','ver_todos','ver_servicios','servicios_list'));

    } 
   


  

   protected function data_duplicado_valida_captacion(Request $request)
  {      

    
    $duplicada = Captador::data_duplicado_valida_captacion($request,1);
    
    $valida = Captador::data_duplicado_valida_captacion($request,0);

    $data = array($duplicada,$valida);

    return response()->json($data);

  }




protected function detalle_linea_tiempo(Request $request)
  {      

    
    $list = Captador::detalle_linea_tiempo($request);
    
    return response()->json($list);

  }

   protected function tracking_list(Request $request)
  {      

    
    $list = Captador::tracking_list($request);
    
    return response()->json($list);

  }

 protected function masiva_duplicados_list(Request $request)
  {      

    
    $list = Captador::masiva_duplicados_list($request);
    
    return response()->json($list);

  }

  protected function filter_captador_cod(Request $request)
  {      

    
    $list = Captador::filter_captador_cod($request);
    
    return response()->json($list);

  }



 protected function filter_captador_companias(Request $request)
  {      

    
    $list = Captador::filter_captador_companias($request);
    
    return response()->json($list);

  }



protected function confirmar_retiro_submit(Request $request){

$rpta = Captador::confirmar_retiro_submit($request);

            if($rpta == 1){

             

                return $this->setRpta("ok",'Se procesó de manera correcta' ); 

            }

            

            return $this->setRpta("error",'Ocurrió un error al guardar');

}



protected function confirmar_operacion_duplicado(Request $request){

try {
  
DB::beginTransaction();


  $data = $request->data;

  if(count($data)==0){

    return $this->setRpta("error",'No hay elementos a confirmar en la lista');

  }


  $split = array_chunk($data,20);

         
           foreach($split as $sub){


              $rpta = Captador::confirmar_operacion_duplicado($request,$sub);


              if($rpta != 1){

                
                  DB::rollback();

                  return $this->setRpta("error",'Ocurrió un error al guardar');
              }


           
            }


      
          
         DB::commit();
          return $this->setRpta("ok",'Se procesó de manera correcta' ); 

} catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }
     
  

    }

protected function confirmar_habilitacion_duplicado(Request $request){

     if(empty($request->comentario)){

        return $this->setRpta("error",'Ingrese un comentario');
     }



       $rpta = Captador::confirmar_habilitacion_duplicado($request);

            if($rpta == 1){

             

                return $this->setRpta("ok",'Se procesó de manera correcta' ); 

            }

            

            return $this->setRpta("error",'Ocurrió un error al guardar');

    }
  


     protected static function list_captacion(){

        $p1 = Auth::user()->empresa;

        
        $stmt = static::$pdo->prepare("begin WEB_PROSPECTOS_SEINFORMO(:p1, :c); end;");
        
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        

        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);
        
        $result =array();

        foreach ($list as $value) {
            
            $result[] = array("id"=>$value["SE_INFORMO"],"text"=>$value["DESCRIPCION"]);
        }
        
        return $result;

    
    }

  
   public function viewAsignaVendedor(){


    $middleRpta = $this->valida_url_permisos(53);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


     //$request = new Request();

     //$request->q="";

     //$captadores = Maestro::filter_captador($request);

    $captadores = Captador::filter_captador_por_servicios();

     $medios = Captador::list_captacion();

     $departamentos = Maestro::list_departamento();

    

     $vendedores = Captador::filter_vendedor_por_servicios();
     
     
     $criterios = Captador::criterios_evaluacion();

     $empresa_user = Auth::user()->empresa;

      return View('captadores.registro.vendedor',compact('empresa_user','captadores','medios',"departamentos","vendedores","criterios"));

    } 


    
    


 protected function reporte_asignacion_grafico(Request $request)
  {      
    


     $empresa_user = Auth::user()->empresa;

    $start = ($request->date[0] =='null'  ||  $request->date[0] == null)?'':Carbon::parse($request->date[0])->format('d/m/Y');

    $end  = ($request->date[1] =='null' ||  $request->date[1] == null)?'':Carbon::parse($request->date[1])->format('d/m/Y');


    $medios = Captador::graficos_medios_captacion($request);

    //$medios = array();

    
        


        $labels = array();
      
        $grupo1 = array();
        $grupo2 = array();
        $grupo3 = array();
        $grupo4 = array();
        $grupo5 = array();
        $grupo6 = array();
        $grupo7 = array();
        $grupo8 = array();

        foreach($medios as $key=>$values){

            
                $labels[] = "'".$values["VENDEDOR"]."'";


                $grupo1[] = $values["CLIENTE"];
                $grupo2[] = $values["CENTROS PRENATAL"];
                $grupo3[] = $values["PSICOPROFILAXIS"];
                $grupo4[] = $values["PUBLICIDAD EN CLINICA"];
                $grupo5[] = $values["PAGINA WEB"];
                $grupo6[] = $values["INSTAGRAM"];
                $grupo7[] = $values["REF. MEDICO O PERSONAL CLINICA"];
                $grupo8[] = $values["REFERIDO POR CLIENTE"];
               
        }   

        $str_label  =  implode(',',$labels);
        $str_grupo1 =  implode(',',$grupo1);
        $str_grupo2 =  implode(',',$grupo2);
        $str_grupo3 =  implode(',',$grupo3);
        $str_grupo4 =  implode(',',$grupo4);
        $str_grupo5 =  implode(',',$grupo5);
        $str_grupo6 =  implode(',',$grupo6);
        $str_grupo7 =  implode(',',$grupo7);
        $str_grupo8 =  implode(',',$grupo8);


        $labels_char ="[$str_label]";

        $grupo1_char ="[$str_grupo1]";
        $grupo2_char ="[$str_grupo2]";
        $grupo3_char ="[$str_grupo3]";
        $grupo4_char ="[$str_grupo4]";
        $grupo5_char ="[$str_grupo5]";
        $grupo6_char ="[$str_grupo6]";
        $grupo7_char ="[$str_grupo7]";
        $grupo8_char ="[$str_grupo8]";

    

          $chart = new \QuickChart(array(
            'width' => 600,
            'height' => 500
          ));

          $chart->setConfig("{
            type: 'horizontalBar',
            data: {
              labels: $labels_char,
              datasets: [
                {
                  label: 'CLIENTE',
                  backgroundColor: 'rgb(96, 125, 198)',
                  data: $grupo1_char,
                },
                {
                  label: 'CENTROS PRENATAL',
                  backgroundColor: 'rgb(254, 169, 38)',
                  data: $grupo2_char,
                },
                {
                  label: 'PSICOPROFILAXIS',
                  backgroundColor: 'rgb(173, 169, 163 )',
                  data: $grupo3_char,
                },
                {
                  label: 'PUBLICIDAD EN CLINICA',
                  backgroundColor: 'rgb(251, 233, 47 )',
                  data: $grupo4_char,
                },
                {
                  label: 'PAGINA WEB',
                  backgroundColor: 'rgb(29, 255, 190 )',
                  data: $grupo5_char,
                }, {
                  label: 'INSTAGRAM',
                  backgroundColor: 'rgb(106, 109, 109)',
                  data: $grupo6_char,
                }, {
                  label: 'REF. MEDICO O PERSONAL CLINICA',
                  backgroundColor: 'rgb(0, 16, 128 )',
                  data: $grupo7_char,
                },
                 {
                  label: 'REFERIDO POR CLIENTE',
                  backgroundColor: 'rgb(138, 81, 48 )',
                  data: $grupo8_char,
                }
              ],
            },
            options: {
              title: {
                display: true,
                text: 'POR MEDIO DE CAPTACION',
              },
              scales: {
                xAxes: [
                  {
                    stacked: true,
                  },
                ],
                yAxes: [
                  {
                    stacked: true,
                  },
                ],
              },
            },
          }");

          $url = $chart->getUrl();


          //segundo grafico
    

          //$grupos = array();

          $grupos = Captador::graficos_grupos_captacion($request);
          
         
          $labels_2 = array();

          $sub_grupo1 = array();
          $sub_grupo2 = array();
          $sub_grupo3 = array();

          foreach($grupos as $key=>$values){

            
                $labels_2[] = "'".$values["VENDEDOR"]."'";


                $sub_grupo1[] = $values["GRUPO1"];
                $sub_grupo2[] = $values["GRUPO2"];
                $sub_grupo3[] = $values["GRUPO3"];
             
               
        }   



        $str_label_2  =  implode(',',$labels_2);
        $str_grupo1_1 =  implode(',',$sub_grupo1);
        $str_grupo2_2 =  implode(',',$sub_grupo2);
        $str_grupo3_3 =  implode(',',$sub_grupo3);
       

        $labels_char_2 ="[$str_label_2]";

        $grupo1_char1 ="[$str_grupo1_1]";
        $grupo2_char2 ="[$str_grupo2_2]";
        $grupo3_char3 ="[$str_grupo3_3]";


          $chart2 = new \QuickChart(array(
            'width' => 600,
            'height' => 500
          ));

          $chart2->setConfig("{
            type: 'horizontalBar',
            data: {
              labels: $labels_char_2,
              datasets: [
                {
                  label: 'Grupo 1',
                  backgroundColor: 'rgb(96, 125, 198)',
                  data: $grupo1_char1,
                },
                {
                  label: 'Grupo 2',
                  backgroundColor: 'rgb(254, 169, 38)',
                  data: $grupo2_char2,
                },
                {
                  label: 'Grupo 3',
                  backgroundColor: 'rgb(173, 169, 163 )',
                  data: $grupo3_char3,
                }
                
              ],
            },
            options: {
              title: {
                display: true,
                text: 'POR GRUPO',
              },
              scales: {
                xAxes: [
                  {
                    stacked: true,
                  },
                ],
                yAxes: [
                  {
                    stacked: true,
                  },
                ],
              },
            },
          }");

          $url2 = $chart2->getUrl();


         //$pdf = \App::make('dompdf.wrapper');
        //$pdf->setPaper('A4','landscape');
        //$pdf->loadView('captadores.reports.vendedores', compact('empresa_user','url','url2'));

        //$random = $this->generaRandomString(10);
        
        //$file = public_path().'/captadores_reporte/'.$random.'.pdf';

        //$pdf->save($file);

        //return $random.'.pdf';

        return array($url,$url2);


  }

      protected function set_asignaciones_vendedor(Request $request)
  {      

    
    $list = Captador::set_asignaciones_vendedor($request);
    
    return response()->json($list);

  }

      protected function vendedor_list(Request $request)
  {      

    
    $list = Captador::vendedor_list($request);
    
    return response()->json($list);

  }


      protected function confirmar_asignaciones_vendedor(Request $request)
  {      


    try {
        

        DB::beginTransaction();
        

   

       $tabla = $request->data;

       if(count($tabla) == 0){

          return $this->setRpta("error",'No hay datos a procesar' ); 
       }


        //enviamos x lote
   

          $split = array_chunk($tabla,20);

         
           foreach($split as $sub){


              $rpta = Captador::confirmar_asignaciones_vendedor($request,$sub);


              if($rpta != 1){

                
                  DB::rollback();

                  return $this->setRpta("error",'Ocurrió un error al guardar');
              }


           
            }


          

            
             DB::commit();

                return $this->setRpta("ok",'Se procesó de manera correcta' );

          




    } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }
   

    
   
    
   

  }

  



 


  public function reportes_periodo_cia(){


    $middleRpta = $this->valida_url_permisos(62);

          if($middleRpta["status"] != "ok"){

              return $this->redireccion_404();
          }

      $empresa_user = Auth::user()->empresa;
      


      $servicios_permisos = $this->botones_servicios('captacion');

    

       $cia_permisosx =  $this->botones_cias('captacion');

        
      $cia_permisos =array();

       $todos = array("id"=>'null',"text"=>"TODOS");

        array_push($cia_permisos, $todos);
        
        foreach ($cia_permisosx as $value) {
            
            $cia_permisos[] = array("id"=>$value["id"],"text"=>$value["text"]);
        }

     



       $all_servicios =Maestro::cmb_servicios_filtro_all();
          
      return View('captadores.reports.periodo_cia',compact('empresa_user','servicios_permisos','cia_permisos','all_servicios'));

    } 


 // public function botones_servicios($type){

 //      $codigo = Auth::user()->codigo;

 //      $cia = Auth::user()->empresa;

 //      $text =[];

 //      if($type=='captacion'){

 //       $query= DB::select("SELECT REP1_CAP_SER FROM BOTONES_USUARIOS WHERE NO_CIA=? AND USUARIO=?",array($cia,$codigo));


 //          $tx = (isset($query[0]->rep1_cap_ser))?$query[0]->rep1_cap_ser:null ;

 //          if(!empty($tx)){

 //              $x = explode(",",$tx);

 //              foreach( $x  as $val){

 //                if($val=='S'){
 //                  $label = 'SANGRE';
 //                }

 //                 if($val=='T'){
 //                  $label = 'TEJIDO';
 //                }

 //                 if($val=='D'){
 //                  $label = 'PULPA';
 //                }
 //                $text[] = array('id'=>$val,'text'=>$label);


 //              }

 //          }

 //      }


 //      return $text;

 //    }

    public function botones_servicios($type){

      $codigo = Auth::user()->codigo;

      $cia = Auth::user()->empresa;

      $text = null;

      if($type=='captacion'){

       $query= DB::select("SELECT REP1_CAP_SER FROM BOTONES_USUARIOS WHERE NO_CIA=? AND USUARIO=?",array($cia,$codigo));


          $text = (isset($query[0]->rep1_cap_ser))?$query[0]->rep1_cap_ser:null ;


      }


      return $text;

    }


    public function botones_cias($type){


     $codigo = Auth::user()->codigo;

      $cia = Auth::user()->empresa;

      $text =[];

      if($type=='captacion'){

       $query= DB::select("SELECT REP1_CAP_CIA FROM BOTONES_USUARIOS WHERE NO_CIA=? AND USUARIO=?",array($cia,$codigo));

      
          $tx = (isset($query[0]->rep1_cap_cia))?$query[0]->rep1_cap_cia:null ;

          if(!empty($tx)){

              $x = explode(",",$tx);

              foreach( $x  as $val){

                if($val=='001'){
                  $label = 'CRIOCORD';
                }

                 if($val=='002'){
                  $label = 'LAZO DE VIDA';
                }

              
                $text[] = array('id'=>$val,'text'=>$label);


              }

          }

      }


      return $text;


    }


     public function reportes_cia_estado(){


      $middleRpta = $this->valida_url_permisos(63);

            if($middleRpta["status"] != "ok"){

                return $this->redireccion_404();
            }

     
      $empresa_user = Auth::user()->empresa;
          
      $servicios_permisos = $this->botones_servicios('captacion');

    

       $cia_permisos =  $this->botones_cias('captacion');

        
         $all_servicios =Maestro::cmb_servicios_filtro_all();

          return View('captadores.reports.cia_estado',compact('empresa_user','servicios_permisos','cia_permisos','all_servicios'));

    } 



     public function reportes_cia_medio(){


        $middleRpta = $this->valida_url_permisos(64);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

      $empresa_user = Auth::user()->empresa;


        $servicios_permisos = $this->botones_servicios('captacion');

    

       $cia_permisos =  $this->botones_cias('captacion');
      

      $all_servicios =Maestro::cmb_servicios_filtro_all();
      
      //var_dump($all_servicios);
      //die();
          return View('captadores.reports.cia_medio',compact('empresa_user','servicios_permisos','cia_permisos','all_servicios'));

    } 






public function report_get_cia_medio(Request $request){


      $list = Captador::report_get_cia_medio($request);

   

      $dataset = [];
      $dataset2 = [];
     
      foreach($list as $key=>$values){

            
                $dataset[] = "'".$values["MEDIO_CAPTACION"]."'";
                $dataset2[] =$values["CANTIDAD"];
               
        
               
        }   



        $label  =  implode(',',$dataset);
         $str  =  implode(',',$dataset2);
      
       

        $label ="[$label]";


          $str ="[$str]";
      

        
        $chart2 = new \QuickChart(array(
            'width' => 800,
            'height' => 500
          ));

          $chart2->setConfig("{
  type: 'horizontalBar',
  data: {
    datasets: [
      {
        label:'',
        data: $str,
        backgroundColor: [
          'rgb(195,123,255)',
          'rgb(9,203,118)',
          'rgb(106,0,78)',
          'rgb(120,87,242)',
          'rgb(118,230,255)',
          'rgb(203,203,203)',
          'rgb(255,144,144)',
           'rgb(255,147,226)',
            'rgb(147,255,162)'
          
        ],
      },
    ],
    labels: $label,
  },
  options: {
    scales:{

      yAxes: [
        {
          ticks: {
            fontSize: 9,
             fontColor: 'black',

          },
        },
      ],

      },
    plugins: {
      datalabels: {
         font: {
          size: 14,

        },
         align: 'end',
         offset: 200,
       formatter: (value, ctx) => {
          let sum = 0;
          let dataArr = ctx.chart.data.datasets[0].data;
          dataArr.map(data => {
            sum += data;
          });
          let percentage = (value*100 / sum).toFixed(2);
          
          let st = value  ;
          
          return  st;
        },
         color: 'black',
         
      },
    },
  },
}");

          $url = $chart2->getUrl();

          return $url;

    }



public function report_get_periodo_cia(Request $request){


      $list = Captador::report_get_periodo_cia($request);

    
      $dataset = [];
      $dataset2 = [];
     
      foreach($list as $key=>$values){

            
                $dataset[] = "'".$values["NOMBRE_CORTO"]."'";
                $dataset2[] =$values["CANTIDAD"];
               
        
               
        }   



        $label  =  implode(',',$dataset);
         $str  =  implode(',',$dataset2);
      
       

        $label ="[$label]";


          $str ="[$str]";
      

        
        $chart2 = new \QuickChart(array(
            'width' => 550,
            'height' => 450
          ));

          $chart2->setConfig("{
  type: 'doughnut',
  data: {
    datasets: [
      {
        data: $str,
        backgroundColor: [
           'rgb(127,107,220)',
           'rgb(255,144,144)',
          'rgb(97,109,255)',
          'rgb(106,0,78)',
          'rgb(120,87,242)',
          'rgb(118,230,255)',
          'rgb(203,203,203)',
          
          
        ],
      },
    ],
    labels: $label,
  },
  options: {
    plugins: {
      datalabels: {
        formatter: (value, ctx) => {
          let sum = 0;
          let dataArr = ctx.chart.data.datasets[0].data;
          dataArr.map(data => {
            sum += data;
          });
          let percentage = (value*100 / sum).toFixed(2);
          
          let st = value +'('+ percentage +'%)' ;
          
          return  st;
        },
         color: 'black',
      },
    },
  },
}");

          $url = $chart2->getUrl();

          return $url;

    }


    public function report_get_cia_estados(Request $request){


      $list = Captador::report_get_cia_estados($request);

      $dataset = [];
      $dataset2 = [];
     

     //var_dump($list);
     //die();

      foreach($list as $key=>$values){

              
              if((int)$values["CANTIDAD"]>0){

                 $dataset[] = "'".$values["ESTADO"]."'";
                $dataset2[] =$values["CANTIDAD"];

              }
                //$dataset[] = "'".$values["ESTADO"]."'";
                //$dataset2[] =$values["CANTIDAD"];
               

               //quitamos cantidades 0 
               //
               

        
               
        }   



        $label  =  implode(',',$dataset);
         $str  =  implode(',',$dataset2);
      
       

        $label ="[$label]";


          $str ="[$str]";
      


        $chart2 = new \QuickChart(array(
            'width' => 550,
            'height' => 550
          ));

          $chart2->setConfig("{
  type: 'doughnut',
  data: {
    datasets: [
      {
        data: $str,
        backgroundColor: [
          'rgb(195,123,255)',
          'rgb(9,203,118)',
          'rgb(106,0,78)',
          'rgb(120,87,242)',
          'rgb(118,230,255)',
          'rgb(203,203,203)',
          'rgb(255,144,144)'
          
        ],
      },
    ],
    labels: $label,
  },
  options: {
    plugins: {
      datalabels: {
        formatter: (value, ctx) => {
          let sum = 0;
          let dataArr = ctx.chart.data.datasets[0].data;
          dataArr.map(data => {
            sum += data;
          });
          let percentage = (value*100 / sum).toFixed(2);
          
          let st = value +'('+ percentage +'%)' ;
          
          return  st;
        },
          color: 'black',
        anchor: 'start',
        align: 'end',
      offset: 40,
        rotation: -45,
      },


    },

  },

   
}"

);

          $url = $chart2->getUrl();

          return $url;

    }



    public function reportes_cia_vendedor(){


        $middleRpta = $this->valida_url_permisos(66);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

      $empresa_user = Auth::user()->empresa;


        $servicios_permisos = $this->botones_servicios('captacion');

    

       $cia_permisos =  $this->botones_cias('captacion');
      

      $all_servicios =Maestro::cmb_servicios_filtro_all();
      
     
          return View('captadores.reports.cia_vendedor',compact('empresa_user','servicios_permisos','cia_permisos','all_servicios'));

    } 



    public function reportes_cia_captador(){


        $middleRpta = $this->valida_url_permisos(67);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

      $empresa_user = Auth::user()->empresa;


        $servicios_permisos = $this->botones_servicios('captacion');

    

       $cia_permisos =  $this->botones_cias('captacion');
      

      $all_servicios =Maestro::cmb_servicios_filtro_all();
      
     
          return View('captadores.reports.cia_captador',compact('empresa_user','servicios_permisos','cia_permisos','all_servicios'));

    }





    public function report_get_cia_vendedor(Request $request){


      $list = Captador::report_get_cia_vendedor($request);

     

      $dataset = [];
      $dataset2 = [];
     
      foreach($list as $key=>$values){

            
                $dataset[] = "'".$values["VENDEDOR"]."'";
                $dataset2[] =$values["CANTIDAD"];
               
        
               
        }   



        $label  =  implode(',',$dataset);
         $str  =  implode(',',$dataset2);
      
       

        $label ="[$label]";


          $str ="[$str]";
      

        
        $chart2 = new \QuickChart(array(
            'width' => 800,
            'height' => 500
          ));

          $chart2->setConfig("{
  type: 'horizontalBar',
  data: {
    datasets: [
      {
        label:'',
        data: $str,
        backgroundColor: [
          'rgb(195,123,255)',
          'rgb(9,203,118)',
          'rgb(106,0,78)',
          'rgb(120,87,242)',
          'rgb(118,230,255)',
          'rgb(203,203,203)',
          'rgb(255,144,144)',
           'rgb(255,147,226)',
            'rgb(147,255,162)'
          
        ],
      },
    ],
    labels: $label,
  },
  options: {
    scales:{

      yAxes: [
        {
          ticks: {
            fontSize: 10
          },
        },
      ],

      },
    plugins: {
      datalabels: {
         font: {
          size: 14,
        },
         align: 'end',
         offset: 200,
       formatter: (value, ctx) => {
          let sum = 0;
          let dataArr = ctx.chart.data.datasets[0].data;
          dataArr.map(data => {
            sum += data;
          });
          let percentage = (value*100 / sum).toFixed(2);
          
          let st = value  ;
          
          return  st;
        },
         color: 'black',
         
      },
    },
  },
}");

          $url = $chart2->getUrl();

          return $url;

    } 



     public function report_get_cia_captador(Request $request){


      $list = Captador::report_get_cia_captador($request);

     

      $dataset = [];
      $dataset2 = [];
     
      foreach($list as $key=>$values){

            
                $dataset[] = "'".$values["CAPTADOR"]."'";
                $dataset2[] =$values["CANTIDAD"];
               
        
               
        }   



        $label  =  implode(',',$dataset);
         $str  =  implode(',',$dataset2);
      
       

        $label ="[$label]";


          $str ="[$str]";
      

        
        $chart2 = new \QuickChart(array(
            'width' => 800,
            'height' => 500
          ));

          $chart2->setConfig("{
  type: 'horizontalBar',
  data: {
    datasets: [
      {
        label:'',
        data: $str,
        backgroundColor: [
          'rgb(195,123,255)',
          'rgb(9,203,118)',
          'rgb(106,0,78)',
          'rgb(120,87,242)',
          'rgb(118,230,255)',
          'rgb(203,203,203)',
          'rgb(255,144,144)',
           'rgb(255,147,226)',
            'rgb(147,255,162)'
          
        ],
      },
    ],
    labels: $label,
  },
  options: {
    scales:{

      yAxes: [
        {
          ticks: {
            fontSize: 10
          },
        },
      ],

      },
    plugins: {
      datalabels: {
         font: {
          size: 14,
        },
         align: 'end',
         offset: 200,
       formatter: (value, ctx) => {
          let sum = 0;
          let dataArr = ctx.chart.data.datasets[0].data;
          dataArr.map(data => {
            sum += data;
          });
          let percentage = (value*100 / sum).toFixed(2);
          
          let st = value  ;
          
          return  st;
        },
         color: 'black',
         
      },
    },
  },
}");

          $url = $chart2->getUrl();

          return $url;

    } 
    
}