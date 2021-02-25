<?php /* Template Name: vm_login */

require_once( __DIR__ . '/vm_functions/loginForm.php');

if ( ! is_user_logged_in() ) {
    get_header(); // Display WordPress login form:

    if (!empty($_GET['redirectTo'])) 
    {$redirectUrl = filter_var($_GET['redirectTo'], FILTER_SANITIZE_STRING);
    } else {$redirectUrl = admin_url();
    }

    $args = array(
        'redirect' => $redirectUrl, 
        'form_id' => 'vm_loginForm',
        'label_username' => __( 'E-mail' ),
        'label_password' => __( 'Heslo' ),
        'label_remember' => __( 'Pamatuj si mě' ),
        'label_log_in' => __( 'Přihlásit' ),
        'remember' => true
    );

    ?>
        <div class='container'>
        <div class='row'>
            <div class='col-lg-8'>
                <h1 class='mt-4'><?php single_post_title(); ?></h1>
                <div class='mt-4 vm_singleContent pb-4'> 
                <?php vm_login_form( $args ); ?>
                </div>

            </div>
    
        </div>
    </div>

<?php
get_footer();

   
} else { // If logged in:
    header('Location: ' . get_site_url());
}

?>

