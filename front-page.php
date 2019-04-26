<?php get_header(); 

$vm_sidebarTitle = get_post_meta(get_the_ID(), "vm_sidebarTitle", true);
?>
 <!-- Page Content -->
 <div class='container'>

<div class='row'>

  <!-- Post Content Column -->
  <div class='<?php if (!is_user_logged_in()): ?>col-xl-8<?php else: ?>col-xl-12<?php endif; ?>'>
    <div class='row'>
      <div class='<?php if (!is_user_logged_in()): ?>col-xl-12<?php else: ?>col-xl-5<?php endif; ?> col-md-12 p-0 vm_frontPageBlock'>
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
  <?php if (!is_user_logged_in()): ?>
  <div class="col-md-12 col-xl-4 vm_frontPageBlock">

    <div id="sidebar" class="card">
       
        <h5 class="card-header personal-project-voting"><?php echo $vm_sidebarTitle; ?></h5>
        <div class="card-body">
                <?php the_excerpt(); ?>
                <div class="vm_alignFlex">
                    <a href="<?php echo wp_login_url(); ?>" class="btn btn-primary">
                        Přihlásit se
                    </a>&nbsp;
                    <a href="<?php echo get_site_url() . '/registrace'; ?>" >Registrace</a>
                </div>
        </div>
        
    </div>
  
  </div>
  <?php endif; ?>

</div>
<!-- /.row -->

</div>
<!-- /.container -->

<?php
get_footer();
