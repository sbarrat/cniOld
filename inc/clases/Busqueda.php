<?php
/**
 * Busqueda File Doc Comment
 * 
 * Clase que gestiona las Busquedas
 * 
 * PHP Version 5.1.4
 * 
 * @category Busqueda
 * @package  inc/clases
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons by-sa 3.0
 * @link     https://github.com/sbarrat/cni
 */
require_once 'Sql.php';
/**
 * Busqueda Class Doc Comment
 * 
 * @category Class
 * @package  Busqueda
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com>
 * @license  http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons 3.0 by-sa 3.0
 * @version  Release: 2.1
 * @link     https://github.com/sbarrat/cni
 *
 */
class Busqueda extends Sql
{
    
    private $_tablas = array ('clientes','servicios2','proveedores','listin');
    private $_resultado = array ();
    /**
     * Busqueda de un dato en las tablas en las que se puede buscar
     * 
     * @param string $term
     */
    public function buscar ( $term )
    {
        $term = parent::escape( $term );
        $this->_busqueda( $term );
        return $this->_resultado;
    }
    /**
     * Enter description here ...
     * 
     * @param string $term
     */
    private function _busqueda ( $term )
    {
        foreach ( $this->_tablas as $tabla ) {
            $sql = "SELECT `Id`, `Nombre` FROM `". $tabla . "` 
        	WHERE `Nombre` LIKE '%" . $term . "%' 
        	ORDER BY `Nombre`";
            parent::consulta( $sql );
            $this->_preparaResultado( $tabla );
        }
    }
    /**
     * Enter description here ...
     * 
     * @param string $tabla
     */
    private function _preparaResultado( $tabla )
    {
        $datos = array();
        foreach ( parent::datos() as $dato ) {
            $datos[] = array(
            	'id'=>$dato['Id'], 
            	'value'=>$dato['Nombre'], 
            	'tabla'=>$tabla
            );
        }
        $this->_resultado = array_merge( $this->_resultado, $datos );
    }
    /*public function busquedaSimple ($vars)
    {
        
        $cadena = '';
        $extrasql = '';
        
        if ( $vars[ 'texto' ] != "" ) {
		
		    $vars[ 'texto' ] = 
			    Auxiliar::codifica( htmlentities( $vars[ 'texto' ] ) );
		
		    if ( $vars[ 'tabla' ] == 'clientes' ) {
			
			    $extrasql = " OR `Contacto` LIKE '%" . $vars[ 'texto' ] . "%' ";
		    }   
			
		    $sql = "SELECT * FROM `" . $vars[ 'tabla' ] . "` 
			WHERE `Nombre` LIKE '%" . $vars[ 'texto' ] . "%'
			" . $extrasql . " 
			ORDER by `Nombre`";
		
		    parent::consulta( $sql );
		
		    $cadena = '<input class="boton" type="button" 
				onclick="cierra_frm_busca()" value="[X]Cerrar">';
			
		    foreach ( parent::datos() as $resultado ) {
			    if ( isset( $resultado[ 'Id' ] ) )
				    $id = $resultado[ 'Id' ];
			    if ( isset( $resultado[ 'id' ] ) )
				    $id = $resultado[ 'id' ];	
			
				$cadena .='<div class="' . Auxiliar::clase() . '">
				<a href="javascript:muestra(' . $id . ')" >
				' . Auxiliar::traduce( 
				    preg_replace( '/'.$vars[ 'texto' ].'/iu' , 
					'<span class="resalta">
						' . strtoupper( $vars[ 'texto' ] ) . '
					</span>',
			        $resultado[ 'Nombre' ] 
			        ) ) 
			    . '</a></div>';
		    }
	    }
	
        return $cadena;
    
    }
    */
    /**
     * Muestra el formulario de busqueda avanzada
     */
    /*public function FormularioBusquedaAvanzada ()
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
   /* private function procesaDatosBusquedaAvz ($sql, $campos, $tabla, $texto)
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
    /*public function BusquedaAvanzada ($vars)
    {
        $cadena = '';
         
        if ($vars['texto'] != NULL){
            $vars['texto'] = parent::escape($vars['texto']); //Texto a mostrar
            $texto = Auxiliar::codifica($vars['texto']); //Texto a buscar
            $cadena .= 
				'Busqueda de:<strong>' . $vars['texto'] . '</strong>';
		}
        /**
         * Chequeamos si es un telefono
         */
      /*  $token = preg_replace("/ /", "//", $texto);
        if (is_numeric($token) && strlen($token) == 9) {
            $texto = $token;
            $vars['texto'] = $token;
        }
        
        $sql = "SELECT `c`.`id`, `c`.`Nombre`, `c`.`Contacto`, 
    	`p`.`nombre`, `p`.`apellidos`
		FROM `clientes` AS `c`
		JOIN `pempresa` AS `p` ON `c`.`id` = `p`.`idemp`
		WHERE (`c`.`Nombre` LIKE '%" . $texto . "%'
		OR `c`.`Contacto` LIKE '%" . $texto . "%'
		OR `p`.`nombre` LIKE '%" . $texto . "%'
		OR `p`.`apellidos` LIKE '%" . $texto . "%'
		OR CONCAT( `p`.`nombre`, ' ' ,`p`.`apellidos`, '%' ) LIKE '%" .
         $texto . "%')
    	and `c`.`Estado_de_cliente` = '-1'";
        $tabla = 'clientes';
        $campos = array('id', 'Nombre', 'Contacto', 'nombre', 'apellidos');
        $cadena .= $this->procesaDatosBusquedaAvz($sql, $campos, $tabla, 
        $vars['texto']
        );
        /**
         * Consultamos telefonos de cliente
         */
       /* $sql = "SELECT `id`, `Nombre` FROM `clientes` 
		WHERE 
		(  REPLACE( `Tfno1`, ' ', '' ) LIKE '%" .
         $texto . "%' 
		OR REPLACE( `Tfno2`, ' ', '' ) LIKE '%" .
         $texto . "%' 
		OR REPLACE( `Tfno3`, ' ', '' ) LIKE '%" .
         $texto . "%' )
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
       /* $sql = "SELECT `c`.`id`, `p`.`nombre`, `p`.`apellidos`, `c`.`Nombre` 
		FROM `pempresa` as `p` 
		INNER JOIN `clientes` as `c` 
		ON `c`.`id` = `p`.`idemp`
		WHERE REPLACE(`p`.`telefono`, ' ', '') 
		LIKE '%" . $texto . "%'
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
       /* $sql = "SELECT `c`.`id` ,`p`.`persona_central`, `c`.`Nombre` 
		FROM `pcentral` as `p` 
		INNER JOIN `clientes` as `c` 
		ON `c`.`id` = `p`.`idemp` 
    	WHERE REPLACE( `p`.`telefono`, ' ', '') 
    	LIKE '%" . $texto . "%'
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
       /* $sql = "SELECT `c`.`id`, `c`.`Nombre`, `p`.`nombre`, `p`.`apellidos`
		FROM `proveedores` AS `c`
		left JOIN `pproveedores` AS `p` ON `c`.`id` = `p`.`idemp`
		WHERE `c`.`Nombre` LIKE '%" . $texto . "%'
		OR `p`.`nombre` LIKE '%" . $texto . "%'
		OR `p`.`apellidos` LIKE '%" . $texto . "%'
		OR CONCAT( `p`.`nombre`, '', `p`.`apellidos`, '%' ) 
		LIKE '%" . $texto . "%'";
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
       /* $sql = "Select `c`.`ID`, `c`.`Nombre`, `z`.`valor`, `z`.`servicio` 
        FROM `clientes` AS `c`
		INNER JOIN `z_sercont` AS `z` 
		ON `c`.`ID` LIKE `z`.`idemp`
		WHERE REPLACE(`valor`, ' ', '') LIKE 
		'%" . $texto . "%'
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
       /* echo $cadena;
    }*/
}
