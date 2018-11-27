<?php

namespace CAUProject3Contact\Model;

class Contact {

    public $id;
    public $firstName;
    public $lastName;
    public $surname;
    public $email;
    public $address;
    public $phoneNumber;
    public $birthday;

    // Constructors

    public function __construct( int $id = null, string $firstName = null, string $lastName = null,
                                 string $surname = null, string $email = null, string $address = null,
                                 string $phoneNumber = null, string $birthday = null ) {
        if ( $id === null || $firstName === null || $lastName === null ) {
            return;
        }
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->surname = $surname;
        $this->email = $email;
        $this->address = $address;
        $this->phoneNumber = $phoneNumber;
        $this->birthday = $birthday;
    }

    public static function getById( int $id ) {
        $req = BDD::instance()->prepare( "SELECT * FROM " . BDTables::CONTACT .
            " WHERE `id` = :id" );
        $req->execute( [ "id" => $id ] );
        $d = $req->fetch();
        return new Contact( $d[ "id" ], $d[ "first_name" ], $d[ "last_name" ], $d[ "surname" ],
            $d[ "email" ], $d[ "address" ], $d[ "phone_number" ], $d[ "birthday" ] );
    }

    // Getters

    public static function insertNewContact( string $firstName, string $lastName, string $surname = null,
                                             string $email = null,
                                             string $address = null, string $phoneNumber = null,
                                             string $birthday = null ) {
        $data = [
            "first_name" => $firstName,
            "last_name" => $lastName,
        ];
        if ( $surname !== null && $surname !== "" ) {
            $data[ "surname" ] = $surname;
        }
        if ( $email !== null && $email !== "" ) {
            $data[ "email" ] = $email;
        }
        if ( $address !== null && $address !== "" ) {
            $data[ "address" ] = $address;
        }
        if ( $phoneNumber !== null && $phoneNumber !== "" ) {
            $data[ "phone_number" ] = $phoneNumber;
        }
        if ( $birthday !== null && $birthday !== "" ) {
            $data[ "birthday" ] = date( "Y-m-d", strtotime( $birthday ) );
        }
        return Model::insert( BDTables::CONTACT, $data );
    }

    public static function deleteContact( int $id ) {
        Model::delete( BDTables::CONTACT, [ "id" => $id ] );
    }

    public static function getAllContact(): array {
        $contacts = [];
        $req = BDD::instance()->prepare( "SELECT * FROM " . BDTables::CONTACT );
        $req->execute();

        foreach ( $req->fetchAll() as $c ) {
            $contacts[] = new Contact( $c[ "id" ], $c[ "first_name" ], $c[ "last_name" ], $c[ "surname" ],
                $c[ "email" ], $c[ "address" ], $c[ "phone_number" ], $c[ "birthday" ] );
        }
        return ( count( $contacts ) > 0 ? $contacts : null );
    }

    public static function search( string $query ) {
        $result = [];
        $words = explode( " ", cleanString( $query ) );

        $q1 = $q2 = $q3 = $q4 = "SELECT * FROM `" . BDTables::CONTACT . "` WHERE ";

        $lastKey = endKey( $words );
        foreach ( $words as $key => $word ) {
            $normal = self::getQuerySearch( $word, [ "first_name", "last_name", "surname" ] );;
            $hard = self::getQuerySearch( $word, [ "email", "address", "phone_number", "birthday" ] );
            $q1 .= $normal;
            $q2 .= $normal;
            $q3 .= $hard;
            $q4 .= $hard;
            if ( $key != $lastKey ) {
                $q1 .= " AND ";
                $q2 .= " OR ";
                $q3 .= " AND ";
                $q4 .= " OR ";
            }
        }

        $req1 = BDD::instance()->prepare( $q1 );
        $req2 = BDD::instance()->prepare( $q2 );
        $req3 = BDD::instance()->prepare( $q3 );
        $req4 = BDD::instance()->prepare( $q4 );

        $req1->execute();
        $req2->execute();
        $req3->execute();
        $req4->execute();

        $tmp1 = $req1->fetchAll();
        $tmp2 = filterArrays( $tmp1, $req2->fetchAll() );
        $tmp3 = filterArrays( $tmp1, filterArrays( $tmp2, $req3->fetchAll() ) );
        $tmp4 = filterArrays( $tmp1, filterArrays( $tmp2, filterArrays( $tmp3, $req4->fetchAll() ) ) );

        if ( count( $tmp1 ) > 0 || count( $tmp2 ) > 0 || count( $tmp3 ) > 0 || count( $tmp4 ) > 0 ) {
            $result[ "1" ] = ( count( $tmp1 ) > 0 ? $tmp1 : null );
            $result[ "2" ] = ( count( $tmp2 ) > 0 ? $tmp2 : null );
            $result[ "3" ] = ( count( $tmp3 ) > 0 ? $tmp3 : null );
            $result[ "4" ] = ( count( $tmp4 ) > 0 ? $tmp4 : null );
            return $result;
        }
        return null;
    }

    private static function getQuerySearch( string $word, array $fields ): string {
        $str = '';
        $i = 0;

        foreach ( $fields as $field ) {
            if ( $i === 0 ) {
                $str .= "(";
            } else {
                $str .= " OR ";
            }
            $str .= "`" . $field . "` LIKE '%" . $word . "%'";
            $i++;
        }
        $str .= ')';
        return $str;
    }

    /**
     * @return string
     */
    public function getFirstName(): string {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getSurname() {
        return $this->surname;
    }

    // Method

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    // Static functions

    /**
     * @return string
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getPhoneNumber() {
        return $this->phoneNumber;
    }

    /**
     * @return string
     */
    public function getBirthday() {
        return $this->birthday;
    }

    public function updateContact( array $data ) {
        foreach ( $data as $key => $value ) {
            $this->{$key} = $value;
        }
        Model::update( BDTables::CONTACT, $data, "id", $this->getId() );
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

}

?>