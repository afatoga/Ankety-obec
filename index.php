<?php get_header(); 
the_post();?>


<div class='container bg-white rounded shadow-sm'>
    <div class='row no-gutters'>
		<?php if (is_front_page()): ?> 
			<div class='col'>
            	<div class='mt-4'><?php the_content(); ?></div>
        	</div>
		<?php else: ?> 
        	<div class='col-lg-8'>
          		<h1 class='mt-4'><?php single_post_title(); ?></h1> 
            	<div class='mt-4'><?php the_content(); ?></div>
        	</div>
		<?php endif; ?>
    </div>
</div>

<?php
get_footer();