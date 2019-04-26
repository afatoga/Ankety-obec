<?php

require_once( __DIR__ . '/vm_functions/projectVote.php');

// --- simple autoload ---------------------------

foreach (glob(get_template_directory() . "/inc/*.php") as $file) {
    require $file;
}

// --- styles & scripts ---------------------------

add_action("wp_enqueue_scripts", "wp_enqueue_scripts_action_callback");

function wp_enqueue_scripts_action_callback()
{
    // styles
    wp_enqueue_style("bootstrap-style", get_template_directory_uri() . "/vendor/bootstrap/css/bootstrap.min.css");
    wp_enqueue_style("glightbox-style", get_template_directory_uri() . "/vendor/glightbox/glightbox.min.css");
    //wp_enqueue_style("opensans-font", "https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800");
    wp_enqueue_style("theme-style", get_template_directory_uri() . "/css/blog-post.css");
    wp_enqueue_style("custom-style", get_template_directory_uri() . "/style.css");
    // scripts
    wp_enqueue_script("bootstrap-script", get_template_directory_uri() . "/vendor/bootstrap/js/bootstrap-native-v4.min.js", '', '', true);
    wp_enqueue_script("glightbox-script", get_template_directory_uri() . "/vendor/glightbox/glightbox.min.js", '', '', true);

    wp_register_script('google-reCaptcha2', 'https://www.google.com/recaptcha/api.js' , '', '', true );

    if(is_page('Registrace')) {
        wp_enqueue_script('google-reCaptcha2');
    }

    if(is_page('Podat projekt')) {
        wp_enqueue_script('jquery');
    }
    // zakazat nacitani jquery
    else {	
        //wp_dequeue_script( 'jquery');
        wp_deregister_script( 'jquery');
    }

}

// --- menus ---------------------------

add_action("after_setup_theme", "after_setup_theme_action_callback");

function after_setup_theme_action_callback()
{
    register_nav_menu("header-menu", __("Menu v hlavičce", "ANKETY-OBEC-CZ"));
}

// --- images ------------------------------


  add_filter( 'wp_get_attachment_image_attributes', 'vm_custom_image_class' );

  function vm_custom_image_class( $attr ) {    
      $attr['class'] = 'img-fluid ';
      return $attr;
  };



// --- thumbnails ---------------------------

add_theme_support("post-thumbnails");

// --- front-page ---------------------------
add_post_type_support( 'page', 'excerpt' );

// --- wp admin bar ---------------------------
add_filter('show_admin_bar', '__return_false');

// --- Remove JQuery migrate ------------------
function remove_jquery_migrate($scripts)
{
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        
        if ($script->deps) { // Check whether the script has any dependencies
            $script->deps = array_diff($script->deps, array(
                'jquery-migrate'
            ));
        }
    }
}

add_action('wp_default_scripts', 'remove_jquery_migrate');