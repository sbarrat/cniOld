<?php
$vars['texto'] = $_GET['term'];
include_once '../clases/Busqueda';
$busqueda = new Busqueda();
$busqueda->BusquedaAvanzada($vars);

echo json_encode($_GET['term']);