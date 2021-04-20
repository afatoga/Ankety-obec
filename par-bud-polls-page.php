<?php /* Template Name: par-bud-polls-page */

// laod posts by category
// the_post();

// get category id of posts to display
$post_tax_id = get_theme_mod('af_polls_post_tax_id', 'false');
//if ($post_tax_id === "false") echo 'deactivate';
$post_tax_id = intval($post_tax_id); //tag_id = 8
//var_dump($post_tax_id);

// if (!$post_tax_id) {
//     display: currently no active polls !
//     wp_redirect(site_url());
//     exit;
// }

$post_type_slug = get_theme_mod('af_polls_cpt_slug', 'navrh');


$post_list = $wpdb->get_col(
    "SELECT ID
    FROM $wpdb->posts
    LEFT JOIN  $wpdb->term_relationships as t ON ID = t.object_id
    WHERE post_type = '$post_type_slug'
    AND post_status = 'publish'
    AND t.term_taxonomy_id = '$post_tax_id'
    ORDER BY ID ASC"
);

get_header(); ?>

<div class="container no-gutters">
    <div class="col-lg-7">
        <?php foreach ($post_list as $post) : ?>

            <div class="row"></div>

        <?php endforeach; ?>
    </div>
    <div class="col">

    </div>

</div>

<?php get_footer();
