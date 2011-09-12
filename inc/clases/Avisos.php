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
require_once 'Sql.php';
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
class Avisos extends Sql
{
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
        parent::__construct();
        $this->_orden = (date( 'm' ))? " DESC ":"";
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
        parent::consulta( $sql );
        return parent::datos();    
    }
    
    /**
     * Devuelve los datos de los que cumplen años
     * 
     * @return array $this->_cumples Datos de los cumpleaños
     */
    public function verCumples() 
    {
        $this->_cumplesProximosCentral();
        $this->_cumplesProximosCentro();
        $this->_cumplesProximosEmpresa();
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
