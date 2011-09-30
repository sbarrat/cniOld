<?php
/**
 * Sql File Doc Comment
 * 
 * Clase que controla las acciones con la base de datos
 * 
 * PHP Version 5.1.4
 * 
 * @category Usuarios
 * @package  cni/inc/clases
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
require_once 'DbConnection.php';
/**
 * Sql Class Doc Comment
 * 
 * @category Class
 * @package  Usuarios
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com>
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @version  Release: 2.1
 * @link     https://github.com/sbarrat/cni
 *
 */
class Sql
{
    private $_conexion = null;
    private $_result = null;
   
    /**
     * Constructor de clase, crea una conexion persistente a la base
     * de datos
     */
    function __construct ()
    {
        $this->_conexion = DbConnection::connect();
    }
    /**
     * Enter description here ...
     * 
     * @param string $sql Consulta preparada
     * 
     */
    function consulta ( $sql )
    {
        $this->_result = $this->_conexion->query( $sql );
    }
    /**
     * Devuelve todos los datos de la consulta en un array asociativa
     */
    function datos ()
    {
        $rows = array();
        while (
        	( $row = mysql_fetch_array( $this->_result, MYSQL_ASSOC ) ) == true ) 
        {
            	$rows[] = $row;
        }
        return $rows;
    }
    /**
     * Devuelve un solo row de la consulta en un array asociativo
     * 
     * @return array
     */
    function datoUnico ()
    {
        $row = mysql_fetch_row( $this->_result, MYSQL_ASSOC );
        return $row;
    }
    /**
     * Devuelve el numero total de datos de la consulta
     * 
     * @return array
     */
    function totalDatos ()
    {
        return mysql_affected_rows();
    }
    /**
     * Devuelve el nombre del campo
     * 
     * @param integer $numeroCampo
     */
    function nombreCampo ( $numeroCampo )
    {
        return mysql_field_name( $this->_result, $numeroCampo );
    }
    /**
     * Prepara la variable para la consulta
     * 
     * @param string $var
     */
    function escape ($var)
    {
        return mysql_real_escape_string( $var, $this->_conexion );
    }
    /**
     * Cierra la conexion a la base de datos
     */
    /*function close ()
    {
        mysql_close( $this->_conexion );
    }*/
}