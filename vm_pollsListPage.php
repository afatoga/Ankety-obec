<?php

/* Template Name: vm_pollListPage */

global $wpdb;

$table_name = "{$wpdb->prefix}posts";

$pollList = $wpdb->get_results("SELECT post_title, post_name
   FROM `$table_name`
   WHERE post_status = 'publish'
   AND post_type = 'torro_form'
   ");


get_header();
the_post();
?>


<div class='container'>
    <div class='row'>
        <div class='col-lg-8'>
            <h1 class='mt-4'><?php single_post_title(); ?></h1>
            <div class='mt-4' style="font-size: 1.4rem;">

                <p class="font-weight-bold mb-2">Seznam probíhajících anket:</p>
                <?php

                if (!empty($pollList)) {
                    foreach ($pollList as $poll) {
                        echo '<p class="ml-2"><a href="/ankety/' . $poll->post_name . '">' . $poll->post_title . '</a></p>';
                    }
                } else echo "<p>Litujeme, ale momentálně žádná neprobíha</p>";

                ?>
            </div>
        </div>

    </div>
</div>

<?php
get_footer();
