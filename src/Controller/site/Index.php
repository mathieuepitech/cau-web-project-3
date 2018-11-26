<?php

namespace CAUProject3Contact\Controller\Site;

use CAUProject3Contact\Controller\ControllerSite;

class Index extends ControllerSite {

	/**
	 * Index constructor.
	 */
	public function __construct() {
		parent::__construct();

		$this->addHead( [
		] );

		$this->addFooter( [

		] );

		$this->addData( [] );
		$this->view();
	}
}

?>