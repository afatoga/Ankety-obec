<?php get_header(); ?>

    <div class="container <?php if (is_user_logged_in()) echo 'col-lg-10'; ?>">
        <div class="row">
            <div class="col-xl-8">

                <h1 class="mt-4"><?php single_cat_title();?></h1>

                <?php  
                    $categoryId = get_queried_object_id();
                    $same_category = new WP_Query(array(
                        'cat'            => get_query_var('cat'),
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'posts_per_page' => '-1'
                    ));
                    
                    $vm_pollRules =  get_page_by_title( "Projekty pravidla", OBJECT, "page");
                    $vm_pollRulesContent = $vm_pollRules->post_content;
                    $vm_allowedVotesCount = get_post_meta($vm_pollRules->ID, "vm_allowedVotesCount", true);
                    $vm_voteCountExceededEcho = get_post_meta($vm_pollRules->ID, "vm_voteCountExceededEcho", true);
                    $vm_successSectionEcho = get_post_meta($vm_pollRules->ID, "vm_successSectionEcho", true);
                    $vm_warningSectionEcho = get_post_meta($vm_pollRules->ID, "vm_warningSectionEcho", true);
                    $vm_projectsNotFound = get_post_meta($vm_pollRules->ID, "vm_projectsNotFound", true);

                    if(is_category($category='projekty') && $same_category->have_posts()):
                ?>
                    <div class="mt-4 pb-2">
                        <p id="vm_projectsPageDescription">
                            <?php echo $vm_pollRulesContent; ?>
                            <div class="mt-2"> 
                                Počet hlasů: <span id='vm_allowedVotesCount'><?php echo $vm_allowedVotesCount ?></span>
                                <span id='vm_voteCountExceededEcho'><?php echo $vm_voteCountExceededEcho ?></span> 
                            </div>
                        </p>
                    </div>

                    <div class="vm_projectList pb-4">

                        <?php
                            while ( $same_category->have_posts() ) : $same_category->the_post(); 
                        ?>
                            <hr>
                            <div id='project_<?php the_ID();  ?>' class='vm_projectItem p-3<?php if( $same_category->current_post %2 == 1 ){ echo ' vm_evenItem';}?>'>
                                <h2><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></h2>

                                <div class="row mt-4 pb-4">
                                    <div class="col-lg-4 col-md-12 vm_categoryItemImage">
                                        <?php the_post_thumbnail();  ?>
                                    </div>
                                    <div class="<?php if(is_user_logged_in()): ?>col-lg-5 col-md-8 col-sm-6<?php else: ?>col-lg-8 col-md-12<?php endif; ?>">
                                        <?php the_excerpt(); ?>

                                            <div class="vm_see-more"><a href="<?php echo get_permalink(); ?>">Zobrazit více...</a></div>

                                    </div>
                                        <?php if(is_user_logged_in() && (!getPreviousUserVote() || canUserRemakeVote())): ?>
                                        <div class="col-lg-3 col-md-4 col-sm-6 vm_projectVoteSection">
                                            <p style="font-weight: 700; font-size: 1.2rem">Hlasování</p>

                                            <form method="post">

                                                <div class="form-group">
                                                    <button class="btn btn-success vm_voteButton" id="voteButton_<?php the_ID();  ?>" type="button" onclick="changeVote(<?php the_ID(); ?>);return false">Vybrat</button>
                                                </div>
                                            </form>
                                        </div>
                                        <?php endif; ?>
                                </div>
                            </div>
                        <?php   endwhile;
                                wp_reset_query();
                        ?>
                    </div>

                <?php 
                    elseif (is_category($category='projekty')): echo '<p class="mt-4">' . $vm_projectsNotFound . '</p>';
                    endif;
                ?>

            </div>
                
                <?php if($same_category->have_posts()): ?>
                    <!-- right sidebar-->
                    <div class="col-md-12 col-xl-4">

                        <div id="sidebar" class="card my-4">
                            <?php if(is_category($category='projekty') && is_user_logged_in() && $same_category->have_posts()): ?>
                                <h5 class="card-header personal-project-voting">Stav mého hlasovaní</h5>
                                <div class="card-body">

                                    <div id="vm_voteStateSection">
                                        <p class="vm_currentUserVote" id="vm_newUserVote">
                                            <?php loadCurrentVoteState(); ?>
                                                <?php if(!getPreviousUserVote()): ?>
                                                    Dosud jste nehlasoval(a).
                                                <?php endif; ?>
                                        </p>

                                        <?php if(!getPreviousUserVote() || canUserRemakeVote()): ?>
                                            <div class="saveVoting">
                                                <button type="button" class="btn btn-primary confirmUserVote" onclick="confirmUserVote();return false">
                                                    <?php if(canUserRemakeVote()): ?>Změnit hlasování
                                                    <?php else: ?>Potvrdit hlasovaní
                                                    <?php endif; ?>
                                                </button>
                                            </div>
                                        <?php endif;?>

                                            <div class="alert alert-info confirmSection" role="alert">
                                                <h4 class="alert-heading">Potvrďte hlasování</h4>
                                                <p>Prosím, potvrďte své hlasování kliknutím na tlačítko:</p>
                                                <button type="button" class="btn btn-secondary" onclick="cancelUserVote();return false">Zrušit</button>
                                                <button type="button" class="btn btn-primary checkRating" onclick="checkVote();return false">Potvrdit</button>
                                            </div>
                                    </div>
                                    <div id="vm_successSection" class="alert alert-success mt-4" role="alert">
                                        <h4 class="alert-heading">Hlasování bylo uloženo</h4>
                                        <p>
                                            <?php echo $vm_successSectionEcho;  ?>
                                        </p>
                                    </div>

                                    <div id="vm_warningSection" class="alert alert-warning mt-4" role="alert">
                                        <h4 class="alert-heading">Hlasování bylo anulováno</h4>
                                        <p>
                                            <?php echo $vm_warningSectionEcho;  ?>
                                        </p>
                                    </div>

                                    <div id="vm_alert-danger" class="vm_error-label vm_sidebar-alert alert alert-danger" style="display: none">
                                    </div>
                                    <div id="vm_alert-warning" class="vm_error-label vm_sidebar-alert alert alert-warning" style="display: none">
                                    </div>
                                    <img id="vm_loadingAnimation" src="<?php echo get_template_directory_uri() . '/img/loading.gif' ?>" alt="loading">
                                </div>
                            <?php else: ?>
                                <h5 class="card-header personal-project-voting">Hlasování</h5>
                                <div class="card-body">
                                    <p>Hlasovat mohou pouze příhlašení uživatelé.</p>
                                    <div class="vm_alignFlex">
                                        <a href="<?php echo wp_login_url(get_term_link($categoryId)); ?>" class="btn btn-primary">Přihlásit se</a>&nbsp;<a href="<?php echo get_site_url() . '/registrace'; ?>">Registrace</a>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <!-- end right sidebar-->
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
    </div>

</div>        
</div>

<?php

    if(is_category($category='projekty')) 
    {
        wp_enqueue_script( 'projectVoting-script', get_template_directory_uri() . '/js/vm_projectVoting.js', '', '', true);
        wp_localize_script( 'projectVoting-script', 'saveUserVoteAjax', [ 'ajax_url' => admin_url( 'admin-ajax.php' ) ] );
    }
    get_footer();
?>