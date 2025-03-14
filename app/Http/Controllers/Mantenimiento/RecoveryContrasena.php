<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;

class RecoveryContrasena extends Controller
{	
	
  

   public function  generateRandomStringPass($length) {

        $upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowerCase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialChars = '@$!%*?&';

       
        $randomString = '';
        $randomString .= $upperCase[random_int(0, strlen($upperCase) - 1)];
        $randomString .= $lowerCase[random_int(0, strlen($lowerCase) - 1)];
        $randomString .= $numbers[random_int(0, strlen($numbers) - 1)];
        $randomString .= $specialChars[random_int(0, strlen($specialChars) - 1)];

        
        $allChars = $upperCase . $lowerCase . $numbers . $specialChars;
        for ($i = 4; $i < $length; $i++) {
            $randomString .= $allChars[random_int(0, strlen($allChars) - 1)];
        }

        
        return str_shuffle($randomString);
    }



   
   public function restablecer_contrasena(Request $request) 
   {	

   			$cia = $request->cia;

        $email = $request->email;

        $valida_usuario = DB::select("SELECT * FROM USERS WHERE EMPRESA=? AND EMAIL=? AND FLAG_ACTIVO=?",array($cia,$email,1));


        if(count($valida_usuario) == 0){

          return redirect("/password/reset")->with("error","El correo no se encuentra registrado");


        }else{

          $config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();

          
          
          $host         = $config['HOST'];
          $puerto       = $config['PUERTO'];
          $encriptacion = $config['ENCRIPTACION'];
          $from         = $config['CORREO'];
          $password     = $config['PASSWORD'];

        
      
          $full_name = strtoupper($valida_usuario[0]->name);

       

          $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

          $mailer = new Swift_Mailer($transport);


          $empresa = ($cia == '001')?'CRIOCORD':'LAZO DE VIDA';

          $random = $this->generateRandomStringPass(8);

          $mensaje=array( 

            'cabezera' => 'Estimado Colaborador : '.$full_name ,
            'cuerpo' => 'Se envia sus credenciales para el ingreso al sistema , contraseña : '.$random,
            'piePagina' => 'Sistema automatizado de envio de correos - '.$empresa

          );

          $body = array('information'=> $mensaje);

          
         

          $message   = (new Swift_Message('ENVIO DE NUEVAS CREDENCIALES'))
              ->setFrom($from, $empresa)
              ->setTo($email)
              ->setBody(view('correos.plantilla_nuevas_credenciales', $body)->render(),'text/html');

            
          
         

          if($mailer->send($message)>0){

              
              $pass_hash = bcrypt($random);

              //$id_user = $valida_usuario[0]->id;

              $id_user = $valida_usuario[0]->identificacion;

              //DB::update("UPDATE USERS SET PASSWORD =? WHERE ID =?",array($pass_hash,$id_user));

              DB::update("UPDATE USERS SET PASSWORD =? WHERE IDENTIFICACION =?",array($pass_hash,$id_user));


              return redirect("/password/reset")->with("success","Se acaba de reestablecer su contraseña , revise su correo");

          }else{

              return redirect("/password/reset")->with("error","No se pudo enviar el correo electrónico");

          }


        }

        

        



      
    }


    
 
    
}