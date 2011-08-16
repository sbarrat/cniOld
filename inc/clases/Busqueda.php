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
    public function BusquedaSimple( $var, $tabla ) {
        
    }
    public function FormularioBusquedaAvanzada() {
        
	$cadena ='
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
    
    private function procesaDatosBusquedaAvz($sql, $campos, $tabla, $texto) {
    
        $cadena = '<p>
        <strong>
        <u>Resultados busqueda en '. $tabla .'</u>
        </strong>
        </p>';
	
	    parent::consulta( $sql );
	
	    if ( parent::totalDatos() != 0 ) {
		
		    foreach ( parent::datos() as $resultado ) {
			
			    $cadena .= '<p class="' . Auxiliar::clase() . '">
				<a 
				href="javascript:muestra(' . $resultado[ $campos[0] ] . ')">';
			    
			    for ($i=1; $i < count( $campos) ; $i++ ){ 
			        $cadena .= 
			            Auxiliar::traduce( $resultado[ $campos[$i] ] ) . ' ';
			    }
			    
			    $cadena .= '</a></p>';
			}
	    } else {
		
		    $cadena .= '<p class="' . Auxiliar::clase() . '">
		    No hay resultados de '. $texto .' en  ' . $tabla . '</p>';
	    }
	    return $cadena;
    }
    /**
     * Busqueda avanzada de varios datos
     * @param array $vars
     */
    public function BusquedaAvanzada( $vars ) {
        $cadena = '';
	
	    if ( $vars[ 'texto' ] != NULL )
		    $cadena .= 'Busqueda de:<strong>' . $vars[ 'texto' ] . '</strong>';
	/**
	 * Chequeamos si es un telefono
	 */
	    $token = preg_replace( "/ /", "//", $vars[ 'texto' ] );
	
	    if ( is_numeric( $token ) && strlen( $token ) == 9 ){
		    $vars['texto'] = $token;
	    }
	
        $vars[ 'texto' ] = 
        parent::escape( Auxiliar::codifica( $vars[ 'texto' ] ) );
    
	
        $sql = "SELECT `c`.`id`, `c`.`Nombre`, `c`.`Contacto`, 
    	`p`.`nombre`, `p`.`apellidos`
		FROM `clientes` AS `c`
		JOIN `pempresa` AS `p` ON `c`.`id` = `p`.`idemp`
		WHERE (`c`.`Nombre` LIKE '%" . $vars[ 'texto' ] . "%'
		OR `c`.`Contacto` LIKE '%" . $vars[ 'texto' ] . "%'
		OR `p`.`nombre` LIKE '%" . $vars[ 'texto' ] ."%'
		OR `p`.`apellidos` LIKE '%" . $vars[ 'texto' ] ."%'
		OR CONCAT( `p`.`nombre`, ' ' ,`p`.`apellidos`, '%' ) LIKE '%" .
        $vars[ 'texto' ] . "%')
    	and `c`.`Estado_de_cliente` = '-1'";

	    $tabla = 'clientes';
        $campos = array('id','Nombre','Contacto','nombre','apellidos');
	    $cadena .= 
        $this->procesaDatosBusquedaAvz($sql, $campos, $tabla, $vars[ 'texto' ]);
	    
	    
		
	//Consultamos telefonos de cliente
	
	    $sql = "SELECT `id`, `Nombre` FROM `clientes` 
		WHERE 
		(  REPLACE( `Tfno1`, ' ', '' ) LIKE '%" . $vars[ 'texto' ] ."%' 
		OR REPLACE( `Tfno2`, ' ', '' ) LIKE '%" . $vars[ 'texto' ] ."%' 
		OR REPLACE( `Tfno3`, ' ', '' ) LIKE '%" . $vars[ 'texto' ] ."%' )
    	AND `Estado_de_cliente` = '-1'";
	
	    parent::consulta( $sql );
	
	    if ( parent::totalDatos() !=0 ) {
		
		    foreach ( parent::datos() as $resultado ) {
			
			    $cadena .= '<p class="' . Auxiliar::clase() . '">
				<a href="javascript:muestra(' . $resultado[ 'id' ] . ')">' . 
			    Auxiliar::traduce( $resultado[ 'Nombre' ] ) . '</a></p>';
			
		    }
	    }
	
	//Consultamos telefonos de empleados
	
	    $sql = "SELECT `c`.`id`, `p`.`nombre`, `p`.`apellidos`, `c`.`Nombre` 
		FROM `pempresa` as `p` 
		INNER JOIN `clientes` as `c` 
		ON `c`.`id` = `p`.`idemp`
		WHERE REPLACE(`p`.`telefono`, ' ', '') 
		LIKE '%" . $vars[ 'texto' ] . "%'
		AND `c`.`Estado_de_cliente` = '-1'";
	
	    parent::consulta($sql);
	    if ( parent::totalDatos() != 0 ) {
		    foreach ( parent::datos() as $resultado ) {
			
			    $cadena .= '<p class="' . Auxiliar::clase() . '">
				<a href="javascript:muestra(' . $resultado[ 'id' ] . ')">' . 
			    Auxiliar::traduce( $resultado[ 'nombre' ] ) . ' ' . 
			    Auxiliar::traduce( $resultado[ 'apellidos' ] ) . ' de ' . 
			    Auxiliar::traduce( $resultado[ 'Nombre' ] ) . '</a></p>';
		    }
	    }
	
	//consultamos telefonos de pcentral
	    $sql = "SELECT `c`.`id` ,`p`.`persona_central`, `c`.`Nombre` 
		FROM `pcentral` as `p` 
		INNER JOIN `clientes` as `c` 
		ON `c`.`id` = `p`.`idemp` 
    	WHERE REPLACE( `p`.`telefono`, ' ', '') 
    	LIKE '%" . $vars[ 'texto' ] . "%'
    	AND `c`.`Estado_de_cliente` = '-1'";
	
	    parent::consulta($sql);
	    if ( parent::totalDatos() != 0 ) {
		    foreach ( parent::datos() as $resultado ) {
			
			    $cadena .= '<p class="' . Auxiliar::clase() . '">
				<a href="javascript:muestra(' . $resultado[ 'id' ] . ')">' . 
			    Auxiliar::traduce( $resultado[ 'persona_central' ] ) . ' de ' . 
			    Auxiliar::traduce( $resultado[ 'Nombre' ] ) . '</a></p>';
		    }
	}
	
	//Consultamos datos de proveedores
	    $cadena.='<p><b><u>Resultados busqueda en Proveedores</u></b></p>';
	
	    $sql = "SELECT `c`.`id`, `c`.`Nombre`, `p`.`nombre`, `p`.`apellidos`
		FROM `proveedores` AS `c`
		left JOIN `pproveedores` AS `p` ON `c`.`id` = `p`.`idemp`
		WHERE `c`.`Nombre` LIKE '%" . $vars[ 'texto' ] . "%'
		OR `p`.`nombre` LIKE '%" . $vars[ 'texto' ] . "%'
		OR `p`.`apellidos` LIKE '%" . $vars[ 'texto' ] . "%'
		OR CONCAT( `p`.`nombre`, '', `p`.`apellidos`, '%' ) 
		LIKE '%" . $vars[ 'texto' ] . "%'";

	    parent::consulta($sql);
	    if ( parent::totalDatos() != 0 ) {
		    foreach ( parent::datos() as $resultado ) {
	        
		        $cadena .= '<p class="' . Auxiliar::clase() . '">
		        <a href="javascript:muestra(' . $resultado['id'] . ')">' . 
		        Auxiliar::traduce( $resultado[ 'Nombre' ] ) . ' - ' . 
		        Auxiliar::traduce( $resultado[ 'nombre' ] ) . ' ' .
		        Auxiliar::traduce( $resultado[ 'apellidos'] ) . '</a></p>';
	        }
	    }
		else
		    $cadena .= '<p class="' . Auxiliar::clase() . '">
		    No hay resultados de ' . Auxiliar::traduce($vars['texto'] ) . '
		     en Proveedores</p>';
		    
	//Consultamos en telecomunicaciones
        $cadena .= '<p><b><u>
        Resultados busqueda en Telecomunicaciones</u></b></p>';
	
        $sql = "Select `c`.`ID`, `c`.`Nombre`, `z`.`valor`, `z`.`servicio` 
        FROM `clientes` AS `c`
		INNER JOIN `z_sercont` AS `z` 
		ON `c`.`ID` LIKE `z`.`idemp`
		WHERE REPLACE(`valor`, ' ', '') LIKE 
		'%" . $vars[ 'texto' ] . "%'
		AND `c`.`Estado_de_cliente` = '-1'";
        
        parent::consulta($sql);
	    if ( parent::totalDatos() != 0 ) {
		    foreach ( parent::datos() as $resultado ) {
		        
		        $cadena .= '<p class="' . Auxiliar::clase() . '">
		        <a href="javascript:muestra(' . $resultado[ 'ID' ] . ')">' . 
		        Auxiliar::traduce( $resultado[ 'Nombre' ] ) . ' - ' . 
		        Auxiliar::traduce( $resultado[ 'valor' ] ) . ' - ' . 
		        Auxiliar::traduce( $resultado[ 'servicio' ] ) . '
		        </a></p>';
		    }
	    }    
	    else
		    $cadena.='<p class="' . Auxiliar::clase() . '">
		    No hay resultados de ' . Auxiliar::traduce( $vars[ 'texto' ] ) .' 
		    en Telecomunicaciones</p>';
	
	echo $cadena;
    }
}
?>