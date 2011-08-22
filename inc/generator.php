<?php

//funciones de creacion dinamica de contenidos Hecha por Ruben Lacasa Mas en Marzo 2007 ruben@ensenalia.com
//***********************************************************************************************/
//switch(opcion) Recoge la opcion pasada por la funcion ajax y la redirecciona a la funcion asiganda php
//despues recoge el valor de la funcion en respuesta y la muestra por pantalla
//***********************************************************************************************/

$ssid = session_id(); //comprobamos si existe el id de session
if ( empty( $ssid ) ) session_start(); // si no existe iniciamos sesion

if ( isset( $_SESSION[ 'usuario' ] ) ) {
	
	include_once 'variables.php';
	$respuesta = "";
		
	if ( isset( $_POST[ 'opcion' ] ) ) {
	
		switch ( $_POST[ 'opcion' ] ) {
			
			case 0:  $respuesta = generador( $_POST );break;
			case 1:  $busca = new Busqueda(); 
			         $respuesta = $busca->BusquedaSimple( $_POST );
			break;
			case 2:  $formulario = new Aplicacion();
			         $respuesta = $formulario->formulario( $_POST );
			break;
			         
			case 3:  $formulario = new Aplicacion();
			         $respuesta = $formulario->subformulario( $_POST );
			break;
			         
			case 4:  $respuesta = actualiza( $_POST );break;
			case 5:  $respuesta = nuevo( $_POST );break;
			case 6:  $respuesta = agregaRegistro( $_POST );break;
			case 7:  $respuesta = borraRegistro( $_POST );break;
			case 8:  $respuesta = fromServiciosFijos( $_POST );break;
			case 9:  $respuesta = cambiaLosOtros( $_POST );break;
			case 10: $respuesta = agregaServicioFijo( $_POST );break;
			case 11: $respuesta = borraServicioFijo( $_POST );break;
			case 12: $respuesta = actualizaServicioFijo( $_POST );break;
		}
	}
	
echo $respuesta;

}			


/**
 * Generación de los formularios segun la eleccion
 * 
 * Esta funcion se encarga de la generacion de los formularios, pasandole
 * como parametros una tabla dada y el registro
 * 
 * @param array $vars
 * @return string
 */
function generador( $vars ) {

	if( $vars[ 'codigo' ] == 6 ) {
		
		$tabla = '
		<div class="gestion_app">
		Gestión de Base de Datos:
		<span class="boton" onclick="hacer_backup()">
		&nbsp;&nbsp;[H]Hacer copia&nbsp;&nbsp;</span>
		<span class="boton" onclick="lista_backup()">
		&nbsp;&nbsp;[L]Listado de Copias realizadas&nbsp;&nbsp;</span>
		<span class="boton" onclick="revisar_tablas()">
		&nbsp;&nbsp;[V]Revisar Tablas&nbsp;&nbsp;</span>
		<span class="boton" onclick="reparar_tablas()">
		&nbsp;&nbsp;[R]Reparar Tablas&nbsp;&nbsp;</span>
		<span class="boton" onclick="optimizar_tablas()">
		&nbsp;&nbsp;[O]Optimizar Tablas&nbsp;&nbsp;</span>
		</div>
		<div class="gestion_app">
		Datos Categorias:
		<span class="boton" onclick="categorias(1)">Categorias Servicios</span>
		<span class="boton" onclick="categorias(2)">Categorias Clientes</span>
		</div>
		<div class="gestion_app">
		Telefonos Centro: 
		<span class="boton" onclick="formulario_telefonos()">
		&nbsp;&nbsp;Gestion Telefonos Centro &nbsp;&nbsp;</span>
		</div>
		<div class="gestion_app">
		Listado Despachos y Domiciliados:
		<input type="button" class="boton" onclick="consulta_especial()" 
		value="Ver Listado Completo" />
		<p><label>Ver listado filtrado de:</label>
		' . listadoCategorias() . '
		</p>
		</div>
		<div id="listado_copias"></div>
		<div id="estado_copia"></div>
		<div id="status_tablas"></div>
		</center>';
	}
	else
	{
		$query = new Sql();
		$sql = "SELECT `pagina` FROM `menus` 
		WHERE `id` LIKE " . $query->escape( $vars[ 'codigo' ] ) ;
		
		$query->consulta( $sql );
		$resultado = $query->datos();
		
		$tabla = '<div id="botoneria">
		 	&nbsp;&nbsp;<span class="titulo_categoria">
			Seleccione ' . ucfirst( $resultado[ 0 ][ 'pagina' ] ) . ':</span>
			<input type="hidden" id="tabla" 
				value="' . $resultado[ 0 ][ 'pagina' ] . '" />
			<input type="hidden" id="nuevo" value="' . $vars[ 'codigo' ] . '" />
			<input type="text" id="texto" autocomplete="off" onkeyup="busca()"/>
			&nbsp;<input class="boton" type="submit" 
			onclick="busca()" value="[M]Mostrar Busqueda">
			&nbsp;<input class="boton" type="submit" 
			onclick="nuevo(' . $vars[ 'codigo' ] . ')" 
			value="[+] Nuevo ' . ucfirst( $resultado[ 0 ][ 'pagina' ] ) . '">';
		
		if ( $vars[ 'codigo' ] == 1 ) {
				
			$tabla .= '&nbsp;<input class="boton" type="submit" 
			onclick = popUp("servicont/index.php") 
			value = "Estadisticas Servicios" />
			&nbsp;<input class="boton" type="submit" 
			onclick = popUp("rapido/index.php") 
			value = "Asignacion de Servicios" />
			&nbsp;<input class="boton" type="submit" 
			onclick = popUp("almacen/index.php") value = "Almacenaje" />
			&nbsp;<input class="boton" type="submit" 
			onclick = popUp("contratos/index.php") value = "Contratos" />
			&nbsp;<input class="boton" type="submit" 
			onclick = popUp("agenda/index.php") value = "Agenda" />
			&nbsp;<input class="boton" type="submit" 
			onclick = popUp("entradas/index.php") value = "Entradas" />';
			
		}
		
		$tabla .= '</div>';
		
	}
	
	return $tabla;

}





