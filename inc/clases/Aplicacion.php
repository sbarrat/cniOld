<?php
/**
 * Aplicacion File Doc Comment
 * 
 * Clase que controla las acciones con la base de datos
 * 
 * PHP Version 5.1.4
 * 
 * @category Aplicacion
 * @package  cni/inc/clases
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
require_once 'Sql.php';
/**
 * Aplicacion Class Doc Comment
 * 
 * @category Class
 * @package  Aplicacion
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com>
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @version  Release: 2.1
 * @link     https://github.com/sbarrat/cni
 *
 */
class Aplicacion extends Sql
{
    /**
     * Enter description here ...
     */
    public function menu ()
    {
        $sql = "SELECT * FROM `menus`";
        parent::consulta( $sql );
        return parent::datos();
    }
    /**
     * Enter description here ...
     * 
     * @param unknown_type $vars
     */
    public function formulario ( $vars )
    {
	   
    	$sql = "SELECT * FROM `" . parent::escape( $vars[ 'tabla' ] ) . "` 
		WHERE `id` LIKE '" . parent::escape( $vars[ 'registro' ] ) ."'";   
	    parent::consulta( $sql );
	    $numeroCampos = 0;
	    foreach ( parent::datos() as $dato ) {
		    foreach ( $dato as $key => $var ) {
			    $resultado[ $key ] = $var; 
			    $numeroCampos++;
		    }
	    }
	    $cadena = '
		<form id="formulario_actualizacion" method="post" 
		onSubmit="actualiza_registro(); return false">
		<input type="hidden" id="opcion" value="0" />
		<input type="hidden" id="idemp" value=" ' .$resultado[ 'Id' ] .'" />
		<table cellpadding="0px" cellspacing="1px" class="formulario">';
		$desvio = '';
	    if ( $vars[ 'tabla' ] == 'clientes' ) {
		    $desvio = Auxiliar::desvioActivo( array(
			    	'desvio' => $resultado[ 'desvio' ],
			    	'estado' => $resultado[ 'Estado_de_cliente' ],
			    	'extranet' => $resultado[ 'extranet' ],
			    	'cliente' => $resultado[ 'Id' ] 
		            )
			    );
	    }
	    $colorCabezera 
	    	= Auxiliar::colorCabezera( 
	    		$vars[ 'tabla' ], 
	    		$resultado['Categoria'] 
	    	);
	    $cadena .= '<th height="24px" bgcolor="' . 
	        $colorCabezera . '" color="#fff" align="left" width="100px">
			<div id="edicion_actividad">
			</div>' . $desvio . '
			</th>
			<th height="24px" align="left" bgcolor="' . $colorCabezera . '" 
			colspan="2">
			<font size="4">' . Auxiliar::traduce( $resultado[ 'Nombre' ] ) .'  
			' . $this->codigoNegocio( $resultado[ 'Id' ] ) . '</font>
			<input type="hidden" name="nombre_tabla" id="nombre_tabla" 
			value="' . $vars[ 'tabla' ] .'" />
			<input type="hidden" name="numero_registro" id="numero_registro" 
			value="' . $vars[ 'registro' ] . '" />
			</th>
			<th align="right" bgcolor="' . $colorCabezera . '">
			<input class="boton" onclick="cierra_el_formulario()" 
			value="[X] Cerrar" >
			</th></tr>';
	    $cadena .= $this->submenus( $vars ); 
	    $j = 0;
	    $i = 1;
	    foreach( $resultado as $key => $dato) {
		    if ( $j % 2 == 0 ) {
			    $cadena .= "</tr><tr>";
		    }
		    $j++;
		
		    $cadena .= '<th align="left" valign="top" class="nombre_campo">
			' . Auxiliar::traduce( $this->aliasCampo( $key, $vars['tabla'] ) ) .
			'</th>
			<td align="left" valign="top" class="valor_campo">' . 
			
		    $this->tipoCampo( array(
		    	'campo'    =>    $key, 
				'tabla'    =>    $vars[ 'tabla' ], 
				'valor'    =>    $dato,
				'opcion'   =>	 'actualiza',
				'orden'    =>    $i 
		        )
			) 
				. '</td>';
		    $i++;		
	    }
	
	    $cadena .= '</tr>';
	
	    if ( isset( $vars[ 'principal' ] ) ) {
		
		    $cadena .= '<tr>
				<th colspan="4" align="center">
				<input class="boton" type="submit" value="[+] Agregar" />
				<input class="boton" type="reset" 
					value="[L] Limpiar formulario" />
				</th></tr>';
	    } else {
		    
	        $cadena .= '<tr>
				<th colspan="4" align="center"><p/>
				<input class="boton" type="submit" value="[*]Actualizar Datos" 
				tabindex="' . $numeroCampos . '"/>
				<input type="button" class="boton" 
				onclick="borrar_registro(' . $resultado['Id'] . ')" 
				value="[X]Borrar Datos" tabindex="' . $numeroCampos. '"/>
				</th></tr>';
	    }
	
	    $cadena .= "</table></form>";
	
	    return $cadena;
    }
    
