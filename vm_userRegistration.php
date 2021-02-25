<?php 

/* Template Name: vm_userRegistration */

if (is_user_logged_in()) {
    header('Location: ' . get_site_url());
}

//recaptcha keys from meta
$queried_post =  get_page_by_title( "Registrace", OBJECT, "page");
$vm_registrationRECAPTCHAsite = get_post_meta($queried_post->ID, "vm_registrationRECAPTCHAsite", true);
$vm_registrationRECAPTCHAsecret = get_post_meta($queried_post->ID, "vm_registrationRECAPTCHAsecret", true);

if (!empty($_POST)) {

    $userName = $userSurname = $userEmail = $userPrivateKey = '';
    $error = '';
    $userName = filter_var($_POST['vm_name'], FILTER_SANITIZE_STRING);
    $userSurname = filter_var($_POST['vm_surname'], FILTER_SANITIZE_STRING);
    $userEmail = filter_var($_POST['vm_email'], FILTER_SANITIZE_EMAIL);
    $userPrivateKey = filter_var($_POST['vm_privateKey'], FILTER_SANITIZE_NUMBER_INT);


    //recaptcha v2 google
    $url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => $vm_registrationRECAPTCHAsecret,
		'response' => $_POST["g-recaptcha-response"]
	);
	$options = array(
		'http' => array (
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded\r\n',
			'content' => http_build_query($data)
		)
    );
    
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success = json_decode($verify);

    if (empty($userName)) 
    {
        $error = 'Vyplňte prosím Vaše jméno';
    }

    if (empty($userSurname))
    {   
        $error = 'Vyplňte prosím Vaše příjmeni';
    }

    if (empty($userEmail)) 
    {
        $error = 'Vyplňte prosím Váš e-mail';
    }

    if (empty($userPrivateKey)) 
    {
        $error = 'Vyplňte prosím poslední 4 číslice z rodného čísla';
    }

    if (strlen($userPrivateKey) != 4) 
    {
        $error = 'Zadejte 4 čísla z rodného čísla';
    }

    if ($captcha_success->success==false) 
    {
        $error = 'Selhalo ověření';
    }

    if (empty($error)) {

                $to = 'tajemnik@obeczdiby.cz';
                $subject = 'Ankety Obec - Žádost o registraci';
                $body = 'Jméno uživatele: ' . $userName .
                '<br /> Příjmení uživatele: ' . $userSurname .
                '<br /> Email uživatele: ' . $userEmail .
                '<br /> Tajné číslo uživatele: ' . $userPrivateKey;
                $headers = ['Content-Type: text/html; charset=UTF-8'];
                $headers[] = 'Cc: Petra Buršíková <petra.bursikova@obeczdiby.cz>';
                
            wp_mail( $to, $subject, $body, $headers );

            $redirectUrl = get_site_url(null, '/zadost-odeslana');
            header('Location: ' . $redirectUrl);
            exit;

    }
}


get_header();
the_post(); ?>


<div class='container'>
    <div class='row'>
        <div class='col-lg-8'>
            
            <?php 
                    
                if (!is_user_logged_in()): ?>
                <div class="mt-4">
                    <?php the_content(); ?>

                
            </div>
                <?php if (!empty($error)):?>
                <div class="error-label alert alert-danger">
                <?php echo $error; ?> 
                </div>
                <?php endif; ?>
                
                <h2 class="mt-4 pb-4">Žádost o registraci</h2>

                <form action='<?php the_permalink(); ?>' class="pb-4" method='POST'>

                <div class='form-group'>
                    <label for='vm_name'>Jméno</label>
                    <input class='form-control' type='text' name='vm_name' id='vm_name' value='<?php echo htmlspecialchars(@$_POST['vm_name']); ?>' required>
                </div>

                <div class='form-group'>
                    <label for='vm_surname'>Příjmení</label>
                    <input class='form-control' type='text' name='vm_surname' id='vm_surname' value='<?php echo htmlspecialchars(@$_POST['vm_surname']); ?>' required>
                </div>
                    
                <div class='form-group'>
                    <label for='vm_email'>E-mail</label>
                    <input class='form-control' type='email' name='vm_email' id='vm_email' value='<?php echo htmlspecialchars(@$_POST['vm_email']); ?>' required>
                </div>

                <div class='form-group'>
                    <label for='vm_privateKey'>Poslední 4 čísla z rodného čísla</label>
                    <input class='form-control' type='text' name='vm_privateKey' id='vm_privateKey' value='<?php echo htmlspecialchars(@$_POST['vm_privateKey']); ?>' required>
                </div>

                <div class='form-group'>
                    <p>Ověření</p>
                    <div class="g-recaptcha" data-sitekey='<?php echo $vm_registrationRECAPTCHAsite; ?>'></div>
                    </div>          


                    <input type='submit' class='btn btn-primary mt-2' value='Odeslat žádost o registraci'>

                    
                </form>


                <?php endif; ?>
        </div>

    </div>
</div>

<?php
get_footer();