/**
 * Genera el formulario dependiendo de nuestra eleccion
 * @param array $vars
 * @return string
 */
/*function formulario( $vars ) {
	
	$query = new Sql();
	
	$sql = "SELECT * FROM `" . $query->escape( $vars[ 'tabla' ] ) . "` 
	WHERE `id` LIKE '" . $query->escape( $vars[ 'registro' ] ) ."'";
	
	$query->consulta( $sql );
	$numeroCampos = 0;
	foreach ( $query->datos() as $dato ) {
		
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
		
		$desvio = desvioActivo( 
			$resultado[ 'desvio' ],
			$resultado[ 'Estado_de_cliente' ],
			$resultado[ 'extranet' ],
			$resultado[ 'Id' ]
			);
	}
	
	$colorCabezera = colorCabezera( $vars[ 'tabla' ], $resultado );
	
	$cadena .= '<th height="24px" bgcolor="' . $colorCabezera . '" color="#fff" 
	align="left" width="100px">
	<div id="edicion_actividad">
	</div>' . $desvio . '
	</th>
	<th height="24px" align="left" bgcolor="' . $colorCabezera . '" 
	colspan="2"><font size="4">' . Auxiliar::traduce( $resultado[ 'Nombre' ] ) .'  
	' . codigoNegocio( $resultado[ 'Id' ] ) . '</font>
	<input type="hidden" name="nombre_tabla" id="nombre_tabla" 
	value="' . $vars[ 'tabla' ] .'" />
	<input type="hidden" name="numero_registro" id="numero_registro" 
	value="' . $vars[ 'registro' ] . '" />
	</th>
	<th align="right" bgcolor="' . $colorCabezera . '">
	<input class="boton" onclick="cierra_el_formulario()" value="[X] Cerrar" >
	</th></tr>';

	$cadena .= submenus( $vars ); 
	
	$j = 0;
	$i = 1;
	
	foreach( $resultado as $key => $dato) {
		if ( $j % 2 == 0 )
			$cadena .= "</tr><tr>";
		
		$j++;
		
		$cadena .= '<th align="left" valign="top" class="nombre_campo">
		' . Auxiliar::traduce( 
			nombreCampo( $key , $vars['tabla'] ) ) .'
		</th>
		<td align="left" valign="top" class="valor_campo">' . 
			tipoCampo( $key, 
				$vars[ 'tabla' ], 
				$dato,
				'actualiza',
				$i ) . '
		</td>';
		$i++;		
	}
	
	$cadena .= '</tr>';
	
	if ( isset( $vars[ 'principal' ] ) ) {
		
		$cadena .= '<tr>
			<th colspan="4" align="center">
			<input class="boton" type="submit" value="[+] Agregar" />
			<input class="boton" type="reset" value="[L] Limpiar formulario" />
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

}*/









