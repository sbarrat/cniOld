<?php
include_once '../clases/Alias.php';
$alias = new Alias();
$tabla = 'servicios2';
$alias->setTabla( $tabla );
$campos = $alias->getCampos();
foreach ( $campos as $campo )
{
    
    if ( $campo['tipo'] == 'hidden') {
        echo "
        <input type='hidden' name='". $campo['variable'] . "' />
        ";
    }
    if ( $campo['tipo'] == 'text' || $campo['tipo'] == 'date' || $campo['tipo'] == 'checkbox' ) {
        echo "
        <p>
        <label for='". $campo['variable'] . "' >" .
        utf8_encode( $campo['campof'] ) . "</label><br/>
        <input type='" . $campo['tipo'] . "' 
        name='" . $campo['variable'] . "' 
        size='" . $campo['size'] . "'/>
        </p>";
    }
    if ( $campo['tipo'] == 'textarea' ) {
        echo "
        <p>
         <label for='". $campo['variable'] . "' >" .
        utf8_encode( $campo['campof'] ) . "</label><br/>
        <textarea name='" . $campo['variable'] . "' 
        rows='" . $campo['size'] . "' ></textarea>
        </p>";
    }
    if ( $campo['tipo'] == 'select' ) {
        echo "
        <p>
         <label for='". $campo['variable'] . "' >" .
        utf8_encode( $campo['campof'] ) . "</label><br/>
        <select name='". $campo['variable']."'>";
        foreach ( $alias->getValoresSelect( $campo['depende'] ) as $valores ) {
            echo "<option value='". $valores['id']. "' >
            ". utf8_encode( $valores['Nombre'] ) . "
            </option>";
        }
        echo "</select></p>";
    }
}

