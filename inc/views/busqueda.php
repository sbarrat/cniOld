<?php
/**
 * Busqueda File Doc Comment
 * 
 * Busqueda de datos en la aplicacion
 * 
 * PHP Version 5.1.4
 * 
 * @category Busqueda
 * @package  cni/inc/views/
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
if ( isset ( $_SESSION['usuario'] ) ) {
    ?>
    <div class='span-24 ui-widget'>
   
    <label for='buscar' class='small'>Buscar:</label>
    <input type='text' class='text' id='buscar' name='buscar' />
    </div>
 
    <script type="text/javascript">
    $("#buscar").autocomplete({
		source: "inc/views/resultados.php",
		minLength: 2,
		maxRows: 12,
		select: function(event, ui) {
			$.post("inc/views/resultados.php",
					{id=ui.item.id;value=ui.item.value;tabla=ui.item.tablag}
			function(data) {
				$("#principal").html(data);
				};
		}
    });
    </script>
    <?php 
}
