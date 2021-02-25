<?php get_header(); 

//$vm_sidebarTitle = get_post_meta(get_the_ID(), "vm_sidebarTitle", true); echo $vm_sidebarTitle;
?>
 <!-- Page Content -->
 <div class='container'>

<div class='row'>

  <!-- Post Content Column -->
  <div class='<?php if (!is_user_logged_in()): ?>col-xl-8<?php else: ?>col-xl-12<?php endif; ?>'>
    <div class='row'>
      <div class='<?php if (!is_user_logged_in()): ?>col-xl-12<?php else: ?>col-xl-5<?php endif; ?> col-md-12 p-0 text-center vm_frontPageBlock'>
          <?php the_post_thumbnail();?>
        </div>

            <div class='<?php if (!is_user_logged_in()): ?>col-xl-12<?php else: ?>col-xl-7 vm_frontPagePadding<?php endif; ?> col-md-12 mx-auto mt-5 pb-5'> 
                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
                    the_content();
                    endwhile; else: ?>
                    <p>Daná stránka neexistuje.</p>
                    <?php endif; ?>
            </div>
        </div>
   </div>

   <?php get_template_part( 'vm_loginPartial', 'vm_login' ); ?>




</div>
<!-- /.row -->

</div>
<!-- /.container -->

<?php
get_footer();
