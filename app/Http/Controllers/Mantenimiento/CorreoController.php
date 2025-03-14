<?php


namespace App\Http\Controllers\Mantenimiento; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

use Auth;

use Swift_Mailer;
use Swift_SmtpTransport;
use Swift_Message;
use App\LogCorreos;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class CorreoController extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
    
   public function envia_informe_laboratorio_masiva($data)
   {

	    $host         = $data['config']['HOST'];
	    $puerto       = $data['config']['PUERTO'];
	    $encriptacion = $data['config']['ENCRIPTACION'];
	    $from         = $data['config']['CORREO'];
	    $password     = $data['config']['PASSWORD'];

	    $destinatarios = $data['destinatarios'];


	    $padre_copia = $data['copia'];

	    $servicio = $data['servicio'];


	    $empresa = ($data['cia'] == '001') ? 'Criocord' : 'Lazo de Vida';




	      $cliente_msj = "Estimada Familia :<strong>" . $data['cliente'] . "</strong>
	      <br><br> Desde " . $empresa . " los saludamos y felicitamos por el nacimiento de su bebé, agradeciendo así que nos elija para cuidar juntos su salud. Es parte fundamental de nuestra misión, mantenerlos informados sobre la criopreservación de las Células Madre de Sangre y Tejido de Cordón Umbilical (USCU y UTCU). Por lo tanto, le indicamos que hemos procesado y almacenado la muestra bajo los más altos estándares de calidad y en las mejores condiciones según Protocolos Internacionales.<br><br>
	      En el archivo adjunto, encontrarán la información del procesamiento de la USCU y la UTCU, celularidad de sus muestras y ubicación en nuestros tanques. Detalles necesarios en caso necesite utilizarlas en un futuro.<br><br>  
	      Sin otro particular, agradecemos la confianza depositada en nosotros y le damos nuevamente la bienvenida a la Familia " . $empresa . ".<br><br>
	      Cualquier duda o consulta adicional estaremos felices de ayudarlos.<br><br> 
	      Atentamente.";


	    if ($servicio == 'S') {

	        $cliente_msj = "Estimada Familia :<strong>" . $data['cliente'] . "</strong><br><br>
	        Desde " . $empresa . " los saludamos y felicitamos por el nacimiento de su bebé, agradeciendo así que nos elija para cuidar juntos su salud. Es parte fundamental de nuestra misión, mantenerlos informados sobre la criopreservación de las Células Madre de Sangre de Cordón Umbilical (USCU). Por lo tanto, le indicamos que hemos procesado y almacenado la muestra bajo los más altos estándares de calidad y en las mejores condiciones según Protocolos Internacionales. 
	        <br><br>
	        En el archivo adjunto, encontrarán la información del procesamiento de la USCU, celularidad de su muestra y ubicación en nuestro tanque. Detalle necesario en caso necesite utilizarlas en un futuro.<br><br> 
	        Sin otro particular, agradecemos la confianza depositada en nosotros y le damos nuevamente la bienvenida a la Familia " . $empresa . ".<br><br> 
	        Cualquier duda o consulta adicional estaremos felices de ayudarlos.<br><br> 
	        Atentamente,
	        ";
	    }

	    if ($servicio == 'T') {

	        $cliente_msj = "Estimada Familia :<strong>" . $data['cliente'] . "</strong><br><br>
	        Desde " . $empresa . " los saludamos y felicitamos por el nacimiento de su bebé, agradeciendo así que nos elija para cuidar juntos su salud. Es parte fundamental de nuestra misión, mantenerlos informados sobre la criopreservación de las Células Madre de Tejido de Cordón Umbilical (UTCU). Por lo tanto, le indicamos que hemos procesado y almacenado la muestra bajo los más altos estándares de calidad y en las mejores condiciones según Protocolos Internacionales. 
	        <br><br>
	        En el archivo adjunto, encontrarán la información del procesamiento de la UTCU, celularidad de su muestra y ubicación en nuestro tanque. Detalle necesario en caso necesite utilizarlas en un futuro. <br><br>
	        Sin otro particular, agradecemos la confianza depositada en nosotros y le damos nuevamente la bienvenida a la Familia " . $empresa . ".<br><br>
	        Cualquier duda o consulta adicional estaremos felices de ayudarlos.<br><br>
	        Atentamente,
	        ";
	    }

	    $correo_logeo = Auth::user()->email;
	    $loge_name = ucfirst(Auth::user()->nombres . ' ' . Auth::user()->apepat . ' ' . Auth::user()->apemat);


	    $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
	      ->setUsername($from)
	      ->setPassword($password);

	    $mailer = new Swift_Mailer($transport);

	    $url0 = "http://sistemas.criocord.com.pe:8088/cryoholdcosistemas/public/imagenes/firma_andrea.png";

	    $url1 = "http://sistemas.criocord.com.pe:8088/cryoholdcosistemas/public/imagenes/lazo_lab.png";

	    $url2 = "http://sistemas.criocord.com.pe:8088/cryoholdcosistemas/public/imagenes/crio_lab.png";


	    $contenidoImagen0 = file_get_contents($url0);
	    $contenidoImagen0 = base64_encode($contenidoImagen0);

	    $contenidoImagen0 = "data:image/png;base64," . $contenidoImagen0;

	    $contenidoImagen1 = file_get_contents($url1);
	    $contenidoImagen1 = base64_encode($contenidoImagen1);

	    $contenidoImagen1 = "data:image/png;base64," . $contenidoImagen1;

	    $contenidoImagen2 = file_get_contents($url2);
	    $contenidoImagen2 = base64_encode($contenidoImagen2);

	    $contenidoImagen2 = "data:image/png;base64," . $contenidoImagen2;


	    $logo_final = ($data['cia'] == '001') ? $contenidoImagen2 : $contenidoImagen1;

	    $mensaje = array(
	      'imagen0' => $contenidoImagen0,
	      'imagen_logo' => $logo_final,

	      'cabezera' => $cliente_msj,
	      'cuerpo' => '',
	      'piePagina' => '',
	      'cia' => $data['cia']

	    );

	    $body = array('information' => $mensaje);


	    $formato_pdf1 = $data['formato1'];

	    $formato_pdf2 = $data['formato2'];

	    $formato_pdf3 = $data['formato3'];


	    //valida ambiente produccion o test


	    if (!config("global.production")) {

	      $destinatarios = config("global.soporte");
	    }


	    $rayasta = ($data['cia'] == '001') ? 'gestiondecalidad@criocord.com.pe' : 'gestiondecalidad@lazodevida.com.pe';

	    //$destinatarios='jisuiza@criocord.com.pe';


	    //$destinatarios='miguel_94_14@outlook.com';
	    //$rayasta="these08@gmail.com";


	    $asunto_informe = "RESULTADOS DE LA CRIOPRESERVACION DE CELULAS MADRE - " . $data['cliente'] . " - " . $data['contrato'];


	    $serv = ($servicio == 'S') ? 'SANGRE_' . $data['contrato'] : 'TEJIDO' . $data['contrato'];

	    $tipo_envio = $data["tipo_envio"];

	    if ($tipo_envio == "sistema") {

	      $setFile1 = 'INFORME_LABORATORIO_' . $serv . '.pdf';
	      $setFile2 = 'INFORME_SEROLOGIA_' . $serv . '.pdf';
	    } else {

	      if (!empty($formato_pdf1)) {


	        $array_file = explode(".", $formato_pdf1);

	        $setFile1 = 'Adjunto1.' . $array_file[1];
	      }


	      if (!empty($formato_pdf2)) {


	        $array_file2 = explode(".", $formato_pdf2);

	        $setFile2 = 'Adjunto2.' . $array_file2[1];
	      }
	    }

	    if (!empty($formato_pdf3)) {
	      $array_file3 = explode(".", $formato_pdf3);
	      $setFile3 = 'CARTA DE INFORME.' . $array_file3[1];
	    }


	    // Crea el mensaje inicialmente
	    $message = (new Swift_Message($asunto_informe))
	      ->setFrom($from, $empresa)
	      ->setTo($destinatarios)
	      ->addBcc($correo_logeo, $loge_name, $rayasta)
	      ->setBody(view('correos.masiva_informe_laboratorio', $body)->render(), 'text/html');

	    // Adjunta $formato_pdf1 si no está vacío
	    if (!empty($formato_pdf1)) {
	      $message->attach(\Swift_Attachment::fromPath($formato_pdf1)->setFilename($setFile1));
	    }

	    // Adjunta $formato_pdf2 si no está vacío
	    if (!empty($formato_pdf2)) {
	      $message->attach(\Swift_Attachment::fromPath($formato_pdf2)->setFilename($setFile2));
	    }

	    // Adjunta $formato_pdf3 si no está vacío
	    if (!empty($formato_pdf3)) {
	      $message->attach(\Swift_Attachment::fromPath($formato_pdf3)->setFilename($setFile3));
	    }


	    foreach ($mensaje as $index => $datax) {

	      if ($datax == $contenidoImagen0) {
	        unset($mensaje[$index]);
	      }

	      if ($datax == $logo_final) {
	        unset($mensaje[$index]);
	      }
	    }

	    //return $this->setRpta("error",$formato_pdf2 );

	    if (!empty($padre_copia)) {
	      // Crea el mensaje inicialmente
	      $message2 = (new Swift_Message($asunto_informe))
	      ->setFrom($from, $empresa)
	      ->setTo($padre_copia)
	      ->addBcc($correo_logeo, $loge_name, $rayasta)
	      ->setBody(view('correos.masiva_informe_laboratorio', $body)->render(), 'text/html');

	      // Adjunta $formato_pdf1 si no está vacío
	      if (!empty($formato_pdf1)) {
	        $message2->attach(\Swift_Attachment::fromPath($formato_pdf1)->setFilename($setFile1));
	      }

	      // Adjunta $formato_pdf2 si no está vacío
	      if (!empty($formato_pdf2)) {
	        $message2->attach(\Swift_Attachment::fromPath($formato_pdf2)->setFilename($setFile2));
	      }

	      // Adjunta $formato_pdf3 si no está vacío
	      if (!empty($formato_pdf3)) {
	        $message2->attach(\Swift_Attachment::fromPath($formato_pdf3)->setFilename($setFile3));
	      }


	      foreach ($message2 as $index => $datax) {

	        if ($datax == $contenidoImagen0) {
	          unset($message2[$index]);
	        }

	        if ($datax == $logo_final) {
	          unset($message2[$index]);
	        }
	      }

	      $mailer->send($message2);
	    }


	    if ($mailer->send($message) > 0) {

	      if (file_exists($formato_pdf1)) {

	        unlink($formato_pdf1);
	      }


	      if (file_exists($formato_pdf2)) {

	        unlink($formato_pdf2);
	      }

	      if (file_exists($formato_pdf3)) {

	         unlink($formato_pdf3);
	      }


	      //envio al padre o correo copia en caso no este vacio , se le adjunta el informe de lab


	      // if (!empty($padre_copia)) {

	      //   $this->labo_padre_correo($data);
	      // }









	      LogCorreos::inserta(1, 'CONTRATACION/INFORME DE LABORATORIO', 'INFORME DE LABORATORIO', 'INFORME DE LABORATORIO', $mensaje, $data, $correo_logeo);

	      return $this->setRpta("ok", "Se envió el correo de manera satisfactoria");
	    } else {

	      LogCorreos::inserta(0, 'CONTRATACION/INFORME DE LABORATORIO', 'INFORME DE LABORATORIO', 'INFORME DE LABORATORIO', $mensaje, $data, $correo_logeo);

	      return $this->setRpta("error", "No se pudo enviar el correo");
	    }
  }


    public function envia_informe_laboratorio_masiva_bk($data)

    {

      $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $destinatarios = $data['destinatarios'];

        
        $padre_copia = $data['copia'];
       
       $servicio = $data['servicio'];


       $empresa = ($data['cia'] == '001')?'Criocord':'Lazo de Vida';

       
        
        
        $cliente_msj="Estimada Familia :<strong>".$data['cliente']."</strong>
      <br><br> Desde ".$empresa." los saludamos y felicitamos por el nacimiento de su bebé, agradeciendo así que nos elija para cuidar juntos su salud. Es parte fundamental de nuestra misión, mantenerlos informados sobre la criopreservación de las Células Madre de Sangre y Tejido de Cordón Umbilical (USCU y UTCU). Por lo tanto, le indicamos que hemos procesado y almacenado la muestra bajo los más altos estándares de calidad y en las mejores condiciones según Protocolos Internacionales.<br><br>
En el archivo adjunto, encontrarán la información del procesamiento de la USCU y la UTCU, celularidad de sus muestras y ubicación en nuestros tanques. Detalles necesarios en caso necesite utilizarlas en un futuro.<br><br>  
Sin otro particular, agradecemos la confianza depositada en nosotros y le damos nuevamente la bienvenida a la Familia ".$empresa.".<br><br>
Cualquier duda o consulta adicional estaremos felices de ayudarlos.<br><br> 
Atentamente.";


if($servicio =='S'){

        $cliente_msj="Estimada Familia :<strong>".$data['cliente']."</strong><br><br>
 Desde ".$empresa." los saludamos y felicitamos por el nacimiento de su bebé, agradeciendo así que nos elija para cuidar juntos su salud. Es parte fundamental de nuestra misión, mantenerlos informados sobre la criopreservación de las Células Madre de Sangre de Cordón Umbilical (USCU). Por lo tanto, le indicamos que hemos procesado y almacenado la muestra bajo los más altos estándares de calidad y en las mejores condiciones según Protocolos Internacionales. 
<br><br>
En el archivo adjunto, encontrarán la información del procesamiento de la USCU, celularidad de su muestra y ubicación en nuestro tanque. Detalle necesario en caso necesite utilizarlas en un futuro.<br><br> 
Sin otro particular, agradecemos la confianza depositada en nosotros y le damos nuevamente la bienvenida a la Familia ".$empresa.".<br><br> 
Cualquier duda o consulta adicional estaremos felices de ayudarlos.<br><br> 
Atentamente,
";
       }

       if($servicio =='T'){

        $cliente_msj="Estimada Familia :<strong>".$data['cliente']."</strong><br><br>
Desde ".$empresa." los saludamos y felicitamos por el nacimiento de su bebé, agradeciendo así que nos elija para cuidar juntos su salud. Es parte fundamental de nuestra misión, mantenerlos informados sobre la criopreservación de las Células Madre de Tejido de Cordón Umbilical (UTCU). Por lo tanto, le indicamos que hemos procesado y almacenado la muestra bajo los más altos estándares de calidad y en las mejores condiciones según Protocolos Internacionales. 
<br><br>
En el archivo adjunto, encontrarán la información del procesamiento de la UTCU, celularidad de su muestra y ubicación en nuestro tanque. Detalle necesario en caso necesite utilizarlas en un futuro. <br><br>
Sin otro particular, agradecemos la confianza depositada en nosotros y le damos nuevamente la bienvenida a la Familia ".$empresa.".<br><br>
Cualquier duda o consulta adicional estaremos felices de ayudarlos.<br><br>
Atentamente,
";
       }

        $correo_logeo = Auth::user()->email;
        $loge_name = ucfirst(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);
       

        $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);





        $url0="http://sistemas.criocord.com.pe:8088/cryoholdcosistemas/public/imagenes/firma_andrea.png";

        $url1="http://sistemas.criocord.com.pe:8088/cryoholdcosistemas/public/imagenes/lazo_lab.png";
        
        $url2="http://sistemas.criocord.com.pe:8088/cryoholdcosistemas/public/imagenes/crio_lab.png";


          $contenidoImagen0 = file_get_contents($url0);
            $contenidoImagen0 = base64_encode($contenidoImagen0);

            $contenidoImagen0 = "data:image/png;base64,".$contenidoImagen0;

              $contenidoImagen1 = file_get_contents($url1);
            $contenidoImagen1 = base64_encode($contenidoImagen1);

            $contenidoImagen1 = "data:image/png;base64,".$contenidoImagen1;

        $contenidoImagen2 = file_get_contents($url2);
            $contenidoImagen2 = base64_encode($contenidoImagen2);

            $contenidoImagen2 = "data:image/png;base64,".$contenidoImagen2;


            $logo_final = ($data['cia'] == '001')?$contenidoImagen2:$contenidoImagen1;

        $mensaje=array( 
            'imagen0'=>$contenidoImagen0,
            'imagen_logo'=>$logo_final,
          
            'cabezera' => $cliente_msj ,
            'cuerpo' => '',
            'piePagina' => '',
            'cia'=>$data['cia']

          );

          $body = array('information'=> $mensaje);

          
         $formato_pdf1 = $data['formato1'];

         $formato_pdf2 = $data['formato2'];

        
        //valida ambiente produccion o test
        

        if( !config("global.production") ){

             $destinatarios = config("global.soporte");

        }
         

        $rayasta = ($data['cia'] == '001')?'gestiondecalidad@criocord.com.pe':'gestiondecalidad@lazodevida.com.pe';

         //$destinatarios='jisuiza@criocord.com.pe';
         

        //$destinatarios='miguel_94_14@outlook.com';
        //$rayasta="these08@gmail.com";


         $asunto_informe = "RESULTADOS DE LA CRIOPRESERVACION DE CELULAS MADRE - ".$data['cliente']." - ".$data['contrato'];


         $serv = ($servicio=='S')?'SANGRE_'.$data['contrato']:'TEJIDO'.$data['contrato'];

         $tipo_envio = $data["tipo_envio"];

         if($tipo_envio=="sistema"){

           $setFile1 ='INFORME_LABORATORIO_'.$serv.'.pdf';
           $setFile2 ='INFORME_SEROLOGIA_'.$serv.'.pdf';

         }else{

              if(!empty($formato_pdf1)){


                  $array_file = explode(".",$formato_pdf1);

                  $setFile1 ='Adjunto1.'.$array_file[1];
              }


               if(!empty($formato_pdf2)){


                  $array_file2 = explode(".",$formato_pdf2);

                  $setFile2 ='Adjunto2.'.$array_file2[1];
              }

             
             
         }
        

        if(!empty($formato_pdf2) && !empty($formato_pdf1)){

       

          $message   = (new Swift_Message($asunto_informe))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name,$rayasta)
              ->setBody(view('correos.masiva_informe_laboratorio', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato_pdf1)->setFilename($setFile1))
              ->attach(\Swift_Attachment::fromPath($formato_pdf2)->setFilename($setFile2));

        }elseif(!empty($formato_pdf1)){

          $message   = (new Swift_Message($asunto_informe))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name,$rayasta)
              ->setBody(view('correos.masiva_informe_laboratorio', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato_pdf1)->setFilename($setFile1));
              

        }elseif(!empty($formato_pdf2)){

          $message   = (new Swift_Message($asunto_informe))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name,$rayasta)
              ->setBody(view('correos.masiva_informe_laboratorio', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato_pdf2)->setFilename($setFile2));
              

        }


        foreach ($mensaje as $index => $datax) {

              if ($datax == $contenidoImagen0 ) {
                  unset($mensaje[$index]);
              }

              if ($datax == $logo_final ) {
                  unset($mensaje[$index]);
              }
          }


        if($mailer->send($message)>0){

            if ( file_exists($formato_pdf1) ) {

                unlink($formato_pdf1);
            }


            if ( file_exists($formato_pdf2) ) {

                unlink($formato_pdf2);
            }


          
          //envio al padre o correo copia en caso no este vacio , se le adjunta el informe de lab
        

        if(!empty($padre_copia)){

          $this->labo_padre_correo($data);

        
        }

          

           





           LogCorreos::inserta(1,'CONTRATACION/INFORME DE LABORATORIO','INFORME DE LABORATORIO','INFORME DE LABORATORIO',$mensaje,$data,$correo_logeo);

          return $this->setRpta("ok","Se envió el correo de manera satisfactoria");

        }else{

           LogCorreos::inserta(0,'CONTRATACION/INFORME DE LABORATORIO','INFORME DE LABORATORIO','INFORME DE LABORATORIO',$mensaje,$data,$correo_logeo);

          return $this->setRpta("error","No se pudo enviar el correo");

        }
              



    }


    public function labo_padre_correo($data){


      $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $empresa = ($data['cia'] == '001')?'Criocord':'Lazo de Vida';

           $padre_copia = $data['copia'];

      $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);


         $asunto_informe = "RESULTADOS DE LA CRIOPRESERVACION DE CELULAS MADRE - ".$data['cliente']." - ".$data['contrato'];

        $formato_lab_estatico = $data["informe_estatico"];

        $correo_logeo = Auth::user()->email;
        $loge_name = ucfirst(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);

        $servicio=$data["servicio"];


        $cliente_msj="Estimada Familia :<strong>".$data['cliente']."</strong>
      <br><br> Desde ".$empresa." los saludamos y felicitamos por el nacimiento de su bebé, agradeciendo así que nos elija para cuidar juntos su salud. Es parte fundamental de nuestra misión, mantenerlos informados sobre la criopreservación de las Células Madre de Sangre y Tejido de Cordón Umbilical (USCU y UTCU). Por lo tanto, le indicamos que hemos procesado y almacenado la muestra bajo los más altos estándares de calidad y en las mejores condiciones según Protocolos Internacionales.<br><br>
