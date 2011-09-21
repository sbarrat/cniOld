<?php
/**
 * Resultados File Doc Comment
 * 
 * Devuelve los resultados de la busqueda
 * 
 * PHP Version 5.1.4
 * 
 * @category Busqueda
 * @package  cni/inc/views/
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
session_start();
session_regenerate_id();
$result = array ("Busqueda Invalida");
if ( isset( $_SESSION['usuario'] ) )
{
    include_once '../clases/Busqueda.php';
    $busqueda = new Busqueda();
    if ( isset ( $_GET['term'] ) ) {
        $cleanTerm = htmlentities( $_GET['term'] );
        $result = $busqueda->buscar( $cleanTerm );
        echo json_encode( $result );
    } 
} 

