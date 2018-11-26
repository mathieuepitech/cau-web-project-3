<?php

namespace CAUProject3Contact\Model;

use Exception;
use PDO;

class BDD {
	const SQL_SERVER = 'sql.sanchez-mathieu.fr';    // BDD Server
	const SQL_LOGIN = 'why7n0_contact';             // BDD Login
	const SQL_PASSWORD = 'fC3c87Gy';                // BDD Password
	const SQL_DB = 'why7n0_contact';                // BDD Name

	private static $bdd;

	public function __construct() {
		try {
			$pdo_options = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_WARNING,
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			];

			self::$bdd = new PDO( 'mysql:host=' . self::SQL_SERVER . ';dbname=' . self::SQL_DB . ';charset=utf8',
				self::SQL_LOGIN, self::SQL_PASSWORD, $pdo_options );
		} catch ( Exception $e ) {
			die( 'Erreur : ' . $e->getMessage() );
		}
	}

	public static function instance() {
		return self::$bdd;
	}

	public static function lastInsertId() {
		return self::$bdd->lastInsertId();
	}
}

?>