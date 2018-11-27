<?php

namespace CAUProject3Contact\Controller\Site;

class SiteRouter {

    /**
     * SiteRouter constructor.
     *
     * @param $pages
     */
    public function __construct( $pages ) {

        set_error_handler( 'errorHandler' );

        if ( $pages[ 0 ] == '' ) {
            new Index();
        } else {
            new SiteError( 404 );
        }
    }
}

?>