<?php

//var_dump($args);
//die;

$mapy_script = '
Loader.load();
';


wp_enqueue_script("seznam-mapy", "https://api.mapy.cz/loader.js");
wp_add_inline_script("seznam-mapy", $mapy_script, 'after');
wp_enqueue_script("seznam-mapy-all-points", get_stylesheet_directory_uri() . '/inc/js/vm_seznam-mapy-all-projects.js', ['seznam-mapy'], false, true);

if (!empty($args['project_list'])) {

    wp_localize_script('seznam-mapy-all-points', 'vm_projectList', $args['project_list']);

}


?>
<!-- style="height:360px" -->
<div id="m" class="h-100 mw-100"></div>
