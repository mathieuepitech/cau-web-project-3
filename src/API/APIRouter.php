<?php

namespace CAUProject3Contact\API;

use CAUProject3Contact\Config;

class APIRouter {

    //Les méthodes HTTP gérée par l'API
    const HTTP_METHODS = [ 'GET', 'POST', 'DELETE', 'PUT' ];

    /**
     * APIRouter constructor.
     *
     * @param string $file
     * @param string $action
     */
    public function __construct( string $file, string $action ) {

        //On vérifie que les paramètres ne sont pas vides
        if ( empty( $file ) || empty( $action ) ) {
            new APIError();
        }

        //On vérifie que la classe appelée existe
        $fileName = 'API' . ucfirst( $file );
        if ( !file_exists( 'src/API/' . $fileName . '.php' ) ) {
            new APIError( 404 );
        }

        //On instancie la classe.
        $class = '\\' . Config::NAMESPACE . '\\API\\' . $fileName;
        $class = new $class( $action );

        //On vérifie que l'action demandé est déclarée
        if ( !array_key_exists( $action, $class->getDeclaredFunctions() ) ) {
            new APIError( 404 );
        }

        //On reconstruit le nom de la fonction
        $array = explode( '-', $action );
        foreach ( $array as $key => $value ) {
            if ( $key == 0 ) {
                $array[ $key ] = $value;
            } else {
                $array[ $key ] = ucfirst( $value );
            }
        }
        $function = implode( '', $array ); //le nom de la fonction

        //On vérifie que la fonction existe dans la classe
        if ( !method_exists( $class, $function ) ) {
            new APIError( 500, 'La fonction ' . $function . ' n\'existe pas dans la classe ' . get_class( $class ) );
        }

        //On vérifie que la méthode d'envoie est référencée
        $method = $class->getDeclaredFunctions()[ $action ][ 'method' ];
        if ( !in_array( $method, self::HTTP_METHODS ) ) {
            new APIError( 500, 'méthode http inconnue' );
        }

        //On vérifie que la méthode requise et la mathode obtenue sont les même
        if ( $method != $_SERVER[ 'REQUEST_METHOD' ] ) {
            new APIError( 400, 'La méthode HTTP ne correspond pas à la méthode prévue' );
        }

        //On met les paramètres dans le tableau $data
        $params = $class->getDeclaredFunctions()[ $action ][ 'params' ];
        $data = [];
        if ( !empty( $params ) ) {
            if ( $method == 'DELETE' || $method == 'PUT' ) {
                parse_str( file_get_contents( 'php://input' ), $data );
            } else if ( $method == 'POST' ) {
                $data = $_POST;
            } else if ( $method == 'GET' ) {
                $data = $_GET;
            }

            //On boucle sur les paramètres de la doc de la fonction
            foreach ( $params as $p => $options ) {
                if ( !isset( $options[ 'required' ] ) ) {
                    $options[ 'required' ] = false;
                }
                //Si le paramètre est obligatoire et qu'il est vide ou non fourni on lève une erreur 400 BAD REQUEST
                if ( $options[ 'required' ] && ( !array_key_exists( $p, $data ) || ( empty( $data[ $p ] ) && $data[ $p ] != '0' ) ) ) {
                    $devMsg = 'Paramètre ' . $p . ' manquant';
                    if ( isset( $options[ 'devMsg' ] ) ) {
                        $devMsg = $options[ 'devMsg' ];
                    }
                    $publicMsg = 'Des paramètres obligatoires ne sont pas envoyés ou sont vides';
                    if ( isset( $options[ 'publicMsg' ] ) ) {
                        $publicMsg = $options[ 'publicMsg' ];
                    }
                    $code = '';
                    if ( isset( $options[ 'code' ] ) ) {
                        $code = $options[ 'code' ];
                    }
                    new APIError( 400, $devMsg, $publicMsg, $code );
                }

                //On vérifie que le type donné correspond au typage requis
                if ( isset( $options[ 'type' ] ) ) {
                    if ( $options[ 'type' ] == 'int' ) {
                        if ( ctype_digit( $data[ $p ] ) ) {
                            $data[ $p ] = (int)$data[ $p ];
                        } else {
                            new APIError( 400, 'Le type donné ne correspond pas au type demandé pour le paramètre ' . $p . ' : string donné, ' . $options[ 'type' ] . ' requis' );
                        }
                    } else if ( $options[ 'type' ] == 'bool' ) {
                        if ( $data[ $p ] == 'true' || $data[ $p ] == '1' ) {
                            $data[ $p ] = true;
                        } else if ( $data[ $p ] == 'false' || $data[ $p ] == '0' ) {
                            $data[ $p ] = false;
                        } else {
                            new APIError( 400, 'Le type donné ne correspond pas au type demandé pour le paramètre ' . $p . ' : string donné, ' . $options[ 'type' ] . ' requis' );
                        }
                    }
                }

                //Si un paramètre non obligatoire n'est pas donné par l'utilisateur on lui donne la valeur par défaut d'une chaine vide
                if ( !array_key_exists( $p, $data ) ) {
                    $data[ $p ] = '';
                }
            }
        }

        //On appelle la fonction correspondante pour le traitement
        $class->$function( $data );
    }
}

?>