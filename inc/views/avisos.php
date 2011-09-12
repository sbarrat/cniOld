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
    <div id='avisos' class='span-24 last'>
    <div class='span-12'>
    <h4>Cumplen Años Proximamente</h4>
    <?php
    $cumples = $avisos->verCumples();
    $contratos = $avisos->verContratos();
    if ( count( $cumples ) == 0 ) {
        echo "<div class='span-12 last'>
        Nadie Cumple años en los proximos dias
        </div>";
    } else {
        
        foreach ( $cumples as $cumple ) {
            echo "<div class='linea span-12 last'>" .
            $cumple[1]. " ". $cumple[2] . " " . $cumple[4] .
            "</div>";
        }
    }
    ?>
    </div>
    <div class='span-12 last'>
    <h4>Finalizan su contrato Proximamente</h4>
    <?php 
    if ( count( $contratos ) == 0 ) {
        echo "<div class='span-12 last'>
        Nadie finaliza su contrato proximamente
        </div>";
    } else {
        foreach ( $contratos as $contrato ) {
            echo "<div class='linea span-12 last'>" .
            Fecha::diaYmes( $contrato['renovacion'] ). " " . 
            $contrato['Nombre'] . 
            "</div>";
        }
    }
    ?>
    </div>  
    </div>
	<script type="text/javascript">
	$('#botonAvisos').click( function() {
		$("#avisos").toggle('slow');
		
	});
	$('document').ready( function() {
		$(".linea:even").css("background-color","#EBE1E9");
		} );
	</script>
    <?php  } ?>