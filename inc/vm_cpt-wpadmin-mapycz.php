<?php

add_action('save_post_navrh', 'af_save_navrh_callback', 10, 3);
function af_save_navrh_callback($post_id, $post, $update)
{
    // If an old book is being updated, exit
    //if ( $update ) {
    //  return;
    // }

    // var_dump($_POST);
    // die;

    if (isset($_POST['vm_location_humantext']) || isset($_POST['vm_location_coords'])) {
        
        if (isset($_POST['vm_location_erase']) && $_POST['vm_location_erase'] === "on") {
            delete_post_meta($post_id, 'vm_location_humantext');
            delete_post_meta($post_id, 'vm_location_coords');
        }

        else {


        $location_humantext = filter_var($_POST["vm_location_humantext"], FILTER_SANITIZE_STRING);
        $location_coords = filter_var($_POST["vm_location_coords"], FILTER_SANITIZE_STRING);

        update_post_meta($post_id, 'vm_location_humantext', $location_humantext);
        update_post_meta($post_id, 'vm_location_coords', $location_coords);

        //     $vm_smap_error = ["msg" => "Prázdné pole s názvem vynačeného místa", "type" => "warning"];
        }


    }
}

function vm_add_smap_box()
{
    $screens = ['navrh'];
    foreach ($screens as $screen) {
        add_meta_box(
            'vm_seznam-mapy',                 // Unique ID
            'Lokalita návrhu',      // Box title
            'vm_render_seznam_mapy',  // Content callback, must be of type callable
            $screen                            // Post type
        );
    }
}
add_action('add_meta_boxes', 'vm_add_smap_box');


// add fields wp post.php
//add_filter('admin_footer', 'vm_render_seznam_mapy');

function vm_render_seznam_mapy($post)
{
    /*global $pagenow,$typenow;
    if (!in_array( $pagenow, array( 'post.php', 'post-new.php' )))  return;*/

    //$screen = get_current_screen();

    //if ($screen->id !== "navrh" || !isset($_GET['post'])) return false; //id = cpt slug

    $post_metas = get_post_meta($post->ID);
    $post_metas = array_combine(array_keys($post_metas), array_column($post_metas, '0'));

    $mapy_script = '
        Loader.load();

        var postForm = document.getElementById("post");
        //var submitButton = document.getElementById("publish");

      
        postForm.addEventListener("submit", function(event) {

           

                    if (document.getElementById("vm_location_coords").value.length && !document.getElementById("vm_location_humantext").value.length && !document.getElementById("vm_location_humantext").classList.contains("is-valid")) {
                        
                        if (!confirm("Lokační bod byl vyznačen, ale název místa chybí. Uložit bod bez názvu?")) event.preventDefault();
                        else {
                            document.getElementById("vm_location_humantext").classList.add("is-valid"); 
                        }
                        
                    }
             
                    
                    
                
                
            });


            ';

    //wp_enqueue_style("bootstrap", "https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css");
    // wp_enqueue_style("bootstrap", get_stylesheet_directory_uri() . "/vendor/bootstrap-wpadmin/bootstrap.min.css");

    wp_enqueue_script("seznam_mapy", "https://api.mapy.cz/loader.js");
    wp_add_inline_script("seznam_mapy", $mapy_script, 'after');
    wp_enqueue_script("seznam_mapy-all_points", get_stylesheet_directory_uri() . '/inc/js/seznam_mapy-select-location.js', ['seznam_mapy'], false, true);

?>

    <div class="acf-field">
        <div id="m" style="height:380px;"></div>
        <div class="acf-label" style="margin-top:1rem;">
            <label for="vm_location_humantext">Adresa vyznačeného bodu:</label>
            <input type="text" id="vm_location_humantext" name="vm_location_humantext" value="<?php echo (isset($post_metas['vm_location_humantext'])) ? $post_metas['vm_location_humantext'] : ""; ?>" />
            <input type="hidden" id="vm_location_coords" name="vm_location_coords" value="<?php echo (isset($post_metas['vm_location_coords'])) ? $post_metas['vm_location_coords'] : ""; ?>" />
            <label style="margin-top:1rem;"><input type="checkbox" name="vm_location_erase">Zrušit označenou lokaci</label>
        </div>
    </div>
<?php
} ?>