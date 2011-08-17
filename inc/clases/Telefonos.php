<?php
require_once 'Sql.php';
/** 
 * Clase que gestiona los telefonos
 * 
 * PHP Version 5.1.4
 * 
 * @author Ruben Lacasa Mas <rubendx@gmail.com>
 * @version 2.0
 * @package clases
 * 
 */
class Telefonos extends Sql
{
    /**
     * 
     */
    public function __construct ()
    {
        parent::__construct();
    }
    /**
     * Funcion que muestra los telefonos por pantalla
     * @return string
     */
    public function verTelefonos ()
    {
        $cadena = '<input type="button" value="[v]Ocultar telefonos" 
	onclick="cerrar_tablon_telefonos()" />';
        $cadena .= $this->listado('Telefono');
        $cadena .= $this->listado('Fax');
        $cadena .= $this->listado('Adsl');
        echo $cadena;
    }
    /**
     * Pasandole el servicio muestra su valor
     * @param string $servicio
     * @return string
     */
    public function listadoTelefonos ($servicio)
    {
        $cadena = '<p/><u><b>' . $servicio . ' del centro</b></u><p/>';
        $sql = "SELECT `c`.`Id`, `c`.`Nombre`, `z`.`valor`, `z`.`servicio`, 
		(
			SELECT `valor`
			FROM `z_sercont`
			WHERE `servicio` LIKE 'Codigo Negocio'
			AND `idemp` LIKE `z`.`idemp`
			LIMIT 1
		) 
		AS `Despacho`, `c`.`Categoria`
		FROM `clientes` AS `c`
		INNER JOIN `z_sercont` AS `z` ON `c`.`Id` = `z`.`idemp`
		WHERE `z`.`servicio` LIKE '" . $servicio . "'
		ORDER BY `Despacho`";
        parent::consulta($sql);
        $cadena .= '<table><tr>';
        $numeroLinea = 0;
        $color = "";
        if (parent::totalDatos() != 0) {
            foreach (parent::datos() as $resultado) {
                $color = '#CCC';
                if (preg_match("/despacho/", $resultado['Categoria']))
                    $color = '#69C';
                if (preg_match("/domicili/", $resultado['Categoria']))
                    $color = '#F90';
                if ($numeroLinea % 4 == 0)
                    $cadena .= '</tr><tr>';
                $cadena .= '<th bgcolor="' . $color . '" align="left">
			<a href="javascript:muestra( ' . $resultado['Id'] . ' )">
			' . $resultado['Despacho'] . '-
			' . Auxiliar::traduce($resultado['Nombre']) . '-<u><b>' .
                 $resultado['valor'] . '</b></u></a></th>';
                $numeroLinea ++;
            }
        }
        $cadena .= '</tr></table>';
        return $cadena;
    }
}