//***********************************************************************************************/
//listado(array vars): Muestra el listado de la tabla que le hemos pasado dentro de la array
//El array $datos = array("tabla" => "$resultado[0]","registro" =>"$vars[registro]","principal"=>"$resultado[1]");
//***********************************************************************************************/
function listado($vars)
{

	
	$sql = "Select * from `$vars[tabla]` where idemp like $vars[registro]";
	$consulta = mysql_db_query($dbname,$sql,$con);
	$totdatos = mysql_num_rows($consulta);
	$tot_columnas = mysql_num_fields($consulta);
	$cadena .= "<table class='listado'><tr>";
	for ($i=2;$i<=$tot_columnas-1;$i++)
		$cadena.="<th>".traduce(ucfirst(mysql_field_name($consulta,$i)))."</th>";
	$cadena .= "</tr>";
	if ($totdatos == 0)
		$cadena .= "<tr><td colspan = '".$tot_columnas."' align='center'>No hay registros</td></tr>";
	else
		while($resultado = mysql_fetch_array($consulta))
			{
			$cadena .= "<tr>";
			for ($i=2;$i<=$tot_columnas-1;$i++)
				$cadena .= "<td>".$resultado[$i]."</td>";
			$cadena .= "</tr>";
			}
	$cadena .= "</table>";	
	return $cadena;
}

//***********************************************************************************************/
//Comprobacion y cambio de valor en los campos check
//***********************************************************************************************/
function comprueba_check($tabla,$campo,$valor)
{
	include("variables.php");
	$sql = "Select tipo from alias where tabla like '$tabla' and campoo like '$campo'";
	//echo $campo.":".$valor."<br/>";
	$consulta = mysql_db_query($dbname,$sql,$con);
	$resultado = mysql_fetch_array($consulta);
	switch($resultado[0])
	{
	case "checkbox":{
					if ($valor == 'on')
						$valor = -1;
					else
						$valor = 0;}break;
	case "date":$valor = cambiaf($valor);break;
	default:$valor=$valor;
	}
	//echo $campo.":".$valor;
	return $valor;
}
//***********************************************************************************************/
//actualizacion de registros
function actualiza($vars)
{
	//todos los valores estan serializados en el formulario 2 nos importan el nombre_tabla y el numero_registro
	//el resto pueden entrar en el bucle
	include("variables.php");
	$sql = "Select * from `$vars[nombre_tabla]`";
	$consulta = mysql_db_query($dbname,$sql,$con);
	$totcamp = mysql_numfields($consulta); //total de campos
	$sql = "Update `$vars[nombre_tabla]` set ";
	for($i=1;$i<=$totcamp-1;$i++) //empezamos desde 1 para saltarnos el id
		{
		$sql .= " `".mysql_field_name($consulta,$i) ."` = '".codifica(comprueba_check($vars[nombre_tabla],mysql_field_name($consulta,$i),$vars[mysql_field_name($consulta,$i)]))."',";
		}
	$longitud = strlen($sql);
	$sql = substr($sql,0,$longitud-1); //eliminamos la , final
	$sql .= " where id like $vars[numero_registro]";
        //foreach($vars as $key => $valor)
	//$valores .= $key ."=>".$valor.";";
	//REASIGNACION DE SERVICIOS
	foreach($vars as $key => $variable)
	$cadenita.=$key."=>".$variable."<br>";
	if(($vars[nombre_tabla]=="clientes")&&(!isset($vars[Estado_de_cliente])))
	{
			//$cadenita.="Dentro";
			//Chequeo de tabla para asignacion directa por codigo de negocio
			$sql2 = "Select * from z_sercont where idemp like $vars[numero_registro]";
			//$cadenita.=$sql2;
			$consulta = @mysql_db_query($dbname,$sql2,$con);
			if(@mysql_numrows($consulta)!=0)
			{
				while($resultado = @mysql_fetch_array($consulta))
				{
					//tomamos valor del codigo de negocio
					if($resultado[servicio]=="Codigo Negocio")
					$cod_despacho = intval($resultado[valor]);
				}
				if($cod_despacho == 23)
				$code = "JUNTAS";
				else
				$code = $cod_despacho;
				$sql3 = "Select id from clientes where nombre like 'LIBRE $code'";
				$consulta = @mysql_db_query($dbname,$sql3,$con);
				$resultado = @mysql_fetch_array($consulta);
				$sql4 = "Update z_sercont set idemp=$resultado[0] where idemp like $vars[numero_registro]";
				$consulta = @mysql_db_query($dbname,$sql4,$con);
				$sql5 = "Delete from z_sercont where idemp like $resultado[0] and servicio like 'Codigo_Negocio'";
				$consulta = @mysql_db_query($dbname,$sql5,$con);
			}
	}

	if(mysql_db_query($dbname,$sql,$con))
		return "<img src='".OK."' alt='Registro Actualizado' width='24'/> Registro Actualizado &nbsp;&nbsp;<p/>".$sql5;
	else
		return "<img src='".NOK."' alt='ERROR' width='24'/> ERROR&nbsp;&nbsp;<p/> ".$sql;
}
//***********************************************************************************************/

