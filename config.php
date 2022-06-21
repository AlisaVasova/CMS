<?php
ini_set( "display_errors", true );
date_default_timezone_set( "Europe/Moscow" );
define( "DB_DSN", "mysql:host=localhost;dbname=cms;charset=utf8mb4" );
define( "DB_USERNAME", "root" );
define( "CLASS_PATH", "classes" );
define( "TEMPLATE_PATH", "templates" );
define( "SCRIPT_PATH", "scripts" );
define( "ADMIN_USERNAME", "admin" );
define( "ADMIN_PASSWORD", "mypass" );
require( CLASS_PATH . "/Page.php" );
require( CLASS_PATH . "/Article.php" );

function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.";
  error_log( $exception->getMessage() );
}
set_exception_handler( 'handleException' );
?>