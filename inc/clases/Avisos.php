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
     * Almacena el resultado de los contratos
     * @var array
     */
    private $_contratos = array();
    /**
     * Constructor de la funcion
     */
    public function __construct ()
    {
        $this->_conexion = DbConnection::connect();
        $this->_orden = (date( 'm' ))? " DESC ":"";
    }
    /**
    * Segun la tabla pasada devuelve unos datos de cumpleaños u otros
    * 
    * @param string $tabla
    */
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
        if ( array_key_exists( $tabla, $campos ) ) {
             $sql = "SELECT
			`clientes`.`Nombre` as Empresa,
             {$campos[$tabla]['Nombre']} as Nombre,
             {$campos[$tabla]['Fecha']} as Fecha, 
			`clientes`.`id`, 
			DATE_FORMAT( {$campos[$tabla]['Fecha']} , '0000-%m-%d' ) AS cumplea
			FROM `clientes` INNER JOIN `$tabla` 
			ON `clientes`.`Id` = {$campos[$tabla]['Id']} 
			WHERE (
 				DAY( {$campos[$tabla]['Fecha']} ) >= DAY( CURDATE() ) 
 				AND MONTH( {$campos[$tabla]['Fecha']} ) 
 				LIKE MONTH( CURDATE() )
 				OR MONTH( {$campos[$tabla]['Fecha']} ) 
 				LIKE MONTH( DATE_ADD( CURDATE(), INTERVAL 40 DAY ) )
			) 
			AND `clientes`.`Estado_de_cliente` != 0
 			ORDER BY MONTH( {$campos[$tabla]['Fecha']} ) " . $this->_orden . ", 
 			DAY( {$campos[$tabla]['Fecha']} ) ";
        } else {
             // Proximos Centro
             $sql = "SELECT 'Centro' as Empresa, CONCAT(`Nombre`,' ', `Apell1`, ' ',`Apell2`) as Nombre,
         `FechNac` as Fecha FROM `empleados` 
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
        $this->_trataResultadoCumples( $this->_conexion->query( $sql ) );
    }
    /**
     * Trata los resultados de los cumpleaños para su visualizacion
     * 
     * @param array $resource
     */
    private function _trataResultadoCumples( $resource )
    {
        foreach ( $resource as $row ){
            $this->_cumples[] = array(
                Fecha::ordenaFecha( $row['Fecha'] ),
                Fecha::diaYmes( $row['Fecha'] ),
                ucwords( strtolower( Auxiliar::traduce( $row['Nombre'] ) ) ),
                strtoupper( Auxiliar::traduce( $row['Empresa'] ) )
            );
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
        $this->_trataResultadoContratos( $this->_conexion->query( $sql ) );       
    }
    /**
     * Trata los resultados de contratos para su visualizacion
     * 
     * @param array $resource
     */
    private function _trataResultadoContratos ( $resource )
    {
        foreach ( $resource as $row ) {
            $this->_contratos[] = array (
                Fecha::ordenaFecha( $row['renovacion'] ),
                Fecha::diaYmes( $row['renovacion'] ),
                strtoupper( Auxiliar::traduce( $row['Nombre'] ) )
            );
        }
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
        $this->_finalizanContrato();
        sort( $this->_contratos );
        return $this->_contratos;   
    }
}
