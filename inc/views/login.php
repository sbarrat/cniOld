<?php 
/**
 * Login File Doc Comment
 * 
 * Formulario de login en la aplicación
 * 
 * PHP Version 5.1.4
 * 
 * @category Login
 * @package  cni/inc/views/
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
?>
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
			<div class='status span-10 last'></div>	
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
<script type="text/javascript">
$('#login').submit(function(){
	$.post("inc/validacion.php",$("#login").serialize(),function(data){
		$('.status').html(data);
	});
	return false;
});
</script>