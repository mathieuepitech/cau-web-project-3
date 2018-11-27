<?php

namespace CAUProject3Contact\Controller;

use CAUProject3Contact\Config;

class ControllerSite {

    private $data;
    private $head;
    private $footer;

    public function __construct() {
        $this->data = [];
        $this->head = [];
        $this->footer = [];
    }


    protected function view( $header = true, $footer = true ) {
        ob_start();
        extract( $this->data );

        if ( empty( $this->head[ 'title' ] ) ) {
            $this->head[ 'title' ] = Config::TITLE_HEADER;
        }
        if ( empty( $this->head[ 'description' ] ) ) {
            $this->head[ 'description' ] = Config::DESCRIPTION_HEADER;
        }
        if ( $header ) {
            require 'src/View/Site/tpl/head.php';
        }

        require 'src/View/' . str_replace( '\\', '/', preg_replace( '#^' . Config::NAMESPACE . '\\\Controller\\\#', '', get_class( $this ) ) ) . '.php';

        if ( $footer ) {
            require 'src/View/Site/tpl/footer.php';
        }

        ob_end_flush();
        exit();
    }

    protected function addHead( $head ) {
        $this->head += $head;
    }

    protected function addData( $data ) {
        $this->data += $data;
    }

    protected function addFooter( $footer ) {
        $this->footer += $footer;
    }

    /**
     * @param $data
     */
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