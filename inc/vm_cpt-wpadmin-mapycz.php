<?php

// add fields wp post.php

add_filter('admin_footer', 'vm_render_seznam_mapy');

function vm_render_seznam_mapy()
{
    /*global $pagenow,$typenow;
    if (!in_array( $pagenow, array( 'post.php', 'post-new.php' )))  return;*/

    $screen = get_current_screen();

    if ($screen->id !== "navrh" || !isset($_GET['post'])) return false; //id = cpt slug

    $current_post = [];
    $current_post['id'] = $_GET['post'];

    $mapy_script = '
        window.onload = function() {
            document.getElementById("post-body").appendChild(document.getElementById("vm_seznam-mapy"));
        }

        Loader.load();
    ';

    //wp_enqueue_style("bootstrap", "https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css");
    // wp_enqueue_style("bootstrap", get_stylesheet_directory_uri() . "/vendor/bootstrap-wpadmin/bootstrap.min.css");

    wp_enqueue_script("seznam_mapy", "https://api.mapy.cz/loader.js");
    wp_add_inline_script("seznam_mapy", $mapy_script, 'after');
    wp_enqueue_script("seznam_mapy-all_points", get_stylesheet_directory_uri() . '/inc/js/seznam_mapy-select-location.js', ['seznam_mapy'], false, true);

?>

    <div id="vm_seznam-mapy" class="postbox-container" style="max-width:800px;">

        <div class="postbox">
        <div class="postbox-header"><h2 class="hndle">Lokalita návrhu</h2></div>
        <div class="inside">
            <div id="m" style="height:380px;"></div>

            <form id="vm_location-settings">
            <div>
              <label>Vybrat lokaci: <input type="text" id="queryAdv" value="" placeholder="ulice, č.p." /></label>
              <input type="button" class="search-adv" value="Hledat" />
            </div>
        </form>
            <form id="map_form" method="POST">
                <!-- <input type="hidden" name="courseItemId" value="">
                    <button class="button" name="courseAttendeesModification" style="margin-top:2rem;" type="submit" onclick="return confirm('Provést změny u účastníků?');">Uložit změny</button> -->
            </form>
            </div>
        </div>
    </div>
<?php
} ?>