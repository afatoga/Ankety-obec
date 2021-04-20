<?php

// $polls_parent_category = get_term_by('slug', 'ankety', 'category');
// $poll_post_categories = get_term_children($polls_parent_category->term_id, 'category');
// $polls_results = [];
$error = null;

if (!empty($_POST)) {

  if (isset($_POST['deactivate_poll'])) {
    set_theme_mod('polls_status', 'disabled');
  } else if (empty($_POST['post_tax_id'])) {
    $error = "Zadejte ročník";
    set_theme_mod('polls_status', 'disabled');
    set_theme_mod('polls_post_tax_id', 'false');
  } else {

    if (isset($_POST['activate_poll']) && get_theme_mod('polls_post_tax_id') === 'false') {
      $error = "Zadejte ročník";
    } else {

      set_theme_mod('polls_status', 'active');
      $max_votes_to_spend = filter_var($_POST["max_votes_to_spend"], FILTER_VALIDATE_INT);
      if ($max_votes_to_spend) set_theme_mod('polls_available_votes_count', $max_votes_to_spend);

      set_theme_mod('polls_post_tax_id', $_POST['post_tax_id']);
    }
  }
  //   $post_category = filter_var($_POST["post_category"], FILTER_VALIDATE_INT);

  //   if ($post_category && array_search($post_category, $poll_post_categories, true) !== false) {

  //     global $wpdb;

  //     $contender_posts = $wpdb->get_col("
  //   SELECT ID
  //   FROM $wpdb->posts
  //   LEFT JOIN  $wpdb->term_relationships as t ON ID = t.object_id
  //   WHERE post_type = 'post'
  //   AND post_status = 'publish'
  //   AND t.term_taxonomy_id = $post_category
  // ");

  //     //array of objects prepared for counting $polls_results
  //     foreach ($contender_posts as $key => $post) {
  //       $polls_results[$key]["id"] = $post;
  //       $polls_results[$key]["result"] = 0;
  //     }

  //     $votes = $wpdb->get_col("
  // SELECT submission_value
  // FROM {$wpdb->prefix}polls
  // ");

  //     foreach ($votes as $single_vote) {
  //       $list = explode(",", $single_vote);

  //       foreach ($polls_results as &$post) {
  //         if (in_array($post["id"], $list, false)) {
  //           $post["result"] += 1;
  //         }
  //       }
  //     }
  //   }

  //   $error = true;

}

?>
<div class="container no-gutters">
  <div class="jumbotron">
    <h2>Nastavení pluginu - <?php _e('Participatory Budgeting', 'participatory-budgeting'); ?></h2>

    <form class="mt-4" action="<?php echo site_url('/wp-admin/admin.php?page=participatory-budgeting'); ?>" method="post">
      <div class="form-group">
        <label for="cpt_slug">CPT slug:</label>
        <div>
          <select name="cpt_slug" id="cpt_slug">
            <option value="<?php echo get_theme_mod('polls_cpt_slug', 'navrh'); ?>"><?php echo get_theme_mod('polls_cpt_slug', 'navrh'); ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="tax_slug">Taxonomy slug:</label>
        <div>
          <select name="tax_slug" id="tax_slug">
            <option value="<?php echo get_theme_mod('polls_cpt_taxonomy_slug', 'rocnik'); ?>"><?php echo get_theme_mod('polls_cpt_taxonomy_slug', 'rocnik'); ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="post_tax_id">Vyberte aktuální ročník:</label>
        <div>
          <select name="post_tax_id" id="post_tax_id">
            <option value="">-</option>
            <?php

            $tax_terms = get_terms([
              'taxonomy' => get_theme_mod('polls_cpt_taxonomy_slug', 'rocnik'), //internationalize
              'hide_empty' => false,
            ]);

            foreach ($tax_terms as $term) {
              $is_selected = (intval($term->term_id) === (int) get_theme_mod('polls_post_tax_id')) ? "selected" : "";
              echo '<option value="' . $term->term_id . '" ' . $is_selected . '>' . $term->name . '</option>';
            }

            ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label for="post_tax_id">Maximální počet hlasů k rozdání</label>
        <div>
          <input type="number" name="max_votes_to_spend" value="<?php echo get_theme_mod('polls_available_votes_count', 1); ?>" step="1" min="0">
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Uložit</button>
      <?php if (get_theme_mod('polls_status') === "disabled") : ?>
        <button type="submit" class="btn btn-success ml-5" name="activate_poll">Zapnout hlasování</button>
      <?php else : ?>
        <button type="submit" class="btn btn-secondary ml-5" name="deactivate_poll">Vypnout hlasování</button>
      <?php endif; ?>

    </form>
  </div>

  <?php if (get_theme_mod('polls_status') === "disabled") : ?>
    <div class="mt-4 alert alert-info" role="alert">
      Hlasování je vypnuto
    </div>
  <?php else : ?>
    <div class="mt-4 alert alert-success" role="alert">
      Hlasování je zapnuto
    </div>
  <?php endif; ?>

  <?php if (!empty($polls_results)) : ?>

    <table class="table mt-4">
      <thead class="thead-light">
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Název příspěvku</th>
          <th scope="col">Počet hlasů</th>
          <!-- <th scope="col">Handle</th> -->
        </tr>
      </thead>
      <tbody>
        <?php //foreach ($polls_results as &$post) : 
        ?>
        <tr>
          <th scope="row"><?php //echo $post["id"]; 
                          ?></th>
          <td><a href="<?php //echo site_url() . '/wp-admin/post.php?post=' . $post["id"] . '&action=edit'; 
                        ?>" target="_blank"><?php// echo get_the_title($post["id"]); ?></a></td>
          <td><?php/// echo $post["result"]; ?></td>
        </tr>
        <?php //endforeach; 
        ?>
      </tbody>
    </table>

  <?php
  elseif ($error) : ?>

    <div class="mt-4 alert alert-danger" role="alert">
      <?= $error ?>
    </div>

  <?php endif; ?>
</div>