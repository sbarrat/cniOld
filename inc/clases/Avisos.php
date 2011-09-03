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
     * Chivato que dice si alguien cumple años o no en cada seccion
     * @var boolean
     */
    private $_nadieCumple = 0;
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
     * Si nadie cumple años muestra el mensaje
     * 
     * @param string $cuando
     * @return string $texto
     */
    private function _nadieCumple( $cuando )
    {
        $texto = '';
        if ( $this->_nadieCumple == 0 ) {
            $texto = '<tr><td class="' . Auxiliar::clase() . '" 
                colspan="2"> Nadie cumple los años ' . $cuando . '
				</td></tr>';
        }
        $this->_nadieCumple = 0;
        return $texto;
    }
    /**
     * Muestra en la cabezera si el aviso es para hoy o mañana
     * 
     * @param string $var
     * @return string $cuando
     */
    private function _todayOrTomorrow( $var )
    {
        $cuando = $var;
        if ( strtotime( $var.'-'.date( 'Y' ) ) == strtotime( 'TODAY' ) ) {
            $cuando = '<strong>HOY</strong>';
        }
        elseif ( strtotime( $var.'-'.date( 'Y' ) ) == strtotime( '+1 DAY' ) ) {
            $cuando = '<strong>MAÑANA</strong>';
        }
        return $cuando;    
    }
    /**
     * Muestra los avisos
     * 
     * @param boolean $cumples
     * @param boolean $contratos
     * @return string $texto
     */
    public function verAvisos ($cumples = true, $contratos = true)
    {
        $texto = '';
        $cierreSimple = '<tr><th colspan="2"><span class="boton" 
                onclick="cierralo()" onkeypress="cierralo()">[X] Cerrar</span>
                </tr></th>';
        /**
         * Cabezera para cumpleaños y contratos
         */
        if ($cumples && $contratos) {
            $texto .= '<input type="button" class="boton" 
            	value="[<]Ocultar Avisos" 
				onclick="cerrar_avisos()"/>
				<table class="tabla">
				<tr><th colspan="2">Cartel de Avisos</th></tr>
				<tr>
				<th>Cumplea&ntilde;os</th>
				<th>Contratos</th>
				</tr>
				<tr><td valign="top">';
        }
        if ( $cumples ) {
            $texto .= '<table class="tabla">';
            if ( !$contratos ) {
                $texto .= $cierreSimple;
            }
            $texto .= '<tr><th colspan="2">Proximos Cumpleaños</th></tr>';
            $this->_cumplesProximosCentral();
            $this->_cumplesProximosEmpresa();
            $this->_cumplesProximosCentro();
          
            
            if ($this->_nadieCumple == 0) {
                $texto .= '<tr><td class="' . Auxiliar::clase() . '" 
                colspan="2"> Nadie cumple los años en los proximos 40 dias
				</td></tr>';
            } else {
                sort( $this->_cumples );
                foreach ($this->_cumples as $cumple) {
                    $texto .= '<tr class="' . Auxiliar::clase() . '">
    					<td>' . $this->_todayOrTomorrow( $cumple[1] ) . '</td>
    					<td>' . $cumple[2];
                    
                    if ($cumple[4] != null) {
                        $texto .= 
                        ' de <a href="javascript:muestra(' . $cumple[3] . ')">
    					' . $cumple[4] . '</a>';
                    }
                    $texto .= '</td></tr>';
                }
            }
            $texto .= '</table>';
        }
        
        if ($cumples && $contratos) {
            $texto .= '</td><td valign="top">';
        }
        if ( $contratos ) {
            $texto .= '<table class="tabla">';
            
            if ( !$cumples ) {
                $texto .= $cierreSimple;
            }
            $texto .= $this->_finalizanContrato();
            $texto .= '</table>';
        }
        
        $texto .= '</td></tr></table>';
        echo $texto;
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
            $this->_nadieCumple = 1;
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
            $this->_nadieCumple = 1;
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
            $this->_nadieCumple = 1;
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
        $cadena = '<tr>
				<th>Dia</th>
				<th>Finalizan contrato en los Proximos dias</th>
				</tr>';
        if (parent::totalDatos() >= 1) {
            foreach (parent::datos() as $resultado) {
                $cadena .= '<tr><td class="' . Auxiliar::clase() . '">' .
                    Fecha::cambiaf( $resultado['renovacion'] ) . '</td>
					<td class="' . Auxiliar::clase() . '">
					<a href="javascript:muestra(' .$resultado['idemp'] . ')" >
					' . Auxiliar::traduce( $resultado['Nombre'] ) . 
					'</a></td></tr>';
            }
        } else {
            $cadena .= '<tr><td colspan="2" class="' . Auxiliar::clase() . '">
				Nadie Finaliza contrato en los proximos dias</td></tr>';
        }
        return $cadena;
    }
}
