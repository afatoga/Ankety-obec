<?php

function vm_login_form( $args = array() ) {
        $defaults = array(
            'echo'           => true,
            // Default 'redirect' value takes the user back to the request URI.
            'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'form_id'        => 'loginform',
            'label_username' => __( 'Username or Email Address' ),
            'label_password' => __( 'Password' ),
            'label_remember' => __( 'Remember Me' ),
            'label_log_in'   => __( 'Log In' ),
            'id_username'    => 'user_login',
            'id_password'    => 'user_pass',
            'id_remember'    => 'rememberme',
            'id_submit'      => 'wp-submit',
            'remember'       => true,
            'value_username' => '',
            // Set 'value_remember' to true to default the "Remember me" checkbox to checked.
            'value_remember' => false,
        );
    
        $args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
    
        $form = '
            <form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post">
                <div class="login-username form-group">
                    <label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
                    <input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="form-control" value="' . esc_attr( $args['value_username'] ) . '" placeholder="Váš e-mail"/>
                </div>
                <div class="login-password form-group">
                    <label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>
                    <input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="form-control" value="" placeholder="Heslo"/>
                </div>
                ' . ( $args['remember'] ? '<p class="login-remember"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' ) . '<div class="vm_alignFlex">
                <input type="submit" name="wp-submit" id="wp-submit" class="btn btn-primary" value="Přihlásit se">&nbsp;
                <a href="' . get_site_url() . '/registrace" >Registrace</a>
                
            </div><p class="vm_loginLostPwd"> <a href="' . get_site_url() . '/wp-login.php?action=lostpassword" >Zapomenuté heslo?</a></p></form>';
    
        if ( $args['echo'] ) {
            echo $form;
        } else {
            return $form;
        }
    }