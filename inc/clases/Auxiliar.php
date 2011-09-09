<?php
/**
 * Clase con metodos estaticos auxiliares para la aplicación
 *
 * metodos estaticos necesarios para la aplicacion
 * 
 * PHP Version 5.1.4
 * 
 * @author Ruben Lacasa Mas <rubendx@gmail.com>
 * @version 2.1
 * @todo Posible fusion con Aplicacion
 */
class Auxiliar
{
    /**
     * Codifica un texto en UTF-8
     * @param string $data
     * @return string
     */
    public static function traduce ($data)
    {
        return utf8_encode($data);
    }
    /**
     * Decodifica un texto de UTF-8
     * @param string $data
     * @return string
     */
    public static function codifica ($data)
    {
        return utf8_decode($data);
    }
    /**
     * Devuelve la clase en las tablas 
     * @return string
     */
    public static function clase ()
    {
        static $fila = 0;
        if ($fila % 2 == 0)
            $clase = 'par';
        else
            $clase = 'impar';
        $fila ++;
        return $clase;
    }
    
    /**
 	* funcion desvioActivo( $desvio, $estado, $extranet, $cliente )
 	* 
 	* Muestra el estado del desvio
 	* @param array desvio, estado, extranet, cliente
 	* @return string
 	*/
    public static function desvioActivo( $vars ) {
	
	    $cadena = '<img src="imagenes/activo.gif" 
		alt="Cliente Activo" width="24px"/>';
        
	    if ( $vars[ 'estado' ] == 0 ) //Cliente no activo
		    $cadena = '<img src="imagenes/noactivo.gif" 
			alt="Cliente Inactivo" width="24px"/>';
	
		if ( $vars[ 'desvio' ] == 0 ) //Desvio activo o no
		    $cadena .= '<img src="imagenes/desvioi.gif" 
			alt="Desvio Inactivo" width="24px"/>';
	    else
		    $cadena .= '<span class="popup" 
			onclick="ver_detalles(0,0,0,' . $vars['cliente'] . ')">
			<img src="imagenes/nudesvioa.gif" 
			alt="Desvio Activo" width="24px" />
			</span>';
		
	    if ( $vars[ 'extranet' ] == 0 ) //Extranet activa o inactiva
		    $cadena .= '<img src="imagenes/extraneti.gif" 
			alt="Extranet Inactivo" width="24px"/>';
	    else
		    $cadena .= '<span class="popup" 
			onclick="ver_detalles(0,0,1,' . $vars[ 'cliente' ] . ')">
			<img src="imagenes/extraneta.gif" 
			alt="Extranet Activa" width="24px"/>
			</span>';
		
	    return $cadena;	

    }
    
/**
 * Establece el color de la cabezera del formulario 
 * 
 * @param string $tabla Tabla a chequear
 * @param array $vars Array de opciones
 * @return string $color Color de la cabezera
 */
    public static function colorCabezera( $tabla, $categoria ) {
	
	$color = '#7d0063';
	
	if ( $tabla == 'clientes' ) {
		
		if ( preg_match( '/despacho/', $categoria ) ) {
			$color = '#6699CC';
		}
		if ( preg_match( '/domicili/', $categoria ) ) {
			$color = '#FF9900';
		}
	}
	
	return $color;

}
	/**
	 * Se pasa un string o un array y comprueba su formato
	 * si esta correcto devuelve el array o el string si
	 * no esta correcto devuelve false
	 * 
	 * @param array|string $vars le pasamos una string o un array a comprobar
	 * @return array|string|boolean devuelve el array, el string o falso si falla
	 */
	public static function sanitize( $vars )
	{
		$sanitize = false;
		$fail = false;
		if (is_array( $vars ) ) {
			foreach ( $vars as $var ) {
				if ( !ctype_alnum( $var ) ) {
					$fail = true;
				} 
			}
			if (!$fail) {
				$sanitize = $vars;
			}
		} elseif ( is_string( $vars ) ) {
			if ( ctype_alnum( $var )) {
				$sanitize = $vars;
			}
		}
		return $sanitize;
	}
}