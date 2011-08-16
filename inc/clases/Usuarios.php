<?php
require_once ('Sql.php');
/** 
 * @author ruben
 * 
 * 
 */
class Usuarios extends Sql
{
    private $_valido = false;
    /**
     * 
     */
    public function __construct ()
    {
        parent::__construct();
    }
    
    /**
     * Pasandole el usuario y contraseña comprueba el usuario y 
     * lo registra
     * @param array $vars
     * @return boolean
     */
    public function validacion ($vars)
    {
        
        $contra = parent::escape(sha1($vars['passwd']));
        $usuario = parent::escape($vars['usuario']);
        $sql = "SELECT `nick`, `contra` 
		FROM `usuarios` 
		WHERE `nick` LIKE '$usuario' 
		AND `contra` LIKE '$contra'";
        parent::consulta($sql);
        if ( parent::totalDatos() == 1) {
            $ssid = session_id();
            if (empty($ssid))
                session_start();
            
            $_SESSION['usuario'] = $usuario;
            
            $this->_valido = true;
            
        }
    }
    
    public function esValido() {
        
        return $this->_valido;
        
    }
    
}
?>