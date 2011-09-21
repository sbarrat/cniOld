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
require_once 'Sql.php';
/**
 * Alias Class Doc Comment
 * 
 * @category Class
 * @package  Personas
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com>
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @version  Release: 2.1
 * @link     https://github.com/sbarrat/cni
 *
 */
class Alias extends Sql
{
    
    private $_tabla = null;
    private $_campos = array();
    
    /**
     * Establece la tabla a consultar
     * 
     * @param string $tabla
     * @throws Exception
     */
    public function setTabla( $tabla = null )
    {
        if ( isset ($tabla) && is_string( $tabla ) ){
             $this->_tabla = $tabla;
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
    public function setCampos()
    {
        $sql = sprintf( 
        "SELECT * FROM `alias` 
        WHERE `tabla` like %s", parent::escape( $this->_tabla ) );
        parent::consulta( $sql );
        $this->_campos = parent::datos();
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
    
}