<?php
/**
 * DbConnection File Doc Comment
 * 
 * Clase estatica de conexion a la base de datos Singleton Pattern
 * 
 * PHP Version 5.1.4
 * 
 * @category DbConnection
 * @package  cni/inc/clases
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com> 
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @link     https://github.com/sbarrat/cni
 */
/**
 * DbConnection Class Doc Comment
 * 
 * @category Class
 * @package  DbConnection
 * @author   Ruben Lacasa Mas <ruben@ensenalia.com>
 * @license  http://creativecommons.org/licenses/by-nc-nd/3.0/ 
 * 			 Creative Commons Reconocimiento-NoComercial-SinObraDerivada 3.0 Unported
 * @version  Release: 2.1
 * @link     https://github.com/sbarrat/cni
 *
 */
final class DbConnection
{
    private static $_handle = null;
    private static $_dsn = "mysql:dbname=centro;host=127.0.0.1";
    private static $_user = "cni";
    private static $_password = "inc";
    
    /**
     * Deny Construct
     */
    private function __construct()
    {
    }
    /**
     * Deny Clone
     */
    private function __clone()
    {
    }
    /**
     * Funcion de conexion a la base de datos
     */
    function connect()
    {
        if ( is_null( self::$_handle ) ) {
            try {
                self::$_handle = new PDO( 
                    self::$_dsn, self::$_user, self::$_password 
                   );
            } catch ( PDOException $error ){
                die ( $error->getMessage() );
            }
        }
        return self::$_handle;
    }
}