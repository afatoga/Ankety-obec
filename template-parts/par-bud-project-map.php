<?php

$mapy_script = '
Loader.load();
';

wp_enqueue_script("seznam_mapy", "https://api.mapy.cz/loader.js");
wp_add_inline_script("seznam_mapy", $mapy_script, 'after');
wp_enqueue_script("seznam_mapy-all_points", get_stylesheet_directory_uri() . '/inc/js/seznam_mapy-all-projects.js', ['seznam_mapy'], false, true);

?>
<!-- style="height:360px" -->
<div id="m" class="h-100 mw-100"></div>
