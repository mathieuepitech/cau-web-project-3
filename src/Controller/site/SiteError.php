<?php

namespace CAUProject3Contact\Controller\Site;

use CAUProject3Contact\Controller\ControllerSite;

class SiteError extends ControllerSite {

    /**
     * SiteError constructor.
     *
     * @param int $ErrCode
     * @param string $message
     */
    public function __construct( $ErrCode = 500, $message = '' ) {
        parent::__construct();

        $tabCode = [
            //Informational 1xx
            100 => [ 'label' => '100 Continue', 'msg' => '100 Continue' ],
            101 => [ 'label' => '101 Switching Protocols', 'msg' => '101 Switching Protocols' ],
            //Successful 2xx
            200 => [ 'label' => '200 OK', 'msg' => '200 OK' ],
            201 => [ 'label' => '201 Created', 'msg' => '201 Created' ],
            202 => [ 'label' => '202 Accepted', 'msg' => '202 Accepted' ],
            203 => [ 'label' => '203 Non-Authoritative Information', 'msg' => '203 Non-Authoritative Information' ],
            204 => [ 'label' => '204 No Content', 'msg' => '204 No Content' ],
            205 => [ 'label' => '205 Reset Content', 'msg' => '205 Reset Content' ],
            206 => [ 'label' => '206 Partial Content', 'msg' => '206 Partial Content' ],
            226 => [ 'label' => '226 IM Used', 'msg' => '226 IM Used' ],
            //Redirection 3xx
            300 => [ 'label' => '300 Multiple Choices', 'msg' => '300 Multiple Choices' ],
            301 => [ 'label' => '301 Moved Permanently', 'msg' => '301 Moved Permanently' ],
            302 => [ 'label' => '302 Found', 'msg' => '302 Found' ],
            303 => [ 'label' => '303 See Other', 'msg' => '303 See Other' ],
            304 => [ 'label' => '304 Not Modified', 'msg' => '304 Not Modified' ],
            305 => [ 'label' => '305 Use Proxy', 'msg' => '305 Use Proxy' ],
            306 => [ 'label' => '306 (Unused)', 'msg' => '306 (Unused)' ],
            307 => [ 'label' => '307 Temporary Redirect', 'msg' => '307 Temporary Redirect' ],
            //Client Error 4xx
            400 => [ 'label' => '400 Bad Request', 'msg' => '400 Bad Request' ],
            401 => [ 'label' => '401 Unauthorized', 'msg' => '401 Unauthorized' ],
            402 => [ 'label' => '402 Payment Required', 'msg' => '402 Payment Required' ],
            403 => [ 'label' => '403 Forbidden', 'msg' => '403 Forbidden' ],
            404 => [ 'label' => '404 Not Found', 'msg' => 'Page non trouvée' ],
            405 => [ 'label' => '405 Method Not Allowed', 'msg' => '405 Method Not Allowed' ],
            406 => [ 'label' => '406 Not Acceptable', 'msg' => '406 Not Acceptable' ],
            407 => [ 'label' => '407 Proxy Authentication Required', 'msg' => '407 Proxy Authentication Required' ],
            408 => [ 'label' => '408 Request Timeout', 'msg' => '408 Request Timeout' ],
            409 => [ 'label' => '409 Conflict', 'msg' => '409 Conflict' ],
            410 => [ 'label' => '410 Gone', 'msg' => 'Cet artisan n\'est plus plus référencé sur Eldotravo' ],
            411 => [ 'label' => '411 Length Required', 'msg' => '411 Length Required' ],
            412 => [ 'label' => '412 Precondition Failed', 'msg' => '412 Precondition Failed' ],
            413 => [ 'label' => '413 Request Entity Too Large', 'msg' => '413 Request Entity Too Large' ],
            414 => [ 'label' => '414 Request-URI Too Long', 'msg' => '414 Request-URI Too Long' ],
            415 => [ 'label' => '415 Unsupported Media Type', 'msg' => '415 Unsupported Media Type' ],
            416 => [ 'label' => '416 Requested Range Not Satisfiable', 'msg' => '416 Requested Range Not Satisfiable' ],
            417 => [ 'label' => '417 Expectation Failed', 'msg' => '417 Expectation Failed' ],
            418 => [ 'label' => '418 I\'m a teapot', 'msg' => '418 I\'m a teapot' ],
            422 => [ 'label' => '422 Unprocessable Entity', 'msg' => '422 Unprocessable Entity' ],
            423 => [ 'label' => '423 Locked', 'msg' => '423 Locked' ],
            426 => [ 'label' => '426 Upgrade Required', 'msg' => '426 Upgrade Required' ],
            428 => [ 'label' => '428 Precondition Required', 'msg' => '428 Precondition Required' ],
            429 => [ 'label' => '429 Too Many Requests', 'msg' => '429 Too Many Requests' ],
            431 => [ 'label' => '431 Request Header Fields Too Large', 'msg' => '431 Request Header Fields Too Large' ],
            //Server Error 5xx
            500 => [ 'label' => '500 Internal Server Error', 'msg' => 'Une erreur est survenue' ],
            501 => [ 'label' => '501 Not Implemented', 'msg' => '501 Not Implemented' ],
            502 => [ 'label' => '502 Bad Gateway', 'msg' => '502 Bad Gateway' ],
            503 => [ 'label' => '503 Service Unavailable', 'msg' => '503 Service Unavailable' ],
            504 => [ 'label' => '504 Gateway Timeout', 'msg' => '504 Gateway Timeout' ],
            505 => [ 'label' => '505 HTTP Version Not Supported', 'msg' => '505 HTTP Version Not Supported' ],
            506 => [ 'label' => '506 Variant Also Negotiates', 'msg' => '506 Variant Also Negotiates' ],
            510 => [ 'label' => '510 Not Extended', 'msg' => '510 Not Extended' ],
            511 => [ 'label' => '511 Network Authentication Required', 'msg' => '511 Network Authentication Required' ]
        ];

        if ( isset( $tabCode[ $ErrCode ] ) ) {
            header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' ' . $tabCode[ $ErrCode ][ 'label' ] );
            if ( isset( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) == 'xmlhttprequest' ) {
                $this->throwError( 'Erreur ' . $tabCode[ $ErrCode ][ 'label' ], 'Erreur_' . $ErrCode );
            }
            $erreur = $tabCode[ $ErrCode ][ 'msg' ];
            $this->addData( [ 'erreur' => $erreur ] );
        } else {
            header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' ' . $tabCode[ 500 ][ 'label' ] );
            if ( isset( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) && strtolower( $_SERVER[ 'HTTP_X_REQUESTED_WITH' ] ) == 'xmlhttprequest' ) {
                $this->throwError( 'Erreur ' . $tabCode[ 500 ][ 'label' ], 'Erreur_500' );
            }
            $erreur = $tabCode[ 500 ][ 'msg' ];
            $this->addData( [ 'erreur' => $erreur ] );
        }

        if ( !empty( $message ) ) {
            $this->addData( [ 'message' => $message ] );
        }

        $this->addHead( [
            'title' => 'Un erreur est survenue',
            'description' => 'Oops une erreur est survenue',
            'robotNoIndex' => true
        ] );

        $this->view();
    }
}