//formulario de registro nuevo, aqui boton de agregar	
function nuevo($vars)
{
	include("variables.php");
	//pasamos el codigo necesito el nombre de tabla
	$sql = "Select pagina from menus where id like $vars[tabla]";
	$consulta = mysql_db_query($dbname,$sql,$con);
	$resultado = mysql_fetch_array($consulta);
	//consulta vacia para nombre de las cabezeras de la tabla
	$sql = "Select * from `$resultado[0]`";
	$consulta = mysql_db_query($dbname,$sql,$con);
	$numero_campos = mysql_num_fields($consulta);
	
	//se queda aqui es lo necesario para los nombres de campo
	$cadena .= "<form id='formulario_alta' action='#' onsubmit='agrega_registro(); return false'><table cellpadding=0px cellspacing=1px class='formulario'><tr>";
	$cadena .= "<input type='hidden' id='opcion' value='0' />";
	$cadena .= "<th align='left' bgcolor='#ccc' colspan='3'>".traduce($resultado[Nombre])."<input type='hidden' name='nombre_tabla' id='nombre_tabla' value='".$resultado[0]."' />
	</th></tr>";
	//Fin de los submenus
	for($i=1;$i<=$numero_campos-1;$i++)
	{
		if($j%2==0)
		$cadena .= "</tr><tr>";
		$j++;
		$cadena .= "<th align='right' valign='top' bgcolor='#7d0063'><font color='#ffffff'>".traduce(nombreCampo(mysql_field_name($consulta,$i),$resultado[0])) ."</font></th><td align='left' valign='top'>".tipoCampo(mysql_field_name($consulta,$i),$resultado[0],'','nuevo',$i) ."</td>";
	}
	$cadena .= "</tr>";
	//parte de la botoneria
	$cadena .= "<tr><th colspan='4' align='center'><input class='boton' type='submit'  name='boton_envio' value='Agregar' />";
	$cadena .= "&nbsp;<input class='boton' type='reset'  value='Limpiar formulario' /></th></tr>";
	$cadena .= "</table></form>";
	return $cadena;
}
//***********************************************************************************************/
//OPCION 6 Agregamos el regis, probaremos a poner la actualizacion de subformulario aqui a ver
//***********************************************************************************************/
/* NOTA MENTAL:
 * Tenemos ahora unos clientes que empiezan por el nombre LIBRE y que hacen
 * referencia a los despachos libres, estos clientes tienen asignadas unas 
 * caracteristicas la cuales son agregadas al cliente cuando se le asigna
 * el codigo de negocio que representa ese despacho, por lo tanto tengo que
 * chequear si se esta agregando a z_sercont y si el valor que se agrega
 * es codigo de negocio, en tal caso se le agregaran todos los datos que
 * tiene ese despacho
 */