En el archivo adjunto, encontrarán la información del procesamiento de la USCU y la UTCU, celularidad de sus muestras y ubicación en nuestros tanques. Detalles necesarios en caso necesite utilizarlas en un futuro.<br><br>  
Sin otro particular, agradecemos la confianza depositada en nosotros y le damos nuevamente la bienvenida a la Familia ".$empresa.".<br><br>
Cualquier duda o consulta adicional estaremos felices de ayudarlos.<br><br> 
Atentamente.";


if($servicio =='S'){

        $cliente_msj="Estimada Familia :".$data['cliente']."<br><br>
 Desde ".$empresa." los saludamos y felicitamos por el nacimiento de su bebé, agradeciendo así que nos elija para cuidar juntos su salud. Es parte fundamental de nuestra misión, mantenerlos informados sobre la criopreservación de las Células Madre de Sangre de Cordón Umbilical (USCU). Por lo tanto, le indicamos que hemos procesado y almacenado la muestra bajo los más altos estándares de calidad y en las mejores condiciones según Protocolos Internacionales. 
<br><br>
En el archivo adjunto, encontrarán la información del procesamiento de la USCU, celularidad de su muestra y ubicación en nuestro tanque. Detalle necesario en caso necesite utilizarlas en un futuro.<br><br> 
Sin otro particular, agradecemos la confianza depositada en nosotros y le damos nuevamente la bienvenida a la Familia ".$empresa.".<br><br> 
Cualquier duda o consulta adicional estaremos felices de ayudarlos.<br><br> 
Atentamente,
";
       }

       if($servicio =='T'){

        $cliente_msj="Estimada Familia :".$data['cliente']."<br><br>
Desde ".$empresa." los saludamos y felicitamos por el nacimiento de su bebé, agradeciendo así que nos elija para cuidar juntos su salud. Es parte fundamental de nuestra misión, mantenerlos informados sobre la criopreservación de las Células Madre de Tejido de Cordón Umbilical (UTCU). Por lo tanto, le indicamos que hemos procesado y almacenado la muestra bajo los más altos estándares de calidad y en las mejores condiciones según Protocolos Internacionales. 
<br><br>
En el archivo adjunto, encontrarán la información del procesamiento de la UTCU, celularidad de su muestra y ubicación en nuestro tanque. Detalle necesario en caso necesite utilizarlas en un futuro. <br><br>
Sin otro particular, agradecemos la confianza depositada en nosotros y le damos nuevamente la bienvenida a la Familia ".$empresa.".<br><br>
Cualquier duda o consulta adicional estaremos felices de ayudarlos.<br><br>
Atentamente,
";
       }



        $url0="http://sistemas.criocord.com.pe:8088/cryoholdcosistemas/public/imagenes/firma_andrea.png";

        $url1="http://sistemas.criocord.com.pe:8088/cryoholdcosistemas/public/imagenes/lazo_lab.png";
        
        $url2="http://sistemas.criocord.com.pe:8088/cryoholdcosistemas/public/imagenes/crio_lab.png";


          $contenidoImagen0 = file_get_contents($url0);
            $contenidoImagen0 = base64_encode($contenidoImagen0);

            $contenidoImagen0 = "data:image/png;base64,".$contenidoImagen0;

              $contenidoImagen1 = file_get_contents($url1);
            $contenidoImagen1 = base64_encode($contenidoImagen1);

            $contenidoImagen1 = "data:image/png;base64,".$contenidoImagen1;

        $contenidoImagen2 = file_get_contents($url2);
            $contenidoImagen2 = base64_encode($contenidoImagen2);

            $contenidoImagen2 = "data:image/png;base64,".$contenidoImagen2;


            $logo_final = ($data['cia'] == '001')?$contenidoImagen2:$contenidoImagen1;

        $mensaje=array( 

            'imagen0'=>$contenidoImagen0,
            'imagen_logo'=>$logo_final,
            'cabezera' => $cliente_msj ,
            'cuerpo' => '',
            'piePagina' => '',
            'cia'=>$data['cia']

          );

          $body = array('information'=> $mensaje);


          $serv = ($servicio=='S')?'SANGRE_'.$data['contrato']:'TEJIDO'.$data['contrato'];

         $setFile1 ='INFORME_LABORATORIO_'.$serv.'.pdf';
       

         //$rayasta = ($data['cia'] == '001')?'rayasta@criocord.com.pe':'rayasta@lazodevida.com.pe';


           $message   = (new Swift_Message($asunto_informe))
              ->setFrom($from, $empresa)
              ->setTo($padre_copia)
              //->addBcc($correo_logeo, $loge_name)
              ->setBody(view('correos.masiva_informe_laboratorio', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato_lab_estatico)->setFilename($setFile1));

              $mailer->send($message);

    }
    public function envia_comprobante_doc_venta_correo($data){

    

        $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $destinatarios = $data['destinatarios'];

        
        
       

        $cliente_msj = 'Estimado cliente : '.$data['cliente'];
       

        $correo_logeo = Auth::user()->email;
        $loge_name = ucfirst(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);
       

        $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);


        $empresa = ($data['cia'] == '001')?'CRIOCORD':'LAZO DE VIDA';

        $mensaje=array( 

            'cabezera' => $cliente_msj ,
            'cuerpo' => 'Se adjunta el documento de venta solicitado',
            'piePagina' => 'Sistema automatizado de envio de correos - '.$empresa

          );

          $body = array('information'=> $mensaje);

          
         $formato_pdf = $data['formato'];

          if( !config("global.production") ){

             $destinatarios = config("global.soporte");

        }

        //$destinatarios='jisuiza@criocord.com.pe';


          $message   = (new Swift_Message('ENVIO DE DOCUMENTO DE VENTA'))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name)
              ->setBody(view('correos.plantilla_documento_venta', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato_pdf));

            
          
         


       


        if($mailer->send($message)>0){

          if ( file_exists($formato_pdf) ) {

                unlink($formato_pdf);
            }

          
           LogCorreos::inserta(1,'ANUALIDAD/DOCUMENTO DE VENTA','DOCUMENTO DE VENTA','ENVIO DE DOCUMENTO DE VENTA',$mensaje,$data,$correo_logeo);

          return $this->setRpta("ok","Se envió el correo de manera satisfactoria");

        }else{

           LogCorreos::inserta(0,'ANUALIDAD/DOCUMENTO DE VENTA','DOCUMENTO DE VENTA','ENVIO DE DOCUMENTO DE VENTA',$mensaje,$data,$correo_logeo);

          return $this->setRpta("error","No se pudo enviar el correo");

        }



   }



   public function enviar_correo_ficha_datos_prospecto($data){

    

        $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $destinatarios = $data['destinatarios'];

        $mensaje_sp    =  $data['mensaje_sp'];
       


        $correo_logeo = Auth::user()->email;
        $loge_name = ucfirst(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);


        $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);


        $empresa = ($data['cia'] == '001')?'CRIOCORD':'LAZO DE VIDA';

        $mensaje=array( 

            'cabezera' => $mensaje_sp ,
            'cuerpo' => 'Se adjunta el formato de ficha de datos solicitado',
            'piePagina' => 'Sistema automatizado de envio de correos - '.$empresa

          );

          $body = array('information'=> $mensaje);

          
         $formato_pdf = $data['formato'];

        
         if( !config("global.production") ){

             $destinatarios = config("global.soporte");

        }


        //$destinatarios='jisuiza@criocord.com.pe';


          $message   = (new Swift_Message('ENVIO DE FICHA DE DATOS'))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name)
              ->setBody(view('correos.plantilla_ficha_datos_prospecto', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato_pdf));

            
          
         
        

        if($mailer->send($message)>0){

          if ( file_exists($formato_pdf) ) {

                unlink($formato_pdf);
            }
            
             LogCorreos::inserta(1,'PROSPECTOS/PROSPECTOS','FICHA DE DATOS','ENVIO DE FICHA DE DATO',$mensaje,$data,$correo_logeo);

          return $this->setRpta("ok","Se envió el correo de manera satisfactoria");

        }else{

           LogCorreos::inserta(0,'PROSPECTOS/PROSPECTOS','FICHA DE DATOS','ENVIO DE FICHA DE DATO',$mensaje,$data,$correo_logeo);
          return $this->setRpta("error","No se pudo enviar el correo");

        }



   }




   public function envia_correo_estado_cuenta_cliente($data){

    

        $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $destinatarios = $data['destinatarios'];

        $mensaje_sp    =  $data['mensaje_sp'];
       

       $correo_logeo = Auth::user()->email;
        $loge_name = ucfirst(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);


        $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);


        $empresa = ($data['cia'] == '001')?'CRIOCORD':'LAZO DE VIDA';

        $mensaje=array( 

            'cabezera' => $mensaje_sp ,
            'cuerpo' => 'Se adjunta el formato de estado de cuenta solicitado',
            'piePagina' => 'Sistema automatizado de envio de correos - '.$empresa

          );

          $body = array('information'=> $mensaje);

          
         $formato_pdf = $data['formato'];

        

          if( !config("global.production") ){

             $destinatarios = config("global.soporte");

        }

        //$destinatarios='jisuiza@criocord.com.pe';


          $message   = (new Swift_Message('ENVIO DE ESTADO DE CUENTA'))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name)
              ->setBody(view('correos.plantilla_estado_cuenta', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato_pdf));

            
          
         
         


        if($mailer->send($message)>0){

          if ( file_exists($formato_pdf) ) {

                unlink($formato_pdf);
            }
            
            LogCorreos::inserta(1,'CONTRATOS/CONTRATO','ESTADO DE CUENTA','ENVIO DE ESTADO DE CUENTA',$mensaje,$data,$correo_logeo);
          return $this->setRpta("ok","Se envió el correo de manera satisfactoria");

        }else{

          LogCorreos::inserta(0,'CONTRATOS/CONTRATO','ESTADO DE CUENTA','ENVIO DE ESTADO DE CUENTA',$mensaje,$data,$correo_logeo);
          return $this->setRpta("error","No se pudo enviar el correo");

        }



   }





   public function envia_acuerdo_contrato_cliente($data){

    

        $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $destinatarios = $data['destinatarios'];

        $mensaje_sp    =  $data['mensaje_sp'];
       

        $correo_logeo = Auth::user()->email;
        $loge_name = ucfirst(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);


        $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);


        $empresa = ($data['cia'] == '001')?'CRIOCORD':'LAZO DE VIDA';

        $mensaje=array( 

            'cabezera' => $mensaje_sp ,
            'cuerpo' => 'Se adjunta el formato de contratación solicitado',
            'piePagina' => 'Sistema automatizado de envio de correos - '.$empresa

          );

          $body = array('information'=> $mensaje);

          
         $formato_pdf = $data['formato'];

          if( !config("global.production") ){

             $destinatarios = config("global.soporte");

        }

        //$destinatarios='jisuiza@criocord.com.pe';

          $message   = (new Swift_Message('ENVIO DE CONTRATO'))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name)
              ->setBody(view('correos.plantilla_contrato_cliente', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato_pdf));

            
          
         
         

        if($mailer->send($message)>0){

          if ( file_exists($formato_pdf) ) {

                unlink($formato_pdf);
            }
            
            LogCorreos::inserta(1,'CONTRATOS/CONTRATO','PLANTILLA CONTRATO','ENVIO DE CONTRATO',$mensaje,$data,$correo_logeo);
          return $this->setRpta("ok","Se envió el correo de manera satisfactoria");

        }else{

          LogCorreos::inserta(0,'CONTRATOS/CONTRATO','PLANTILLA CONTRATO','ENVIO DE CONTRATO',$mensaje,$data,$correo_logeo);
          return $this->setRpta("error","No se pudo enviar el correo");

        }



   }


   public function envia_acuerdo_solicitud_desv($data){

    

        $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $destinatarios = $data['destinatarios'];

        $mensaje_sp    =  $data['mensaje_sp'];
       

        $correo_logeo = Auth::user()->email;
        $loge_name = ucfirst(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);

        $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);


        $empresa = ($data['cia'] == '001')?'CRIOCORD':'LAZO DE VIDA';

        $mensaje=array( 

            'cabezera' => $mensaje_sp ,
            'cuerpo' => "Es un gusto saludarlos cordialmente y adjuntarles el Acuerdo de resolución de contrato, el cual se solicita lo impriman, firmen y coloquen sus huellas digitales; asimismo por favor adjuntar las copias de sus DNI.<br>
            Sírvanse enviarlo a través de este medio.<br>
            Se hace de su conocimiento que hasta que no remita el acuerdo de resolución del contrato firmado y con huellas dactilares de cada uno de los padres,  continuará recibiendo las comunicaciones del área de cobranzas<br>
            Agradeciendo la confianza depositada en nuestro Instituto, quedamos como siempre a su disposición",
            
            'piePagina' => 'Sistema automatizado de envio de correos - '.$empresa

          );

          $body = array('information'=> $mensaje);

          
         $formato_pdf = $data['formato'];

          if( !config("global.production") ){

             $destinatarios = config("global.soporte");

        }

        //$destinatarios='jisuiza@criocord.com.pe';

          $message   = (new Swift_Message('ENVIO DE INFORME DE DESVINCULACION'))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name)
              ->setBody(view('correos.plantilla_desvinculacion', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato_pdf));

            
          
         

         

        if($mailer->send($message)>0){

          if ( file_exists($formato_pdf) ) {

                unlink($formato_pdf);
            }
            
            LogCorreos::inserta(1,'CONTRATOS/CONTRATO','SOLICITUD DESVINCULACION','ENVIO DE INFORME DE DESVINCULACION',$mensaje,$data,$correo_logeo);
          return $this->setRpta("ok","Se envió el correo de manera satisfactoria");

        }else{


          LogCorreos::inserta(0,'CONTRATOS/CONTRATO','SOLICITUD DESVINCULACION','ENVIO DE INFORME DE DESVINCULACION',$mensaje,$data,$correo_logeo);
          return $this->setRpta("error","No se pudo enviar el correo");

        }



   }
  


  public function envia_informe_constancia_recojo($data){

        $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $destinatarios = $data['destinatarios'];

        $asunto = $data['asunto'];

        $comentario = $data['mensaje'];


        $correo_logeo = Auth::user()->email;
        $loge_name = ucfirst(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);



        $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);


        $mensaje=array( 

            'cabezera' => '',
            'cuerpo' => $comentario,
            'piePagina' => 'Sistema de mensajeria automatizado'

          );

          $body = array('information'=> $mensaje);

          $empresa = ($data['cia'] == '001')?'CRIOCORD':'LAZO DE VIDA';
         
          $formato = $data['formato'];

           if( !config("global.production") ){

             $destinatarios = config("global.soporte");

        }

        //$destinatarios='jisuiza@criocord.com.pe';


          $message   = (new Swift_Message($asunto))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name)
              ->setBody(view('correos.plantilla_constancia_recojo', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato));

            
          
          
         

        if($mailer->send($message)>0){


          if ( file_exists($formato) ) {

                unlink($formato);
            }

            LogCorreos::inserta(1,'COLECTAS/CONSTANCIA DE RECOJO','ENVIO DE CONSTANCIA DE RECOJO','ENVIO DE CONSTANCIA DE RECOJO',$mensaje,$data,$correo_logeo);

          return $this->setRpta("ok","Se envió el correo de manera satisfactoria");

        }else{

          LogCorreos::inserta(0,'COLECTAS/CONSTANCIA DE RECOJO','ENVIO DE CONSTANCIA DE RECOJO','ENVIO DE CONSTANCIA DE RECOJO',$mensaje,$data,$correo_logeo);
          return $this->setRpta("error","No se pudo enviar el correo");

        }

          






  }


  public function envia_informe_laboratorio_bitacora($data){

        $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $destinatarios = $data['destinatarios'];

        

        $asunto = $data['asunto'];

        $comentario = $data['mensaje'];


        $correo_logeo = Auth::user()->email;
        $loge_name = ucfirst(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);

        $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);


        $mensaje=array( 

            'cabezera' => '',
            'cuerpo' => $comentario,
            'piePagina' => 'Sistema de mensajeria automatizado'

          );

          $body = array('information'=> $mensaje);

          $empresa = ($data['cia'] == '001')?'CRIOCORD':'LAZO DE VIDA';
         
          $formato = $data['formato'];

           if( !config("global.production") ){

             $destinatarios = config("global.soporte");

        }

        //$destinatarios='jisuiza@criocord.com.pe';

          $message   = (new Swift_Message($asunto))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name)
              ->setBody(view('correos.plantilla_informe_laboratorio', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato));

            
          
          
         

        if($mailer->send($message)>0){


          if ( file_exists($formato) ) {

                unlink($formato);
            }

          //graba fecha de envio
        

           
          $envio = Carbon::now()->format('Y-m-d');

          \DB::update("UPDATE LAB_COLECTAS  SET ENVIO_FECHA_CORREO=? WHERE NO_CIA=? AND NUMERO_CONTRATO=?",array($envio,$data['cia'],$data['contrato']));


          LogCorreos::inserta(1,'ANUALIDAD/LLAMADA COBRANZA/BITACORA','ENVIO DE INFORME DE LABORATORIO','ENVIO DE INFORME DE LABORATORIO',$mensaje,$data,$correo_logeo);

          return $this->setRpta("ok","Se envió el correo de manera satisfactoria");

        }else{

          LogCorreos::inserta(0,'ANUALIDAD/LLAMADA COBRANZA/BITACORA','ENVIO DE INFORME DE LABORATORIO','ENVIO DE INFORME DE LABORATORIO',$mensaje,$data,$correo_logeo);

          return $this->setRpta("error","No se pudo enviar el correo");

        }

          






  }



