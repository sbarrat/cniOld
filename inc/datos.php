<?php
require_once 'variables.php';
$avisos = new Avisos();
$busqueda = new Busqueda();
if ( isset ($_POST['dato']))
{
    if ( $_POST[ 'dato' ] == 1)
        $avisos->verAvisos( false, true );
    elseif ( $_POST[ 'dato' ] == 2 )
        $avisos->verAvisos( true, false );
    else {
        $busqueda->FormularioBusquedaAvanzada();
    }
        
}
elseif ( isset($_POST[ 'texto' ] ) )
{
    $busqueda->BusquedaAvanzada($_POST);
     
}
