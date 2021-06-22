<?php

//var_dump($args);
//die;

$mapy_script = '
Loader.load();
';


wp_enqueue_script("seznam_mapy", "https://api.mapy.cz/loader.js");
wp_add_inline_script("seznam_mapy", $mapy_script, 'after');
wp_enqueue_script("seznam_mapy-all_points", get_stylesheet_directory_uri() . '/inc/js/seznam_mapy-all-projects.js', ['seznam_mapy'], false, true);

if (!empty($args['project_list'])) {

    wp_localize_script('seznam_mapy-all_points', 'vm_projectList', $args['project_list']);

}


?>
<!-- style="height:360px" -->
<div id="m" class="h-100 mw-100"></div>