function agregaRegistro($vars)
{
	 include("variables.php");
	 $sql = "Select * from `$vars[nombre_tabla]`";
	 $campos = mysql_db_query($dbname,$sql,$con);
	 $total = mysql_num_fields($campos);
	 if($vars[boton_envio] == "Agregar")
	 {
		$sql = "Insert into `$vars[nombre_tabla]` ("; 
	//todo junto
		for($i=1;$i<=$total -1;$i++)
		{
			$sql .= "`".mysql_field_name($campos,$i)."`,";
			$sql2 .= "'".codifica(comprueba_check($vars[nombre_tabla],mysql_field_name($campos,$i),$vars[mysql_field_name($campos,$i)]))."',";
		}
	//quitamos la , del final y pongo parentesis
		$longitud = strlen($sql);
		$longitud2 = strlen($sql2);
		$sql = substr($sql,0,$longitud-1) .") values (".substr($sql2,0,$longitud2-1) .")";
	//ahora los valores
	//CASO DE CODIGO NEGOCIO
		if(($vars[nombre_tabla]== 'z_sercont') && ($vars[servicio] == 'Codigo Negocio'))
		{
		//chequeamos los valores del LIBRE
			$code = intval($vars[valor]);
			if ($code == 23)
				$code = "JUNTAS";
			$sql2 = "Select id from clientes where Nombre like 'LIBRE $code'";
			$consulta = @mysql_db_query($dbname,$sql2,$con);
			$resultado = @mysql_fetch_array($consulta);
			$code_cli = $resultado[0];
			$sql2 = "Select * from z_sercont where idemp like $resultado[0]";
			$consulta = @mysql_db_query($dbname,$sql2,$con);
			if(@mysql_numrows($consulta)!=0)
			{
				$sql3 = "Update z_sercont set idemp=$vars[idemp] where idemp like $code_cli";
				$consulta = @mysql_db_query($dbname,$sql3,$con);
			}
		}
	$tipo = "Agregado";
	}
	else
	{
		//Caso de la baja reasignamos sus datos al despacho
		$sql = "Update `$vars[nombre_tabla]` set ";
		for($i=1;$i<=$total-1;$i++) //empezamos desde 1 para saltarnos el id
		$sql .= " `".mysql_field_name($campos,$i) ."` = '".codifica(comprueba_check($vars[nombre_tabla],mysql_field_name($campos,$i),$vars[mysql_field_name($campos,$i)]))."',";
		$longitud = strlen($sql);
		$sql = substr($sql,0,$longitud-1); //eliminamos la , final
		if(($vars[nombre_tabla] == 'facturacion')||($vars[nombre_tabla] == 'z_facturacion')||($vars[nombre_tabla] == 'cfm') ||($vars[nombre_tabla] == 'tllamadas')) 
		$sql .= " where idemp like $vars[id]";
		else
		$sql .= " where id like $vars[id]";
		
		$tipo = "Actualizado";
		}
	
	if($consulta = mysql_db_query($dbname,$sql,$con))
	return "<img src='".OK."' alt='Registro ".$tipo."' width='24'/> Registro ".$tipo."&nbsp;&nbsp;<p/>".$test;
	else
		return "<img src='".NOK."' alt='ERROR' width='24'/> ERROR&nbsp;&nbsp;<p/>";
}
//***********************************************************************************************/
function borraRegistro($vars)
{
	include("variables.php");
	$sql = "Delete from `$vars[tabla]` where id like $vars[registro]";
	if($consulta = mysql_db_query($dbname,$sql,$con))
		return "<img src='".OK."' alt='Registro Borrado' width='24'/> Registro Borrado&nbsp;&nbsp;<p/>";
	else
		return "<img src='".NOK."' alt='ERROR' width='24'/> ERROR&nbsp;&nbsp;<p/>".$sql;
}

//***********************************************************************************************/
//OPCION:3 GENERADOR del subformulario
//***********************************************************************************************/


//***********************************************************************************************/
//CHEQUEA EL ESTADO SI hay registro sale actualizar no lo hay registro sale agregar*************/
//***********************************************************************************************/
function chequea_estado_tabla($tabla,$registro)
{
	include("variables.php");
	$sql = "Select * from `$tabla` where idemp like $registro";
	$consulta = mysql_db_query($dbname,$sql,$con);
	$total = mysql_numrows($consulta);
	if ($total == 0)
	$tipo = "nuevo";
	else
	$tipo = "Actualizar";
	return $tipo;
}

