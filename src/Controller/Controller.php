<?php

namespace CAUProject3Contact\Controller;

use CAUProject3Contact\Config;

class Controller {

	private $data;

	public function __construct() {
		$this->data = [];
	}

	protected function view() {
		ob_start();
		extract( $this->data );
		require 'src/View/' . str_replace( '\\', '/', preg_replace( '#^' . Config::NAMESPACE . '\\\Controller\\\#', '', get_class( $this ) ) ) . '.php';
		ob_end_flush();
		exit();
	}

	protected function addData( $data ) {
		$this->data += $data;
	}

	protected function returnJson( $data ) {
		header( 'Content-Type: application/json' );
		echo json_encode( $data );
		exit();
	}

	protected function throwError( $msg, $code = '' ) {
		header( 'Content-Type: application/json' );
		echo json_encode( [ 'status' => 'echec', 'msg' => $msg, 'code' => $code ], JSON_PRETTY_PRINT );
		exit();
	}
}

?>