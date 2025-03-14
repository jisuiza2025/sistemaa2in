<?php

namespace App\Http\Controllers\Mantenimiento;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SignNow;
use App\Contrato;
use Auth;
use DB;
use PDF;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Http\Controllers\Mantenimiento\CloudConvertController;
use App\Http\Controllers\Mantenimiento\WordController;
use Illuminate\Support\Facades\Storage;
class PruebaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){


    $empresa_user = Auth::user()->empresa;

    return view('mantenimiento.prueba.index', compact('empresa_user'));

        //return View('matenimiento.prueba.index');
    }

    protected function generar_token_temp()
    {
        $rpta=SignNow::get_signnow();

        $client = new Client();

        $username = $rpta[0]['USUARIO'];
        $password = $rpta[0]['PASWOORD'];
        $basic = $rpta[0]['TOKENBASIC'];

        try {
            $response = $client->post('https://api.signnow.com/oauth2/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . $basic, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                ],
                'form_params' => [
                    'username' => $username,
                    'password' => $password,
                    'grant_type'=> 'password',
                    'scope'=>'*'
                    // Agrega aquí los datos de formulario que deseas enviar
                ]
            ]);

            $statusCode = $response->getStatusCode();

            $body = json_decode($response->getBody()->getContents(), true);
            return $body['access_token'];

        } catch (\Exception $e) {
            // Manejar el error
            return null;
        }
    }

    //servicios signNow
    protected function generar_token(Request $request)
    {
        $client = new Client();

        $username = $request->usuario;
        $password = $request->pass;
        $basic = $request->tokengenerado;

        try {
            $response = $client->post('https://api.signnow.com/oauth2/token', [
                'headers' => [
                    'Authorization' => 'Basic ' . $basic, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                ],
                'form_params' => [
                    'username' => $username,
                    'password' => $password,
                    'grant_type'=> 'password',
                    'scope'=>'*'
                    // Agrega aquí los datos de formulario que deseas enviar
                ]
            ]);

               // Obtener el código de estado HTTP
            $statusCode = $response->getStatusCode();

            // Obtener el cuerpo de la respuesta
            //$body = $response->getBody()->getContents();
            $body = json_decode($response->getBody()->getContents(), true);

            return response()->json(['status' => $statusCode, 'obj' => $body]);

        } catch (\Exception $e) {
            // Manejar el error
            return $e->getMessage();
        }
    }

    protected function verificar_token()
    {
        $client = new Client();
        $tokenTemp= $this->generar_token_temp();
        try {
            $response = $client->get('https://api.signnow.com/oauth2/token', [
                'headers' => [
                    'Authorization' => 'Bearer '.$tokenTemp,// . $basic, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                ]
            ]);

               // Obtener el código de estado HTTP
            $statusCode = $response->getStatusCode();

            // Obtener el cuerpo de la respuesta
            //$body = $response->getBody()->getContents();
            $body = json_decode($response->getBody()->getContents(), true);

            return response()->json(['status' => $statusCode, 'obj' => $body]);

        } catch (\Exception $e) {
            // Manejar el error
            return $e->getMessage();
        }
    }

    //mantenimiento signNow
    protected function salvar_signnow(Request $request)
    {
       $rpta=SignNow::salvar_signnow($request);
       return response()->json($rpta);
    }

    protected function get_signnow()
    {
       $rpta=SignNow::get_signnow();
       return response()->json($rpta);
    }

    //plantilla
    protected function cargar_plantilla_contrato($file)
	{
        $client = new Client();
        $tokenTemp= $this->generar_token_temp();

        $pathtoFilePagoValidar = (Auth::user()->empresa=='001') ? public_path().'/formatos_nuevos/ICTC/'.$file : public_path().'/formatos_nuevos/LAZO_DE_VIDA/'.$file;

        try {

            //prod

            if (file_exists($pathtoFilePagoValidar)) {

                $randomName = $this->generaRandomString(10);

                $subDirectory = '/resultadoFormatoWord/'.$randomName.'.docx';

                $file_to = public_path().$subDirectory;

                if(!copy($pathtoFilePagoValidar, $file_to)){
                      return  $this->setRpta('error','No se pudo mover el fichero temporal contratacion word ');
                }

                $CloudConvert = new CloudConvertController;

                $rptaConver = $CloudConvert->convert($subDirectory);
                $data = $rptaConver['data'];

                $fileTemp = public_path().'/cloudConvert/'.$data;
                $filenameTemp = preg_replace('/\.[^.]+$/', '.pdf', $file);

                $response = $client->post('https://api.signnow.com/document', [
                    'headers' => [
                        'Authorization' => 'Bearer '.$tokenTemp, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                    ],
                    'multipart' => [
                        [
                            'name'     => 'file', // Nombre del campo de formulario donde se enviará el archivo
                            'contents' => fopen($fileTemp, 'r'), // Ruta al archivo que deseas enviar
                            'filename' => $filenameTemp // Nombre del archivo que se enviará (opcional)
                        ]
                    ]
                ]);

                // Obtener el código de estado HTTP
                $statusCode = $response->getStatusCode();

                $body = json_decode($response->getBody()->getContents(), true);

                return response()->json(['status' => $statusCode, 'obj' => $body,'convert' =>  $rptaConver ]);


            } else {

                return $this->redireccion_404();
            }


            //local

            // $fileTemp=public_path().'/cloudConvert/IMPRESION_PLANTILLA_23592-T.pdf';

            // $response = $client->post('https://api.signnow.com/document', [
            //     'headers' => [
            //         'Authorization' => 'Bearer '.$tokenTemp, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
            //     ],
            //     'multipart' => [
            //         [
            //             'name'     => 'file', // Nombre del campo de formulario donde se enviará el archivo
            //             'contents' => fopen($fileTemp, 'r'), // Ruta al archivo que deseas enviar
            //             'filename' => 'IMPRESION_PLANTILLA_23592-T.pdf' // Nombre del archivo que se enviará (opcional)
            //         ]
            //     ]
            // ]);

            //     // Obtener el código de estado HTTP
            // $statusCode = $response->getStatusCode();

            // $body = json_decode($response->getBody()->getContents(), true);

            // return response()->json(['status' => $statusCode, 'obj' => $body]);

        } catch (\Exception $e) {
            // Manejar el error
            return $e->getMessage();
        }
	}

    protected function mantenimiento_signnow_plantilla(Request $request)
    {

        if($request->accion=="sigmail")
        {
            $rpta=SignNow::save_sigmail($request);
            return response()->json($rpta);
        }

        if($request->accion=="sigdoc")
        {
            $rpta=SignNow::save_sigdoc($request);
            return response()->json($rpta);
        }

        if($request->accion=="sigplantilla")
        {
            $rpta=SignNow::save_sigrol($request);
            $rpta=SignNow::save_sigcampos($request);
            $rpta=SignNow::save_sigmod($request);
            return response()->json($rpta);
        }

    }

    protected function get_signnow_plantilla($accion,$idplantilla)
    {


        if($accion =="sigmail")
        {
            $rpta=SignNow::get_sigmail($idplantilla);
            return response()->json($rpta);
        }

        if($accion=="sigdoc")
        {
            $rpta=SignNow::get_sigdoc($idplantilla);
            return response()->json($rpta);
        }

        if($accion=="sigplantilla")
        {
            $rpta=SignNow::get_sigrol($idplantilla);
            $rpta2=SignNow::get_sigcampos($idplantilla);

            return response()->json(['roles' => $rpta, 'campos' => $rpta2]);
        }

    }

    protected function servicio_get_document($iddoc)
    {
        $client = new Client();
        $tokenTemp= $this->generar_token_temp();
        try {
            $response = $client->get('https://api.signnow.com/document/'.$iddoc, [
                'headers' => [
                    'Authorization' => 'Bearer '.$tokenTemp,// . $basic, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                ]
            ]);

               // Obtener el código de estado HTTP
            $statusCode = $response->getStatusCode();

            // Obtener el cuerpo de la respuesta
            //$body = $response->getBody()->getContents();
            $body = json_decode($response->getBody()->getContents(), true);

            return response()->json(['status' => $statusCode, 'obj' => $body]);

        } catch (\Exception $e) {
            // Manejar el error
            return $e->getMessage();
        }
    }

    //contrato solicitud de firma
    protected function contrato_signnow_solicitudfirma(Request $request)
    {
        $client = new Client();
        $tokenTemp= $this->generar_token_temp();

        //idcontrato
        //idplantilla
        $rpta=SignNow::validate_sigcontrato($request->numcontrato);
        //$rpta = json_decode($rpta, true);

        if ($rpta[0]['CODIGO'] > 0) {


            $rpta_get_token_vigencia=SignNow::get_sigcontrato_token();
            $rpta_get_token=SignNow::get_sigcontrato_parametros();


            $middleRpta =  $this->valida_impresion_contrato('CON',$request->numcontrato);
            if($middleRpta["status"]=="ok"){

                //prod
                $genera = $this->genera_contrato_impresion($request->numcontrato,'CON');
                $data = $genera['data'];
                $outName ="IMPRESION_PLANTILLA_".$request->numcontrato.".pdf";

                //prod
                $fileTemp = public_path().'/cloudConvert/'.$data;
                //local
                //$fileTemp=public_path().'/cloudConvert/IMPRESION_PLANTILLA_23592-T.pdf';

                $response = $client->post('https://api.signnow.com/document', [
                    'headers' => [
                        'Authorization' => 'Bearer '.$tokenTemp, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                    ],
                    'multipart' => [
                        [
                            'name'     => 'file', // Nombre del campo de formulario donde se enviará el archivo
                            'contents' => fopen($fileTemp, 'r'), // Ruta al archivo que deseas enviar
                            //'filename' => 'IMPRESION_PLANTILLA_23592-T.pdf' // Nombre del archivo que se enviará (opcional)
                            'filename' => $outName // Nombre del archivo que se enviará (opcional) prod
                        ]
                    ]
                ]);

                $statusCode = $response->getStatusCode();

                $body = json_decode($response->getBody()->getContents(), true);

                if (isset($body['id'])) {
                    // Capturar el valor del campo 'id'
                    $id = $body['id'];

                    $rpta_save_sigcontrato=SignNow::save_sigcontrato($request->numcontrato,$id,$rpta[0]['CODIGO']);

                    if ($rpta_save_sigcontrato === 1 || $rpta_save_sigcontrato === '1') {
                        
                        $rpt_get_campos=SignNow::get_sigcontrato_campos($id);
                        $rpt_get_roles=SignNow::get_sigcontrato_roles($id);

                        // Nuevo arreglo en el formato requerido
                        $nuevos_campos = [];
                        $nuevos_roles = [];

                        foreach ($rpt_get_campos as $item) {
                            $nuevos_campos[] = [
                                // "type" => "text",
                                "type" => $item["TIPO"],
                                "x" => intval($item["UBI_X"]),
                                "y" => intval($item["UBI_Y"]),
                                "width" => intval($item["ANCHO"]),
                                "height" => intval($item["ALTO"]),
                                "required" => ($item["REQUERIDO"] === "true"),
                                "name" => $item["NOMBRE"],
                                //"name" => $item["NOMBRE_FIRMANTE"],
                                "prefilled_text" => "",
                                // "page_number" => intval($item["PAGINA"]) - 1,
                                "page_number" => intval($item["PAGINA"]),
                                "role" => $item["NOMBRE_FIRMANTE"],
                                //"role" => $item["NOMBRE"],
                                "originator" => $item["CUENTA"],
                                "fulfiller" => null,
                                "field_request_id" => null,
                                "element_id" => null,
                                "field_request_canceled" => null,
                                "template_field_id" => null,
                                "field_id" => null
                            ];
                        }

                        foreach ($rpt_get_roles as $role) {
                            $recipient = [
                                "email" => $role['EMAIL'],
                                "role_id" => "",
                                "role" => $role['NOMBRE'],
                                "order" => intval($role['ORDEN']),
                                "reassign" => "0",
                                "decline_by_signature" => "0",
                                "reminder" => 0,
                                "expiration_days" => intval($role['DIAS_EXPIRA']),
                                "authentication_type" => $role['REQUIEREPWD'] === "Y" ? "password" : "",
                                "password" => $role['PWD'] ?? "",
                                "subject" => $role['ASUNTO'],
                                "message" => $role['MENSAJE']
                            ];
                            
                            $nuevos_roles[] = $recipient;
                        }

                        $new_data = [
                            "document_id" => $id,
                            "to" => $nuevos_roles,
                            "from" => $role['CUENTA'],
                            "cc" => [
                                $role['COPIA'],
                            ],
                            "subject" => $role['ASUNTO'], // Aquí se pasa el valor de $role['ASUNTO'] al campo "subject"
                            "message" => $role['MENSAJE']
                        ];


                        $response_update = $client->put('https://api.signnow.com/document/'.$id, [
                            'headers' => [
                                'Authorization' => 'Bearer '.$tokenTemp, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                            ],
                            'json' => [
                                "fields" => $nuevos_campos
                            ]
                        ]);

                        $statusCode = $response_update->getStatusCode();
                        $body_update = json_decode($response_update->getBody()->getContents(), true);

                        if($statusCode==200)
                        {                            
                            $response_invite = $client->post('https://api.signnow.com/document/'.$id."/invite", [
                                'headers' => [
                                    'Authorization' => 'Bearer '.$tokenTemp, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                                ],
                                'json' => 
                                    $new_data
                                
                            ]);

                            $statusCode = $response_invite->getStatusCode();
                            $body_invite = json_decode($response_invite->getBody()->getContents(), true);

                            if($statusCode==200){
                                return response()->json(['bEstado' => true,
                                'mensaje' => "Documento cargado y enviado correctamente.",
                                'obj' =>$body_invite]);   
                            } else {
                                return response()->json(['bEstado' => false, 'mensaje' => "Error en Post CLient document invite"]);
                            }
                     
                            
                        } else {

                            return response()->json(['bEstado' => false, 'mensaje' => "Error en Put CLient document"]);
                        }

                    } else {
                        return response()->json(['bEstado' => false, 'mensaje' => "Error en WEB_VEN_CONTRATOS_SIGNNOW_INS"]);
                    }
                  
                } else {
                    return response()->json(['bEstado' => false, 'mensaje' => "Error en cargar el PDF."]);
                }


                // return response()->json(['bEstado' => true,
                // 'mensaje' => $rpta[0]['DESCRIPCION'],
                // 'obj' =>$body]);

                //return $genera;
            }else
            {
                return response()->json(['bEstado' => false, 'mensaje' => $middleRpta]);
            }


            // return response()->json(['bEstado' => true,
            // 'mensaje' => $rpta[0]['DESCRIPCION'],
            // 'obj' =>$rpta_get_token]);

        } else {

            return response()->json(['bEstado' => false, 'mensaje' => $rpta[0]['DESCRIPCION']]);
        }

        //return response()->json($rpta);
    }

    protected function contrato_cargar_listados($numcontrato)
    {
        $rpta=SignNow::get_contrato_firmantes($numcontrato);
        $rpta2=SignNow::get_contrato_campos($numcontrato);
        $rpta3=SignNow::get_contrato_historial($numcontrato);
        $rpta4=SignNow::get_contrato_botones($numcontrato);

        return response()->json(['firmantes' => $rpta,'campos' => $rpta2,'historial' => $rpta3,'botones' => $rpta4]);
    }

    protected function contrato_cargar_reenviar($numcontrato,$idcontrato,$firmante)
    {
        if ($firmante === "all") {
            $firmante = "";
        }

        $rpta=SignNow::get_contrato_reenviar($numcontrato,$idcontrato,$firmante);

        return response()->json(['reenviar' => $rpta]);
    }

    protected function servicio_cancelar_solicitud($numcontrato,$iddoc)
    {
        $client = new Client();
        $tokenTemp= $this->generar_token_temp();
        try {
            $response = $client->put('https://api.signnow.com/document/'.$iddoc.'/fieldinvitecancel', [
                'headers' => [
                    'Authorization' => 'Bearer '.$tokenTemp,// . $basic, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                ]
            ]);

               // Obtener el código de estado HTTP
            $statusCode = $response->getStatusCode();

            // Obtener el cuerpo de la respuesta
            //$body = $response->getBody()->getContents();
            $body = json_decode($response->getBody()->getContents(), true);

            SignNow::save_sigcontrato_historial($numcontrato,$iddoc,3);

            return response()->json(['status' => $statusCode, 'obj' => $body]);

        } catch (\Exception $e) {
            // Manejar el error
            return $e->getMessage();
        }
    }

    protected function servicio_anular_solicitud($numcontrato,$iddoc)
    {
        $client = new Client();
        $tokenTemp= $this->generar_token_temp();
        try {
            $response = $client->delete('https://api.signnow.com/document/'.$iddoc, [
                'headers' => [
                    'Authorization' => 'Bearer '.$tokenTemp,// . $basic, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                ]
            ]);

               // Obtener el código de estado HTTP
            $statusCode = $response->getStatusCode();

            // Obtener el cuerpo de la respuesta
            //$body = $response->getBody()->getContents();
            $body = json_decode($response->getBody()->getContents(), true);

            
            SignNow::save_sigcontrato_historial($numcontrato,$iddoc,9);

            return response()->json(['status' => $statusCode, 'obj' => $body]);

        } catch (\Exception $e) {
            // Manejar el error
            return $e->getMessage();
        }
    }

    protected function servicio_reenviar_solicitud($ids)
    {
        $client = new Client();
        $tokenTemp= $this->generar_token_temp();
        try {

            $idArray = explode(',', $ids);

            foreach ($idArray as $iddoc) {
                try {
                    $response = $client->put('https://api.signnow.com/fieldinvite/'.$iddoc.'/resend', [
                        'headers' => [
                            'Authorization' => 'Bearer '.$tokenTemp,
                        ]
                    ]);

                } catch (\Exception $e) {
                    return response()->json(['status' => false]);
                }
            }

            return response()->json(['status' => true]);

        } catch (\Exception $e) {
            // Manejar el error
            return response()->json(['status' => false]);
        }
    }

    protected function servicio_descargar_document($iddoc,$numcontrato)
    {
        try {

            $status_byte = SignNow::get_byte_status($numcontrato);

            if($status_byte['DESCARGADO']=="S")
            {
                //resultadoFormatoWord
                // $filepath2 = public_path() . '/download_signnow/' . $iddoc .'-blob'. '.pdf';
                $filepath2 = public_path() . '/resultadoFormatoWord/' . $iddoc .'-blob'. '.pdf';
                $byte = SignNow::get_byte($numcontrato,$iddoc);
                file_put_contents($filepath2, $byte);
    
                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
    
                $outName ="document_".$numcontrato.".pdf";
                return response()->download($filepath2, $outName, $headers)->deleteFileAfterSend(true);

            }else{

                $client = new Client();
                $tokenTemp= $this->generar_token_temp();
    
                $response = $client->get('https://api.signnow.com/document/'.$iddoc.'/download?type=collapsed', [
                    'headers' => [
                        'Accept' => 'application/pdf',
                        'Authorization' => 'Bearer '.$tokenTemp,// . $basic, // Reemplaza YOUR_ACCESS_TOKEN con tu token de acceso
                    ]
                ]);
    
                   // Obtener el código de estado HTTP
                $statusCode = $response->getStatusCode();
    
                // Obtener el cuerpo de la respuesta
                //$body = $response->getBody()->getContents();
                // $body = json_decode($response->getBody()->getContents(), true);
                $pdfContent = $response->getBody()->getContents();
    
                // $filepath = public_path() . '/download_signnow/' . $iddoc . '.pdf';
                $filepath = public_path() . '/resultadoFormatoWord/' . $iddoc . '.pdf';

                file_put_contents($filepath, $pdfContent);
    
                $contenidoPDF = file_get_contents($filepath);
    
                $rptasave= SignNow::save_byte($numcontrato,$iddoc,$contenidoPDF);

                $headers = [
                    'Content-Type' => 'application/pdf',
                ];
    
                $outName ="document_".$numcontrato.".pdf";
                return response()->download($filepath, $outName, $headers)->deleteFileAfterSend(true);

              
                //return response()->json(['status' => $statusCode,'rpta'=>$rptasave, 'obj' => $status_byte['DESCARGADO']]);

            }

        } catch (\Exception $e) {
            // Manejar el error
            return $e->getMessage();
        }
    }

    protected function save_sigcontrato_campos(Request $request)
    {
       $rpta=SignNow::save_sigcontrato_campos($request);
       $rpta2=SignNow::save_sigcontrato_envios($request);
       //$rpta3=SignNow::save_sigcontrato_campos($request);
        if ($request->cadenaAtencion !== null) {
        $rpta3 = SignNow::save_sigcontrato_atencion($request);
        //$rpta3 = null;
        } else {
        $rpta3 = null; // Set $rpta3 to null or any default value if the condition is not met
        }

       return response()->json(['rpta' => $rpta, 'rpta2' => $rpta2, 'rpta3' => $rpta3 ]);
    }

    //adicionales
    protected function valida_impresion_contrato($variable,$contrato){

        $filespnamex = Contrato::solicitar_desvinculacion_contrato_get_file($variable,$contrato);

        if(empty($filespnamex)){

             return $this->setRpta('error','No se encontro la plantilla para el contrato : '.$contrato);

        }else{


            $file_path = (Auth::user()->empresa == '001')? public_path().'/formatos_nuevos/ICTC/'.$filespnamex: public_path().'/formatos_nuevos/LAZO_DE_VIDA/'.$filespnamex;

            if(file_exists($file_path)){

             return $this->setRpta('ok','');

            }else{

                return $this->setRpta('error','No se encontro la ruta de la plantilla  : '.$filespnamex);

            }

        }


    }

    protected function genera_contrato_impresion($contrato,$variable){
        try {

           $documento = Contrato::solicitar_desvinculacion_contrato_get_file($variable,$contrato);

           $plantilla = (Auth::user()->empresa == '001')? public_path().'/formatos_nuevos/ICTC/'.$documento: public_path().'/formatos_nuevos/LAZO_DE_VIDA/'.$documento;

            $word = new WordController;

            $middleRpta = $word->generacion_contrato($plantilla,$contrato,$variable);

            if($middleRpta["status"]=="ok"){

                $CloudConvert = new CloudConvertController;

                $filePath = $middleRpta["data"];

                return $CloudConvert->convert($filePath);
            }
            return $middleRpta;
        } catch (\PhpOffice\PhpWord\Exception\Exception $e) {
           return $this->setRpta("error",$e->getCode());
        }
    }


}
