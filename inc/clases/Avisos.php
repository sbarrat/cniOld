<?php
require_once 'Sql.php';
/** 
 * Clase que gestiona los avisos
 * 
 * PHP Version 5.1.4
 * 
 * @author Ruben Lacasa Mas <rubendx@gmail.com>
 * @version 2.0
 * @package clases
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
    public function __construct ()
    {
        parent::__construct();
    }
    /**
     * Muestra los avisos
     * 
     * @todo Reducir complejidad
     * @return string
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
        
        if ($cumples) {
            $texto .= '<table class="tabla">';
            if (! $contratos)
                $texto .= $cierreSimple;
            $texto .= '<tr><th colspan="2">Hoy hace los años</th></tr>';
            $texto .= $this->cumplesHoyTomorrowCentral('hoy');
            $texto .= $this->cumplesHoyTomorrowEmpresa('hoy');
            $texto .= $this->cumplesHoyTomorrowCentro('hoy');
            
            if ($this->_nadieCumple == 0) {
                $texto .= '<tr><td class="' . Auxiliar::clase() . '" 
                colspan="2"> Nadie cumple los años hoy
				</td></tr>';
            }
            
            $this->_nadieCumple = 0;
            $texto .= '<tr><th colspan="2">Y mañana:</th></tr>';
            $texto .= $this->cumplesHoyTomorrowCentral('tomorrow');
            $texto .= $this->cumplesHoyTomorrowEmpresa('tomorrow');
            $texto .= $this->cumplesHoyTomorrowCentro('tomorrow');
            
            if ($this->_nadieCumple == 0) {
                $texto .= '<tr><td class="' . Auxiliar::clase() . '" 
                colspan="2">Nadie cumple los años mañana</td></tr>';
            }
            
            $this->_nadieCumple = 0;
            $texto .= '<tr><th colspan="2">En 40 dias:</th></tr>';
            
            if (date('m') == 12)
                $this->_orden = " DESC ";
            
            $this->cumplesProximosCentral();
            $this->cumplesProximosEmpresa();
            $this->cumplesProximosCentro();
            
            if ($this->_nadieCumple == 0) {
                $texto .= '<tr><td class="' . Auxiliar::clase() . '" 
                colspan="2"> Nadie cumple los años en los proximos 40 dias
				</td></tr>';
            } else {
                sort($this->_cumples);
                foreach ($this->_cumples as $cumple) {
                    $texto .= '<tr class="' . Auxiliar::clase() . '">
    					<td>' . $cumple[1] . '</td>
    					<td>' . $cumple[2];
                    if ($cumple[4] != NULL) {
                        $texto .= 
                        ' de <a href="javascript:muestra(' . $cumple[3] . ')">
    					' . $cumple[4] . '</a>';
                    }
                    $texto .= '</td></tr>';
                }
            }
            $texto .= '</table>';
        }
        
        if ($cumples && $contratos)
            $texto .= '</td><td valign="top">';
        
        if ($contratos) {
            $texto .= '<table class="tabla">';
            
            if (! $cumples)
                $texto .= $cierreSimple;
            $texto .= $this->finalizanContrato('hoy');
            $texto .= $this->finalizanContrato('mes');
            $texto .= $this->finalizanContrato('proximos');
            $texto .= '</table>';
        }
        
        $texto .= '</td></tr></table>';
        echo $texto;
    }
    /**
     * Muestra los cumpleaños de hoy de los empleados de la central
     * @param string hoy/tomorrow
     * @return string
     */
    private function cumplesHoyTomorrowCentral ($cuando)
    {
        $texto = '';
        if ($cuando == 'tomorrow')
            $formato = 'ADDDATE( CURDATE(), 1 )';
        else
            $formato = 'CURDATE()';
        $sql = "SELECT  
		`clientes`.`Nombre`, 
		`pcentral`.`persona_central`, 
		`pcentral`.`cumple`,
		`clientes`.`id`
		FROM `clientes` INNER JOIN `pcentral` 
		ON `clientes`.`Id` = `pcentral`.`idemp` 
		WHERE DATE_FORMAT(`pcentral`.`cumple`,'%d %c') 
		LIKE DATE_FORMAT(" . $formato . ",'%d %c') 
		AND `clientes`.`Estado_de_cliente` != 0";
        parent::consulta($sql);
        if (parent::totalDatos() != 0) {
            $this->_nadieCumple = 1;
            foreach (parent::datos() as $resultado) {
                $texto .= '<tr>
                <td class="' .
                 Auxiliar::clase() . '" colspan="2">
			' .
                 Auxiliar::traduce($resultado['persona_central']) . ' de 
			<a href="javascript:muestra(' . $resultado['id'] . ')">
			' .
                 Auxiliar::traduce($resultado['Nombre']) . '</a>
			</td></tr>';
            }
        }
        return $texto;
    }
    /**
     * Muestra los que cumplen años hoy en la central
     * @param string hoy/tomorrow
     * @return string
     */
    private function cumplesHoyTomorrowEmpresa ($cuando)
    {
        $texto = '';
        if ($cuando == 'tomorrow')
            $formato = 'ADDDATE( CURDATE(), 1 )';
        else
            $formato = 'CURDATE()';
        $sql = "SELECT  
		`clientes`.`Nombre`, 
		`pempresa`.`nombre`,
		`pempresa`.`apellidos`, 
		`pempresa`.`cumple`,
		`clientes`.`id`
		FROM `clientes` INNER JOIN `pempresa` 
		ON `clientes`.`Id` = `pempresa`.`idemp` 
		WHERE DATE_FORMAT(`pempresa`.`cumple`,'%d %c') 
		LIKE DATE_FORMAT(" . $formato . ",'%d %c') 
		AND `clientes`.`Estado_de_cliente` != 0";
        parent::consulta($sql);
        
        if (parent::totalDatos() != 0) {
            $this->_nadieCumple = 1;
            foreach (parent::datos() as $resultado) {
                $texto .= '<tr>
                <td class="' .
                 Auxiliar::clase() . '" colspan="2">
			' .
                 Auxiliar::traduce($resultado['nombre']) . ' 
			' .
                 Auxiliar::traduce($resultado['apellidos']) . ' de 
			<a href="javascript:muestra(' . $resultado['id'] . ')">
			' .
                 Auxiliar::traduce($resultado['Nombre']) . '</a></td></tr>';
            }
        }
        return $texto;
    }
    /**
     * Muestra los empleados del centro que cumplen años hoy
     * @param string hoy/tomorrow
     * @return string
     */
    private function cumplesHoyTomorrowCentro ($cuando)
    {
        $texto = '';
        if ($cuando == 'tomorrow')
            $formato = 'ADDDATE( CURDATE(), 1 )';
        else
            $formato = 'CURDATE()';
            
        $sql = "SELECT * FROM `empleados` 
	WHERE DATE_FORMAT( `FechNac`, '%d %c' )
	LIKE DATE_FORMAT( " . $formato . ", '%d %c' )";
        parent::consulta($sql);
        if (parent::totalDatos() != 0) {
            $this->_nadieCumple = 1;
            foreach (parent::datos() as $resultado) {
                $texto .= '<tr><td class="
            ' . Auxiliar::clase() . '" colspan="2">
			' .
                 Auxiliar::traduce($resultado['Nombre']) . '  
			' .
                 Auxiliar::traduce($resultado['Apell1']) . ' 
			' .
                 Auxiliar::traduce($resultado['Apell2']) . ' </td></tr>';
            }
        }
        return $texto;
    }
    /**
     * Muestra los  que cumplen años los proximos 40 dias de la central
     * 
     */
    private function cumplesProximosCentral ()
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
 			DAY( `pcentral`.`cumple` ) > DAY( CURDATE() ) 
 			AND MONTH( `pcentral`.`cumple` ) LIKE MONTH( CURDATE() )
 			OR MONTH( `pcentral`.`cumple` ) 
 			LIKE MONTH( DATE_ADD( CURDATE(), INTERVAL 40 DAY ) )
		) 
		AND `clientes`.`Estado_de_cliente` != 0
 		ORDER BY MONTH( `pcentral`.`cumple` ) " . $this->_orden . ", 
 		DAY( `pcentral`.`cumple` ) ";
        parent::consulta($sql);
        if (parent::totalDatos() != 0) {
            $this->_nadieCumple = 1;
            foreach (parent::datos() as $resultado) {
                $this->_cumples[] = array(
                Fecha::invierte(Fecha::diaYmes($resultado['cumple'])), 
                Fecha::diaYmes($resultado['cumple']), 
                Auxiliar::traduce($resultado['persona_central']), 
                $resultado['id'], Auxiliar::traduce($resultado['Nombre']));
            }
        }
    }
    /**
     * Muestra los  que cumplen años los proximos 40 dias de la empresa
     * @return array;
     */
    private function cumplesProximosEmpresa ()
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
 			DAY( `pempresa`.`cumple` ) > DAY( CURDATE() ) 
 			AND MONTH( `pempresa`.`cumple`) 
 			LIKE MONTH( CURDATE() )
 			OR MONTH( `pempresa`.`cumple`) 
 			LIKE MONTH( DATE_ADD( CURDATE(), INTERVAL 40 DAY ) )
		) 
		AND `clientes`.`Estado_de_cliente` != 0
 		ORDER BY MONTH( `pempresa`.`cumple`) " . $this->_orden . ", 
 		DAY( `pempresa`.`cumple` )";
        parent::consulta($sql);
        if (parent::totalDatos() != 0) {
            $this->_nadieCumple = 1;
            foreach (parent::datos() as $resultado) {
                $this->_cumples[] = array(
                Fecha::invierte(Fecha::diaYmes($resultado['cumple'])), 
                Fecha::diaYmes($resultado['cumple']), 
                Auxiliar::traduce($resultado['nombre']) . ' 
				' .
                 Auxiliar::traduce($resultado['apellidos']), $resultado['id'], 
                Auxiliar::traduce($resultado['Nombre']));
            }
        }
    }
    /**
     * Muestra los  que cumplen años los proximos 40 dias del centro
     * 
     */
    private function cumplesProximosCentro ()
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
        parent::consulta($sql);
        if (parent::totalDatos() != 0) {
            $this->_nadieCumple = 1;
            foreach (parent::datos() as $resultado) {
                $this->_cumples[] = array(
                Fecha::invierte(Fecha::cambiaf($resultado['FechNac'])), 
                Fecha::cambiaf($resultado['FechNac']), 
                Auxiliar::traduce($resultado['Nombre']) . ' 
					' .
                 Auxiliar::traduce($resultado['Apell1']) . '
					' .
                 Auxiliar::traduce($resultado['Apell2']), NULL, NULL);
            }
        }
    }
    /**
     * Muestra las empresas que finalizan contrato hoy
     * @param string hoy/mes/proximos
     * @return string
     */
    private function finalizanContrato ($cuando)
    {
        $finalizacion = '';
        if ($cuando == 'hoy') {
            $finalizacion = 'Hoy';
            $sql = "SELECT `facturacion`.`id`, 
		`facturacion`.`idemp`, 
		`facturacion`.`finicio`, 
		`facturacion`.`duracion`, 
		`facturacion`.`renovacion`, 
		`clientes`.`Nombre`
		FROM `facturacion` INNER JOIN `clientes` 
		ON `facturacion`.`idemp` = `clientes`.`Id`
		WHERE DATE_FORMAT( `renovacion`, '%d %c %y' ) 
		LIKE DATE_FORMAT( CURDATE(),'%d %c %y' ) 
		AND `clientes`.`Estado_de_cliente` != 0";
        } elseif ($cuando == 'mes') {
            $finalizacion = 'este Mes';
            $sql = "SELECT `facturacion`.`id`, 
		`facturacion`.`idemp`, 
		`facturacion`.`finicio`, 
		`facturacion`.`duracion`, 
		`facturacion`.`renovacion`, 
		`clientes`.`Nombre`
		FROM `facturacion` INNER JOIN `clientes` 
		ON `facturacion`.`idemp` = `clientes`.`Id`
		WHERE MONTH( `renovacion` ) 
		LIKE MONTH( CURDATE() ) 
		AND YEAR( `renovacion` ) 
		LIKE YEAR( CURDATE() ) 
		AND `clientes`.`Estado_de_cliente` != 0 ORDER BY `renovacion` ASC";
        } else {
            $finalizacion = 'en los proximos 60 dias';
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
        }
        parent::consulta($sql);
        $cadena = '<tr>
				<th>Dia</th>
				<th>Finalizan contrato ' . $finalizacion . '</th>
				</tr>';
        if (parent::totalDatos() >= 1) {
            foreach (parent::datos() as $resultado) {
                $cadena .= '<tr><td class="' . Auxiliar::clase() . '">
			' .
                 Fecha::cambiaf($resultado['renovacion']) . '</td>
			<td class="' . Auxiliar::clase() . '">
			<a href="javascript:muestra(' .
                 $resultado['idemp'] . ')" >
			' .
                 Auxiliar::traduce($resultado['Nombre']) . '</a></td></tr>';
            }
        } else {
            $cadena .= '<tr><td colspan="2" class="' . Auxiliar::clase() . '">
		Nadie Finaliza contrato ' . $finalizacion .
             '</td></tr>';
        }
        //$cadena .= '</table>';
        return $cadena;
    }
}
