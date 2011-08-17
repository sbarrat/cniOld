<?php
/**
 * 
 * inc/logout.php Cierre de sesion
 * 
 * Cierra la session y vuelve a la pagina principal
 *  
 * PHP Version 5.1.4
 * 
 * @author Ruben Lacasa Mas <rubendx@gmail.com>
 * @version 2.1
 */
session_start();
session_destroy();
header('Location:../index.php?exit=0');

