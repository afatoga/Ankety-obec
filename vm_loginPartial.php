<?php

require_once( __DIR__ . '/vm_functions/loginForm.php');

if ( ! is_user_logged_in() ) {

    $args = array(
        'redirect' => home_url( $wp->request ), 
        'form_id' => 'vm_loginForm',
        'label_username' => __( 'E-mail' ),
        'label_password' => __( 'Heslo' ),
        'label_remember' => __( 'Pamatuj si mě' ),
        'label_log_in' => __( 'Přihlásit' ),
        'remember' => true
    );

    ?>


    <div class="col-md-12 col-xl-4 vm_frontPageBlock">

<div id="sidebar" class="card">
   
    <h5 class="card-header personal-project-voting">Participace</h5>
    <div class="card-body">
    <?php vm_login_form( $args ); ?>
    </div>
    
</div>

</div>
<?php } ?>