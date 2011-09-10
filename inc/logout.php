<?php
/**
 * Logout File Doc Comment
 * 
 * Cierre de la aplicacion
 * 
 * PHP Version 5.1.4
 * 
 * @category Logout
 * @package  cni/inc/views/
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
session_start();
session_destroy();
header( 'Location:../index.php' );

