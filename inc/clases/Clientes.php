<?php
/**
 * Clientes File Doc Comment
 * 
 * Clase que controla las propiedades basicas de Clientes
 * 
 * PHP Version 5.1.4
 * 
 * @category Clientes
 * @package  cni/inc/clases
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
require_once 'Alias.php';
/**
 * Personas Class Doc Comment
 * 
 * @category Class
 * @package  Clientes
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com>
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @version  Release: 2.1
 * @link     https://github.com/sbarrat/cni
 *
 */
class Clientes extends Alias
{
    private $_tabla = "clientes";
    
    /**
     * Constructor que inicializa las propiedades 
     */
    public function __construct()
    {
        parent::setTabla( $this->_tabla );
        parent::setCampos();
    }
    
}