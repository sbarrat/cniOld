<?php
include_once '../clases/Alias.php';
$alias = new Alias();
if (isset( $cleanOpt ) ){
    $tabla = $cleanOpt;
} else {
    die ("La tabla especificada no es valida");
}
$alias->setTabla( $tabla );
$campos = $alias->getCampos();
$posicion = 0;
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
        size='" . $campo['size'] . "'
        tabindex='" . $posicion . "' />
        </p>";
    }
    if ( $campo['tipo'] == 'textarea' ) {
        echo "
        <p>
         <label for='". $campo['variable'] . "' >" .
        utf8_encode( $campo['campof'] ) . "</label><br/>
        <textarea name='" . $campo['variable'] . "' 
        rows='" . $campo['size'] . "' cols='46' 
        tabindex='" . $posicion . "'></textarea>
        </p>";
    }
    if ( $campo['tipo'] == 'select' ) {
        echo "
        <p>
         <label for='". $campo['variable'] . "' >" .
        utf8_encode( $campo['campof'] ) . "</label><br/>
        <select name='". $campo['variable']."' tabindex='" . $posicion . "'>";
        foreach ( $alias->getValoresSelect( $campo['depende'] ) as $valores ) {
            echo "<option value='". $valores['id']. "' >
            ". utf8_encode( $valores['Nombre'] ) . "
            </option>";
        }
        echo "</select></p>";
    }
    $posicion++;
}

