<?php

require_once( __DIR__ . '/vm_functions/projectVote.php');
require_once( __DIR__ . '/vm_functions/projectPost.php');

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
    wp_enqueue_style("robot-font", "https://fonts.googleapis.com/css?family=Roboto&display=swap&subset=latin-ext");
    wp_enqueue_style("theme-style", get_template_directory_uri() . "/css/blog-post.css");
    wp_enqueue_style("custom-style", get_template_directory_uri() . "/style.css");
    // scripts
    wp_enqueue_script("bootstrap-script", get_template_directory_uri() . "/vendor/bootstrap/js/bootstrap-native-v4.min.js", '', '', true);
    wp_enqueue_script("glightbox-script", get_template_directory_uri() . "/vendor/glightbox/glightbox.min.js", '', '', true);
    wp_dequeue_style( 'wp-block-library' );

    //wp_register_script('google-reCaptcha2', 'https://www.google.com/recaptcha/api.js' , '', '', true );

    if(is_page('Registrace')) {
        wp_enqueue_script('google-reCaptcha2', 'https://www.google.com/recaptcha/api.js' , '', '', true );
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



// --- login ---------------------------
function vm_loginLogo() { ?>
    <style type="text/css">
        #login h1 a, .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/ankety-zdiby-logo.png);
        	padding-bottom: 30px;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'vm_loginLogo' );

function vm_loginUrl( $url ) {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'vm_loginUrl' );

/*add_filter('login_form_bottom','my_added_login_field');
function my_added_login_field(){
    $additional_field = '<div class="vm_alignFlex">
    <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary" value="Přihlásit">&nbsp;
    <a href="' . get_site_url() . '/registrace" >Registrace</a>
    
</div><p class="vm_loginLostPwd"> <a href="' . get_site_url() . '/wp-login.php?action=lostpassword" >Zapomenuté heslo?</a></p>';

    return $additional_field;
}*/


// --- thumbnails ---------------------------

add_theme_support("post-thumbnails");

// --- submit-user-project-post -------------
/*add_shortcode( 'vm_fronted_post', 'vm_fronted_post' );
function vm_fronted_post() {
    submitUserProjectPost();
*/
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

// -- defer javascript
        function add_defer_attribute($tag, $handle) {
            if ( 'vm_hiddenFormElement-script' !== $handle )
                return $tag;
            return str_replace( ' src', ' defer src', $tag );
        }
        add_filter('script_loader_tag', 'add_defer_attribute', 10, 2);

add_filter( 'retrieve_password_title',
  function( $title )
  {
    $title = __( 'Anketyzdiby.cz - Nastavení hesla' );
    return $title;
  }
);

// -- redirect after login

function my_login_redirect( $redirect_to, $request, $user ) {
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

add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );

// -- dashboard user

        // removes admin color scheme options

        remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

        //Removes the leftover 'Visual Editor', 'Keyboard Shortcuts' and 'Toolbar' options.

        add_action( 'admin_head', function () {

            ob_start( function( $subject ) {

                $subject = preg_replace( '#<h[0-9]>'.__("Personal Options").'</h[0-9]>.+?/table>#s', '', $subject, 1 );
                return $subject;
            });
        });

        add_action( 'admin_footer', function(){

            ob_end_flush();
        }); 

//Remove fields from Admin profile page via JS to hide nickname field which is mandatory
function vm_remove_personal_options(){
	if ( ! current_user_can('manage_options') ) { // 'update_core' may be more appropriate
		echo '<script type="text/javascript">jQuery(document).ready(function($) {
			$(\'form#your-profile tr.user-nickname-wrap\').hide(); 
			$(\'form#your-profile tr.user-display-name-wrap\').hide();
			$(\'form#your-profile tr.user-url-wrap\').hide();
			$(\'form#your-profile tr.user-description-wrap\').parent().parent().prev().hide();
			$(\'form#your-profile tr.user-description-wrap\').hide();
			$(\'button#contextual-help-link\').hide();
		});</script>';
		
	remove_menu_page( 'index.php' );                  //Dashboard
	
	}
}
add_action('admin_head','vm_remove_personal_options');
        
// Admin footer modification
  
function remove_footer_admin () 
{
    echo '<div><p><a href="http://lite.g6.cz" target="_blank">Testovací web</a></p>
	<p><a href="https://drive.google.com/open?id=1sTu2rqrc45itBmN3paZiY-irQ27psqeO" target="_blank">Návody</a></p></div>';
}
 
add_filter('admin_footer_text', 'remove_footer_admin');

// remove comments section

add_action( 'admin_menu', 'vm_remove_admin_menus' );
function vm_remove_admin_menus() {

    $author = wp_get_current_user();

    if( isset( $author->roles[0] ) ) { 
        $current_role = $author->roles[0];
    } else {
        $current_role = 'no_role';
    }

    if ($current_role = 'editor') {
        // remove these items from the admin menu
        // remove_menu_page( 'edit.php' );          // Posts
        //remove_menu_page( 'upload.php' );        // Media
        remove_menu_page( 'tools.php' );         // Tools
        remove_menu_page( 'edit-comments.php' ); // Comments
    }

}

add_action( 'current_screen', 'vm_restrict_admin_pages' );
function vm_restrict_admin_pages() {

    // retrieve the current page's ID
    $current_screen_id = get_current_screen()->id;

    // determine which screens are off limits
    $restricted_screens = array(
        'tools',
        'edit-comments',
    );

    // Restrict page access
    foreach ( $restricted_screens as $restricted_screen ) {

        // compare current screen id against each restricted screen
        if ( $current_screen_id === $restricted_screen ) {
            wp_die('Nemáte povolení přistupovat na tuto stránku.');
        }

    }

}

//remove comments from pages
add_action('init', 'vm_remove_comment_support', 100);

function vm_remove_comment_support() {
	remove_post_type_support( 'page', 'comments' );
	remove_post_type_support( 'post', 'comments' );
}

// Remove comments metaboxes

function vm_remove_comments_metaboxes() {
	remove_meta_box( 'commentstatusdiv' , 'post' , 'normal' ); //removes comments status
	remove_meta_box( 'commentsdiv' , 'post' , 'normal' ); //removes comments
	remove_meta_box( 'commentstatusdiv' , 'page' , 'normal' ); //removes comments status
	remove_meta_box( 'commentsdiv' , 'page' , 'normal' ); //removes comments
}
//add_action( 'admin_menu' , 'vm_remove_comments_metaboxes' );

// Hide sticky posts
add_action( 'admin_print_styles', 'hide_sticky_option' );
function hide_sticky_option() {
global $post_type, $pagenow;
if( 'post.php' != $pagenow && 'post-new.php' != $pagenow && 'edit.php' != $pagenow )
    return;
?>
<style type="text/css">#sticky-span { display:none!important }
.quick-edit-row .inline-edit-col-right div.inline-edit-col > :last-child > label.alignleft:last-child{ display:none!important; }</style>
<?php
}

//add meta to header
function vm_add_meta_tags() {
    echo (is_front_page()) ? '<link rel="canonical" href="'. get_home_url() .'" />' : null;
  }
  add_action('wp_head', 'vm_add_meta_tags');

// wp generator
remove_action('wp_head', 'wp_generator');

/* mail from
function af_replace_user_mail_from( $from_email ) {
	return 'moderator@trida.eu';
}
 
add_filter( 'wp_mail_from', 'af_replace_user_mail_from' );
*/