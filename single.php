<?php
get_header();
the_post();
wp_enqueue_script('vm_glightbox-script', get_template_directory_uri() . '/js/vm_glightbox.js', '', '', true);
?>


<div class='container'>
    <div class='row'>
        <div class='col-lg-8'>
            <h1 class='mt-4'><?php single_post_title(); ?></h1>
            <div class='mt-4 vm_singleContent pb-4'><?php the_content(); ?>
                <?php

                $tags = get_the_tags();

                if (!empty($tags)) {
                    $html = '<div class="vm_post_tags pb-4"> Štítky: ';
                    foreach ($tags as $tag) {
                        $html .= "{$tag->name}, ";
                    }
                    $html = rtrim($html, ", ");
                    $html .= '</div>';
                    echo $html;
                }

                $images = get_attached_media('image', get_the_ID());
                $imageUrls = [];
                foreach ($images as $image) {
                    $imageUrls[] = $image->guid;
                }
                foreach ($imageUrls as $url) {
                    echo '<a href="' . $url . '" class="vm_glightbox"><img src="' .
                        $url . '" alt="fotografie" height="150"></a> &nbsp;';
                }
                ?>
            </div>

        </div>

    </div>
</div>

<?php
get_footer();
