<?php
/**
 * inc/validacion.php Se encarga de validar el usuario y mostrar el menu
 * 
 * Se le envian los datos de inicio de sesion, realiza la autentificacion
 * una vez autentificado muestra el menu de la aplicacion
 * 
 * @author Ruben Lacasa Mas <rubendx@gmail.com>
 * @version 2.0
 */
if (isset($_POST['opcion'])) {
    switch ($_POST['opcion']) {
        case 0:
            $respuesta = valida($_POST);
            break;
    }
    echo $respuesta;
}
/**
 * Funcion de validación del usuario
 * @param array $vars
 */
function valida ($vars)
{
    require_once 'clases/Sql.php';
    $query = new Sql();
    $contra = $query->escape(sha1($vars['passwd']));
    $usuario = $query->escape($vars['usuario']);
    $sql = "SELECT `nick`, `contra` 
	FROM `usuarios` 
	WHERE `nick` LIKE '$usuario' 
	AND `contra` LIKE '$contra'";
    $query->consulta($sql);
    if ($query->totalDatos() == 1) {
        $ssid = session_id();
        if (empty($ssid))
            session_start();
        $_SESSION['usuario'] = $usuario;
        header('Location:../index.php');
    } else {
        header('Location:../index.php?error=1');
    }
}
/**
 * Genera el menu de la aplicacion
 * @return string La tabla del menu
 * 
 */
function menu ()
{
    require_once 'clases/Sql.php';
    $query = new Sql();
    $sql = "SELECT * FROM `menus`";
    $query->consulta($sql);
    $tabla = "<table width='100%'><tr>";
    foreach ($query->datos() as $dato) {
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
?>