<?php 

/* Template Name: vm_userPostSubmit */

get_header();
the_post();
$vm_userMustBeLoggedIn = get_post_meta(get_the_ID(), "vm_userMustBeLoggedIn", true);
?>


<div class='container'>
    <div class='row'>
        <div class='col-lg-8'>
            <div class="mt-4 pb-4">
            <h1 class='mt-4'><?php single_post_title(); ?></h1>
            
               
                        

<?php if (function_exists('user_submitted_posts') && is_user_logged_in()): ?>
    <div class='mt-4'><?php the_content(); ?></div>

<?php   
user_submitted_posts();
else:
?>
            <p class="mt-4 pb-2"><?php echo $vm_userMustBeLoggedIn; ?></p>
             <div class="vm_alignFlex">
               <a href="<?php echo wp_login_url(get_permalink(get_the_ID())); ?>" class="btn btn-primary">Přihlásit se</a>
               &nbsp;
               <a href="<?php echo get_site_url(null,'/registrace');  ?>">Registrace</a>
             </div>
<?php endif; ?>

            </div>
        </div>        
    </div>
</div>

<?php

get_footer();