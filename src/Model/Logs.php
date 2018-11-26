<?php

namespace CAUProject3Contact\Model;

use PDO;

class Logs {

	const ERROR_LEVEL = [
		1     => 'E_ERROR',
		2     => 'E_WARNING',
		4     => 'E_PARSE',
		8     => 'E_NOTICE',
		16    => 'E_CORE_ERROR',
		32    => 'E_CORE_WARNING',
		64    => 'E_COMPILE_ERROR',
		128   => 'E_COMPILE_WARNING',
		256   => 'E_USER_ERROR',
		512   => 'E_USER_WARNING',
		1024  => 'E_USER_NOTICE',
		2048  => 'E_STRICT',
		4096  => 'E_RECOVERABLE_ERROR',
		8192  => 'E_DEPRECATED',
		16384 => 'E_USER_DEPRECATED',
		32767 => 'E_ALL'
	];
	public $id;
	public $level;
	public $message;
	public $file;
	public $line;
	public $date;

	/**
	 * Logs constructor.
	 *
	 * @param int|null $id
	 * @param string|null $level
	 * @param string|null $message
	 * @param string|null $file
	 * @param string|null $line
	 * @param string|null $date
	 */
	public function __construct( int $id = null, string $level = null, string $message = null, string $file = null, string $line = null, string $date = null ) {
		if ( $id === null ) {
			return;
		}
		$this->id      = $id;
		$this->level   = $level;
		$this->message = $message;
		$this->file    = $file;
		$this->line    = $line;
		$this->date    = $date;
	}


	public static function insert( $level, $message, $file, $line, $date ) {
		Model::insert( BDTables::LOGS, [
			'level'   => $level,
			'message' => $message,
			'file'    => $file,
			'line'    => $line,
			'date'    => date( "Y-m-d H:i:s", strtotime( $date ) )
		] );
	}

	/**
	 * Retourne un tableau des derniers logs (limite en param)
	 *
	 * @param int $limit
	 *
	 * @return array
	 */
	public static function getLastLogs( int $limit ) {
		$req = BDD::instance()->prepare( 'SELECT *
                                         FROM ' . BDTables::LOGS . ' 
                                         ORDER BY date DESC
                                         LIMIT :limit' );
		$req->bindValue( 'limit', $limit, PDO::PARAM_INT );
		$req->execute();
		$return = [];

		foreach ( $req->fetchAll() as $l ) {
			$log      = new Logs( $l['id'], $l['level'], $l['message'], $l['file'], $l['line'], $l['date'] );
			$return[] = $log;
		}

		return $return;
	}

	/**
	 * @return int
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @param string $level
	 */
	public function setLevel( $level ) {
		$this->level = $level;
	}

	/**
	 * @return string
	 */
	public function getMessage() {
		return htmlspecialchars( $this->message );
	}

	/**
	 * @param string $message
	 */
	public function setMessage( $message ) {
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	public function getFile() {
		return htmlspecialchars( $this->file );
	}

	/**
	 * @param string $file
	 */
	public function setFile( $file ) {
		$this->file = $file;
	}

	/**
	 * @return string
	 */
	public function getLine() {
		return htmlspecialchars( $this->line );
	}

	/**
	 * @param string $line
	 */
	public function setLine( $line ) {
		$this->line = $line;
	}

	/**
	 * @return string
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param string $date
	 */
	public function setDate( $date ) {
		$this->date = $date;
	}

	/**
	 * Retourne le type d'erreur en string (label)
	 * @return string
	 */
	public function getErrorLabel() {
		return htmlspecialchars( self::ERROR_LEVEL[ $this->level ] );
	}
}

?>