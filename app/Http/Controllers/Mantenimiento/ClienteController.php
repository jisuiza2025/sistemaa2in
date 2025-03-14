<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\Cliente;
use Auth;
use Peru\Jne\DniFactory;


class ClienteController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
	{      


    $middleRpta = $this->valida_url_permisos(2);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


		$categorias = Maestro::list_categoria();

		$documentos = Maestro::list_tipo_documento();

		$ecivil 	= Maestro::list_estado_civil();

		$ocupacion 	= Maestro::list_ocupacion();

		$paises = Maestro::list_paises();

		$departamentos = Maestro::list_departamento();

		$empresa_user = Auth::user()->empresa;

		
    $btn_nuevo = $this->botones_usuario('mant_clientes_nuevo');


		return View('mantenimiento.cliente.index',compact('categorias','documentos','ecivil','ocupacion','paises','departamentos','empresa_user','btn_nuevo'));
		

	}	
	
	 protected function list_cliente(Request $request){      

		$list = Cliente::list_cliente($request);


		return response()->json($list);
		

	}


  protected function set_complementos_cliente(Request $request){      

    $list = Cliente::set_complementos_cliente($request);


    return response()->json($list);
    

  }

  
  protected function cliente_list_get_categoria(Request $request){      

    $list = Cliente::cliente_list_get_categoria($request);


    return response()->json($list);
    

  }

	protected function save_cliente(Request $request){

		DB::beginTransaction();

        try {

           $valida_cliente = $this->valida_cliente($request);
           
           if($valida_cliente["status"] == "ok"){

                $rpta = Cliente::save_cliente($request);
            
                if($rpta == 1){

                    DB::commit();

                    return $this->setRpta("ok","Se procesó correctamente");

                }
          
                DB::rollback();

                return $this->setRpta("error","Ocurrió un error");

           }

           return $valida_cliente;
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }


	}

	protected function valida_cliente($request){
        
        
        $rules = [
            
           'vm_documento'=> 'required',
           'vm_ndocumento'=> 'required',
           'vm_tcliente'=> 'required',
           'vm_pais'=> 'required',
           //'vm_ocupacion'=> 'required',
           'vm_residencia'=> 'required',
           'vm_sexo'=> 'required',
           'vm_ecivil'=> 'required',
           //'vm_nacimiento'=> 'required',
           'vm_estado'=> 'required',
           // 'selectedubigeo'=> 'required'
           'selected_ubigeo'=> 'required'
           
           
           
        ];


        $messages = [

        	  'vm_documento.required' => 'El Tipo de documento es obligatorio.',
            'vm_ndocumento.required' => 'El N° de documento es obligatorio.',
            'vm_tcliente.required' => 'El Tipo de cliente es obligatorio.',
            'vm_pais.required' => 'El Pais es obligatorio.',
            //'vm_ocupacion.required' => 'La Ocupación es obligatoria.',
            'vm_residencia.required' => 'La Residencia es obligatoria.',
            'vm_sexo.required' => 'El Género es obligatorio.',
            'vm_ecivil.required' => 'El Estado Civil es obligatorio.',
            //'vm_nacimiento.required' => 'La Fecha de Nacimiento es obligatoria.',
            'vm_estado.required' => 'El Estado es obligatorio.',
            // 'selectedubigeo.required' => 'El Ubigeo es obligatorio.'
            'selected_ubigeo.required' => 'El Ubigeo es obligatorio.'
            
        ];

        //documentos con nombres vs ruc
        
        if($request->vm_documento =='RC'){

          $rules = array_merge($rules,['vm_razonsocial'=> 'required',
                                       'vm_nombrecomercial'=> 'required']);

          $messages = array_merge($messages,
            ['vm_razonsocial.required' => 'La Razón Social es obligatoria.',
             'vm_nombrecomercial.required' => 'El Nombre Comercial es obligatorio.']);

        }else{

          $rules = array_merge($rules,['vm_apepat' => 'required',
                                       //'vm_apemat' => 'required',
                                       'vm_nombres'=> 'required']);

          $messages = array_merge($messages,
            ['vm_apepat.required' => 'El Apellido Paterno es obligatorio.',
              //'vm_apemat.required' => 'El Apellido Materno es obligatorio.',
              'vm_nombres.required' => 'El Nombre es obligatorio.']);
          
        }

        

         $validate = \Validator::make($request->all(),$rules,$messages);

         if ($validate->fails())
         {   
            
            return $this->setRpta("warning",$this->msgValidator($validate),$validate->messages() );

         }
        
        


        return $this->setRpta("ok","valido inputs cliente");


    }


     protected function search_cliente(Request $request){      

        $data = Cliente::search_cliente($request);

        if(count($data) == 0){

          return $this->search_api($request);

        }

        return $this->setRpta("ok","Consulta ejecutada exitosamente",$data);
    

    }


    protected function get_item_cliente(Request $request){      


        if($request->documento == '0'){

          //nuevo cliente
          $list = array(array("NO_CIA"=>'',
                             "IDENTIFICACION"=>'',
                             "CODIGO_DOCUMENTO"=>'',
                             "APATERNO"=>'',
                             "AMATERNO"=>'',
                             "NOMBRE_CORTO"=>'',
                             "NOMBRE"=>'',
                             "ALIAS"=>'',
                             "DIRECCION"=>'',
                             "REFERENCIA_DE_UBICACION"=>'',
                             "LOCALIDAD"=>'',
                             "CODIGO_PAIS"=>'',
                             "UBIGEO"=>'',
                             "OCUPACION"=>'',
                             "TELEFONO_CONTACTO"=>'',
                             "CELULAR_CONTACTO"=>'',
                             "MAIL_CONTACTO"=>'',
                             "TELEFONO_CONTACTO2"=>'',
                             "CELULAR_CONTACTO2"=>'',
                             "MAIL_CONTACTO2"=>'',
                             "TELEFONO_CONTACTO3"=>'',
                             "CELULAR_CONTACTO3"=>'',
                             "MAIL_CONTACTO3"=>'',
                             "FECHA_NACIMIENTO"=>'',
                             "FECHA_INGRESO"=>'',
                             "ESTADO"=>'',
                             "SEXO"=>'',
                             "ESTADO_CIVIL"=>'',
                             "OBSERVACIONES"=>'',
                             "CLASE_CLIENTE"=>'',
                             "INUBICABLE"=>'N',
                             "EDAD"=>'',
                             "FULL_UBIGEO"=>''
                           )
                );

        }else{

          $list = Cliente::search_cliente($request);

        }
        

        return response()->json($list);

       
    }


    public function search_api($request) 
   {  

      $dni = trim($request->documento);

      require '../vendor/autoload.php';
    
      $factory = new DniFactory();

      $cs = $factory->create();

      $person = $cs->get($dni);

      if (!$person) {
        
          return $this->setRpta("error","No se encontraron registros");

      }else{

        

        $cia = (Auth::user()->empresa == 1)?'001':'002';

        $data = array(array("NO_CIA" => $cia ,
                      "IDENTIFICACION" => $dni,
                      "CODIGO_DOCUMENTO" => 'DI',
                      "APATERNO" => $person->apellidoPaterno,
                      "AMATERNO" => $person->apellidoMaterno,
                      "NOMBRE_CORTO" => $person->nombres,
                      "NOMBRE" => '',
                      "ALIAS" => '',
                      "DIRECCION" => '',
                      "REFERENCIA_DE_UBICACION" => '',
                      "LOCALIDAD" => '',
                      "CODIGO_PAIS" => '604',
                      "UBIGEO" => '',
                      "OCUPACION" => '',
                      "TELEFONO_CONTACTO" => '',
                      "CELULAR_CONTACTO" => '',
                      "MAIL_CONTACTO" => '',
                      "TELEFONO_CONTACTO2" => '',
                      "CELULAR_CONTACTO2" => '',
                      "MAIL_CONTACTO2" => '',
                      "TELEFONO_CONTACTO3" => '',
                      "CELULAR_CONTACTO3" => '',
                      "MAIL_CONTACTO3" => '',
                      "FECHA_NACIMIENTO" => '',
                      "FECHA_INGRESO" => '',
                      "ESTADO" => '',
                      "SEXO" => '',
                      "ESTADO_CIVIL" => '',
                      "OBSERVACIONES" => '',
                      "CLASE_CLIENTE" => '',
                      "INUBICABLE" => 'N',
                      "EDAD"=>'',
                      "FULL_UBIGEO"=>''))

                ;


        return $this->setRpta("ok","Consulta Exitosa de Reniec",$data);
      }

    
       

        
    }

    protected function desactiva_cliente(Request $request){      

        $rpta = Cliente::desactiva_cliente($request);


        if($rpta == 1){

           return $this->setRpta("ok","Se desactivó sastisfactoriamente");

        }
      
         return $this->setRpta("error","Ocurrió un error");
    }

    

    protected function salvar_plan_aseguradora_cl(Request $request){      

        $rpta = Cliente::salvar_plan_aseguradora_cl($request);


        if($rpta == 1){

           return $this->setRpta("ok","Se procesó sastisfactoriamente");

        }
      
         return $this->setRpta("error","Ocurrió un error");
    }


    protected function cliente_aseguradora(Request $request){      

        $list = Cliente::cliente_aseguradora($request);

        return response()->json($list);
         
    }

}

