<?php
function af_send_password_reset($user_id) {

    if ( !class_exists('WP_Mail') ) require_once get_stylesheet_directory() . "/vendor/WP_Mail.php";

    $user = get_user_by('id', $user_id);

    $adt_rp_key = get_password_reset_key($user);
    //$user_login = $user->user_login;
    $siteUrl = get_site_url(null, '', 'https');

    $login_page = get_page_by_path( 'prihlaseni', OBJECT, 'page' );
    if (!$login_page) $login_page = get_page_by_path( 'login', OBJECT, 'page' );

    $rp_linkURL =  $siteUrl . "/".$login_page->post_name."?akce=nastaveni-hesla&reset=$adt_rp_key&login=" . rawurlencode($user->user_email);

    $urlparts = parse_url(home_url());
    $domain = $urlparts['host'];
    $domain_first_char_cap = ucfirst($domain);

    $email = WP_Mail::init()
    ->to($user->user_email)
    ->from($domain_first_char_cap.' <info@'.$domain.'>')
    ->subject('Změna hesla')
    ->template(get_stylesheet_directory() . "/email-templates/password_reset.php", [
        "user_email"=>$user->user_email,
        "reset_link_url"=>$rp_linkURL
    ])
    ->send();

}
