<?php
/**
 * inc/validacion.php Se encarga de validar el usuario y mostrar el menu
 * 
 * Se le envian los datos de inicio de sesion, realiza la autentificacion
 * una vez autentificado muestra el menu de la aplicacion
 * 
 * @author Ruben Lacasa Mas <rubendx@gmail.com>
 * @version 2.1
 */
require_once 'variables.php';

if ( isset($_POST['opcion']) && $_POST['opcion'] == 0) {
    
    $usuario = new Usuarios();
    $usuario->validacion( $_POST );
      
    if ( !$usuario->esValido() )
        header("Location:../index.php?error=1");
    else 
        header("Location:../index.php");  
}




