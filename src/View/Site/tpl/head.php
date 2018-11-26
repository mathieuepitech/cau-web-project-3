<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="manifest" href="/manifest.json">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" id="status-bar" content="white-translucent">
    <meta name="format-detection" content="telephone=no">

    <meta name="author" content="Eldotravo">

    <title><?= $this->head['title'] ?></title>
    <meta name="description" content="<?= $this->head['description'] ?>">

    <meta property="og:title" content="<?= $this->head['title'] ?>"/>
    <meta property="og:description" content="<?= $this->head['description'] ?>"/>
    <meta property="og:url" content="https://<?= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>"/>
    <meta property="og:image" content="https://<?= $_SERVER['SERVER_NAME'] . \CAUProject3Contact\Config::FAVICON_PATH ?>"/>
    <!--    <meta property="fb:app_id"              content="1000452166691027" /> -->

    <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700%7CRoboto+Condensed:400,700%7CMaterial+Icons'
          rel='stylesheet' type='text/css'>

    <link href="/css/theme.css?v=<?= CAUProject3Contact\Config::SITE_CSS_VERSION ?>" rel="stylesheet">
    <link href="/css/select2.css" rel="stylesheet">

    <link rel="image_src" href="<?php \CAUProject3Contact\Config::FAVICON_PATH ?>"/>
    <link rel="icon" type="image/png" href="<?php \CAUProject3Contact\Config::FAVICON_PATH ?>"/>

    <meta name="theme-color" content="#ffffff">

	<?php if ( isset( $this->head['robotNoIndex'] ) && $this->head['robotNoIndex'] == true ) { ?>
        <meta name="robots" content="noindex"/>
	<?php } ?>

</head>

<body>