	/**
 	* Devuelve el codigo de Negocio de la Empresa
 	* 
 	* @param string $idemp id de empresa
 	* @return string codigo de negocio
 	*/
	public function codigoNegocio( $idemp )
	{
		$cadena = '';
		if ( isset( $idemp ) && $idemp != null ) {
			$sql = "SELECT * FROM `z_sercont` 
				WHERE `idemp` LIKE '" . parent::escape( $idemp ) ."' 
				AND `servicio` LIKE 'Codigo Negocio'";
			parent::consulta( $sql );
			if ( parent::totalDatos() >= 1 ) {
				$resultado = parent::datos();
				$cadena = '<span class="codigoNegocio">' . 
				$resultado[ 0 ][ "valor" ] . '</span>';
			}
		}
		return $cadena;
    }
    
/**
 * Genera los submenus del formulario
 * 
 * Pasando la tabla chequea la tabla submenus y si lo hay lo genera
 * 
 * @param string $tabla
 * @return string
 */
public function submenus( $tabla )
{
	
	
	
	$sql = "SELECT `submenus`.* FROM `submenus`
	INNER JOIN `menus` ON `submenus`.`menu` = `menus`.`id` 
	WHERE `menus`.`pagina` LIKE '" . parent::escape( $tabla[ 'tabla' ] ) . "'";
	
	parent::consulta( $sql );
	
	$cadena = '<tr><th colspan="4" width="100%" height="26px"><table><tr>';
	
	foreach ( parent::datos() as $resultado ) {
		if ( $resultado[ 'nombre' ] == "Principal" ) {
		
			$cadena .= '<th>
			<span class="boton" 
			onclick="muestra(' . $tabla[ 'registro' ] . ')" >
		 	' . Auxiliar::traduce( $resultado[ 'nombre' ] ) . 
		 	'</span></th>';
		
		} else {
		
			$cadena .= '<th>
			<span class="boton" onclick="submenu(' . $resultado[ 'id' ] . ')" >
			' . Auxiliar::traduce( $resultado[ 'nombre' ] ) . 
			'</span></th>';
		}
	}
	
	$cadena .= '</tr></table></th></tr>';
	
	return $cadena;

    }
    
