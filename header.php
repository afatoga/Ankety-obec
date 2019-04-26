<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset='<?php bloginfo("charset"); ?>'>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="keywords" content="dovoz,auta,auto,německo,eu,rakousko,volkswagen,audi,bmw,škoda">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.gif" type="image/x-icon">

    <title><?php echo  wp_title();?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>


<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container col-lg-10">
      <a class="navbar-brand" href="<?php echo get_home_url(); ?>">ANKETY OBEC</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
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

           <?php 
           if (is_user_logged_in()) {
           $current_user = wp_get_current_user();
           $userEmail = $current_user->user_email;
           ?>
            <li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement" id="vm_userIsLoggedIn" class="menu-item menu-item-type-post_type menu-item-object-page vm_userIsLoggedIn nav-item"><a href='<?php echo admin_url('profile.php'); ?>' class='nav-link vm_userIsLoggedIn'><?php echo $userEmail; ?></a></li>
           <?php } ?>
        </ul>
      </div>
    </div>
  </nav>