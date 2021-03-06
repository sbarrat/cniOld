<?php
/**
 * Usuarios File Doc Comment
 * 
 * Clase que controla las partes de los usuarios
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
 * AlumnosController Class Doc Comment
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
class Usuarios
{
    /**
     * Si no se dice otra cosa el usuario nunca es valido
     * @var boolean $_valido;
     */
	private $_valido = false;
    /**
     * Pasandole el usuario y contraseña comprueba el usuario y 
     * lo registra
     * 
     * @param array $vars
     * @return boolean
     * 
     */
    public function validacion ($vars)
    {
        $conexion = DbConnection::connect();
    	$password = sha1( $vars['password'] );
        $usuario = $vars['usuario'];
        $sql = "SELECT `nick`, `contra` 
		FROM `usuarios` 
		WHERE `nick` LIKE :usuario 
		AND `contra` LIKE :password";
        $query = $conexion->prepare( $sql );
        if ( $query->execute( array(':usuario'=>$usuario,':password'=>$password ) ) ) {
            if ( $query->rowCount() == 1 ) {
                $ssid = session_id();
                if ( empty( $ssid ) ) {
                    session_start();
                }
                $_SESSION['usuario'] = $usuario;
                $this->_valido = true;
            }    
        }
        return $this->_valido;  
    }
}
?>