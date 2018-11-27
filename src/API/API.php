<?php

namespace CAUProject3Contact\API;

use CAUProject3Contact\Controller\Controller;

class API extends Controller {

    private $declaredFunctions = [];

    /**
     * API constructor.
     *
     * @param array $declaredFunctions
     */
    public function __construct( array $declaredFunctions ) {
        parent::__construct();
        $this->declaredFunctions = $declaredFunctions;
    }

    /**
     * @return array
     */
    public function getDeclaredFunctions() {
        return $this->declaredFunctions;
    }
}

?>