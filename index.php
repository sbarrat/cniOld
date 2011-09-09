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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.8.custom.min.js"></script>
<script type="text/javascript" src='js/independencia.js'></script>
<link href="estilo/blueprint/screen.css" rel="stylesheet" type="text/css"></link>
<link href="estilo/custom-theme/jquery-ui-1.8.8.custom.css" rel="stylesheet" type="text/css"></link>
<link href="estilo/perfect.css" rel="stylesheet" type="text/css"></link>
<title>Aplicacion Gestion Independencia Centro Negocios 2.1</title>
</head>
<body>
<div id="cuerpo" class='container showgrid'>
	<div class="span-12 prepend-5 last">
	<!--  <img src='imagenes/logotipo2.png' width='538px' alt='The Perfect Place' /> -->
	</div>
	<br/>
	<div class="span-12 prepend-6 last">
		<form id="login" action="" method="post">
			<fieldset>
				<legend>Acceso Usuarios</legend>
				<p>
					<label for="usuario">Usuario:</label><br/>
					<input type="text" class="title" name="usuario" id="usuario" />
				</p>
				<p>
					<label for="password">Contraseña:</label><br/>
					<input type="password" class="title" name="password" id="password" />
				</p>
				<p> 
              		<input type="submit" value="Entrar" /> 
              		<input type="reset" value="Cancelar" /> 
            	</p> 
				<div class='status span-10 prepend-1 last'></div>	
				</fieldset>
		</form>
	</div>
	<div id='footer' class='span-24 last'>
		<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/3.0/">
			<img alt="Licencia Creative Commons" style="border-width:0" 
			src="http://i.creativecommons.org/l/by-nc-nd/3.0/88x31.png" />
		</a>
		<br />
		<span xmlns:dct="http://purl.org/dc/terms/" 
			href="http://purl.org/dc/dcmitype/Text" property="dct:title" 
			rel="dct:type">
			CNI 2.1
		</span> por 
		<a xmlns:cc="http://creativecommons.org/ns#" 
			href="http://sbarrat.wordpress.com" 
			property="cc:attributionName" 
			rel="cc:attributionURL">&copy;Rubén Lacasa::<?php echo date( 'Y' ); ?>
		</a> 
	</div>
</div>
<script type="text/javascript">
$('#login').submit(function(){
	$.post("inc/validacion.php",$("#login").serialize(),function(data){
		$('#cuerpo').html(data);
	});
});
</script>
</body>
</html>