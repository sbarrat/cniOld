<?php
/**
 * Borrar
 * inc/datos.php Gestion de avisos de Cumpleaños, Contratos, Busqueda Avanzada
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
require_once 'variables.php';
$avisos = new Avisos();
$busqueda = new Busqueda();
if (isset($_POST['dato'])) {
    if ($_POST['dato'] == 1)
        $avisos->verAvisos(false, true);
    elseif ($_POST['dato'] == 2)
        $avisos->verAvisos(true, false);
    else {
        $busqueda->FormularioBusquedaAvanzada();
    }
} elseif (isset($_POST['texto'])) {
    $busqueda->BusquedaAvanzada($_POST);
}
