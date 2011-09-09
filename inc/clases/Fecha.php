<?php
/**
 * Fecha File Doc Comment
 * 
 * Clase que controla las acciones con la base de datos
 * 
 * PHP Version 5.1.4
 * 
 * @category Fecha
 * @package  cni/inc/clases
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
/**
 * AlumnosController Class Doc Comment
 * 
 * @category Class
 * @package  Fecha
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com>
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @version  Release: 2.1
 * @link     https://github.com/sbarrat/cni
 *
 */
class Fecha
{
    /**
     * Constructor de clase, establece la zona horaria
     */
    public function __construct ()
    {
        date_default_timezone_set( 'Europe/Madrid' );
    }
    /**
	 * Funcion que devuelve el nombre de los meses
	 */
    public function getMeses ()
    {
        $meses = array('1'=>"Enero", "Febrero", "Marzo", "Abril", "Mayo", 
        "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", 
        "Diciembre");
        return $meses;
    }
    /**
	 * Funcion que devuelve un array con el nombre de los meses cortos
	 */
    public function getMesesCortos ()
    {
        $meses = array('1'=>"Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", 
        "Ago", "Sep", "Oct", "Nov", "Dic");
        return $meses;
    }
    /**
	 * Funcion Auxiliar que cambia la fecha del formato MySQl al 
	 * castellano y viceversa
	 * 
	 * @param string $fecha
	 */
    public static function cambiaf ( $fecha )
    {
        $dia = explode( "-", $fecha );
        return $dia[2] . "-" . $dia[1] . "-" . $dia[0];
    }
    /**
	 * Funcion Auxiliar que devuelve el dia pasado por parametro
	 * 
	 * @param string $fecha
	 * @return string
	 */
    public function verDia ( $fecha )
    {
        return date( "j", strtotime( $fecha ) );
    }
    /**
	 * Funcion Auxiliar que devuelve el mes pasado por parametro
	 * 
	 * @param string $fecha
	 * @return string
	 */
    public function verMes ( $fecha )
    {
        return date( "n", strtotime( $fecha ) );
    }
    /**
	 * Funcion Auxiliar que devuelve el año pasado como parametro
	 * 
	 * @param string $fecha
	 * @return string
	 */
    public function verAnyo ( $fecha )
    {
        return date( 'Y', strtotime( $fecha ) );
    }
    /**
     * Devuelve el dia y el mes pasando la fecha como parametro
     * 
     * @param string $stamp
     * @return string $fecha
     */
    public static function diaYmes ( $stamp )
    {
        $fdia = explode( '-', $stamp );
        $fecha = $fdia[2] . '-' . $fdia[1];
        return $fecha;
    }
    /**
     * Invierte el formato de fecha
     * 
     * @param string $fecha
     * @return string
     */
    public static function invierte ( $fecha )
    {
        $reves = explode( '-', $fecha );
        return $reves[1] . '-' . $reves[0];
    }
}