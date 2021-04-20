<?php

// --- simple autoload ---------------------------

foreach (glob(get_template_directory() . "/inc/*.php") as $file) {
    require $file;
}

// --- styles & scripts ---------------------------

add_action("wp_enqueue_scripts", "wp_enqueue_scripts_action_callback");

function wp_enqueue_scripts_action_callback()
{
    
	// styles
    wp_enqueue_style("bootstrap", "https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css");
	wp_enqueue_style("theme-custom", get_stylesheet_directory_uri() . "/style.css");

	// fonts and icons
	wp_enqueue_style("roboto-font", "https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap");
	wp_enqueue_style("dashicons");


    // scripts
    $inline_script = "
        var prevScrollpos = window.pageYOffset;
        window.onscroll = function() {
        var currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
            document.querySelector('nav').style.top = '0';
        } else {
            document.querySelector('nav').style.top = '-58px';
        }
        prevScrollpos = currentScrollPos;
        }
    ";

    wp_enqueue_script("bootstrap", get_template_directory_uri() . "/vendor/bootstrap/js/bootstrap-native-v4.min.js", '', '', true);
    wp_add_inline_script("bootstrap", $inline_script, 'after');
}

// --- menus ---------------------------

add_action("after_setup_theme", "after_setup_theme_action_callback");

function after_setup_theme_action_callback()
{
    register_nav_menu("header-menu", __("Menu v hlavičce", "ZDIBY_NAS_BAVI"));
}

// --- images bootstrap css ------------------------------


add_filter('wp_get_attachment_image_attributes', 'vm_custom_image_class');

function vm_custom_image_class($attr)
{
    $attr['class'] = 'img-fluid ';
    return $attr;
};



// --- login ---------------------------
function vm_loginLogo()
{ ?>
    <style type="text/css">
        /* #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/ankety-zdiby-logo.png);
        	padding-bottom: 30px;
        } */
    </style>
<?php }
add_action('login_enqueue_scripts', 'vm_loginLogo');

function vm_loginUrl($url)
{
    return get_bloginfo('url');
}
add_filter('login_headerurl', 'vm_loginUrl');


// --- thumbnails ---------------------------

add_theme_support("post-thumbnails");

// --- front-page ---------------------------
add_post_type_support('page', 'excerpt');

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

// -- defer javascript
function add_defer_attribute($tag, $handle)
{
    if ('vm_hiddenFormElement-script' !== $handle)
        return $tag;
    return str_replace(' src', ' defer src', $tag);
}
add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);

// add_filter( 'retrieve_password_title',
//   function( $title )
//   {
//     $title = __( 'Anketyzdiby.cz - Nastavení hesla' );
//     return $title;
//   }
// );

// -- redirect after login

function after_login_redirect($redirect_to, $request, $user)
{
    //is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        //check for subscribers
        if (in_array('subscriber', $user->roles)) {
            // redirect them to another URL, in this case, the homepage
            $redirect_to =  home_url();
        }
    }

    return $redirect_to;
}

add_filter('login_redirect', 'after_login_redirect', 10, 3);

// Hide sticky posts
add_action('admin_print_styles', 'hide_sticky_option');
function hide_sticky_option()
{
    global $post_type, $pagenow;
    if ('post.php' != $pagenow && 'post-new.php' != $pagenow && 'edit.php' != $pagenow)
        return;
?>
    <style type="text/css">
        #sticky-span {
            display: none !important
        }

        .quick-edit-row .inline-edit-col-right div.inline-edit-col> :last-child>label.alignleft:last-child {
            display: none !important;
        }
    </style>
<?php
}

//add meta to header
function vm_add_meta_tags()
{
    echo (is_front_page()) ? '<link rel="canonical" href="' . get_home_url() . '" />' : null;
}
add_action('wp_head', 'vm_add_meta_tags');

// wp generator
remove_action('wp_head', 'wp_generator');


//ankety

$url = $_SERVER["REQUEST_URI"];
if (strpos($url, '/ankety/') === 0) {

    wp_enqueue_script('vm_torroScript-form', get_template_directory_uri() . "/js/vm_torroForms.js", '', '', true);

    if (is_user_logged_in()) {

        $script = "
        window.addEventListener('load', function() {
            var itemsToReveal = document.querySelectorAll('div.torro-element-wrap')

                for (var i=0, count=itemsToReveal.length; i<count; i++) {
                    if (itemsToReveal[i].classList.contains('hidden'))
                        itemsToReveal[i].classList.remove('hidden')
                }
        })
        
        ";

        wp_register_script('vm_torroScript-hidden', '', [], '', true);
        wp_enqueue_script('vm_torroScript-hidden');
        wp_add_inline_script('vm_torroScript-hidden', $script);
    }
}
