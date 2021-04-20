<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset='<?php bloginfo("charset"); ?>'>
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="keywords" content="ankety, dotazníky, Zdiby, obec, Středočeský, kraj">
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.gif" type="image/x-icon">

  <title><?php wp_title(' | ', true, 'right');
          bloginfo('name'); ?></title>
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>


  <nav class="navbar navbar-expand-lg fixed-top navbar-light bg-light py-md-2">
    <div class="container col-lg-10 py-md-4">
      <a class="navbar-brand" href="<?php echo get_home_url(); ?>"><?php bloginfo('name'); ?></a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-2 ml-md-4">
          <?php
          wp_nav_menu([
            "theme_location" => "header-menu",
            "depth" => 2,
            "container" => false,
            "menu_class" => "navbar-nav mr-auto",
            "fallback_cb" => "WP_Bootstrap_Navwalker::fallback",
            "walker" => new WP_Bootstrap_Navwalker,
          ]);
          ?>
        </ul>
      </div>
    </div>
  </nav>