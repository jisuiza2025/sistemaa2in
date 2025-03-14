<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\User;
use Auth;
use Peru\Jne\DniFactory;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Mantenimiento\CorreoController;

class UsuarioController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
  	
	
	 public function index(){      

    $middleRpta = $this->valida_url_permisos(29);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

	 	$empresa_user = Auth::user()->empresa;

	 	$cargos = Maestro::list_ocupacion();


		return View('usuario.index',compact('empresa_user','cargos'));

	}

	 
	protected function list_usuarios(Request $request){      

		

		$list = User::list_usuarios($request);

		return response()->json($list);

	}


	protected function get_item_usuario(Request $request){      

		if($request->dni==0){

			$list =array(array('IDENTIFICACION'=>'',
								'NOMBRES'=>'',
								'APEPAT'=>'',
								'APEMAT'=>'',
								'DIRECCION'=>'',
								'TELEFONO'=>'',
								'EMAIL'=>'',
								'PASSWORD'=>'',
								'CARGO'=>'',
                'FOTO'=>'profiles/default.jpg',
								'FLAG_ACTIVO'=>1,
                'FLAG_FACTURACION'=>'ANU',
                'CODIGO'=>''


                ));
		}else{

			$list = User::get_item_usuario($request);
		}

		

		return response()->json($list);

	}
	
	
protected function envia_correo_nuevo_usuario($request,$random){



    if(empty($request->vm_usuario_password)){



       $cia = Auth::user()->empresa;

       $config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();


       $parametros = array(

                      "cia"          => $cia,
                      "config"       => $config,
                      "request"      => $request,
                      "random"       => $random,
                      "destinatarios" =>$request->email
                  );


       $correo = new CorreoController;

       return $correo->envia_correo_nuevo_usuario($parametros);


    }

     return $this->setRpta("ok","");


}

	protected function save_usuario(Request $request){      

		DB::beginTransaction();

        try {

           $valida_usuario = $this->valida_usuario($request,'U');
           
           if($valida_usuario["status"] == "ok"){

                $rpta = User::save_usuario($request);
            
                if($rpta[0] == 1){

                    $middleRpta = $this->envia_correo_nuevo_usuario($request,$rpta[1]);

                        
                    DB::commit();

                    return $this->setRpta("ok","Se procesó correctamente , ".$middleRpta["description"]);

                }
          
                DB::rollback();

                return $this->setRpta("error","Ocurrió un error");

           }

           return $valida_usuario;
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }

		

	}

	

	protected function api_item_usuario(Request $request){      

		

		$data = User::get_item_usuario($request);

        if(count($data) == 0){

          return $this->search_api($request);

        }

        return $this->setRpta("ok","Consulta ejecutada exitosamente",$data);

	}

	public function search_api($request) 
   {  

      $dni = trim($request->dni);

      require '../vendor/autoload.php';
    
      $factory = new DniFactory();

      $cs = $factory->create();

      $person = $cs->get($dni);

      if (!$person) {
        
          return $this->setRpta("error","No se encontraron registros");

      }else{

        
      	$data =array(array('IDENTIFICACION'=>$dni,
								'NOMBRES'=>$person->nombres,
								'APEMAT'=>$person->apellidoMaterno,
								'APEPAT'=>$person->apellidoPaterno,
								'DIRECCION'=>'',
								'TELEFONO'=>'',
								'EMAIL'=>'',
								'PASSWORD'=>'',
								'CARGO'=>'',
                'FOTO'=>'profiles/default.jpg',
								'FLAG_ACTIVO'=>1,
                'FLAG_FACTURACION'=>"ANU",
                'CODIGO'=>''

                ));
      

        return $this->setRpta("ok","Consulta Exitosa de Reniec",$data);
      }

    
       

        
    }


	protected function valida_usuario($request,$type){
       

        //$empresa = Auth::user()->empresa;

        $rules = [
            
           'vm_usuario_dni'=> 'required',
           'vm_usuario_nombre'=> 'required',
           'vm_usuario_apepat'=> 'required',
           'vm_usuario_apemat'=> 'required',
           'vm_usuario_codigo'=>'required',
           //'vm_usuario_correo'=> 'required|email|unique:users,email,'.$request->vm_usuario_dni,
           'email' => ['required', 'email', Rule::unique('users')->ignore($request->vm_usuario_dni, 'identificacion')],
           'vm_usuario_cargo'=> 'required'
           
            
        ];

      
        //validacion contraseña , al menos 8 digitos ,al menos 1 mayuscula , al menos 1 caracter en especial
        
        if($type == 'P'){

              $id = $request->vm_id_user;

             $old_password = User::find($id)->password;

            if($old_password!=$request->vm_usuario_password){

                  $rules['vm_usuario_password'] = [
                    'required',
                    'string',
                    'min:8',            
                    'regex:/[A-Z]/',     
                    'regex:/[a-z]/',     
                    'regex:/[0-9]/',    
                    'regex:/[@$!%*?&#^()\{\}\[\]:;<>,.?~_+\-=|\\\\]/'
                  ];

               

            }


        }

      


              $messages = [
          'vm_usuario_codigo.required' => 'El Código es obligatorio.',
          'vm_usuario_dni.required' => 'El N° de documento es obligatorio.',
            'vm_usuario_nombre.required' => 'El Nombre es obligatorio.',
            'vm_usuario_apepat.required' => 'El A.Paterno es obligatorio.',
            'vm_usuario_apemat.required' => 'El A.Materno es obligatorio.',
            'email.required' => 'El Correo es obligatorio.',
            'email.email' => 'Ingrese un correo valido.',
            'vm_usuario_cargo.required' => 'El Cargo es obligatorio.',
            'email.unique' => 'El Correo ya se encuentra registrado.',

            'vm_usuario_password.required' => 'La contraseña es obligatoria.',
            'vm_usuario_password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'vm_usuario_password.regex' => 'La contraseña debe incluir al menos una letra mayúscula, una minúscula, un número y un carácter especial.'
            
            
        ];




         $validate = \Validator::make($request->all(),$rules,$messages);

         if ($validate->fails())
         {   
            
            return $this->setRpta("warning",$this->msgValidator($validate),$validate->messages() );

         }
        

        return $this->setRpta("ok","valido inputs usuario");


    }




    protected function perfil($id){      

    

        $usuario = User::find($id);

        $cargos = Maestro::list_ocupacion();


        return View('mantenimiento.perfil.index',compact('usuario','cargos'));

    

  }


  protected function salvar_profile(Request $request){      

    

    $valida_usuario = $this->valida_usuario($request,'P');
           
      if($valida_usuario["status"] == "ok"){

          $rpta = User::salvar_profile($request);
            
            if($rpta == 1){

                   
              return $this->setRpta("ok","Se Actualizó correctamente su perfil");

            }
          
            
            return $this->setRpta("error","Ocurrió un error");

      }

    return $valida_usuario;

  }
  
    
}

