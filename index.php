<?php
require_once 'inc/variables.php';
/**
 * CNI version 2.1
 * 
 * Aplicacion de gestion de centros de negocios
 */
session_start();
error_reporting(E_ALL); //Todos los errores menos los deprecated
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="estilo/cni.css" rel="stylesheet" type="text/css"></link>
<link href="estilo/calendario.css" rel="stylesheet" type="text/css"></link>
<script type="text/javascript" src='js/prototype.js'></script>
<script type="text/javascript" src="js/calendar.js"></script>
<script type="text/javascript" src="js/lang/calendar-es.js"></script>
<script type="text/javascript" src="js/calendar-setup.js"></script>
<script type="text/javascript" src='js/independencia.js'></script>
<title>Aplicacion Gestion Independencia Centro Negocios 2.1</title>
</head>
<body>
<div id='cuerpo'>
<?php
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
if ( isset($_SESSION['usuario'] ) ) {
    $aplicacion = new Aplicacion();
    echo '<div id="menu_general">';
    echo $aplicacion->menu();
    echo '</div>';
} else {
    ?>
<div id='registro'>
<div style='margin-left: 200px'><img src='imagenes/logotipo2.png'
	width='538px' alt='The Perfect Place' /></div>
<div style='margin-left: 300px'>
	<?php
    if (isset($_GET["exit"]))
        echo '<span class="ok">Sesion Cerrada</span>';
    if (isset($_GET["error"]))
        echo '<span class="ko">Usuario o Contraseña Incorrecta</span>';
    ?>
	<form id='login_usuario' onsubmit='validar();return false'
	method='post'>
<table width='30%' class="login">
	<tr>
		<td align='right'>Usuario:</td>
		<td><input type='text' id="usuario" accesskey="u" tabindex="1" /></td>
	</tr>
	<tr>
		<td align='right'>
		Contraseña:
		</td>
		<td>
		<input type='password' id="passwd" accesskey="c" tabindex="2" />
		</td>
	</tr>
	<tr>
		<td align='center' colspan="2"><input type='submit' class='boton'
			accesskey="e" tabindex="3" value='[->]Entrar' /></td>
	</tr>
	<tr>
		<td colspan='2'></td>
	</tr>
</table>
</form>
</div>

<div style='margin-left: 300px'>
<p><span class="etiqueta">Desarrollado por sbarrat::<?php
    echo date('Y');
    ?></span>
</p>
<p><a href='http://www.ensenalia.com'><img src='imagenes/ensenalia.jpg'
	width='128' /></a></p>
</div>
</div>
<?php
}
?>
</div>

<div id='datos_interesantes'></div>
<div id='debug'></div>
<?php
if ( isset($_SESSION['usuario'] ) ) {
    echo '<div id="avisos">';
    include_once 'inc/avisos.php'; //Se muestran los avisos solo con el include
    echo '</div>';
    echo '<div id="resultados"></div>'; //linea de los resultados de busqueda
    echo '<div id="formulario"></div>'; //linea del formulario
}
?>
</body>
</html>