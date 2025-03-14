<?php

namespace App\Http\Controllers\Anualidad; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Maestro;
use App\DocVenta;
use App\NotaCredito;
use Auth;
use App\Http\Controllers\Mantenimiento\CorreoController;

class DocumentoVentaController extends Controller
{
    

    public function index()
    {      

        $middleRpta = $this->valida_url_permisos(24);

        if($middleRpta["status"] != "ok"){

            return $this->redireccion_404();
        }

        $empresa_user=Auth::user()->empresa;
        $btn_asignar_contrato = $this->botones_usuario('fac_asigna_contrato_doc');
        $btn_anular_documento = $this->botones_usuario('fac_anular_doc');

        $tipo_cambio=NotaCredito::tipo_cambio();

        $centros_facturacion=Maestro::facturacion_centros();

        $motivo_nota_credito=Maestro::motivo_nota_credito();

        $btn_nota_credito = $this->botones_usuario('anu_docventa_nc');
    
        return View('anualidad.documento_venta.index',compact('empresa_user','centros_facturacion','tipo_cambio','motivo_nota_credito',
        'btn_nota_credito','btn_asignar_contrato','btn_anular_documento'));   

    }

    public function filter_tipo_documento(Request $request){

        $list=Maestro::filter_tipo_doc($request);

        return response()->json($list);

    }

    public function list_referencia(Request $request){

        $list=DocVenta::list_referencia($request);

        return response()->json($list);

    }

    public function list_documento_venta(Request $request){
        $list = DocVenta::list_documento_venta($request);

        return response()->json($list);
    } 



    public function reporte_facturacion_envio_correo($request){

        $list=  DocVenta::reporte_facturacion($request);

        $cuotas = DocVenta::cuotas_detalle_documento_venta($request);


        $empresa_user=Auth::user()->empresa;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('A4');
        $pdf->loadView('anualidad.documento_venta.reporte.reporte_factura', compact('empresa_user','list','cuotas'));

        $random = $this->generaRandomString(10);

        $file = public_path().'/documentos_venta/'.$random.'.pdf';

        $pdf->save($file);

        return $file;
    } 



     public function reporte_facturacion(Request $request){

        $list=  DocVenta::reporte_facturacion($request);


        $cuotas = DocVenta::cuotas_detalle_documento_venta($request);


       

        $empresa_user=Auth::user()->empresa;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->setPaper('A4');
        $pdf->loadView('anualidad.documento_venta.reporte.reporte_factura', compact('empresa_user','list','cuotas'));
        return $pdf->stream();
    } 

    public function list_documento_detalle(Request $request){
        $list = DocVenta::list_documento_detalle($request);

        return response()->json($list);
    }  

    public function anular_visible(Request $request){
       

        $rpta =  DocVenta::anular_visible($request);

       

        if($rpta == 1){ 

         

            return $this->setRpta("ok","Se procesó correctamente"); 

        }
       
  
    
        return $this->setRpta("error","Ocurrió un error");
    }

    public function documento_mensaje(Request $request){
        

        $rpta =  DocVenta::documento_mensaje($request);

       

        if($rpta == 1){ 

           

            return $this->setRpta("ok","DOCUMENTO ENVIADO A  RATIFICA"); 

        }
        else if($rpta == 0){

           

            return $this->setRpta("error","");

        }
  
        

        return $this->setRpta("error","Ocurrió un error");
    } 

    public function list_info_fac(Request $request){

        $list = DocVenta::list_info_fac($request);

        return response()->json($list);
    } 

   



    public function envia_comprobante_doc_venta_correo(Request $request){

        
       $cia = Auth::user()->empresa;

       $config = ($cia == '001')?$this->mailCriocord():$this->mailLazoVida();

       $destinatarios = $this->set_contactos_comprobante_doc_venta_correo($request);


       $formato = $this->reporte_facturacion_envio_correo($request);


        if($destinatarios["status"] == "error"){

            return $destinatarios;
        }

       $parametros = array(

                      "cia"           => $cia,
                      "config"        => $config,
                      "destinatarios" => $destinatarios["data"],
                      "formato"       => $formato,
                      "cliente"       => strtoupper($request->razon_social)

                  );

       
       $correo = new CorreoController;

       return $correo->envia_comprobante_doc_venta_correo($parametros);


    } 



     protected function set_contactos_comprobante_doc_venta_correo($request){

        
        $cia = Auth::user()->empresa;

        $identificacion = trim($request->identificacion);

        $query = DB::select("SELECT MAIL_CONTACTO,NOMBRE FROM VEN_CLIENTES WHERE NO_CIA=? AND IDENTIFICACION = ?",array($cia,$identificacion));


        $list = json_decode(json_encode($query),true);

        

        $data = array();


        foreach ($list as $value) {
            
           

            if(!empty($value["mail_contacto"])){

                $data[$value["mail_contacto"]] = $value["nombre"];
            }
        }

        if(count($data) == 0){

            return $this->setRpta("error","El contrato no tiene correos asociados"); 
        }

        return $this->setRpta("ok","Valido correos contrato",$data);
         

    }


    protected function anular_documento(Request $request){

    DB::beginTransaction();

        try {

           $valida = $this->valida_motivo($request);
           
           if($valida["status"] == "ok"){

                $rpta = DocVenta::anular_documento($request);
            
                if($rpta == 1){

                    DB::commit();

                    return $this->setRpta("ok","Anulado correctamente"); 

                }
                else if($rpta == 0){

                    DB::rollback();

                    return $this->setRpta("error","No se pudo anular el documento");

                }
          
                DB::rollback();

                return $this->setRpta("error","Ocurrió un error");

           }

           return $valida;
           

        } catch (\Exception $e) {
            
            DB::rollback();

            return $this->setRpta("error",$e->getMessage());
        }


    }
    protected function asignar_contrato(Request $request){

            DB::beginTransaction();
    
            try {
    
                    $rpta = DocVenta::asignar_contrato($request);
                
                    if($rpta == 1){
    
                        DB::commit();
    
                        return $this->setRpta("ok","Asignado correctamente"); 
    
                    }
                    else{
    
                        DB::rollback();
    
                        return $this->setRpta("error","No se pudo Asignar el contrato");
    
                    }
              
                    DB::rollback();
    
                    return $this->setRpta("error","Ocurrió un error");
               
    
            } catch (\Exception $e) {
                
                DB::rollback();
    
                return $this->setRpta("error",$e->getMessage());
            }
    
    
    }

  protected function valida_motivo($request){
        
        
        $rules = [
            
           'motivo_anulacion'=> 'required' 
           
        ];
        $messages = [

            'motivo_anulacion.required' => 'El motivo de anulación es obligatorio.'
            
        ];
         $validate = \Validator::make($request->all(),$rules,$messages);

         if ($validate->fails())
         {   
            
            return $this->setRpta("warning",$this->msgValidator($validate),$validate->messages() );

         }

        return $this->setRpta("ok","valido inputs motivo");

    }
    
}
