<?php
require_once 'Sql.php';
/** 
 * @author ruben
 * 
 * 
 */
class Aplicacion extends Sql
{
    /**
     * 
     */
    public function __construct ()
    {
        parent::__construct();
    }
    
    public function menu() {
      
        $sql = "SELECT * FROM `menus`";
        parent::consulta($sql);
    
        $tabla = "<table width='100%'><tr>";
        foreach (parent::datos() as $dato) {
        switch ($dato['id']) {
            case 7:
                $seccion = 'javascript:datos(1)';
                break;
            case 8:
                $seccion = 'javascript:datos(2)';
                break;
            case 9:
                $seccion = 'javascript:datos(3)';
                break;
            default:
                $seccion = 'javascript:menu(' . $dato['id'] . ')';
                break;
        }
        $tabla .= '<th><a href="' . $seccion . '">
				  <img src="' . $dato['imagen'] . '" 
				  alt="' . $dato['nombre'] . '" width="32" />
				  <p />' . $dato['nombre'] . '</a></th>';
    }
    $tabla .= '<th><a href="inc/logout.php">
				<img src="imagenes/salir.png" width="32" alt="Salir" />
				<p/>Salir<a>
			</th>';
    $tabla .= '</tr></table>';
    $tabla .= '<div id="principal"></div>';
    return $tabla;
    }
}
?>