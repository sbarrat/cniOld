<?php
/**
 * Avisos File Doc Comment 
 * 
 * Gestion de avisos de Cumpleaños y Contratos - Borrar
 * 
 * Muestra los cumpleaños de los clientes, agrupados por los de hoy,
 * los de mañana y los de los proximo 60 dias
 * Tambien muestra los contratos de clientes que finalizan hoy, los
 * que finalizan mañana y los que finalizan en los proximos 60 dias
 * 
 * PHP Version 5.1.4
 * 
 * @category Avisos
 * @package  cni/inc
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
$ssid = session_id(); //comprobamos si existe el id de session
if ( empty( $ssid ) ) {
    session_start();
} // si no existe iniciamos sesion
if ( isset( $_SESSION[ 'usuario' ] ) ) {
    include_once 'variables.php';
        $avisos = new Avisos();
        $telefonos = new Telefonos();
    if ( isset( $_POST[ 'opcion' ] ) ) {
        if ( $_POST[ 'opcion' ] == 0 ) {
            $avisos->verAvisos();
        }
        else {
            echo $telefonos->verTelefonos();
        }
    } else {
        $avisos->verAvisos();
    }
}
