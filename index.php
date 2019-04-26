<?php get_header(); 
the_post();?>


<div class='container'>
    <div class='row'>
        <div class='col-lg-8'>
            <h1 class='mt-4'><?php single_post_title(); ?></h1>
            <div class='mt-4'><?php the_content(); ?></div>
        </div>

    </div>
</div>

<?php
get_footer();