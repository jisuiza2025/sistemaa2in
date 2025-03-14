<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;
use Auth;
use PDO;

class User extends Authenticatable
{
    use Notifiable;




    public function cmr_notificaciones(){

     $p1 = Auth::user()->empresa;
        
        $p2 = Auth::user()->codigo;

        
    
        $stmt = DB::getPdo()->prepare("begin WEB_CRM_INDICADOR_ALERTAS(:p1,:p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        return $list;
  }

    protected static function get_menu_usuario_por_compania($request){

        $p1 = $request->codigo;
        
        $p2 = $request->cia;

        
    
        $stmt = DB::getPdo()->prepare("begin WEB_MENULIST_USUARIO(:p1,:p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        return $list;

    }


    protected static function get_usuarios_por_compania($request){

        $p1 = $request->cia;

        $p2 = 1;
    
        $stmt = DB::getPdo()->prepare("begin WEB_USUARIOS_LISTADO(:p1,:p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        return $list;

    }


    protected static function list_usuarios($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->flag_activo;
    
        $stmt = DB::getPdo()->prepare("begin WEB_USUARIOS_LISTADO(:p1,:p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        return $list;

    }

    protected static function get_item_usuario($request){

        $p1 = Auth::user()->empresa;

        $p2 = $request->dni;
    
        $stmt = DB::getPdo()->prepare("begin WEB_USUARIO_GETITEM(:p1,:p2, :c); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':c', $cursor, PDO::PARAM_STMT);
        
        $stmt->execute();

        oci_execute($cursor, OCI_DEFAULT);
        oci_fetch_all($cursor, $list, 0, -1, OCI_FETCHSTATEMENT_BY_ROW + OCI_ASSOC );
        oci_free_cursor($cursor);

        return $list;

    }


    protected static function save_usuario($request){

       

        $id = User::orderBy('id','desc')->get()->first()->id;

        $rpta  = self::salva_usuario($request,$id+1);

       
        return $rpta;

    }



    protected static function  generateRandomStringPass($length) {

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



    protected static function salva_usuario($request,$id){

      
       

        $empresa   = Auth::user()->empresa;
        $nombres   = $request->vm_usuario_nombre; 
        $apepat    = $request->vm_usuario_apepat; 
        $apemat    = $request->vm_usuario_apemat; 
        $dni       = $request->vm_usuario_dni; 
        $direccion = $request->vm_usuario_direcion; 
        $telefono  = $request->vm_usuario_telefono; 
        $correo    = $request->email; 

        $facturacion = $request->tipo_facturacion; 
        

        $codigo_user = $request->vm_usuario_codigo;

        //$random = str_random(8);
        $random = self::generateRandomStringPass(8);

        $password  = (empty($request->vm_usuario_password))?bcrypt($random):$request->vm_usuario_password; 

        $cargo     = $request->vm_usuario_cargo; 
        $estado    = $request->vm_usuario_estado; 
        
        $old_imagen = User::where('identificacion',$dni)->where('empresa',$empresa)->first();

       

         $imagen  = (isset($old_imagen))?$old_imagen->foto:'profiles/default.jpg';
        

         if ($request->hasFile('set_imagen')) {

            $dir = 'profiles/';
            $ext = strtolower($request->file('set_imagen')->getClientOriginalExtension()); 
            $fileName = str_random() . '.' . $ext;
            $request->file('set_imagen')->move($dir, $fileName);

            $imagen = 'profiles/'.$fileName;

        }

        

        if($request->bnt_elimina_foto == 1){

            $imagen  = 'profiles/default.jpg';
        }
        
       

        $do_usuario = ($request->flag_otra_empresa)?1:0; 

        $stmt =  DB::getPdo()->prepare("begin WEB_USUARIOS_INPUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:rpta); end;");
        $stmt->bindParam(':p1', $empresa, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $nombres, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $apepat, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $apemat, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $dni, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $direccion, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $password, PDO::PARAM_STR);
        $stmt->bindParam(':p10', $cargo, PDO::PARAM_STR);
        $stmt->bindParam(':p11', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':p12', $imagen, PDO::PARAM_STR);
        $stmt->bindParam(':p13', $id, PDO::PARAM_STR);
        $stmt->bindParam(':p14', $do_usuario, PDO::PARAM_STR);
        $stmt->bindParam(':p15', $facturacion, PDO::PARAM_STR);
        $stmt->bindParam(':p16', $codigo_user, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        
        return array($rpta,$random);

    }



    //mantenimiento de vendedor
    

    protected static function save_vendedor($request){

   
        $p1 = Auth::user()->empresa;
       
        $p2 = $request->vendendor;

        $p3 = ($request->estado==1)?'A':'I';

        $p4 = $request->telefono;

        $p5 = ($request->serologia)?'S':'N';

        $p6 = ($request->colectas)?'S':'N';

        $p7 = $request->tipo;

        $p8 = $request->categoria;

        $p9 = $request->supervisor;

        //$p10 = implode(",",$request->servicio);

          $p10 = ($request->servicio=='null')?NULL:$request->servicio;

        $stmt = DB::getPdo()->prepare("begin WEB_COR_USUARIOS_INUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:rpta); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
          $stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
            $stmt->bindParam(':p8', $p8, PDO::PARAM_STR);
            $stmt->bindParam(':p9', $p9, PDO::PARAM_STR);
            $stmt->bindParam(':p10', $p10, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;

    
    }


    //mantenimiento de captador
    
    protected static function save_captador($request){

    

        $p1 = Auth::user()->empresa;
       
        $p2 = $request->captador;

        $p3 = ($request->estado==1)?'A':'I';

        $p4 = $request->categoria;

        //$p5 = implode(",",$request->servicio);
         $p5 = $request->servicio;
        $p6 = ($request->tipo==1)?'S':'N';

       
        $p7 = $request->asig_auto;


         $p8 = $request->dist_auto;

        $stmt = DB::getPdo()->prepare("begin WEB_CAPTADORES_INPUT(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:rpta); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);

        $stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $p8, PDO::PARAM_STR);


        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;

    
    }

    //mantenimiento de clinica
    
    protected static function save_clinica($request){


        $p1  = Auth::user()->empresa;
        $p2  = trim($request->identificacion_clinica);
        $p3  = strtoupper($request->rsocial_clinica);
        $p4  = ($request->estado_clinica == null)?'INC':'ACT';
        $p5  = strtoupper($request->direccion_clinica);
        $p6  = $request->telefono_clinica;
        $p7  = $request->celular_clinica;
        $p8  = $request->email_clinica;
        $p9  = '';
        $p10 = (empty($request->ubigeo_clinica))?null:$request->ubigeo_clinica;
        $p11 = strtoupper($request->alias_clinica);
        $p12 = $request->tipo_clinica;
        $p13 = strtoupper($request->ciudad_clinica);
        $p14 = strtoupper($request->distrito_clinica);
        $p15 = $request->web_clinica;
        
        
       

        $stmt = DB::getPdo()->prepare("begin WEB_HOSPITALES_INPUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:rpta); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $p3, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $p4, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $p5, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $p6, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $p7, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $p8, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $p9,PDO::PARAM_STR);
        $stmt->bindParam(':p10', $p10 ,PDO::PARAM_STR);
        $stmt->bindParam(':p11', $p11 ,PDO::PARAM_STR);
        $stmt->bindParam(':p12', $p12 ,PDO::PARAM_STR);
        $stmt->bindParam(':p13', $p13 ,PDO::PARAM_STR);
        $stmt->bindParam(':p14', $p14 ,PDO::PARAM_STR);
        $stmt->bindParam(':p15', $p15 ,PDO::PARAM_STR);

        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;

    
    }


    protected static function desactiva_clinica($request){


        $p1 = Auth::user()->empresa;
       
        $p2 = $request->clinica;

        $stmt = DB::getPdo()->prepare("begin WEB_HOSPITALES_INACTIVAR(:p1,:p2,:rpta); end;");
        $stmt->bindParam(':p1', $p1, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $p2, PDO::PARAM_STR);
      
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        
        $stmt->execute();
        return $rpta;

    
    }




    protected static function salvar_opciones_usuario($request){


        $p1 = $request->empresa;
       
        $p2 = $request->usuario;

        

        $opciones = self::set_opciones_usuario($request->opciones);

       

       DB::statement("DELETE  FROM WEB_USUARIO_MENU WHERE NO_CIA=? AND CODIGO_USUARIO=?",array($p1,$p2));


       foreach ($opciones as $value) {
          

            $rpta = DB::statement("INSERT INTO WEB_USUARIO_MENU VALUES(?,?,?)",array($p2,$value,$p1));

            if($rpta == 0 ){

                return 0;
            }


       }

       return 1;

    
    }


    protected static function set_opciones_usuario($list){


        
                $data = array();

               

                for($i=0;$i<count($list);$i++){

                    if($list[$i]['selected']){

                        
                        $data[] = $list[$i]['id'];

                        

                        if(count($list[$i]['children'])>0){

                            for($j=0;$j<count($list[$i]['children']);$j++){


                                

                                if($list[$i]['children'][$j]["selected"]){

                                   

                                    $data[] = $list[$i]['children'][$j]["id"];

                                  
                                    if(isset($list[$i]['children'][$j]["children"])){


                                        if(count($list[$i]['children'][$j]["children"])>0){


                                        for( $z = 0;$z<count($list[$i]['children'][$j]["children"]);$z++){



                                            if($list[$i]['children'][$j]["children"][$z]["selected"]){

                                                

                                                $data[]=$list[$i]['children'][$j]["children"][$z]["id"] ;

                                            }


                                        }





                                   }

                                    }

                                   
                                }
                                
                            }
                        }
                    }




                }     

                return $data;   

   

}



public static function get_permisos_opciones(){

        $usuario= Auth::user()->codigo;

        $cia = Auth::user()->empresa;

        $list = DB::select("SELECT CODIGO_MENU FROM WEB_USUARIO_MENU WHERE CODIGO_USUARIO=? AND NO_CIA=?",array($usuario,$cia));

        $opciones = array();
        
        $list=json_decode(json_encode($list),true);
        
        foreach ($list as  $value) {
            
            $opciones[] =$value['codigo_menu'];
        }
        
        return implode(",",$opciones);
    }



public static function get_permisos_kits(){

       $usuario= Auth::user()->codigo;

        $cia = Auth::user()->empresa;

        $list = DB::select("SELECT VM_KITS FROM BOTONES_USUARIOS WHERE USUARIO=? AND NO_CIA=?",array($usuario,$cia));


        $val = 0;
        
       if(count($list)>0){

            $list=json_decode(json_encode($list),true);
            $val = ($list[0]['vm_kits']==1)?1:0;
       }
        return $val;
    }



    protected static function salvar_profile($request){

      
       

        $empresa   = Auth::user()->empresa;
        $nombres   = $request->vm_usuario_nombre; 
        $apepat    = $request->vm_usuario_apepat; 
        $apemat    = $request->vm_usuario_apemat; 
        $dni       = $request->vm_usuario_dni; 
        $direccion = $request->vm_usuario_direcion; 
        $telefono  = $request->vm_usuario_telefono; 
        $correo    = $request->email; 

        $facturacion = $request->tipo_facturacion; 
        

        $codigo_user = $request->vm_usuario_codigo;

        $id = $request->vm_id_user;
       

        $old_password = User::find($id)->password;

            if($old_password==$request->vm_usuario_password){

                $password = $old_password;

            }else{

                

                $password = bcrypt($request->vm_usuario_password);
            }


        $cargo     = $request->vm_usuario_cargo; 
        $estado    = $request->vm_usuario_estado; 
        
        $old_imagen = User::where('identificacion',$dni)->where('empresa',$empresa)->first();

       

         $imagen  = (isset($old_imagen))?$old_imagen->foto:'profiles/default.jpg';
        

         if ($request->hasFile('set_imagen')) {

            $dir = 'profiles/';
            $ext = strtolower($request->file('set_imagen')->getClientOriginalExtension()); 
            $fileName = str_random() . '.' . $ext;
            $request->file('set_imagen')->move($dir, $fileName);

            $imagen = 'profiles/'.$fileName;

        }

        

        if($request->bnt_elimina_foto == 1){

            $imagen  = 'profiles/default.jpg';
        }
        
       

        $do_usuario = ($request->flag_otra_empresa)?1:0; 

        $stmt =  DB::getPdo()->prepare("begin WEB_USUARIOS_INPUPD(:p1,:p2,:p3,:p4,:p5,:p6,:p7,:p8,:p9,:p10,:p11,:p12,:p13,:p14,:p15,:p16,:rpta); end;");
        $stmt->bindParam(':p1', $empresa, PDO::PARAM_STR);
        $stmt->bindParam(':p2', $nombres, PDO::PARAM_STR);
        $stmt->bindParam(':p3', $apepat, PDO::PARAM_STR);
        $stmt->bindParam(':p4', $apemat, PDO::PARAM_STR);
        $stmt->bindParam(':p5', $dni, PDO::PARAM_STR);
        $stmt->bindParam(':p6', $direccion, PDO::PARAM_STR);
        $stmt->bindParam(':p7', $telefono, PDO::PARAM_STR);
        $stmt->bindParam(':p8', $correo, PDO::PARAM_STR);
        $stmt->bindParam(':p9', $password, PDO::PARAM_STR);
        $stmt->bindParam(':p10', $cargo, PDO::PARAM_STR);
        $stmt->bindParam(':p11', $estado, PDO::PARAM_STR);
        $stmt->bindParam(':p12', $imagen, PDO::PARAM_STR);
        $stmt->bindParam(':p13', $id, PDO::PARAM_STR);
        $stmt->bindParam(':p14', $do_usuario, PDO::PARAM_STR);
        $stmt->bindParam(':p15', $facturacion, PDO::PARAM_STR);
        $stmt->bindParam(':p16', $codigo_user, PDO::PARAM_STR);
        $stmt->bindParam(':rpta', $rpta, PDO::PARAM_INT);
        $stmt->execute();

        
        return $rpta;

    }

}
