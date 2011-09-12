<?php
/**
 * Menu File Doc Comment
 * 
 * Menu de la aplicación
 * 
 * PHP Version 5.1.4
 * 
 * @category Menu
 * @package  cni/inc/views/
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
if ( isset($_SESSION['usuario'] ) ) {
    $aplicacion = new Aplicacion();
    $opciones = $aplicacion->menu();
    ?>
	<div id="menu" class="span-24 last">
    <?php 
    foreach ( $opciones as $opcion ) {
        echo '<div class="opcion span-2" id="' . $opcion['nombre'] .'">
    		<img src="' . $opcion['imagen'] . '" 
				alt="' . $opcion['nombre'] . '" width="32" /><br/>' . 
            $opcion['nombre'] . '</div>';
    }
    ?>
		<div class="opcion span-2 last" id="Salir">
			<img src="imagenes/salir.png" width="32" alt="Salir"/>
			<br/>
			Salir
		</div>
	</div>
	<div id="aplicacion">
	<!-- Autogenerado por la opcion -->
	</div>
	<script type="text/javascript">
	$('.opcion').click( function() {
		$.post('inc/views/aplicacion.php','opcion='+this.id, function(data){
			$('#aplicacion').html(data);
			});		
	});
	</script>
    <?php  } 