//***********************************************************************************************/
//SUBLISTADO DENTRO DEL SUBFORMULARIO************************************************************/
//***********************************************************************************************/
function sublist($sql,$tabla)
{
	
	//$cadena = $sql;
	//opcion en la que estamos
	$esecuele = "Select id from submenus where pagina like '$tabla'";
	$laconsulta = mysql_db_query($dbname,$esecuele,$con);
	$elresultado = mysql_fetch_array($laconsulta);
	$cadena .= "<tr><td colspan='2'><input type='hidden' id='opcion' value='".$elresultado[0]."' />";
	//echo $sql;
	$consulta = mysql_db_query($dbname,$sql,$con);
	$totcampos = mysql_num_fields($consulta);
	$cadena .= "<table width='100%' class='sublistado' cellspacing='0'><tr><th align='center' bgcolor='#7d0063'></th><th align='center' bgcolor='#7d0063'></th>";
	for($i=2;$i<=$totcampos-1;$i++)
	$cadena .= "<th align='center' bgcolor='#7d0063'><font color='#ffffff'>".ucfirst(mysql_field_name($consulta,$i))."</font></th>";
	$cadena .="</tr>";
	while ($resultado = mysql_fetch_array($consulta))
	{
		$j++;
		if($j%2==0)
			{$color = "par";$botoncico1 = "boton_borrar_par";$botoncico2 = "boton_editar_par";}
		else
			{$color = "impar";$botoncico1 = "boton_borrar_impar";$botoncico2 = "boton_editar_impar";}
		
		$cadena .= "<tr><td align='center' class='".$color."'>
		<input type='hidden' id='nombre_tabla' value='".$tabla."' />
		<input type='hidden' id='codigo' value='".$elresultado[0]."' />
		<input type='button' class='".$botoncico2."' onclick='muestra_registro(".$resultado[0].")' /></td>
		<td align='center' class='".$color."'>
		<input type='button' class='".$botoncico1."' onclick='borrar_registro(".$resultado[0].")' /></td>";
		for($i=2;$i<=$totcampos-1;$i++)
		$cadena .= "<td align='center' class='".$color."'>".ucfirst(traduce(comprueba_check($tabla,mysql_field_name($consulta,$i),$resultado[$i])))."</td>";
		$cadena .= "</tr>";
	}
	$cadena .= "</table></td></tr>";
	return $cadena;
}
//***********************************************************************************************/
//SERVICIOS FIJOS EN FACTURACION
//***********************************************************************************************/
function servicios_fijos($cliente)
{
	include("variables.php");
	$sql = "Select Id,ID_Cliente,Servicio,Imp_Euro,unidades,iva,observaciones from `tarifa_cliente` where `ID_Cliente` like $cliente";
	$consulta = @mysql_db_query($dbname,$sql,$con);
	$totcampos = @mysql_num_fields($consulta);
	$span = $totcampos-2;
	$cadena .= "<tr><td colspan='2'><table width='100%' class='sublistado' cellspacing='0'>";
	$cadena .= "<tr><th colspan='".$span."' bgcolor='#ccc'>Servicios Fijos Mensuales</th>";
	$cadena .= "<th align='center' bgcolor='#ccc'>
	<input type='button' class='agregar' onclick='frm_srv_fijo(".$cliente.")' /></th></tr>";
	$cadena .= "<tr><td colspan='4'><div id='frm_srv_fijos'></div></td></tr>";
	$cadena .= "<tr><th bgcolor='#7d0063'></th><th bgcolor='#7d0063'></th>";
	for($i=2;$i<=$totcampos-2;$i++)
	$cadena .= "<th align='center' bgcolor='#7d0063'><font color='#ffffff'>".ucfirst(mysql_field_name($consulta,$i))."</font></th>";
	$cadena .= "</tr>";
	while ($resultado = @mysql_fetch_array($consulta))
	{
		$j++;
		if($j%2==0)
			{$color = "par";$botoncico1 = "boton_borrar_par";$botoncico2 = "boton_editar_par";}
		else
			{$color = "impar";$botoncico1 = "boton_borrar_impar";$botoncico2 = "boton_editar_impar";}
		
		$cadena .= "<tr>";
		//borrado y edicion
		$cadena .= "<td align='center' class='".$color."'>
		<input type='button'  class='".$botoncico2."' onclick='muestra_srv_fijo(".$resultado[0].")' /></td>
		<td align='center' class='".$color."'>
		<input type='button' class='".$botoncico1."' onclick='borra_srv_fijo(".$resultado[0].")' /></td>";
		$cadena .= "<td class='".$color."'>".$resultado[Servicio]." ".$resultado[observaciones]."</td>";
		$cadena .= "<td class='".$color."' align='center'>".$resultado[Imp_Euro]."</td>";
		$cadena .= "<td class='".$color."' align='center'>".$resultado[unidades]."</td>";
		$cadena .= "<td class='".$color."' align='center'>".$resultado[iva]."</td>";
		$cadena .= "</tr>";
	}
	$cadena .= "</table></td></tr>";
	return $cadena;
}
//***********************************************************************************************/
function fromServiciosFijos($vars)
{
	include("variables.php");
	//Listado de servicios disponibles
	///AGTUNG, ALERTA, ATENCION !!!!TOMO COMO SERVICIOS A SERVICIOS2
	
	$sql = "Select id,Nombre from `servicios2` where `Estado_de_servicio` like '-1' order by Nombre";
	$consulta = @mysql_db_query($dbname,$sql,$con);
	//formulario
	if(isset($vars[cliente])) //si el parametro es cliente es nuevo si es id es modificacion
	{
		$cadena .= "</form><form id='frm_srv_fijos' name='frm_srv_fijos' action='#' onsubmit='agrega_srv_fijos(); return false'>";
		$cadena .= "<table id='tabla_srv_fijos' cellpadding='2px' cellspacing='2px'>
		<tr>
		<th>Servicio:</th><td><input type='hidden' id='id_Cliente' name='id_Cliente' value='".$vars[cliente]."' />
		<select id='servicio' name='servicio' onchange='cambia_los_otros()'>
		<option value='0'>--Servicio--</option>";
		while($resultado = @mysql_fetch_array($consulta))
		{
			$cadena .= "<option value='".traduce($resultado[1])."'>".traduce($resultado[1])."</option>";
		}
		$cadena .= "</select></td>";
		$cadena .= "<th>Importe:</th><td><input type='text' name='importe' id='importe' size='8'/>&euro;</td>";
		$cadena .= "<th>Unidades:</th><td><input type='text' name='unidades' id='unidades' size='2' value='1' /></td>";
		$cadena .= "<th>Iva:</th><td><input type='text' name='iva' id='iva' size='2'/></td></tr>";
		$cadena .= "<tr><th valign='top'>Observaciones:</th><td><textarea name='observaciones' id='observaciones' cols='30'></textarea></td>";
		$cadena .= "<td colspan='4' align='center'><input class='agregar' type='submit' name='agregar' value='Agregar' /></td></tr></table>";
	}
	else //se pasa el id de tarifa_cliente para modificar
	{
		$sql2 = "Select * from tarifa_cliente where id like $vars[id]";
		$consulta2 = @mysql_db_query($dbname,$sql2,$con);
		$resultado2 = @mysql_fetch_array($consulta2);
		$cadena .= "</form><form id='frm_srv_fijos' name='frm_srv_fijos' action='#' onsubmit='actualiza_srv_fijos(); return false'>";
		$cadena .= "<table id='tabla_srv_fijos' cellpadding='2px' cellspacing='2px'>
		<tr>
		<th>Servicio:</th><td><input type='hidden' id='id' name='id' value='".$resultado2[0]."' />
		<input type='hidden' id='id_Cliente' name='id_Cliente' value='".$resultado2[1]."' />
		<select id='servicio' name='servicio' onchange='cambia_los_otros()'>
		<option value='0'>--Servicio--</option>";
		while($resultado = @mysql_fetch_array($consulta))
		{
			if($resultado[1] == $resultado2[Servicio])
				$cadena .= "<option selected value='".traduce($resultado[1])."'>".traduce($resultado[1])."</option>";
			else
				$cadena .= "<option value='".traduce($resultado[1])."'>".traduce($resultado[1])."</option>";
		}
		$cadena .= "</select></td>";
		$cadena .= "<th>Importe:</th><td><input type='text' name='importe' id='importe' size='8'value='".$resultado2[Imp_Euro]."'/>&euro;</td>";
		$cadena .= "<th>Unidades:</th><td><input type='text' name='unidades' id='unidades' size='2' value='1' /></td>";
		$cadena .= "<th>Iva:</th><td><input type='text' name='iva' id='iva' size='2'value='".$resultado2[iva]."'/></td></tr>";
		$cadena .= "<tr><th valign='top'>Observaciones:</th><td><textarea name='observaciones' id='observaciones' cols='30'>".$resultado2[observaciones]."</textarea></td>";
		$cadena .= "<td colspan='4' align='center'><input type='submit' class='boton_actualizar' name='actualizar' value='Actualizar' /></td></tr></table>";
	}
	return $cadena;
}
//***********************************************************************************************/
function cambiaLosOtros($vars)
{
	include("variables.php");
	$servicio = codifica($vars[servicio]);
	$sql = "Select PrecioEuro, iva from servicios2 where Nombre like '$servicio'";
	$consulta = @mysql_db_query($dbname,$sql,$con);
	$resultado = @mysql_fetch_array($consulta);
	$cadena = $resultado[0].":".$resultado[1];
	return $cadena;
}
//***********************************************************************************************/
function agregaServicioFijo($vars)
{
	include("variables.php");
	//recogida de variables y agregamos
	$sql = "Insert into tarifa_cliente (`ID_Cliente`,`Servicio`,`Imp_Euro`,`iva`,`unidades`,`observaciones`) values ('$vars[id_Cliente]','$vars[servicio]','$vars[importe]','$vars[iva]','$vars[unidades]','$vars[observaciones]')";
	if($consulta = @mysql_db_query($dbname,$sql,$con))
		return "<img src='".OK."' alt='Servicio Agregado' width='64'/> Servicio Agregado&nbsp;&nbsp;<p/>";
	else
		return "<img src='".NOK."' alt='ERROR' width='64'/> ERROR&nbsp;&nbsp;<p/>".$sql;
	
}
//***********************************************************************************************/
function borraServicioFijo($vars)
{
	include("variables.php");
	$sql = "Delete from tarifa_cliente where id like $vars[id]";
	if($consulta = @mysql_db_query($dbname,$sql,$con))
		return "<img src='".OK."' alt='Servicio Borrado' width='64'/> Servicio Borrado&nbsp;&nbsp;<p/>";
	else
		return "<img src='".NOK."' alt='ERROR' width='64'/> ERROR&nbsp;&nbsp;<p/>";
}
//***********************************************************************************************/
function actualizaServicioFijo($vars)
{
	include("variables.php");
	$sql = "Update `tarifa_cliente` set `Servicio`='$vars[servicio]', `Imp_Euro`='$vars[importe]', `iva`='$vars[iva]', `unidades`='$vars[unidades]',`observaciones`='$vars[observaciones]' where id like $vars[id]";
	if($consulta = @mysql_db_query($dbname,$sql,$con))
		return "<img src='".OK."' alt='Servicio Actualizado' width='64'/> Servicio Actualizado&nbsp;&nbsp;<p/>";
	else
		return "<img src='".NOK."' alt='ERROR' width='64'/> ERROR&nbsp;&nbsp;<p/>".$sql;
}

/**
 * Generamos el listado de categorias de clientes en un select
 * @return string
 */
function listadoCategorias() {
	
	$tabla = Auxiliar::codifica( 'categorías clientes' );
	
	$query = new Sql();
	$sql = "SELECT * FROM `" . $tabla ."`";
	
	$query->consulta($sql);
	
	$cadena = '<select id="tipo_cliente" onchange="filtra_listado()">
	<option value="0">--Selecciona Tipo--</option>';
	foreach ( $query->datos() as $resultado ) {
		$cadena .= '<option value="' . $resultado['Id'] .' ">
		' . Auxiliar::traduce( $resultado['Nombre'] ) . '</option>';
	}
	$cadena .= '<option value="social">
		Con direccion Facturación</option>';
	$cadena .= '<option value="comercial">
		Con direccion Contrato</option>';
	$cadena .= '<option value="independencia">
		Con direccion Independencia</option>';
	$cadena .= '<option value="conserje">
		Listado Conserje</option>';
	
	$cadena.="</select>";
	
	return $cadena;
}
?>
