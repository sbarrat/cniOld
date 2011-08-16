<?php
/**
 * Clase con metodos estaticos auxiliares para la aplicación
 * 
 * @author Ruben Lacasa Mas <rubendx@gmail.com>
 * @version 2.0
 *
 */
class Auxiliar {
	
	/**
	 * Codifica un texto en UTF-8
	 * @param string $data
	 * @return string
	 */
	public static function traduce($data) {
		return utf8_encode($data);
	}
	
	/**
	 * Decodifica un texto de UTF-8
	 * @param string $data
	 * @return string
	 */
	public static function codifica($data) {
		return utf8_decode($data);
	}
	
	/**
	 * Devuelve la clase en las tablas 
	 * @return string
	 */
	public static function clase()
	{
		static $fila = 0;
		if ( $fila % 2 == 0 )
			$clase = 'par';
		else
			$clase = 'impar';
		
		$fila++;
		return $clase;
	}
	
 }