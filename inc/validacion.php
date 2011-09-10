<?php
/**
 * Validacion File Doc Comment
 * 
 * Se encarga de validar el usuario y mostrar el menu una vez autentificado
 * el usuario
 * 
 * PHP Version 5.1.4
 * 
 * @category Validacion
 * @package  cni/inc
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
require_once 'variables.php';
$usuario = new Usuarios();
$errorText = "<div class='error'>Usuario/Contraseña Invalido</div>";
if ( isset( $_POST['usuario']) && ( isset( $_POST['password'] ) ) ) {
    if ( ( $cleanVars = sanitize( $_POST ) ) !== false ) {
        if ( $usuario->validacion( $cleanVars ) ) {
            header( "Location:../index.php" );
        } else {
            echo $errorText;
        }  
    } else {
        echo $errorText;
    }
} else {
    echo $errorText;
}