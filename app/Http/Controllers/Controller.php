<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\User;
use App\Botones;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setRpta($status,$description,$data=NULL){

        return array("status"=>$status,"description"=>$description,"data"=>$data);

    }

    public function msgValidator( $validator )
    {
        $rpta = '';
        foreach( $validator->messages()->all() as $msg )
        {
            $rpta .= $msg." ";
        }
        return substr( $rpta , 0 , -1 );
    }

     public function redireccion_404(){

        return redirect('/pagina_no_encontrada');

    }


     public function mailCriocord_Laboratorio(){


        

        return array("HOST"     =>'smtp.office365.com',
                     "PUERTO"   => 587,
                     "CORREO"   => 'reportes@criocord.com.pe',
                     "PASSWORD"  => 'wgdnqstpndqhttfy',
                     "ENCRIPTACION"=> 'tls');

      
       


    }

    public function mailLazoVida_Laboratorio(){


         return array("HOST"     =>'smtp.office365.com',
                     "PUERTO"   => 587,
                     "CORREO"   => 'reportes@lazodevida.com.pe',
                     "PASSWORD"  => 'fgfbbzfjkjhjpgwr',
                     "ENCRIPTACION"=> 'tls');



    }

    public function mailCriocord(){


        

        return array("HOST"     =>"smtp.office365.com",
                     "PUERTO"   => 587,
                     "CORREO"   => "alertas@criocord.com.pe",
                     "PASSWORD"  => "syfdkthzgtjlnfvf",
                     "ENCRIPTACION"=> "tls");

        

      
       


    }

     public function mailLazoVida(){


       return array("HOST"     =>'smtp.office365.com',
                     "PUERTO"   => 587,
                     "CORREO"   => 'alertas@lazodevida.com.pe',
                      "PASSWORD"  => 'lnfgcygsnbhdqkxc',
                     "ENCRIPTACION"=> 'tls');



    }

    public function obtener_mes_actual_espanol(){


        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        return $meses[date('n')-1];

    }


     public function botones_usuario($boton){


        $cia = Auth::user()->empresa;

        $usuario = Auth::user()->codigo;

         

        $botones = Botones::where([['USUARIO',$usuario],['NO_CIA',$cia]])->get()->toArray();

        
        $data = array();


        foreach ($botones as  $value) {
            
            $data[] = $value;
        }

        if(isset($data[0])){

            return ($data[0][$boton] == 1)?true:false;

        }else{

            return false;

        }
         
    }

    public function generaRandomString($n=10){


        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
  
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        
  
        return $randomString;
    }

    public function valida_url_permisos($opcion){

        $permisos = new User;

        $string_opciones = $permisos->get_permisos_opciones();
      
        $opciones_permitidas = explode(",",$string_opciones);

       
        if(!in_array($opcion, $opciones_permitidas)){

           return $this->setRpta('error','pagina no encontrada');
        }

        return $this->setRpta('ok','valido permiso');
        
    }
}
