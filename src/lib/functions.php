<?php

include( 'src/lib/mail/PHPMailerAutoload.php' );

/**
 * Permet de remplacer les accents et les apostrophes dans l'url
 *
 * @param string $str l'url à formater
 * @param string $encoding
 *
 * @return string
 */
function formatURL( string $str, $encoding = 'utf-8' ) {

    $str = str_replace( "+", "_plus_", $str );
    $str = str_replace( "%", "_pourcent_", $str );
    $str = str_replace( "&", "_et_", $str );

    //on remplace les apotrophes et espaces par des underscore
    $str = str_replace( array( "'", " ", "," ), "_", $str );

    $str = str_replace( "__", "_", $str );

    // transformer les caractères accentués en entités HTML
    $str = htmlentities( $str, ENT_NOQUOTES, $encoding );
    // remplacer les entités HTML pour avoir juste le premier caractères non accentués
    // Exemple : "&ecute;" => "e", "&Ecute;" => "E", "Ã " => "a" ...
    $str = preg_replace( '#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str );

    // Remplacer les ligatures tel que : Œ, Æ ...
    // Exemple "Å“" => "oe"
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    // Supprimer tout le reste
    //$str = preg_replace('#&[^;]+;#', '', $str);
    $str = str_replace( array( "#", "&", "[", "^", ";", "]" ), '', $str );

    //on passe tout en minuscule
    $str = strtolower( $str );

    if ( substr( $str, -1 ) == '_' ) {
        $str = substr( $str, 0, -1 );
    }

    return $str;
}

/**
 * La fonction darkroom() renomme et redimensionne les photos envoyées lors de l'ajout d'un objet.
 *
 * @param $img String Chemin absolu de l'image d'origine.
 * @param $to String Chemin absolu de l'image générée (.jpg).
 * @param $width Int Largeur de l'image générée. Si 0, valeur calculée en fonction de $height.
 * @param $height Int Hauteur de l'image génétée. Si 0, valeur calculée en fonction de $width.
 * Si $height = 0 et $width = 0, dimensions conservées mais conversion en .jpg
 *
 * @return bool
 */
function darkroom( $img, $to, $width = 0, $height = 0, $quality = 100, $useGD = true ) {

    $dimensions = getimagesize( $img );
    $ratio = $dimensions[ 0 ] / $dimensions[ 1 ];

    // Calcul des dimensions si 0 passé en paramètre
    if ( $width == 0 && $height == 0 ) {
        $width = $dimensions[ 0 ];
        $height = $dimensions[ 1 ];
    } else if ( $height == 0 ) {
        $height = round( $width / $ratio );
    } else if ( $width == 0 ) {
        $width = round( $height * $ratio );
    }

    if ( $dimensions[ 0 ] > ( $width / $height ) * $dimensions[ 1 ] ) {
        $dimY = $height;
        $dimX = round( $height * $dimensions[ 0 ] / $dimensions[ 1 ] );
    }
    if ( $dimensions[ 0 ] < ( $width / $height ) * $dimensions[ 1 ] ) {
        $dimX = $width;
        $dimY = round( $width * $dimensions[ 1 ] / $dimensions[ 0 ] );
    }
    if ( $dimensions[ 0 ] == ( $width / $height ) * $dimensions[ 1 ] ) {
        $dimX = $width;
        $dimY = $height;
    }

    // Création de l'image avec la librairie GD
    if ( $useGD ) {
        $pattern = imagecreatetruecolor( $width, $height );
        $type = mime_content_type( $img );
        switch ( substr( $type, 6 ) ) {
            case 'jpeg':
                $image = imagecreatefromjpeg( $img );
                break;
            case 'gif':
                $image = imagecreatefromgif( $img );
                break;
            case 'png':
                $image = imagecreatefrompng( $img );
                break;
        }
        imagecopyresampled( $pattern, $image, 0, 0, 0, 0, $dimX, $dimY, $dimensions[ 0 ], $dimensions[ 1 ] );
        imagedestroy( $image );
        imagejpeg( $pattern, $to, $quality );

        return true;
    }

    return true;
}

/**
 * Redéfini la gestion des erreurs
 *
 * @param $errno
 * @param $errstr
 * @param $errfile
 * @param $errline
 *
 * @return bool|void
 */
function errorHandler( $errno, $errstr, $errfile, $errline ) {
    if ( !( error_reporting() & $errno ) ) {
        // Ce code d'erreur n'est pas inclus dans error_reporting()
        return;
    }

    // Insertion des logs
    \CAUProject3Contact\Model\Logs::insert( $errno, $errstr, $errfile, $errline, date( 'Y-m-d H:i:s' ) );

    ob_clean();
    new \CAUProject3Contact\Controller\Site\SiteError( 500 );

    /* Ne pas exécuter le gestionnaire interne de PHP */

    return;
}

/**
 * @return array
 * Fonction permettant de récupérer des informations sur le navigateur utiliser par l'utilisateur
 */
function getBrowser() {

    $u_agent = $_SERVER[ 'HTTP_USER_AGENT' ];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $ub = "";

    //First get the platform?
    if ( preg_match( '/android/i', $u_agent ) || preg_match( '/Android/i', $u_agent ) ) {
        $platform = 'android';
    } else if ( preg_match( '/linux/i', $u_agent ) ) {
        $platform = 'linux';
    } else if ( preg_match( '/macintosh|mac os x/i', $u_agent ) ) {
        $platform = 'mac';
    } else if ( preg_match( '/windows|win32/i', $u_agent ) ) {
        $platform = 'windows';
    }

    if ( strstr( $u_agent, 'mobile' ) || strstr( $u_agent, 'Mobile' ) ) {
        $platform .= ' mobile';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if ( preg_match( '/MSIE/i', $u_agent ) && !preg_match( '/Opera/i', $u_agent ) ) {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    } else if ( preg_match( '/Edge/i', $u_agent ) ) {
        $bname = 'Microsoft Edge';
        $ub = "Edge";
    } else if ( preg_match( '/Trident/i', $u_agent ) ) {
        $bname = 'Internet Explorer';
        $ub = "rv";
    } else if ( preg_match( '/Firefox/i', $u_agent ) ) {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    } else if ( preg_match( '/Chrome/i', $u_agent ) ) {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    } else if ( preg_match( '/Safari/i', $u_agent ) ) {
        $bname = 'Apple Safari';
        $ub = "Safari";
    } else if ( preg_match( '/Opera/i', $u_agent ) ) {
        $bname = 'Opera';
        $ub = "Opera";
    } else if ( preg_match( '/Netscape/i', $u_agent ) ) {
        $bname = 'Netscape';
        $ub = "Netscape";
    }

    // finally get the correct version number
    // Added "|:"
    $known = array( 'Version', $ub, 'other' );
    $pattern = '#(?<browser>' . join( '|', $known ) . ')[/|: ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if ( !preg_match_all( $pattern, $u_agent, $matches ) ) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count( $matches[ 'browser' ] );
    if ( $i != 1 ) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if ( strripos( $u_agent, "Version" ) < strripos( $u_agent, $ub ) ) {
            $version = $matches[ 'version' ][ 0 ];
        } else {
            $version = $matches[ 'version' ][ 1 ];
        }
    } else {
        $version = $matches[ 'version' ][ 0 ];
    }

    // check if we have a number
    if ( $version == null || $version == "" ) {
        $version = "?";
    }

    return array(
        'userAgent' => $u_agent,
        'platform' => $platform,
        'version' => $version,
        'pattern' => $pattern,
        'name' => $bname
    );
}

/**
 * @param $string
 * @param $limit
 *
 * @return int
 */
function getLimitWord( $string, $limit ) {
    $i = $limit;
    if ( !isset( $string ) || empty( $string ) ) {
        return 0;
    }
    while ( $i > 0 && $string[ $i ] != ' ' ) {
        $i--;
    }

    return $i;
}

/**
 * @param array $destinataires [nom du destinataire => adresse du destinataire]    On peut en ajouter autant que l'on veut
 * @param string $subject Objet du mail
 * @param string $body Corp du mail
 * @param string|null $auteurMail L'auteur du mail                                    Par défaut eldotravo@gmail.com
 * @param array|null $files [nom du fichier => chemin du fichier]               On peut en ajouter autant que l'on veut
 * @param array|null $cci [nom du cci => adresse du cci]                      On peut en ajouter autant que l'on veut
 * @param array|null $cc [nom du cc => adresse du cc]                      On peut en ajouter autant que l'on veut
 */
/*
function email(array $destinataires, string $subject, string $body, string $auteurMail = null, array $files = null, array $cci = null, $cc = null) {
    date_default_timezone_set('Etc/UTC');

    //Create a new PHPMailer instance
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = ""; // Host SMTP du server d'envoi du mail
    $mail->SMTPAuth = true;
    $mail->Username = ""; // Identifiant de connection
    $mail->Password = ""; // Mot de passe de connection
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465; // Port
    //Enable SMTP debugging (0 = off (for production use), 1 = client messages, 2 = client and server messages)
    $mail->SMTPDebug = 0;
    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';
    $mail->Sender = ""; // l'email d'envoi
    if ($auteurMail == 'Marie-Paule'){
        $mail->setFrom("" , ""); // Mail affiché d'envoi, nom affiché d'envoi
        $mail->addReplyTo("", ""); // Mail de reply, nom de reply
    }

    // Ajout de tout les utilisateurs
    foreach ($destinataires as $nom => $adresseMail){
        if (!empty($adresseMail)) {
            $mail->addAddress($adresseMail, $nom);
        }
    }

    // Ajout de pièces jointes
    if (!empty($files)){
        foreach ($files as $name => $file){
            if (file_exists($file)){
                $mail->addAttachment($file, $name);
            }
        }
    }

    // Ajout des CCI
    if (!empty($cci)){
        foreach ($cci as $nom => $adresseMail){
            if (!empty($adresseMail)){
                $mail->addBCC($adresseMail, $nom);
            }
        }
    }

    // Ajout des CC
    if (!empty($cc)){
        foreach ($cc as $nom => $adresseMail){
            if (!empty($adresseMail)){
                $mail->addCC($adresseMail, $nom);
            }
        }
    }

    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    //Replace the plain text body with one created manually
    $mail->AltBody = '';

    if (!$mail->send()) {
//        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
//        echo "Message sent!";
    }
}
*/

/**
 * @param string $file
 * @param int $angle
 * @param string $newName
 *
 * @return bool
 */
function rotateImage( string $file, int $angle, string $newName ) {
    // Initialisation variable pou test futur
    $image = null;
    $type = mime_content_type( $file );
    // Création ressources en fonction de l'image
    switch ( substr( $type, 6 ) ) {
        case 'jpeg':
            $image = imagecreatefromjpeg( $file );
            break;
        case 'png':
            $image = imagecreatefrompng( $file );
            break;
    }
    // Si format image non prit en charge
    if ( $image == null ) {
        return false;
    }
    // Rotation de l'image
    $rotate = imagerotate( $image, $angle, 0 );
    // On recrée l'image au format de base
    switch ( substr( $type, 6 ) ) {
        case 'jpeg':
            imagejpeg( $rotate, $file );
            break;
        case 'png':
            imagepng( $rotate, $file );
            break;
    }
    imagedestroy( $image );
    imagedestroy( $rotate );
    rename( $file, $newName );

    return true;
}

/**
 * @param array $data
 *
 * @return array
 * Clean toutes les strings dans array en récursif, et filtre pour n'avoir qu'un espaces entre chaque mot
 */
function cleanArray( array $data ): array {
    if ( !empty( $data ) ) {
        foreach ( $data as $key => $value ) {
            switch ( gettype( $value ) ) {
                case 'string':
                    if ( !empty( $str ) ) {
                        $data[ $key ] = cleanString( $value );
                    }
                    break;
                case 'array':
                    if ( !empty( $value ) ) {
                        $data[ $key ] = cleanArray( $value );
                    }
                    break;
            }
        }
    }

    return $data;
}

function cleanString( string $str ): string {
    $newStr = '';
    foreach ( explode( ' ', trim( $str ) ) as $word ) {
        if ( !empty( $word ) && $word != '' ) {
            if ( $newStr != '' ) {
                $newStr .= ' ';
            }
            $newStr .= $word;
        }
    }
    return $newStr;
}

/**
 * @param $array
 *
 * @return mixed
 */
function endKey( $array ) {
    end( $array );

    return key( $array );
}

function filterArrays( $array1, $array2 ): array {
    $newArray = [];

    foreach ( $array2 as $item2 ) {
        $add = true;
        foreach ( $array1 as $item1 ) {
            if ( $item2[ "id" ] == $item1[ "id" ] ) {
                $add = false;
            }
        }
        if ( $add ) {
            $newArray[] = $item2;
        }
    }

    return $newArray;
}

?>