public function envia_correo_nuevo_usuario($data){

        $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $data_usuario = $data['request'];

        $destinatarios = $data_usuario->email;
        
        $full_name = strtoupper($data_usuario->vm_usuario_apepat.' '.$data_usuario->vm_usuario_apemat.' '.$data_usuario->vm_usuario_nombre);

        $random = $data['random'];

        $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);


        $empresa = ($data['cia'] == '001')?'CRIOCORD':'LAZO DE VIDA';

        $mensaje=array( 

            'cabezera' => 'Estimado Colaborador : '.$full_name ,
            'cuerpo' => 'Se envia sus credenciales para el ingreso al sistema , contraseña : '.$random,
            'piePagina' => 'Sistema automatizado de envio de correos - '.$empresa

          );

          $body = array('information'=> $mensaje);

          
           if( !config("global.production") ){

             $destinatarios = config("global.soporte");

        }
         

        //$destinatarios='jisuiza@criocord.com.pe';


          $message   = (new Swift_Message('ENVIO DE NUEVAS CREDENCIALES'))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->setBody(view('correos.plantilla_nuevas_credenciales', $body)->render(),'text/html');

            
          
         
         


        if($mailer->send($message)>0){

          LogCorreos::inserta(1,'SEGURIDAD/USUARIOS','RESTABLECER CONTRASENA','ENVIO DE NUEVAS CREDENCIALES',$mensaje,$data,'');

          return $this->setRpta("ok","Al nuevo usuario se le envió un correo electrónico con sus credenciales");

        }else{

           LogCorreos::inserta(0,'SEGURIDAD/USUARIOS','RESTABLECER CONTRASENA','ENVIO DE NUEVAS CREDENCIALES',$mensaje,$data,'');

          return $this->setRpta("error","No se pudo enviar el correo con las credenciales del nuevo usuario");

        }

          






  }



  public function envia_correo_constancia_medico_seg_captador($data){

    

        $host         = $data['config']['HOST'];
        $puerto       = $data['config']['PUERTO'];
        $encriptacion = $data['config']['ENCRIPTACION'];
        $from         = $data['config']['CORREO'];
        $password     = $data['config']['PASSWORD'];

        $destinatarios = $data['destinatarios'];

        
        
       $contrato = $data['contrato'];

        $cliente_msj = 'Estimado(a) : '.$data['cliente'];
       

        $correo_logeo = Auth::user()->email;
        $loge_name = ucfirst(Auth::user()->nombres.' '.Auth::user()->apepat.' '.Auth::user()->apemat);
       

        $transport = (new Swift_SmtpTransport($host, $puerto, $encriptacion))
              ->setUsername($from)
              ->setPassword($password);

        $mailer = new Swift_Mailer($transport);


        $empresa = ($data['cia'] == '001')?'CRIOCORD':'LAZO DE VIDA';

        $mensaje=array( 

            'cabezera' => $cliente_msj ,
            'cuerpo' => '  Se adjunta la constancia del pago realizado por la atención del contrato N°'.$contrato,
            'piePagina' => 'Sistema automatizado de envio de correos - '.$empresa

          );

          $body = array('information'=> $mensaje);

          
         $formato_pdf = $data['formato'];


         //valida ambiente produccion o test
        

        if( !config("global.production") ){

             $destinatarios = config("global.soporte");

        }

        //$destinatarios='jisuiza@criocord.com.pe';

          $message   = (new Swift_Message('ENVIO DE CONSTANCIA DE PAGO MEDICO - CONTRATO N°'.$contrato))
              ->setFrom($from, $empresa)
              ->setTo($destinatarios)
              ->addBcc($correo_logeo, $loge_name)
              ->setBody(view('correos.plantilla_constancia_medico', $body)->render(),'text/html')
              ->attach(\Swift_Attachment::fromPath($formato_pdf));

            
          
         


       


        if($mailer->send($message)>0){

          if ( file_exists($formato_pdf) ) {

                unlink($formato_pdf);
            }

          
           LogCorreos::inserta(1,'CONTRATACION/SEGUIMIENTO SEROLOGICO','CONSTANCIA DE PAGO','ENVIO DE CONSTANCIA DE PAGO',$mensaje,$data,$correo_logeo);

          return $this->setRpta("ok","Se envió el correo de manera satisfactoria");

        }else{

           LogCorreos::inserta(0,'CONTRATACION/SEGUIMIENTO SEROLOGICO','CONSTANCIA DE PAGO','ENVIO DE CONSTANCIA DE PAGO',$mensaje,$data,$correo_logeo);

          return $this->setRpta("error","No se pudo enviar el correo");

        }



   }


 
 public function vista_correos_enviados(){



   $middleRpta = $this->valida_url_permisos(38);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }


    $empresa_user = Auth::user()->empresa;

      
      
    return View('mantenimiento.correos_enviados.index',compact('empresa_user'));

 }


public function list_correos_enviados(Request $request){


  

   $list = LogCorreos::list_correos_enviados($request);

   

   return response()->json($list);

 }

 
    
}