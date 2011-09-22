<?php
/**
 * Index File Doc Comment
 * 
 * Fichero principal de la aplicacion
 * 
 * PHP Version 5.1.4
 * 
 * @category Index
 * @package  cni
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
/**
 * FIXME! Arreglar todos los enlaces de la nueva estructura
 * FIXME! No salen los clientes de oficina móvil en 
 * el listado de clientes para facturación.
 * FIXME! Ana no puede ver el cuadro de E/S, agenda…varias cosas 
 * desde su ordenador. Es como si estuviera vacío.
 * FIXME! Habrá que añadir un cliente de dom. integral en el 
 * acumulado a 31/12/2007.
 * FIXME! Seprotec: sale duplicado (código actual 2058).
 * FIXME! Salen en cumpleaños los clientes que ya no están activos.
 * FIXME! Modificar contraseña de gestión y poder hacerlo nosotras 
 * (si es fácil y cuesta poco).
 * FIXME! Volcar a búsqueda avanzada lo que se ponga en 
 * casilla “nombre comercial” de Apdo. proveedores.
 * FIXME! Al sacar los listados filtrando por categorías, 
 * a la hora de imprimir, no sale el nombre del primer cliente.
 * FIXME! Al hacer clic en los clientes en la pantalla de avisos 
 * deberian abrirse los clientes
 */
require_once 'inc/variables.php';
session_start();
error_reporting( E_ALL ); //Todos los errores menos los deprecated
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8" >
<meta name="author" content="Ruben Lacasa" >
<script src="js/jquery-1.4.4.min.js"></script>
<script src="js/jquery-ui-1.8.8.custom.min.js"></script>
<script src='js/independencia.js'></script>
<link rel="stylesheet" href="estilo/blueprint/screen.css" 
media="screen, projection">
<link rel="stylesheet" href="estilo/blueprint/print.css" 
media="print">
<!--[if lt IE 8]>
<link rel="stylesheet" href="estilo/blueprint/ie.css" 
media="screen, projection" />
<![endif]-->
<link rel="stylesheet" href="estilo/custom-theme/jquery-ui-1.8.8.custom.css">
<link rel="stylesheet" href="estilo/perfect.css" >
<title>Aplicacion Gestion Independencia Centro Negocios 2.1</title>
</head>
<body>
<div id="cuerpo" class='container showgrid'>
<!-- Autogenerado -->	
</div>
<script type='text/javascript'>
$('document').ready( function() {
	$('#cuerpo').load('inc/views/index.php');
} );
</script>
</body>
</html>