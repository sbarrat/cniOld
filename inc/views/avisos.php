<?php
/**
 * Avisos File Doc Comment
 * 
 * Seccion de los avisos
 * 
 * PHP Version 5.1.4
 * 
 * @category Avisos
 * @package  cni/inc/views/
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
if ( isset( $_SESSION['usuario'] ) ) {
    $avisos = new Avisos();
    ?>
    <div class='span-3 last'>
    <input type='button' id='botonAvisos' value="Avisos" />
    </div>
    <div id='avisos'>
    <div class='span-11'>
    <?php 
    echo "<pre>";
    var_dump( $avisos->verCumples() );
    echo "</pre>";
    //echo $avisos->verAvisos();
    ?>
    </div>
    <div class='span-11 last'>
    
    </div>
    
    </div>
	<script type="text/javascript">
	$('#botonAvisos').click( function() {
		$("#avisos").toggle('slow');
		
	});
	</script>
    <?php  } ?>