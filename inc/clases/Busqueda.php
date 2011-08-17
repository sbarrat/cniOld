<?php
require_once 'Sql.php';
/** 
 * @author ruben
 * 
 * 
 */
class Busqueda extends Sql
{
    /**
     * 
     */
    public function __construct ()
    {
        parent::__construct();
    }
    /**
     * Busqueda de un dato en una tabla
     * @param string $var
     * @param string $tabla
     */
    public function BusquedaSimple ($var, $tabla)
    {
        // TODO
    }
    /**
     * Muestra el formulario de busqueda avanzada
     */
    public function FormularioBusquedaAvanzada ()
    {
        $cadena = '
	<table class="tabla">
	<tr>
		<th aling="left">
		<span class="boton" onclick="cierralo()" onkeypress="cierralo()">
		[X] Cerrar
		</span>
		</th>
		<th>Busqueda Avanzada</th>
	</tr>";
	<tr>
		<th colspan="2">
		<form id="busqueda_avanzada" 
			onsubmit="busqueda_avanzada(); return false" >
			<input type="text" name="texto" size="40" />
			<input type="submit" name="Buscar" value="Buscar" />
		</form>
		</th>
	</tr>
	<tr>
		<td colspan="2">
			<div id="resultados_busqueda_avanzada"></div>
		</td>
	</tr>
	</table>';
        echo $cadena;
    }
    /**
     * Procesa la consulta y le da formato para la visualizacion
     * @param string $sql Consulta sql
     * @param array $campos array con campos a mostrar
     * @param string $tabla titulo de la seccion a mostar
     * @param string $texto texto buscado
     * @return string devolvemos los datos con formato
     */
    private function procesaDatosBusquedaAvz ($sql, $campos, $tabla, $texto)
    {
        $cadena = '<p>
        <strong>
        <u>Resultados busqueda en ' . $tabla . '</u>
        </strong>
        </p>';
        parent::consulta($sql);
        
        if (parent::totalDatos() != 0) {
            foreach (parent::datos() as $resultado) {
                
                $cadena .= '<p class="' . Auxiliar::clase() . '">
					<a href="javascript:muestra(' .
                     $resultado[$campos[0]] . ')">';
                    
                     for ($i = 1; $i < count($campos); $i ++) 
                        $cadena .= 
                           preg_replace(
                        	'/' . $texto . '/iu', 
                    		'<span class="encontrado">' . $texto . '</span>', 
                            Auxiliar::traduce($resultado[$campos[$i]])
                            ) 
                            . ' ';
                    
                     $cadena .= '</a></p>';
            }
        } else 
            $cadena .= '<p class="' . Auxiliar::clase() . '">
		    No hay resultados de <strong>' . $texto . '</strong> 
		    en  ' . $tabla . '</p>';
        
        return $cadena;
    }
    /**
     * Busqueda avanzada de varios datos
     * @param array $vars
     */
    public function BusquedaAvanzada ($vars)
    {
        $cadena = '';
        if ($vars['texto'] != NULL)
            $cadena .= 'Busqueda de:<strong>' . $vars['texto'] . '</strong>';
        /**
         * Chequeamos si es un telefono
         */
        $token = preg_replace("/ /", "//", $vars['texto']);
        if (is_numeric($token) && strlen($token) == 9) {
            $vars['texto'] = $token;
        }
        $vars['texto'] = parent::escape(Auxiliar::codifica($vars['texto']));
        $sql = "SELECT `c`.`id`, `c`.`Nombre`, `c`.`Contacto`, 
    	`p`.`nombre`, `p`.`apellidos`
		FROM `clientes` AS `c`
		JOIN `pempresa` AS `p` ON `c`.`id` = `p`.`idemp`
		WHERE (`c`.`Nombre` LIKE '%" . $vars['texto'] . "%'
		OR `c`.`Contacto` LIKE '%" . $vars['texto'] . "%'
		OR `p`.`nombre` LIKE '%" . $vars['texto'] . "%'
		OR `p`.`apellidos` LIKE '%" . $vars['texto'] . "%'
		OR CONCAT( `p`.`nombre`, ' ' ,`p`.`apellidos`, '%' ) LIKE '%" .
         $vars['texto'] . "%')
    	and `c`.`Estado_de_cliente` = '-1'";
        $tabla = 'clientes';
        $campos = array('id', 'Nombre', 'Contacto', 'nombre', 'apellidos');
        $cadena .= $this->procesaDatosBusquedaAvz($sql, $campos, $tabla, 
        $vars['texto']
        );
        /**
         * Consultamos telefonos de cliente
         */
        $sql = "SELECT `id`, `Nombre` FROM `clientes` 
		WHERE 
		(  REPLACE( `Tfno1`, ' ', '' ) LIKE '%" .
         $vars['texto'] . "%' 
		OR REPLACE( `Tfno2`, ' ', '' ) LIKE '%" .
         $vars['texto'] . "%' 
		OR REPLACE( `Tfno3`, ' ', '' ) LIKE '%" .
         $vars['texto'] . "%' )
    	AND `Estado_de_cliente` = '-1'";
        $tabla = 'Telefonos de clientes';
        $campos = array('id', 'Nombre');
        $cadena .= 
            $this->procesaDatosBusquedaAvz(
                $sql, 
                $campos, 
                $tabla, 
                $vars['texto']
                );
        /**
         * Consultamos telefonos de empleados
         */
        $sql = "SELECT `c`.`id`, `p`.`nombre`, `p`.`apellidos`, `c`.`Nombre` 
		FROM `pempresa` as `p` 
		INNER JOIN `clientes` as `c` 
		ON `c`.`id` = `p`.`idemp`
		WHERE REPLACE(`p`.`telefono`, ' ', '') 
		LIKE '%" . $vars['texto'] . "%'
		AND `c`.`Estado_de_cliente` = '-1'";
        $tabla = 'Telefonos de clientes';
        $campos = array('id', 'nombre', 'apellidos', 'Nombre');
        $cadena .= 
            $this->procesaDatosBusquedaAvz(
                $sql, 
                $campos, 
                $tabla, 
                $vars['texto']
                );
        /**
         * consultamos telefonos de pcentral
         */
        $sql = "SELECT `c`.`id` ,`p`.`persona_central`, `c`.`Nombre` 
		FROM `pcentral` as `p` 
		INNER JOIN `clientes` as `c` 
		ON `c`.`id` = `p`.`idemp` 
    	WHERE REPLACE( `p`.`telefono`, ' ', '') 
    	LIKE '%" . $vars['texto'] . "%'
    	AND `c`.`Estado_de_cliente` = '-1'";
        $tabla = 'Telefonos de la central';
        $campos = array('id', 'persona_central', 'Nombre');
        $cadena .= 
            $this->procesaDatosBusquedaAvz(
                $sql, 
                $campos, 
                $tabla, 
                $vars['texto']
                );
        /**
         * Consultamos datos de proveedores
         */
        $sql = "SELECT `c`.`id`, `c`.`Nombre`, `p`.`nombre`, `p`.`apellidos`
		FROM `proveedores` AS `c`
		left JOIN `pproveedores` AS `p` ON `c`.`id` = `p`.`idemp`
		WHERE `c`.`Nombre` LIKE '%" . $vars['texto'] . "%'
		OR `p`.`nombre` LIKE '%" . $vars['texto'] . "%'
		OR `p`.`apellidos` LIKE '%" . $vars['texto'] . "%'
		OR CONCAT( `p`.`nombre`, '', `p`.`apellidos`, '%' ) 
		LIKE '%" . $vars['texto'] . "%'";
        $tabla = 'Proveedores';
        $campos = array('id', 'Nombre', 'nombre', 'apellidos');
        $cadena .= 
            $this->procesaDatosBusquedaAvz(
                $sql, 
                $campos, 
                $tabla, 
                $vars['texto']
                );
       
        /**
         * Consultamos en telecomunicaciones
         */
        $sql = "Select `c`.`ID`, `c`.`Nombre`, `z`.`valor`, `z`.`servicio` 
        FROM `clientes` AS `c`
		INNER JOIN `z_sercont` AS `z` 
		ON `c`.`ID` LIKE `z`.`idemp`
		WHERE REPLACE(`valor`, ' ', '') LIKE 
		'%" . $vars['texto'] . "%'
		AND `c`.`Estado_de_cliente` = '-1'";
        $tabla = 'Telecomunicaciones';
        $campos = array('ID', 'Nombre', 'valor', 'servicio');
        $cadena .= 
            $this->procesaDatosBusquedaAvz(
                $sql, 
                $campos, 
                $tabla, 
                $vars['texto']
                );
        /**
         * Mostramos el resultado final
         */
        echo $cadena;
    }
}
