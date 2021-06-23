<?php /* Template Name: par-bud-polls-page */

// laod posts by category
// the_post();
$mods = get_theme_mods();
// get tax term id of posts to display
$post_tax_id = isset($mods['polls_post_tax_id']) && $mods['polls_status'] !== "disabled" ? $mods['polls_post_tax_id'] : 'false';
//var_dump($mods);
//if ($post_tax_id === "false") echo 'deactivate';
$post_tax_id = intval($post_tax_id); //tag_id = 8
//var_dump($post_tax_id);

// if (!$post_tax_id) {
//     display: currently no active polls !
//     wp_redirect(site_url());
//     exit;
//   }

$post_type_slug = isset($mods['polls_cpt_slug']) ? $mods['polls_cpt_slug'] : 'navrh';

$post_list = $wpdb->get_col(
    "SELECT ID
    FROM $wpdb->posts
    LEFT JOIN  $wpdb->term_relationships as t ON ID = t.object_id
    WHERE post_type = '$post_type_slug'
    AND post_status = 'publish'
    AND t.term_taxonomy_id = '$post_tax_id'
    ORDER BY ID ASC"
);

// foreach ($post_list as $post_id) {
//     get_the_date('d. m. Y', $post_id)
// }

get_header(); ?>

<div class="d-flex container no-gutters mx-auto mt-5 py-4">
<button type="button" onclick="vm_changeProjectDisplayMode(this)" data-display-mode="blocks">Změna zobrazení</button>
</div>

<div class="container px-0 row mx-auto">


    <div class="col-lg-7 d-flex align-items-start flex-wrap px-0 pr-lg-2 vm_project-container">
        <?php
        if (!empty($post_list)) :
            foreach ($post_list as $post_id):

                $post_project_status = get_field_object('project_status', $post_id);
                if ($post_project_status["value"] !== "approved") continue;

                $post_metas = get_post_meta($post_id);
                $post_metas = array_combine(array_keys($post_metas), array_column($post_metas, '0'));

                $vm_smap_details[$post_id] = [
                    "title" => get_the_title($post_id),
                    "humantext" => isset($post_metas['vm_location_humantext']) ? $post_metas['vm_location_humantext'] : "",
                    "coords" => isset($post_metas['vm_location_coords']) ? $post_metas['vm_location_coords'] : "",
                    "slug" => get_post_field( 'post_name', $post_id )
                ];

                $post_content = get_the_content(null, false, $post_id);
                if (mb_strlen($post_content) > 300) $post_content = trim(mb_substr($post_content, 0, 300)) . "&hellip;";

                $status_label = "";
                foreach ($post_project_status["choices"] as $value => $label) {
                    if ($post_project_status["value"] === $value) {
                        $status_label = $label;
                        break;
                    }
                }

        ?>

                <div class="col-12 col-lg-6 mb-2 border py-2 vm_project-item">

                    <div>
                        <img class="mw-100 rounded" src="<?php echo get_the_post_thumbnail_url($post_id); ?>" alt="obrázek návrhu <?= $post_id ?>">
                    </div>
                    <p class="mt-2 mb-0">
                        podáno: <?= get_the_date('d. m. Y', $post_id) ?>
                    </p>
                    <p class="">
                        stav: <?= $status_label ?>
                    </p>
                    <p class="text-justify mt-2">
                        <?= $post_content ?>
                    </p>
                    <div>
                        <button class="btn btn-success" type="button">Hlasovat
                        </button>
                    </div>


                </div>

        <?php endforeach;
        endif; ?>
    </div>
    <div class="col-lg-5 border px-0 vh-100">
        <?php get_template_part('template-parts/par-bud-project-map', 'mapa', ['project_list' => $vm_smap_details]); ?>
        
        <!-- <form id="form">
            <p>
              <label>Hledat v omezené oblasti dané bounding boxem: <input type="text" id="queryAdv" value="Brno" /></label>
              <input type="button" class="search-adv" value="Hledat" />
            </p>
        </form> -->
    </div>

</div>

<?php 
wp_enqueue_script("vm_layout-effects", get_stylesheet_directory_uri() . '/inc/js/vm_layout-effects.js', [], false, true);
get_footer();
