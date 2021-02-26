<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset='<?php bloginfo("charset"); ?>'>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="keywords" content="ankety, dotazníky, Zdiby, obec, Středočeský, kraj">
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.gif" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

  <title><?php wp_title(' | ', true, 'right');
          bloginfo('name'); ?></title>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>


  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container col-lg-10">
      <a class="navbar-brand" href="<?php echo get_home_url(); ?>"><?php bloginfo('name'); ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
          <?php
          wp_nav_menu([
            "theme_location" => "header-menu",
            "depth" => 2,
            "container" => false,
            "menu_class" => "navbar-nav ml-auto",
            "fallback_cb" => "WP_Bootstrap_Navwalker::fallback",
            "walker" => new WP_Bootstrap_Navwalker,
          ]);
          ?>
      </div>
    </div>
  </nav>