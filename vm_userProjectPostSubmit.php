<?php

/* Template Name: vm_userProjectPostSubmit */

/*if (isset($_SERVER["CONTENT_LENGTH"])) {
    if ($_SERVER["CONTENT_LENGTH"] > ((int)ini_get('post_max_size') * 1024 * 1024)) {
        die('<script type="text/javascript">window.open("some page youre gonna handle the error","_self");</script>');
    }
}*/

if (!empty($_POST)) {

    $title = $description = '';
    $error = '';
    $title = filter_var($_POST['vm_title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['vm_description'], FILTER_SANITIZE_STRING);
    if (mb_strlen($description) > 2000) $error = "Maximální délka popisu činí 2000 znaků";

    if (empty($title)) {
        $error = 'Vyplňte prosím název projektu';
    }

    if (empty($description)) {
        $error = 'Vyplňte popis';
    }

    $userId = get_current_user_id();
    $postData = [];
    $postData = [
        'post_title'    => $title,
        'post_content'  => $description,
        'post_status'   => 'draft',
        'post_author'   => $userId,
        'post_category' => [2]
    ];

    if (empty($error)) {

        $post_id = wp_insert_post($postData);

        if (!empty($_FILES['vm_file'])) {
            $files = $_FILES['vm_file'];
            $filePaths = [];
            $allowedExtensions =  array('gif', 'png', 'jpg', 'jpeg', 'docx', 'doc', 'pdf');

            foreach ($files['name'] as $key => $value) {

                if ($files['name'][$key]) {
                    $file = array(
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    );
                    $_FILES = array("vm_file" => $file);
                    foreach ($_FILES as $file => $array) {
                        $extension = pathinfo($array['name'], PATHINFO_EXTENSION);
                        if (!in_array($extension, $allowedExtensions)) {
                            $error = 'Nepovolený typ souboru, povoléné jsou: gif, png, jpg, pdf, docx nebo doc';
                        } else {
                            if ($array['size'] >= 5242880) {
                                $error = 'Nepovolená velikost souboru, maximální velikost jednoho souboru je 5 MB)';
                            } else {
                                $filePaths[] = vm_handleAttachment($file, $post_id);
                            }
                        }
                    }
                }
            }

            if (empty($filePaths)) wp_delete_post($post_id, true);
        }

        if (get_post_status($post_id)) {
            $to = 'afatoga@gmail.com';
            $subject = 'Ankety Obec -' . $title;
            $body = 'Název projektu: ' . $title .
                '<br /> Popis: ' . $description;
            //'<br /> Soubory: ' . implode('<br /> ',$filePaths);
            $headers = array('Content-Type: text/html; charset=UTF-8');

            wp_mail($to, $subject, $body, $headers, $filePaths);

            $redirectUrl = get_site_url(null, '/projekt-podan');
            header('Location: ' . $redirectUrl);
            exit;
        }
    }
}

get_header();
the_post();
?>


<div class='container'>
    <div class='row'>
        <div class='col-lg-8'>
            <div class="mt-4 pb-4">
                <h1 class='mt-4'><?php single_post_title(); ?></h1>




                <?php if (is_user_logged_in()) {
                    if (!empty($error)) { ?>
                        <div class="error-label alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php } ?>

                    <form action='<?php the_permalink(); ?>' class="pb-4" method='POST' enctype='multipart/form-data'>

                        <div class='form-group'>
                            <label for='vm_title'>Název projektu</label>
                            <input class='form-control' type='text' name='vm_title' id='vm_title' value='<?php echo htmlspecialchars(@$_POST['vm_title']); ?>'>
                        </div>

                        <div class='form-group'>
                            <label for='vm_description'>Popis</label>
                            <textarea class="form-control" rows="5" name="vm_description" id='vm_description' required><?php echo htmlspecialchars(@$_POST['vm_description']); ?></textarea>
                        </div>

                        <div id='vm_fileUpload' class="form-group">
                            <label for="vm_files">Nahrát soubor</label>
                            <input type="file" class="form-control-file" id="vm_file[]" name='vm_file[]'>
                            <a id='inputSupplier' class='pt-2' href='javascript:void(0)' onclick='addFileInput(); return false'>Přidat další soubor</a>
                            <p class='pt-4'><a href='javascript:void(0)' onclick='resetFileInput(); return false'>Resetovat soubory</a></p>
                        </div>

                        <input type='submit' class='btn btn-primary mt-2' value='Podat projekt'>


                    </form>


                <?php } else { ?>

                    <p class="mt-4 pb-2">Musíte se nejdříve přihlásit.</p>
                    <div class="vm_alignFlex">
                        <a href="<?php echo get_site_url(null, '/prihlaseni'); ?>" class="btn btn-primary">Přihlásit se</a>
                        &nbsp;
                        <a href="<?php echo get_site_url(null, '/registrace');  ?>">Registrace</a>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<script>
    let inputs = 1;

    function addFileInput() {
        if (inputs < 5) {
            inputs++;
            let el = document.createElement("input");
            el.className = "form-control-file";
            el.id = "vm_file[]";
            el.name = "vm_file[]";
            el.type = "file";
            let link = document.getElementById('inputSupplier');
            document.getElementById("vm_fileUpload").insertBefore(el, link);
        }
    }

    function resetFileInput() {
        inputs = 1;
        var fileInputs = document.getElementsByClassName('form-control-file');
        for (var i = 0, n = fileInputs.length; i < n; ++i) {
            fileInputs[i].value = '';
        }

        while (fileInputs.length > 1) {
            fileInputs[0].parentNode.removeChild(fileInputs[0]);
        }
    }
</script>

<?php

get_footer();
