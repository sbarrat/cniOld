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
/*if ( isset($_POST['opcion']) && $_POST['opcion'] == 0) {
    if ( ctype_alnum( $_POST['usuario'] ) && ctype_alnum( $_POST['passwd'] ) ) {
		$usuario = new Usuarios();
    	if ( !$usuario->validacion( $_POST ) ) {
        	header( "Location:../index.php?error=1" );
    	} else { 
        	header( "Location:../index.php" );
    	}
    } else {
    	header( "Location:../index.php?error=1" );
    }  
}*/
$usuario = new Usuarios();
$errorText = "Usuario/Contraseña Invalido";
if ( isset( $_POST['usuario']) && ( isset( $_POST['password'] ) ) ) {
    $cleanVars = sanitize( $_POST );
    if ( ( $cleanVars = sanitize( $_POST ) ) !== false ) {
       $check = $usuario->validacion( $cleanVars );
    } else {
        $errorText = "Usuario/Contraseña Invalido";
    }
} else {
    
}