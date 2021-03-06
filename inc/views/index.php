<?php
/**
 * Index File Doc Comment
 * 
 * Formulario de login en la aplicación
 * 
 * PHP Version 5.1.4
 * 
 * @category Index
 * @package  cni/inc/views/
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
session_start();
session_regenerate_id();
if ( isset($_SESSION['usuario'] ) ) {
    include_once '../variables.php';
    include_once 'menu.php';
    include_once 'busqueda.php';
    include_once 'principal.php';
    include_once 'avisos.php';
} else {
    include_once 'login.php';
}
