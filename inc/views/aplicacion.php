<?php
/**
 * Aplicacion File Doc Comment
 * 
 * Cuerpo de la aplicacion
 * 
 * PHP Version 5.1.4
 * 
 * @category Aplicacion
 * @package  cni/inc/views/
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
session_start();
session_regenerate_id();
if ( isset( $_SESSION['usuario']) ) {
    include_once '../clases/Aplicacion.php';
    $cleanOpt = "Error - Seccion no encontrada";
    
    if ( ctype_alnum( $_POST['opcion'] ) ) {
        $cleanOpt = $_POST['opcion'];
        include_once 'test.php';
        
    }
    if ( $cleanOpt == 'Salir' ) {
        header( "Location:../logout.php" );
    }
    echo $cleanOpt;
}
