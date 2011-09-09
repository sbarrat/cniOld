<?php
/**
 * Variables File Doc Comment
 * 
 * Se require de este fichero en la cabezara para la autocarga de las clases
 * 
 * PHP Version 5.1.4
 * 
 * @category Variables
 * @package  cni/inc
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
/**
 * Autocarga de Clases
 * 
 * @param string $className
 */
function __autoload ($className)
{
    if ( ctype_alpha( $className ) ) {
		include 'clases/' . $className . '.php';
    } else {
    	throw new Exception($error);
    }
}
/**
 * Comprueba si los parametros introducidos son correctos
 * @param string|array $vars
 * @return string|array|boolean
 */
function sanitize ( $vars )
{
    return $vars;
}

