<?php
if ( isset( $_POST[ 'opcion' ] ) ) {
	
	switch ( $_POST[ 'opcion' ] ){
		
		case 0 : $cad = buscaValores( $_POST );
		break;
	}
	
	echo $cad;
}

function buscaValores( $vars ) {
	
	$cadena = '';
	
	//valores texto,razon,comercial,empleado,onombre,telefono,email
	if ( $vars[ 'texto' ] != NULL )
		$cadena .= 'Busqueda de:' . $vars[ 'texto' ];
	
	if ( $vars[ 'empleado' ] != NULL )
		$cadena .= ' empleado ';
		
	if ( $vars[ 'onombre' ] != NULL )
		$cadena .= ' onombre ';
		
	if ( $vars[ 'telefono' ] != NULL )
		$cadena .= ' telefono ';
		
	if ( $vars[ 'email' ] != NULL )
		$cadena .= ' email ';
	
	/**
	 * Chequeamos si es un telefono
	 */
	$token = preg_replace( "/ /", "//", $vars[ 'texto' ] );
	
	if ( is_numeric( $token ) && strlen( $token ) == 9 ){
		$vars['texto'] = $token;
	}
	
    $query = new Sql();
	$vars[ 'texto' ] = $query->escape( Auxiliar::codifica( $vars[ 'texto' ] ) );
    
	
    $sql = "SELECT `c`.`id`, `c`.`Nombre`, `c`.`Contacto`, 
    `p`.`nombre`, `p`.`apellidos`
	FROM `clientes` AS `c`
	JOIN `pempresa` AS `p` ON `c`.`id` = `p`.`idemp`
	WHERE (`c`.`Nombre` LIKE '%" . $vars[ 'texto' ] . "%'
	OR `c`.`Contacto` LIKE '%" . $vars[ 'texto' ] . "%'
	OR `p`.`nombre` LIKE '%" . $vars[ 'texto' ] ."%'
	OR `p`.`apellidos` LIKE '%" . $vars[ 'texto' ] ."%'
	OR CONCAT( `p`.`nombre`, ' ' ,`p`.`apellidos`, '%' ) LIKE '%" .
    $vars[ 'texto' ] . "%')
    and `c`.`Estado_de_cliente` = '-1'";

	
	$cadena .= '<p><b><u>Resultados busqueda en Clientes</u></b></p>';
	
	$query->consulta( $sql );
	
	if ( $query->totalDatos() != 0 ) {
		
		foreach ( $query->datos() as $resultado ) {
			
			$cadena .= '<p class="' . Auxiliar::clase() . '">
			<a href="javascript:muestra(' . $resultado[ 'id' ] . ')">' . 
			Auxiliar::traduce( $resultado[ 'Nombre' ] ) . ' - ' .
			Auxiliar::traduce( $resultado[ 'Contacto' ] ) . ' - ' . 
			Auxiliar::traduce( $resultado[ 'nombre' ] ) . ' ' . 
			Auxiliar::traduce( $resultado[ 'apellidos' ] ) . 
			'</a></p>';
			}
	} else {
		
		$cadena .= '<p class="' . Auxiliar::clase() . '">No hay resultados</p>';
	}
		
		
	//Consultamos telefonos de cliente
	
	$sql = "SELECT `id`, `Nombre` FROM `clientes` 
	WHERE 
	(  REPLACE( `Tfno1`, ' ', '' ) LIKE '%" . $vars[ 'texto' ] ."%' 
	OR REPLACE( `Tfno2`, ' ', '' ) LIKE '%" . $vars[ 'texto' ] ."%' 
	OR REPLACE( `Tfno3`, ' ', '' ) LIKE '%" . $vars[ 'texto' ] ."%' )
    AND `Estado_de_cliente` = '-1'";
	
	$query->consulta( $sql );
	
	if ( $query->totalDatos() !=0 ) {
		
		foreach ( $query->datos() as $resultado ) {
			
			$cadena .= '<p class="' . Auxiliar::clase() . '">
			<a href="javascript:muestra(' . $resultado[ 'id' ] . ')">' . 
			Auxiliar::traduce( $resultado[ 'Nombre' ] ) . '</a></p>';
			
		}
	}
	
	//Consultamos telefonos de empleados
	
	$sql = "SELECT `c`.`id`, `p`.`nombre`, `p`.`apellidos`, `c`.`Nombre` 
	FROM `pempresa` as `p` 
	INNER JOIN `clientes` as `c` 
	ON `c`.`id` = `p`.`idemp`
	WHERE REPLACE(`p`.`telefono`, ' ', '') 
	LIKE '%" . $vars[ 'texto' ] . "%'
	AND `c`.`Estado_de_cliente` = '-1'";
	
	$query->consulta($sql);
	if ( $query->totalDatos() != 0 ) {
		foreach ( $query->datos() as $resultado ) {
			
			$cadena .= '<p class="' . Auxiliar::clase() . '">
				<a href="javascript:muestra(' . $resultado[ 'id' ] . ')">' . 
			Auxiliar::traduce( $resultado[ 'nombre' ] ) . ' ' . 
			Auxiliar::traduce( $resultado[ 'apellidos' ] ) . ' de ' . 
			Auxiliar::traduce( $resultado[ 'Nombre' ] ) . '</a></p>';
		}
	}
	
	//consultamos telefonos de pcentral
	$sql = "SELECT `c`.`id` ,`p`.`persona_central`, `c`.`Nombre` 
	FROM `pcentral` as `p` 
	INNER JOIN `clientes` as `c` 
	ON `c`.`id` = `p`.`idemp` 
    WHERE REPLACE( `p`.`telefono`, ' ', '') 
    LIKE '%" . $vars[ 'texto' ] . "%'
    AND `c`.`Estado_de_cliente` = '-1'";
	
	$query->consulta($sql);
	if ( $query->totalDatos() != 0 ) {
		foreach ( $query->datos() as $resultado ) {
			
			$cadena .= '<p class="' . Auxiliar::clase() . '">
			<a href="javascript:muestra(' . $resultado[ 'id' ] . ')">' . 
			Auxiliar::traduce( $resultado[ 'persona_central' ] ) . ' de ' . 
			Auxiliar::traduce( $resultado[ 'Nombre' ] ) . '</a></p>';
		}
	}
	
	//Consultamos datos de proveedores
	$cadena.="<p><b><u>Resultados busqueda en Proveedores</u></b></p>";
	//$sql = "Select Id, Nombre from proveedores where Nombre like '%$vars[texto]%' or nocor like '%$vars[texto]%'";
	$sql = "SELECT c.id, c.Nombre, p.nombre, p.apellidos
	FROM proveedores AS c
	left JOIN pproveedores AS p ON c.id = p.idemp
	WHERE c.Nombre LIKE '%$vars[texto]%'
	OR p.nombre LIKE '%$vars[texto]%'
	OR p.apellidos LIKE '%$vars[texto]%'
	OR concat( p.nombre, '', p.apellidos, '%' ) LIKE '%$vars[texto]%'";
	$consulta = mysql_db_query($dbname,$sql,$con);
	$prov = 0;
	if(mysql_numrows($consulta)!=0)
	{
		$prov = 1;
		while($resultado = mysql_fetch_array($consulta))
		$cadena.="<p class='".clase($k++)."'><a href='javascript:muestra(".$resultado[0].")'>".utf8_encode($resultado[1])." - ".utf8_encode($resultado[2])." ".utf8_encode($resultado[3])."</a></p>";
	}
		
	/*$sql = "Select * from pproveedores where nombre like '%$vars[texto]%' 
	or apellidos like '%$vars[texto]%'
	or telefono like '%$vars[texto]%' 
	or email like '%$vars[texto]%'";
	$consulta = @mysql_db_query($dbname,$sql,$con);
	if(mysql_numrows($consulta)!=0)
	{
		$prov = 1;
		while($resultado = mysql_fetch_array($consulta))
		$cadena.="<p/><a href='javascript:muestra(".$resultado[1].")'>
		".utf8_encode($resultado[2])." ".utf8_encode($resultado[3])."
		</a>";
	
	}
	if($prov == 0)*/
	else
		$cadena.="<p class='".clase($k++)."'>No hay resultados de ".utf8_encode($vars[texto])." en Proveedores</p>";
	/*Busqueda de valores en teleco*/
	$cadena.="<p><b><u>Resultados busqueda en Telecomunicaciones</u></b></p>";
	$sql = "Select c.ID, c.Nombre, z.valor, z.servicio from clientes c
inner join z_sercont z on c.ID like z.idemp
where replace(valor, ' ', '') like '%$vars[texto]%'
and c.Estado_de_cliente = '-1'";
	$consulta = @mysql_db_query($dbname,$sql,$con);
	if(mysql_numrows($consulta)!= 0)
	while($resultado = @mysql_fetch_array($consulta))
		$cadena.="<p class='".clase($k++)."'><a href='javascript:muestra(".$resultado[0].")'>".utf8_encode($resultado[1])." - ".utf8_encode($resultado[2])." - ".utf8_encode($resultado[3])."</a></p>";
	else
		$cadena.="<p class='".clase($k++)."'>No hay resultados de ".utf8_encode($vars[texto])." en Telecomunicaciones</p>";
	
	return $cadena;
}


?>
