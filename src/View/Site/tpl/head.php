<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!--    <link rel="manifest" href="/manifest.json">-->

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" id="status-bar" content="white-translucent">
    <meta name="format-detection" content="telephone=no">

    <meta name="author" content="Mathieu Sanchez">

    <title><?= $this->head[ 'title' ] ?></title>
    <meta name="description" content="<?= $this->head[ 'description' ] ?>">

    <meta property="og:title" content="<?= $this->head[ 'title' ] ?>"/>
    <meta property="og:description" content="<?= $this->head[ 'description' ] ?>"/>
    <meta property="og:url" content="https://<?= $_SERVER[ 'SERVER_NAME' ] . $_SERVER[ 'REQUEST_URI' ] ?>"/>
    <meta property="og:image"
          content="https://<?= $_SERVER[ 'SERVER_NAME' ] . \CAUProject3Contact\Config::FAVICON_PATH ?>"/>
    <!--    <meta property="fb:app_id"              content="1000452166691027" /> -->

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700%7CRoboto+Condensed:400,700%7CMaterial+Icons'
          rel='stylesheet' type='text/css'>

    <link rel="image_src" href="<?= CAUProject3Contact\Config::FAVICON_PATH ?>"/>
    <link rel="icon" type="image/ico" href="<?= CAUProject3Contact\Config::FAVICON_PATH ?>"/>

    <meta name="theme-color" content="#ffffff">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="/css/materialize.min.css" media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="/css/modal.css">

    <?php if ( isset( $this->head[ 'robotNoIndex' ] ) && $this->head[ 'robotNoIndex' ] == true ) { ?>
        <meta name="robots" content="noindex"/>
    <?php } ?>

</head>

<body>

<nav class="blue" role="navigation">
    <div class="nav-wrapper container">
        <a id="logo-container" class="brand-logo">
            <img src="/img/logo.png" alt="Logo of Your Contact" height="64px"/>
        </a>

        <ul class="right hide-on-med-and-down">
            <li>
                <a class="nav-search"><input type="text" placeholder="Search Contact" id="search" name="search"/></a>
            </li>
            <li data-position="bottom" data-tooltip="Add a new contact">
                <a class="nav-add"><img class="add-contacts" src="/img/add-contacts.png" alt="Add contact"></a>
            </li>
        </ul>

        <!--        <ul id="nav-mobile" class="sidenav">-->
        <!--            <li><a href="#">Navbar Link</a></li>-->
        <!--        </ul>-->
        <!--        <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>-->
    </div>
</nav>

<main>
    <div class="container">