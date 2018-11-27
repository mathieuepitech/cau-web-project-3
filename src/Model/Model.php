<?php

namespace CAUProject3Contact\Model;

class Model {

    /**
     * Crée une reqette d'insertion en base àpartir du nom de la table et d'un tableau associatif et l'exécute
     *
     * @param string $tableName
     * @param array $data
     *
     * @return int lastInsertId
     */
    public static function insert( string $tableName, array $data ) {
        $req = BDD::instance()->prepare( 'INSERT INTO ' . $tableName . ' (' . implode( ', ', array_keys( $data ) ) . ') 
                                         VALUES (' . ':' . implode( ', :', array_keys( $data ) ) . ')' );
        $req->execute( $data );

        return BDD::lastInsertId();
    }

    /**
     * Met à jour les données d'une ligne d'un table données
     *
     * @param string $tableName
     * @param array $data
     * @param string $idColumn
     * @param int $idValue
     */
    public static function update( string $tableName, array $data, string $idColumn, int $idValue ) {
        $reqStr = 'UPDATE ' . $tableName . ' SET ';
        $lastKey = endKey( $data );
        foreach ( $data as $key => $value ) {
            $reqStr .= $key . ' = :' . $key;
            if ( $key != $lastKey ) {
                $reqStr .= ', ';
            }
        }
        $reqStr .= ' WHERE ' . $idColumn . ' = :' . $idColumn;
        $data[ $idColumn ] = $idValue;

        //echo $reqStr; exit();

        $req = BDD::instance()->prepare( $reqStr );
        $req->execute( $data );
    }

    public static function delete( string $tableName, array $conditions ) {
        $reqStr = 'DELETE FROM ' . $tableName . ' WHERE ';
        $lastKey = endKey( $conditions );

        foreach ( $conditions as $key => $value ) {
            $reqStr .= $key . ' = :' . $key;
            if ( $key != $lastKey ) {
                $reqStr .= ' AND ';
            }
        }

        $req = BDD::instance()->prepare( $reqStr );
        $req->execute( $conditions );
    }

}

?>