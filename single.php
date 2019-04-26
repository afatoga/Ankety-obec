<?php get_header(); 
the_post();
wp_enqueue_script( 'vm_glightbox-script', get_template_directory_uri() . '/js/vm_glightbox.js', '', '', true);
     
?>


<div class='container'>
        <div class='row'>
            <div class='col-lg-8'>
                <h1 class='mt-4'><?php single_post_title(); ?></h1>
                <div class='mt-4 vm_singleContent'><?php the_content(); ?></div>

            </div>
    
        </div>
    </div>


<?php
get_footer();