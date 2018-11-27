<?php

namespace CAUProject3Contact\API;

use CAUProject3Contact\Model\Contact;

class APIContact extends API {

    private $declaredFunctions = [
        'insert' => [
            'method' => 'POST',
            'params' => [
                'firstName' => [
                    'required' => true,
                    'type' => 'string'
                ],
                'lastName' => [
                    'required' => true,
                    'type' => 'string'
                ],
                'surname' => [
                    'required' => false,
                    'type' => 'string'
                ],
                'email' => [
                    'required' => false,
                    'type' => 'string'
                ],
                'address' => [
                    'required' => false,
                    'type' => 'string'
                ],
                'phoneNumber' => [
                    'required' => false,
                    'type' => 'string'
                ],
                'birthday' => [
                    'required' => false,
                    'type' => 'string'
                ],
            ]
        ],
        'delete' => [
            'method' => 'POST',
            'params' => [
                'id' => [
                    'required' => true,
                    'type' => 'int'
                ]
            ]
        ],
        'get-contacts' => [
            'method' => 'GET',
            'params' => []
        ],
        'update' => [
            'method' => 'POST',
            'params' => [
                'id' => [
                    'required' => true,
                    'type' => 'int'
                ],
                'firstName' => [
                    'required' => false,
                    'type' => 'string'
                ],
                'lastName' => [
                    'required' => false,
                    'type' => 'string'
                ],
                'surname' => [
                    'required' => false,
                    'type' => 'string'
                ],
                'email' => [
                    'required' => false,
                    'type' => 'string'
                ],
                'address' => [
                    'required' => false,
                    'type' => 'string'
                ],
                'phoneNumber' => [
                    'required' => false,
                    'type' => 'string'
                ],
                'birthday' => [
                    'required' => false,
                    'type' => 'string'
                ]
            ]
        ],
        'search' => [
            'method' => 'POST',
            'params' => [
                'query' => [
                    'required' => true,
                    'type' => 'string'
                ]
            ]
        ]
    ];

    /**
     * APIContact constructor.
     *
     * @param array $declaredFunctions
     */
    public function __construct() {
        parent::__construct( $this->declaredFunctions );
    }

    /**
     * @return array
     */
    public function getDeclaredFunctions() {
        return $this->declaredFunctions;
    }

    public function insert( array $data ) {

        $id = Contact::insertNewContact( $data[ "firstName" ], $data[ "lastName" ], $data[ "surname" ],
            $data[ "email" ], $data[ "address" ], $data[ "phoneNumber" ], $data[ "birthday" ] );

        $this->returnJson( json_encode( [
            "status" => "success",
            "data" => [
                "id" => $id
            ]
        ] ) );
    }

    public function delete( array $data ) {
        Contact::deleteContact( $data[ "id" ] );
    }

    public function getContacts() {
        $this->returnJson( json_encode( [
            "contacts" => Contact::getAllContact()
        ] ) );
    }

    public function update( array $data ) {
        $contact = Contact::getById( $data[ "id" ] );

        $newData = [];

        if ( $data[ "firstName" ] !== null && $data[ "firstName" ] !== "" ) {
            $newData[ "first_name" ] = $data[ "firstName" ];
        }
        if ( $data[ "lastName" ] !== null && $data[ "lastName" ] !== "" ) {
            $newData[ "last_name" ] = $data[ "lastName" ];
        }
        if ( $data[ "surname" ] !== null && $data[ "surname" ] !== "" ) {
            $newData[ "surname" ] = $data[ "surname" ];
        }
        if ( $data[ "email" ] !== null && $data[ "email" ] !== "" ) {
            $newData[ "email" ] = $data[ "email" ];
        }
        if ( $data[ "address" ] !== null && $data[ "address" ] !== "" ) {
            $newData[ "address" ] = $data[ "address" ];
        }
        if ( $data[ "phoneNumber" ] !== null && $data[ "phoneNumber" ] !== "" ) {
            $newData[ "phone_number" ] = $data[ "phoneNumber" ];
        }
        if ( $data[ "birthday" ] !== null && $data[ "birthday" ] !== "" ) {
            $newData[ "birthday" ] = date( "Y-m-d", strtotime( $data[ "birthday" ] ) );
        }

        $contact->updateContact( $newData );
        $this->returnJson( json_encode( $contact ) );
    }

    public function search( array $data ) {
        if ( count_chars( $data[ "query" ] ) >= 2 ) {
            $result = Contact::search( $data[ "query" ] );
            if ( $result !== null ) {
                $this->returnJson( [
                    "status" => "success",
                    "code" => 200,
                    "result" => $result,
                ] );
            } else {
                $this->returnJson( [
                    "status" => "error",
                    "code" => 404,
                    "message" => "Nothing find",
                ] );
            }
        } else {
            $this->returnJson( [
                "status" => "error",
                "code" => 400,
                "message" => "Need at least 3 chars",
            ] );
        }
    }

}

?>