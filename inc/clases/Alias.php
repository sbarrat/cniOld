<?php
/**
 * Alias File Doc Comment
 * 
 * Clase que controla las propiedades basicas de alias
 * 
 * PHP Version 5.1.4
 * 
 * @category Alias
 * @package  cni/inc/clases
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
require_once 'DbConnection.php';
/**
 * Alias Class Doc Comment
 * 
 * @category Class
 * @package  Alias
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com>
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @version  Release: 2.1
 * @link     https://github.com/sbarrat/cni
 *
 */
class Alias
{
    
    private $_tabla = null;
    private $_campos = array();
    private $_conexion = null;
    
    /**
     * Establecemos los campos de la tabla y los valores
     * 
     * @param unknown_type $name
     * @param unknown_type $value
     */
    public function __set( $name, $value )
    {
        $this->_campos[$name] = $value;
    }
    /**
     * Establece la tabla a consultar
     * 
     * @param string $tabla
     * @throws Exception
     */
    public function setTabla( $tabla = null )
    {
        if ( is_null( $this->_conexion ) ) {
            $this->_conexion = DbConnection::connect();
        }
        
        if ( isset ($tabla) && is_string( $tabla ) ){
             $this->_tabla = $tabla;
             $this->_setCampos();
        } else {
             throw new Exception("Debe especificar tabla");
        }
    }
    /**
     * Devuelve el nombre de la tabla
     * 
     * @return string
     */
    public function getTabla()
    {
        return $this->_tabla;
    }
    /**
     * Inicializa el array de  campos
     * 
     * @return array
     */
    private function _setCampos()
    {
        $sql = sprintf( 
        "SELECT * FROM `alias` 
        WHERE `tabla` like '%s' 
        AND `mostrar` like 'Si'
        ORDER BY `orden`", parent::escape( $this->_tabla ) );
        parent::consulta( $sql );
        
        $this->_campos =  parent::datos();
    }
    /**
     * Devuelve los campos
     * 
     * @return array
     */
    public function getCampos()
    {
        return $this->_campos;
        
    }
    /**
     * En caso de campo select devolvemos los valores que tiene
     * 
     * @param string $tabla
     * @return array 
     */
    public function getValoresSelect( $tabla )
    {
        $sql = sprintf(
        "SELECT * FROM `%s`", parent::escape( $tabla )
        );
        parent::consulta( $sql );
        return parent::datos();
    }
    
    
}