    /**
 * Funcion nombreCampo( $campo, $tabla ) Devuelve el nombre del campo
 * 
 * Pasando como parametro el nombre del campo y la tabla devuelve el nombre
 * del campo que tenemos en la tabla alias
 * 
 * @param string $campo
 * @param string $tabla
 * @return string
 */
public function aliasCampo( $campo, $tabla ) {
	
	
	$sql = "SELECT `campof` FROM `alias` 
	WHERE `tabla` LIKE '" . parent::escape( $tabla ) . "' 
	AND `campoo` LIKE '" . parent::escape($campo) ."'";
	
	parent::consulta($sql);
	$resultado = parent::datoUnico();
	
	return $resultado[ 'campof' ];
}

/**
 * funcion tipoCampo( $vars )
 * 
 * Devuelve el tipo de campo que es para mostrarlo en el formulario
 * @param array campo, tabla, valor, opcion, orden
 * @return string
 */
function tipoCampo( $vars ) {
	

	$sql = "SELECT * FROM `alias` 
	WHERE `tabla` LIKE '" . parent::escape( $vars['tabla'] ) . "' 
	AND `campoo` LIKE '" . parent::escape( $vars['campo'] ) . "'";
	
	parent::consulta($sql);
	$resultado = parent::datoUnico();
	
	$contador = 0;
	
	switch ( $resultado[ 'tipo' ] )
	{
		case 'text': 
		 			if ( ( $vars['tabla'] == 'z_sercont' ) && 
		 			( $resultado[ 'campoo' ] == 'valor' ) ) {
						
		 				$cadena = '<div id="tipo_teleco">
						 <input type="text" size="' .$resultado[ 'size' ] . '" 
						 id="' . $resultado[ 'variable' ] . '" 
						 name="' . $resultado[ 'campoo' ] .'" 
						 value="' . Auxiliar::traduce( $vars['valor'] ) . '" 
						 tabindex="' . $contador . '"  
						 onkeyup="chequea_valor()" />
						 </div>';
		 			
		 			} else {
					
		 				$cadena = '<input type="text" 
		 				size="' . $resultado[ 'size' ] .'" 
		 				id="' . $resultado[ 'variable' ] .'" 
		 				name="' . $resultado[ 'campoo' ] .'" 
		 				value="' . Auxiliar::traduce( $vars[ 'valor' ] ) .'" 
		 				tabindex="' . $contador . '" />';
		 			}
		break;
		
		case 'textarea': $cadena = '<textarea 
							id="' . $resultado[ 'variable' ] . '" 
							name="' . $resultado[ 'campoo' ] . '" 
							rows="' . $resultado[ 'size' ] . '" 
							cols="46" tabindex="' . $contador . '">
							' . Auxiliar::traduce( $vars['valor'] ) . '
							</textarea>';
		break;
		
		case 'checkbox': 
						$chequeado = ( $vars['valor'] != 0 ) ? 'checked' : ''; 
						$cadena = '<input  type="checkbox" 
						id="' . $resultado[ 'variable' ] . '" 
						' . $chequeado . '
						name="' . $resultado[ 'campoo' ] . '" 
						tabindex="' . $contador .'" />';
							
		break;
		
		case 'date': $cadena = '<input type="text" 
						id="' . $resultado[ 'variable' ] . '" 
						name="' . $resultado[ 'campoo' ] . '" 
						size="' . $resultado[ 'size' ] . '"  
						value="' . Fecha::cambiaf( $vars['valor'] ) . '" 
						tabindex="' . $contador . '"/>
						<button type="button" class="calendario" 
						id="f_trigger_' . $resultado[ 'variable' ] . '" 
						tabindex="' . $contador . '"></button>';
		break;
		/**
		 * @todo continuar revisando esto, no me convence esta nueva consulta
		 */
		case 'select':  
					   $sql = "SELECT * FROM 
					   `" . parent::escape( $resultado[ 'depende' ] ) . "` 
					   ORDER BY 2";
					   parent::consulta($sql);
					   
					   if ( $vars['tabla'] == 'z_sercont' ) {
					   		
					   		$cadena = '<select 
					   			id="' . $resultado[ 'variable' ] . '" 
					   			name="' . $resultado[ 'campoo' ] . '" 
					   			tabindex="' . $contador . '" 
					   			onchange="muestra_campo()">';
					   	} else {
					   	
					   		$cadena = '<select 
					   			id="' . $resultado[ 'variable' ] . '" 
					   			name="' . $resultado[ 'campoo' ] . '" 
					   			tabindex="' . $contador . '">
								<option value="0">-::' . 
					  			Auxiliar::traduce( $resultado[ 'campoo' ] ) . '
					  			":-</option>';
					  			
					  			
					  			foreach ( parent::datos() as $dato ) {
								
					  				$marcado = ( 
					  				Auxiliar::traduce( $dato[ 'Nombre' ] ) 
					  				==
					  			  	Auxiliar::traduce( $vars[ 'valor' ] ) 
					  				) ? 'selected' : ''; 
								
					  				$cadena .= '<option ' . $marcado . ' 
					  				value="' . 
					  				Auxiliar::traduce( $dato[ 'Nombre' ] ) 
					  				. '">'. 
					  				Auxiliar::traduce( $dato[ 'Nombre' ] ) 
					  				. '</option>';
								}
							
							$cadena .= '</select>' . 
							Auxiliar::traduce( $vars[ 'valor' ] );
							}
		break;					
		
		default: $cadena = $vars[ 'valor' ];
		break;
		
	}
	if ( isset( $resultado[ 'enlace' ] ) ) {
		switch ( $resultado[ 'enlace' ] ) {
		
			case 'web': $cadena .= '<a href="http://' . $vars[ 'valor' ] . ' " 
						target="_blank">
						<img src="iconos/package_network.png" width="14" 
						alt="Abrir Web"/>
						</a>';
			break;
			
			case 'mail' : $cadena .= '<a href="mailto:' . $vars[ 'valor' ] . '">
						<img src="iconos/mail_generic.png" width="14" 
						alt="Enviar Correo"/></a>';
			break;
		}
	}
	
	return $cadena;
}

// opcion,codigo,registro, codigo = codigo de submenu, registro = cliente 
public function subformulario( $vars ) 
{
	
	if (!isset($vars['marcado']) )
	    $vars['marcado'] = NULL;
	    
    $sql = "SELECT `s`.`pagina` AS `subpagina`, `m`.`pagina`, 
	`s`.`listado`, `s`.`nombre` 
	FROM `submenus` AS `s` 
	INNER JOIN `menus` AS `m` 
	ON `s`.`menu` = `m`.`id` 
	WHERE `s`.`id` LIKE " . parent::escape($vars[ 'codigo' ] );
	
	parent::consulta($sql);
	
	//0=>subpagina, 1=>pagina, 2=>listado, 3=>nombre
	
	$resultado = parent::datoUnico();
	
	$tabla = array(
	 'tabla' => $resultado[ 'pagina' ],
	 'registro' => $vars[ 'registro' ]
	);
	
//2 casos de subformularios, proveedores y clientes
	if ( isset( $vars[ 'tabla' ] ) ) {
		
		$subtabla = ( $vars[ 'tabla '] == 'pproveedores' ) ? 
			'proveedores' : 'clientes';
		$sql = "SELECT `c`.`id` FROM `" . $subtabla . "` AS `c` 
				INNER JOIN `" . parent::escape( $vars[ 'tabla' ] ) . "` AS `t` 
				ON `c`.`id` = `t`.`idemp` 
				WHERE `t`.`id` 
				LIKE " . parent::escape( $vars[ 'registro' ] );
		
		parent::consulta( $sql );
		$dato = parent::datoUnico() ;
		$tabla[ 'registro' ] = $dato[ 'id' ];
	}
	else
		$registro = $vars[ 'registro' ];
		
	
	switch( $resultado[ 'pagina' ] )
	{
		
		case "proveedores":
            $sql = "SELECT `Nombre` FROM `proveedores` 
            WHERE `id` LIKE ". parent::escape( $vars[ 'registro' ] );
            $codigoNegocio = '';
        break;
        
        default: 
        	$sql = "SELECT `Nombre` FROM `clientes` 
        	WHERE `id` LIKE " . $registro;
            $codigoNegocio = $this->codigoNegocio( $registro );
        break;    
	}
	//$cadena .= $sql; Para depurar
	parent::consulta( $sql );
	$resultado2 = parent::datoUnico();
	
	$cadena = '<form id="formulario_alta" action="#" 
	onsubmit="agrega_registro(); return false">
	<table cellpadding="0px" cellspacing="1px" class="formulario" ><tr>
	<th bgcolor="#7d0063" align="left"></th>
	<th bgcolor="#7d0063" colspan = "3" bgcolor="#ccc" align="left">
	<font size="4">' .Auxiliar::traduce( ucfirst( $resultado[ 'nombre' ] ) ) . 
	' de ' . Auxiliar::traduce( ucfirst( $resultado2[ 'Nombre' ] ) ) . 
	' ' . $codigoNegocio . '</font>
	<input type="hidden" id="id" name="id" 
	value="' . $vars[ 'registro' ] . '" />
	<input type="hidden" id="idemp" name="idemp" value="' . $registro . '" />
	<input type="hidden" name="nombre_tabla" id="nombre_tabla" 
	value="' . $resultado[ 'subpagina' ] .'" /></th>
	<th><input class="boton" onclick="cierra_el_formulario()" 
	value="[X] Cerrar" ></th></tr>';
	
	$cadena .= $this->submenus( $tabla );
	
	$formulario = "SELECT * 
	FROM `" . parent::escape( $resultado[ 'subpagina' ] ) . "` 
	WHERE `id` LIKE '" . parent::escape( $vars[ 'registro' ] ) . "'";
	
	$listado = "SELECT * 
	FROM `" . $resultado[ 'subpagina' ] . "` 
	WHERE `idemp` LIKE '" . parent::escape( $registro ) ."'";
	
	//Caso de telecos
	if ( $resultado[ 'subpagina' ] == 'z_sercont' )
		$listado .= " ORDER BY `servicio`";
	
	var_dump($vars);
	switch ( $resultado[ 'subpagina' ] ) {
		
		case ( 'facturacion' ) : 
			$cadena .= $this->subform( 
				$listado, 
				$resultado[ 'subpagina' ], 
				$registro, 
				$vars[ 'marcado' ] );
			$cadena .= '<input type="button" class="boton" 
				value="Parametros Factura" 
				onclick="parametros_factura(' . $registro . ')" />
				<div id="parametros_factura"></div>';
			//$cadena .= servicios_fijos( $registro );
		break;
		
		case ( 'z_facturacion' ) : 
			$cadena .= $this->subform( 
				$listado, 
				$resultado[ 'subpagina' ],
				$registro,
				$vars['marcado'] );
		break;
		case ( 'cfm' ) :
			$cadena .= $this->subform( 
				$listado,
				$resultado[ 'subpagina' ],
				$registro,
				$vars[ 'marcado' ]
				);
		break;
		case ( 'tllamadas' ) : 
			$cadena .= $this->subform(
				$listado,
				$resultado[ 'subpagina' ],
				$registro,
				$vars[ 'marcado' ] 
				);
		break;
		
		default: 
			$cadena .= $this->subform(
				$formulario,
				$resultado[ 'subpagina' ],
				$registro,
				$vars[ 'marcado' ]
				) . '' . sublist( $listado, $resultado[ 'subpagina' ] );
		break;
	}
	
	$cadena .= '</table></form>';
	
	return $cadena;
}
//***********************************************************************************************/
public function subform( $sql, $tabla, $registro, $marcado = NULL ) 
{
	
	
	parent::consulta($sql);
	
	$resultado = parent::datoUnico();
	$numeroCampos = count( $resultado );
	$numeroResultados = parent::totalDatos();
	
	switch ( $tabla )
	{
		case "facturacion": 
		    $cadenaOpcion = "<input type='hidden' id='opcion' value='2' >";
		    $tipo = chequea_estado_tabla($tabla,$registro);
		    break;
		case "z_facturacion":
		    $cadenaOpcion = "<input type='hidden' id='opcion' value='8' >";
		    $tipo = chequea_estado_tabla($tabla,$registro);
		    break;
		case "cfm":
		    $cadenaOpcion = "<input type='hidden' id='opcion' value='9' >";
		    $tipo = chequea_estado_tabla($tabla,$registro);
		    break;
		case "tllamadas":
		    $cadenaOpcion = "<input type='hidden' id='opcion' value='10' >";
		    $tipo = chequea_estado_tabla($tabla,$registro);
		    break;
		default:
		    $cadenaOpcion = "";
		    if(isset($marcado))$tipo = "Actualizar"; 
		    else $tipo = "nuevo";
		    break;
	}
	for ($i=2;$i<=$numeroCampos-1;$i++) {
			$nombreCampo = parent::nombreCampo($i);
		    $cadena .='<tr><th align="left" valign="top" bgcolor="#7d0063">
			<font color="#ffffff">' . Auxiliar::traduce($nombreCampo) . 
		    '</font></th>
		    <td align="left" valign="top" width="100%" bgcolor="#eeeeee">'; 
			
		    if($tipo == "nuevo")
			{
				$valorEnvio = 'Agregar';
				$valor = '';
			}
			else {
			    $valorEnvio = 'Actualizar';
			    $valor = $resultado[$i];
			}	
				
			$cadena .= 
			$this->tipoCampo( array( 
					'campo' => parent::nombreCampo($i),
					'tabla' => $tabla,
					'valor' => $valor,
					'opcion' => $tipo,
					'orden' => $i
				    )
				);
		
			$cadena .= '</td></tr>';
			
			$boton = '<input type="submit" class="boton" 
			name="boton_envio" value="' . $valorEnvio . '">';	
	}
			
	$cadena .= $cadenaOpcion;
	$cadena .= "<tr><th colspan='2'>".$boton."</th></tr>";
	
	return $cadena;
    }
}