<?php
/**
 * Avisos File Doc Comment
 * 
 * Clase que gestiona los avisos
 * 
 * PHP Version 5.1.4
 * 
 * @category Avisos
 * @package  inc/clases
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons by-sa 3.0
 * @link     https://github.com/sbarrat/cni
 */
require_once 'DbConnection.php';
/**
 * Avisos Class Doc Comment
 * 
 * @category Class
 * @package  Avisos
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com>
 * @license  http://creativecommons.org/licenses/by-sa/3.0/ Creative Commons 3.0 by-sa 3.0
 * @version  Release: 2.1
 * @link     https://github.com/sbarrat/cni
 *
 */
class Avisos
{
    private $_conexion = null;
    /**
     * Controla la ordenacion de la vista
     * @var string
     */
    private $_orden = '';
    
    /**
     * Almacena el resultado de proximos cumpleaños
     * @var array
     */
    private $_cumples = array();
    /**
     * Constructor de la funcion
     */
    public function __construct ()
    {
        $this->_conexion = DbConnection::connect();
        $this->_orden = (date( 'm' ))? " DESC ":"";
    }
    
    private function _cumples( $tabla = 'empleados' )
    {
        $campos = array(
        'pcentral' => array(
            'Id' =>'`pcentral`.`idemp`',
            'Nombre' => '`pcentral`.`persona_central`',
            'Fecha' => '`pcentral`.`cumple`'
            ),
        'pempresa' => array(
            'Id' => '`pempresa`.`idemp`',
            'Nombre' => "CONCAT(`pempresa`.`nombre`, ' ', `pempresa`.`apellidos`)",
            'Fecha' => '`pempresa`.`cumple`'
            )    
        );
        
        
        //Proximos Central y Empresa
        if ( array_key_exists($tabla, $campos) ){
             $sql = "SELECT
			`clientes`.`Nombre` as Empresa,
		    $campos[$tabla]['Nombre'] as Nombre,
		    $campos[$tabla]['Fecha'] as Fecha, 
			`clientes`.`id`, 
			DATE_FORMAT( $campos[$tabla]['Fecha'] , '0000-%m-%d' ) AS cumplea
			FROM `clientes` INNER JOIN `$tabla` 
			ON `clientes`.`Id` = $campos[$tabla]['Id'] 
			WHERE (
 				DAY( $campos[$tabla]['Fecha'] ) >= DAY( CURDATE() ) 
 				AND MONTH( $campos[$tabla]['Fecha'] ) 
 				LIKE MONTH( CURDATE() )
 				OR MONTH( $campos[$tabla]['Fecha'] ) 
 				LIKE MONTH( DATE_ADD( CURDATE(), INTERVAL 40 DAY ) )
			) 
			AND `clientes`.`Estado_de_cliente` != 0
 			ORDER BY MONTH( $campos[$tabla]['Fecha'] ) " . $this->_orden . ", 
 			DAY( $campos[$tabla]['Fecha'] ) ";
        } else {
         // Proximos Centro
             $sql = "SELECT 'Centro' as Empresa, CONCAT(`Nombre`,' ', `Apell1`, ' ',`Apell2`) as Nombre,
         `FechNac` as Fecha FROM `empleados`,  
		WHERE ( 
		DATEDIFF( 
			DATE_FORMAT( DATE_ADD( CURDATE(), INTERVAL 40 DAY ), '0000-%m-%d' ),
			DATE_FORMAT( `FechNac`, '0000-%m-%d' )
			) <= 39
			AND
			DATEDIFF(
			DATE_FORMAT( DATE_ADD( CURDATE(), INTERVAL 40 DAY ), '0000-%m-%d' ),
			DATE_FORMAT( `FechNac`, '0000-%m-%d' )
			) >= 0
			) ";
        }
        $this->_trataResultadoCumples($this->_conexion->query( $sql ) );
        
        
    }
    private function _trataResultadoCumples( $resource )
    {
        foreach ( $resource as $row ){
            $this->_cumples[] = array($row['Fecha'],$row['Nombre'].$row['Empresa']);
        }
    }
    /**
     * Muestra los  que cumplen años los proximos 40 dias de la central
     * 
     */
    private function _cumplesProximosCentral ()
    {
        $sql = "SELECT
		`clientes`.`Nombre`, 
		`pcentral`.`persona_central`, 
		`pcentral`.`cumple`,
		`clientes`.`id`, 
		DATE_FORMAT( `pcentral`.`cumple`, '0000-%m-%d' ) AS cumplea
		FROM `clientes` INNER JOIN `pcentral` 
		ON `clientes`.`Id` = `pcentral`.`idemp` 
		WHERE (
 			DAY( `pcentral`.`cumple` ) >= DAY( CURDATE() ) 
 			AND MONTH( `pcentral`.`cumple` ) LIKE MONTH( CURDATE() )
 			OR MONTH( `pcentral`.`cumple` ) 
 			LIKE MONTH( DATE_ADD( CURDATE(), INTERVAL 40 DAY ) )
		) 
		AND `clientes`.`Estado_de_cliente` != 0
 		ORDER BY MONTH( `pcentral`.`cumple` ) " . $this->_orden . ", 
 		DAY( `pcentral`.`cumple` ) ";
        parent::consulta( $sql );
        if (parent::totalDatos() != 0) {
            foreach (parent::datos() as $resultado) {
                $this->_cumples[] = array(
                    Fecha::invierte( Fecha::diaYmes( $resultado['cumple'] ) ), 
                    Fecha::diaYmes( $resultado['cumple'] ), 
                    Auxiliar::traduce( $resultado['persona_central'] ), 
                    $resultado['id'], Auxiliar::traduce( $resultado['Nombre'] )
                );
            }
        }
    }
    /**
     * Muestra los  que cumplen años los proximos 40 dias de la empresa
     * 
     * @return void
     */
    private function _cumplesProximosEmpresa ()
    {
        $sql = "SELECT
		`clientes`.`Nombre`,
		`pempresa`.`nombre`,
		`pempresa`.`apellidos`,
		`pempresa`.`cumple`,
		`clientes`.`id`, 
		DATE_FORMAT( `pempresa`.`cumple`, '0000-%m-%d' ) AS cumplea
		FROM `clientes` INNER JOIN `pempresa` 
		ON `clientes`.`Id` = `pempresa`.`idemp` 
		WHERE (
 			DAY( `pempresa`.`cumple` ) >= DAY( CURDATE() ) 
 			AND MONTH( `pempresa`.`cumple`) 
 			LIKE MONTH( CURDATE() )
 			OR MONTH( `pempresa`.`cumple`) 
 			LIKE MONTH( DATE_ADD( CURDATE(), INTERVAL 40 DAY ) )
		) 
		AND `clientes`.`Estado_de_cliente` != 0
 		ORDER BY MONTH( `pempresa`.`cumple`) " . $this->_orden . ", 
 		DAY( `pempresa`.`cumple` )";
        parent::consulta( $sql );
        if (parent::totalDatos() != 0) {
            foreach (parent::datos() as $resultado) {
                $this->_cumples[] = array(
                    Fecha::invierte( Fecha::diaYmes( $resultado['cumple'] ) ), 
                    Fecha::diaYmes( $resultado['cumple'] ), 
                    Auxiliar::traduce( $resultado['nombre'] ) . ' 
						' .Auxiliar::traduce( $resultado['apellidos'] ), 
                    $resultado['id'], 
                    Auxiliar::traduce( $resultado['Nombre'] )
                );
            }
        }
    }
    /**
     * Muestra los  que cumplen años los proximos 40 dias del centro
     * 
     */
    private function _cumplesProximosCentro ()
    {
        $sql = "SELECT * FROM `empleados` 
		WHERE ( 
		DATEDIFF( 
			DATE_FORMAT( DATE_ADD( CURDATE(), INTERVAL 40 DAY ), '0000-%m-%d' ),
			DATE_FORMAT( `FechNac`, '0000-%m-%d' )
			) <= 39
			AND
			DATEDIFF(
			DATE_FORMAT( DATE_ADD( CURDATE(), INTERVAL 40 DAY ), '0000-%m-%d' ),
			DATE_FORMAT( `FechNac`, '0000-%m-%d' )
			) >= 0
			) ";
        parent::consulta( $sql );
        if (parent::totalDatos() != 0) {
            foreach (parent::datos() as $resultado) {
                $this->_cumples[] = array(
                    Fecha::invierte( Fecha::cambiaf( $resultado['FechNac'] ) ), 
                    Fecha::cambiaf( $resultado['FechNac'] ), 
                    Auxiliar::traduce( $resultado['Nombre'] ) . ' 
						' . Auxiliar::traduce( $resultado['Apell1'] ) . '
						' . Auxiliar::traduce( $resultado['Apell2'] ),
                    null, 
                    null
                    );
            }
        }
    }
    /**
     * Muestra las empresas que finalizan contrato hoy
     *
     *@return string cadena
     */
    private function _finalizanContrato ()
    {
        
        $sql = "SELECT `facturacion`.`id`, 
		`facturacion`.`idemp`, 
		`facturacion`.`finicio`, 
		`facturacion`.`duracion`, 
		`facturacion`.`renovacion`, 
		`clientes`.`Nombre`
		FROM `facturacion` INNER JOIN `clientes` 
		ON `facturacion`.`idemp` = `clientes`.`Id`
		WHERE ( CURDATE() <= `renovacion` ) 
		AND ( DATE_ADD( CURDATE(), INTERVAL 60 DAY ) ) >= `renovacion` 
		AND `clientes`.`Estado_de_cliente` != 0 
		ORDER by MONTH( `renovacion` ) ASC, 
		DAY( `renovacion` ) ASC";
        return $this->_conexion->query( $sql );
       /* parent::consulta( $sql );
        return parent::datos();   */ 
    }
    
    /**
     * Devuelve los datos de los que cumplen años
     * 
     * @return array $this->_cumples Datos de los cumpleaños
     */
    public function verCumples() 
    {
        $this->_cumples( 'pempresa' );
        $this->_cumples( 'pcentral' );
        $this->_cumples();
        
        sort( $this->_cumples );
        return $this->_cumples;
    }
    /**
     * Devuelve los datos de los que finalizan contrato
     * 
     * @return array $this->_finalizanContrato Datos de los contratos
     */
    public function verContratos()
    {
        return $this->_finalizanContrato();
    }
}
