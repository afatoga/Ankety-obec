<?php get_header(); ?>


<div class="container <?php if(is_user_logged_in()): ?>col-lg-10<?php endif; ?>">
    <div class="row">
        <div class="col-xl-8">

            <h1 class="mt-4"><?php single_cat_title();?></h1>
            <!-- //echo category_description(); -->



            <?php if(is_category($category='projekty')): ?>
            <div class="px-3 mt-4 pb-2">
            <p class="lead">
                <?php 
            $queried_post =  get_page_by_title( "Projekty Pravidla", OBJECT, "page");
            $content = $queried_post->post_content;
            echo $content;
            ?>
            </p>
            </div>

            <div class="vm_projectList pb-4">

    <?php
        // The Query
        $categoryId = get_queried_object_id();
        $same_category = new WP_Query(array(
            'cat'            => get_query_var('cat'),
            'orderby'        => 'date',
            'order'          => 'DESC',
            'posts_per_page' => '-1'
        ));
            
    while ( $same_category->have_posts() ) : $same_category->the_post(); 
            //    $customFields = get_post_custom(); ?>
      <hr>
            <div id='project_<?php the_ID();  ?>' class='vm_projectItem p-3<?php if( $same_category->current_post%2 == 1 ){ echo ' vm_evenItem';}?>'>
                <h2><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h2>

                <div class="row mt-4 pb-4">
                    <div class="col-lg-4 col-md-12 vm_categoryItemImage mx-auto">
                        <?php the_post_thumbnail();  ?>
                    </div>
                    <div class="<?php if(is_user_logged_in()): ?>col-lg-5 col-md-8 col-sm-6<?php else: ?>col-lg-8 col-md-12<?php endif; ?>">
                    <?php the_excerpt(); ?>
            
                        <div class="vm_see-more"><a href="<?php echo get_permalink(); ?>">Zobrazit více</a></div>

                    </div>
                    <?php if(is_user_logged_in()): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                            <p style="font-weight: bold">Hlasování</p>

                            <form method="POST">

                                <div class="form-group">

                                        <label for="rating_<?php the_ID();  ?>">Ohodnoťte body od&nbsp;-1&nbsp;do&nbsp;2</label><br />
                                        <div id='input-group-project_<?php the_ID(); ?>' class='input-group mb-3'>
                                                
                                            <div class="input-group-prepend">
                                                <button type="button" class="minusRating btn btn-danger"
                                                    onclick="changeRating(<?php the_ID(); ?>,'sub');return false">-</button>
                                            </div>
                                            
                                            <input type="text" class="rating form-control" value="0" min="-2" max="2"
                                                name="rating_<?php the_ID(); ?>" id="rating_<?php the_ID(); ?>">
                                            
                                        
                                            <div class="input-group-append">
                                                <button type="button" class="plusRating btn btn-success"
                                                    onclick="changeRating(<?php the_ID(); ?>,'add');return false">+</button>
                                            </div>
                                        </div>
                                    
                                </div>
                            </form>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            

        <?php endwhile;
 
	  // Reset Query
	  wp_reset_query();
 
      ?>

        <?php if(!$same_category->have_posts()): ?>
        Žádné projekty nebyly nalezeny
    <?php endif; ?>

    </div>

        </div>
        <?php if($same_category->have_posts()): ?>
        <!-- right sidebar-->

        <div class="col-md-12 col-xl-4">

            <div id="sidebar" class="card my-4">
                <?php if(is_category($category='projekty') && is_user_logged_in() && $same_category->have_posts()): ?>
                <h5 class="card-header personal-project-voting">Moje hlasovaní</h5>
                <div class="card-body">
                    
                    <p class="vm_currentUserVote" id="vm_newUserVote">
                        <?php loadCurrentVoteState(); ?>

                        </p>
                            
                         <?php   if(existsUserVote()): ?>
                        <div class="saveVoting">
                            <button type="button" class="btn btn-primary confirmUserVote"
                                onclick="confirmUserVote();return false">Změna hlasovaní</button>
                        </div>
                        <?php else: ?>
                        <div class="saveVoting">
                            <button type="button" class="btn btn-primary confirmUserVote"
                                onclick="confirmUserVote();return false">Uložit hlasovaní</button>
                        </div>
                        <?php endif;?>

                        <div class="alert alert-info confirmSection" role="alert">
                            <h4 class="alert-heading">Potvrďte hlasování</h4>
                            <p>Prosím, potvrďte své hlasování kliknutím na tlačítko:</p>
                            <button type="button" class="btn btn-secondary" onclick="cancelUserVote();return false">Zrušit</button>
                            <button type="button" class="btn btn-primary checkRating"
                                onclick="checkRating();return false">Potvrdit</button>
                        </div>

                        <div id="vm_successSection" class="alert alert-success mt-4" role="alert">
                            <h4 class="alert-heading">Hlasování bylo uloženo</h4>
                            <p>Děkujeme za Vaši účast v hlasování o projektech. Změnu můžete provést během 24 hodin.</p>
                        </div>

                        
                    </p>

                    <div class="vm_error-label vm_sidebar-alert alert alert-danger" style="display: none">

                    </div>
                </div>
                <?php else: ?>
                <h5 class="card-header personal-project-voting">Hlasování</h5>
                <div class="card-body">
                        <p>Hlasovat mohou pouze příhlašení uživatelé.</p>
                        <div class="vm_alignFlex">
                            <a href="<?php echo wp_login_url(get_term_link($categoryId)); ?>" class="btn btn-primary">
                                Přihlásit se
                            </a>&nbsp;
                            <a href="<?php echo get_site_url() . '/registrace'; ?>" >Registrace</a>
                        </div>
                </div>
                </div>
                </div>
                <?php endif; ?>
                
            </div>
            <?php endif; ?>

        </div>

        <!-- end right sidebar -->
    <?php endif; ?>

    </div>
</div>

<?php

if(is_category($category='projekty')) {
    wp_enqueue_script( 'projectVoting-script', get_template_directory_uri() . '/js/vm_projectVoting.js', '', '', true);
    wp_localize_script( 'projectVoting-script', 'checkUserVoteAjax', [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ] );
}
get_footer();