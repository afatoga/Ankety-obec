<?php 
  get_header();
?>
<div class='container'>

  <div class='row'>

    <div class='col-xl-12'>
      <div class='row'>
        <div class='col-xl-5 col-md-12 p-0 text-center vm_frontPageBlock'>
          <?php the_post_thumbnail(); ?>
        </div>

        <div class='col-xl-7 vm_frontPagePadding col-md-12 mx-auto my-3 mt-md-5 pb-2'>
          <?php if (have_posts()) : while (have_posts()) : the_post();
              the_content();
            endwhile;
          else : ?>
            <p>Daná stránka neexistuje.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

</div>

<?php
get_footer();
