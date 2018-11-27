<?php

namespace CAUProject3Contact;

use CAUProject3Contact\API\APIRouter;
use CAUProject3Contact\Controller\Error;
use CAUProject3Contact\Controller\Site\SiteRouter;
use CAUProject3Contact\Model\BDD;

session_start();
date_default_timezone_set( 'Europe/Paris' );
setlocale( LC_TIME, "fr_FR.UTF-8" );
error_reporting( E_ALL );

require( 'src/lib/functions.php' );
require( 'src/config.php' );

require 'src/Autoloader.php';
Autoloader::register();

new BDD();

$parts = explode( '?', $_SERVER['REQUEST_URI'] );
$urlA  = ltrim( $parts[0], '/' );
$pages = explode( '/', $urlA );

//Si il y a un / en fin d'url on redirige vers la meme page sans le /
if ( count( $pages ) > 1 && $pages[ count( $pages ) - 1 ] == '' ) {
	$args = ( count( $parts ) > 1 ? '?' . $parts[1] : '' );
	header( $_SERVER['SERVER_PROTOCOL'] . ' 301 Moved Permanently' );
	header( 'Location: /' . rtrim( $urlA, '/' ) . $args );
	exit();
}

if ( $pages[0] == 'api' && isset( $pages[1] ) && preg_match( '#^([a-z]+)$#', $pages[1], $api1 ) && isset( $pages[2] ) && preg_match( '#^([a-z-]+)$#', $pages[2], $api2 ) ) {
	new APIRouter( $api1[0], $api2[0] );

} else if ( preg_match( '#^contact.sanchez-mathieu\.test$#', $_SERVER[ 'SERVER_NAME' ] ) || preg_match( '#^contact.sanchez-mathieu\.fr#', $_SERVER[ 'SERVER_NAME' ] ) ) {
	new SiteRouter( $pages );
} else {
	new Error( 404 );
}

/*
} elseif (preg_match('#^(www\.)?mvc\.eldotravo\.dev$#', $_SERVER['SERVER_NAME'])) {
    new SiteRouter($pages);
*/
?>