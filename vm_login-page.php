<?php /* Template Name: vm_login-page */

if (is_user_logged_in()) {

    if (isset($_GET["akce"]) && $_GET["akce"] === "odhlasit") wp_logout();
    wp_redirect(site_url());
    exit;
}

global $post;
$page_slug = $post->post_name;

if (isset($_POST['login_form'])) {
    $login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
    $creds = array(
        'user_login'    => $login,
        'user_password' => $password,
        'remember'      => true
    );

    $user = get_user_by('login', $login);

    if (!$user) {
        $error = 'Nesprávné přihlašovací údaje';
    } else {

        $is_approved = get_user_meta($user->ID, 'vm_reg_approved', true);

        if ($is_approved === "0") $error = 'Účet zatím nebyl ověřen';

        else {

            $user = wp_signon($creds, is_ssl());

            wp_set_current_user($user->ID);
            //setcookie("vize2030_med", "", time()-3600, "/");

            if (isset($_GET["presmerovani"]) && filter_var($_GET["presmerovani"], FILTER_SANITIZE_STRING)) wp_redirect($_GET["presmerovani"]);
            else wp_redirect(site_url());
            
            exit;
        }
    }

} else if (isset($_POST['resetPw_check']) && !empty($_POST['resetPw_login'])) {
    $email = filter_var($_POST['resetPw_login'], FILTER_VALIDATE_EMAIL);

    if (!$email) $error = 'Nevalidní e-mail';
    else {
        $user_id = email_exists($email);
        if ($user_id) af_send_password_reset($user_id);

        wp_redirect(site_url("/" . $page_slug . "?akce=zadost-zmena-hesla"));
        exit;
    }
} else if (isset($_POST["password_retyped"]) && !empty($_POST['password'])) {

    $password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
    $password_retyped = filter_var(trim($_POST['password_retyped']), FILTER_SANITIZE_STRING);

    $userLogin = filter_var(rawurldecode($_GET['login']), FILTER_SANITIZE_STRING);
    $reset = filter_var($_GET['reset'], FILTER_SANITIZE_STRING);

    if (!$password || $password !== $password_retyped) {
        $error = 'Heslo není validní';
    } else if (mb_strlen($password) < 6) {
        $error = 'Krátké heslo, zadejte minimálně 6 znaků';
    } else if (check_password_reset_key($reset, $userLogin)) {
        $user = get_user_by('email', $userLogin);
        wp_set_password($password, $user->ID);
        wp_redirect(site_url("/" . $page_slug . "?akce=heslo-zmeneno"));
        exit;
    }
}

/*
    frontend
*/


wp_enqueue_script("pristine", get_stylesheet_directory_uri() . "/includes/pristine.min.js", [], false, true);

get_header();

$alert = "";

if (isset($_GET["presmerovani"])) {
    $alert = [
        "message" => "Pro vstup do této sekce je nutné se přihlásit.",
        "type" => "warning"
    ];
}

// success or error alerts >
if (!empty($error)) {
    $alert = [
        "message" => $error,
        "type" => "danger"
    ];
}

if (isset($_GET['akce'])) {
    if ($_GET['akce'] === 'heslo-zmeneno') {

        $alert = [
            "message" => "Heslo bylo změněno, nyní se můžete přihlásit.",
            "type" => "success"
        ];
    } else if ($_GET['akce'] === 'zadost-zmena-hesla') {

        $alert = [
            "message" => "Na Váš e-mail byl zaslán odkaz pro změnu hesla.",
            "type" => "success"
        ];
    }
}

// user forms bellow >

if (isset($_GET['akce']) && $_GET['akce'] === 'nastaveni-hesla') {
    get_template_part('/template-parts/login/set-pw', 'set-pw', []);
} else if (isset($_GET['akce']) && $_GET['akce'] === 'zapomenute-heslo') {
    get_template_part('/template-parts/login/lost-pw', "lost-pw", []);
} else {
    get_template_part('/template-parts/login/index', 'login-form', ["page_slug" => $page_slug, "alert" => $alert]);
}


get_footer();
