<?php
/**
 * 
 * inc/avisos.php Gestion de avisos de Cumpleaños y Contratos
 * 
 * Muestra los cumpleaños de los clientes, agrupados por los de hoy,
 * los de mañana y los de los proximo 60 dias
 * Tambien muestra los contratos de clientes que finalizan hoy, los
 * que finalizan mañana y los que finalizan en los proximos 60 dias
 * 
 * PHP Version 5.1.4
 * 
 * @author Ruben Lacasa Mas <rubendx@gmail.com>
 * @version 2.1
 */
$ssid = session_id(); //comprobamos si existe el id de session
if ( empty( $ssid ) )
    session_start(); // si no existe iniciamos sesion
if ( isset( $_SESSION[ 'usuario' ] ) ) {
    require_once 'variables.php';
        $avisos = new Avisos();
        $telefonos = new Telefonos();
    if ( isset( $_POST[ 'opcion' ] ) ) {
        if ( $_POST[ 'opcion' ] == 0 )
            $avisos->verAvisos();
        else
            echo $telefonos->verTelefonos();
    } else {
        $avisos->verAvisos();
    }
}
