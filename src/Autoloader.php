<?php

namespace CAUProject3Contact;

class Autoloader {

	/**
	 * Enregistre notre autoloader
	 */
	static function register() {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Inclue le fichier correspondant à notre classe
	 *
	 * @param $class string Le nom de la classe à charger
	 */
	static function autoload( $class ) {
		if ( preg_match( '#^' . Config::NAMESPACE . '\\\(.+)$#', $class, $matches ) ) {
			require 'src/' . str_replace( '\\', '/', $matches[1] ) . '.php';
		}
	}
}