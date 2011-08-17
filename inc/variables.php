<?php
/**
 * 
 * inc/variables.php Fichero de Autocarga de Clases
 * 
 * Se require de este fichero en la cabezara para la autocarga de las clases
 *  
 * PHP Version 5.1.4
 * 
 * @author Ruben Lacasa Mas <rubendx@gmail.com>
 * @version 2.1
 */
function __autoload ($class_name)
{
    include 'clases/' . $class_name . '.php';
}

