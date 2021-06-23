<?php /* Template Name: vm_custom-polls-page */

// laod posts by category
the_post();

// get category id of posts to display
$post_category_id = intval(get_field('post_category')[0]);

if (!$post_category_id) {
	wp_redirect(site_url());
	exit;
}


$post_list = $wpdb->get_col(
	"SELECT ID
				FROM $wpdb->posts
				LEFT JOIN  $wpdb->term_relationships as t ON ID = t.object_id
				WHERE post_type = 'post'
				AND post_status = 'publish'
				AND t.term_taxonomy_id = $post_category_id
				ORDER BY ID ASC"
);

$recaptchaSiteKey = "6LcwKJcaAAAAAGVQ-fqvrvBLhltNmWlWRH5du5C3";
wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . $recaptchaSiteKey, [], false, true);

$session_id = session_id(); //$_COOKIE["PHPSESSID"]

$inline_script = "
	const rest_api_root = '".esc_url_raw(rest_url() . 'aa_restserver/v1')."'
	const rest_api_site_url = '".site_url()."'
	const rest_api_user_key = '".$session_id."'
	const rest_api_posts_cat_id = '".$post_category_id."'
	const rest_api_poll_page_id = '".get_the_ID()."'
	const rest_api_recaptcha_site_key = '".$recaptchaSiteKey."'

	let favouriteItems = []

	function af_manageFavouriteItems(id, action) {

		let itemInList = false

		for( let i = 0, itemsCount = favouriteItems.length; i < itemsCount; i++){

			if ( favouriteItems[i] === id && action === 'remove') {
				favouriteItems.splice(i, 1)
				break
			}

			if (favouriteItems[i] === id && action === 'add') {
				itemInList = true
				break
			}
		}

		if (!itemInList && action === 'add') favouriteItems.push(id)

	}

	function af_chooseItem(id) {

		if (!document.getElementById('error_section').classList.contains('d-none')) document.getElementById('error_section').classList.toggle('d-none')

		const votingSection = document.getElementById('votingsection_'+id)
		const pollsItem = document.getElementById('pollsitem_'+id)

		if (pollsItem.classList.contains('active')) {
			af_manageFavouriteItems(id, 'remove');
		} else {
			af_manageFavouriteItems(id, 'add');
		}

		pollsItem.classList.toggle('active')

		const votingButtons = votingSection.children

		for (let i = 0; i<=1; i++) {
			votingButtons[i].classList.toggle('d-none')
		}
	}

	function af_submitMyVote(event) {

		event.preventDefault()
		document.getElementById('polls_vote_submission').setAttribute('disabled', '')

		grecaptcha.ready(function () {
			grecaptcha
			  .execute(rest_api_recaptcha_site_key, { action: 'submit' })
			  .then(function (token) {
				af_sendXHR(token)
			  });
		  })



	}

	function af_sendXHR(reCaptchaToken) {
		const activePollsItems = document.querySelectorAll('div.af_polls_item.active')

		let inputData = []
		for (let i = 0, itemsCount = activePollsItems.length; i < itemsCount; i++){
			inputData.push(parseInt(activePollsItems[i].dataset.postid))
		}

		if (!inputData.length) {
			document.getElementById('error_section').classList.toggle('d-none')
			document.getElementById('error_section').innerHTML = 'Neudělil(a) jste žádný hlas.'
			document.getElementById('polls_vote_submission').removeAttribute('disabled')
			return false
		}

		inputData = JSON.stringify({
			post_list: inputData,
			user_key: rest_api_user_key,
			form_id: rest_api_posts_cat_id,
			poll_id: rest_api_poll_page_id,
			g_recaptcha_response: reCaptchaToken
		})

		var xhr = new XMLHttpRequest();
		xhr.open('POST', rest_api_root + '/submit_polls_vote', true);
		xhr.setRequestHeader('Content-type','application/json; charset=utf-8');
		xhr.onload = function() {
			var data = JSON.parse(xhr.responseText);
			if (xhr.status === 201) {
				document.location = '/hlasovani-ulozeno'
			} else if (xhr.status >= 400) {
				document.getElementById('error_section').classList.toggle('d-none')
				document.getElementById('error_section').innerHTML = data.message
			}
			document.getElementById('polls_vote_submission').removeAttribute('disabled')
		};
		xhr.send(inputData);
	}

";
foreach ($post_list as $post_id) {
	$inline_script .= " new SimpleLightbox('.lightbox_" . $post_id . " a', { /* options */ }); ";
}

$inline_style = "
	p.item_title {
		font-size: 1.6rem;
	}

	div.af_polls_item.active {
		background-color: rgba(255, 221, 121, 1);
		/*background-color: rgba(255, 221, 121, 0.75);*/
	}

	div.af_polls_item {
		background-color: rgba(255, 255, 255, 1);
		/*background-color: rgba(199, 199, 199, 0.1);*/
	}

	div.active>div.af_star {
		background-color: #6c757d;
		color: #ffc107;
	}

	div.af_star {
		background-color: transparent;
		color: #000;
		width: 48px;
		height: 48px;
		margin: 0 auto;
	}

	span.dashicons {
		margin: 0.46rem 0.55rem;
    	font-size: 2rem;
	}

	.image_container>img{
		width: 100%;
    	height: 100%;
    	object-fit: cover;
    	overflow: hidden;
	}

	.image_container {
		width: 100%;
	}

	@media only screen and (min-width: 768px) {
		.image_container {
			max-width:295px !important;
			max-height:190px !important;
		}
	}
	
	
";

wp_enqueue_style('simplelightbox', get_stylesheet_directory_uri() . '/vendor/simplelightbox/simple-lightbox.min.css', [], false, false);
wp_enqueue_script('simplelightbox', get_stylesheet_directory_uri() . '/vendor/simplelightbox/simple-lightbox.min.js', [], false, true);
wp_add_inline_script('simplelightbox', $inline_script, 'after');
wp_add_inline_style('simplelightbox', $inline_style);

get_header(); ?>

<div class="container">
	
	<div class="rounded bg-white p-2">
		<?php the_content(); ?>
	</div>

	<form name="polls">

		<div class="form-group">
			<?php foreach ($post_list as $post_id) {
				$media = get_attached_media('image', $post_id);
			?>

				<div class="my-4 rounded shadow-sm af_polls_item p-2" id="pollsitem_<?php echo $post_id; ?>" data-postid="<?php echo $post_id; ?>">
					<p class="font-weight-bold item_title"><?php echo get_the_title($post_id); ?></p>

					<div class="row no-gutters mt-4">

						<div class="col-12 order-1 col-md-2 order-md-0 mt-2 mt-md-0 px-md-2 row align-items-center no-gutters" onclick="af_chooseItem(<?php echo $post_id; ?>)" id="votingsection_<?php echo $post_id; ?>">
							<div class="active d-none col">
								<div class="rounded-circle af_star">
									<span class="dashicons dashicons-star-filled"></span>
								</div>
								<button type="button" class="btn btn-secondary d-block mx-auto mt-2 w-100 w-md-75">Odebrat</button>
							</div>

							<div class="inactive col">
								<div class="rounded-circle af_star">
									<span class="dashicons dashicons-star-empty"></span>
								</div>
								<button type="button" class="btn btn-warning d-block mx-auto mt-2 w-100 w-md-75">Vybrat</button>
							</div>
						</div>

						<div class="col-12 col-md-10 row no-gutters lightbox_<?php echo $post_id; ?>">

							<?php

							$index = 0;

							if(has_post_thumbnail($post_id)) {
								$thumbnail_url = get_the_post_thumbnail_url($post_id);

								echo "<a class='pr-md-2 image_container col d-md-block' href='" . $thumbnail_url . "'><img class='img-fluid rounded' src='" . $thumbnail_url . "' alt='fotografie'></a>";

								$index++;
							}

							foreach ($media as $item) {
								if ($item->ID === get_post_thumbnail_id($post_id)) continue; // skip thumbnail

								$index++;
								$classes = "";
								if ($index > 1 && $index < 4) $classes .= " d-none d-md-block";
								if ($index > 3) $classes .= " d-none";

								echo "<a class='pr-md-2 image_container col" . $classes . "' href='" . $item->guid . "'><img class='img-fluid rounded' src='" . $item->guid . "' alt='fotografie'></a>";
							} ?>
						</div>
					</div>
				</div>

			<?php

			} ?>
		</div>

		<div class="d-none alert alert-danger" id="error_section" role="alert">
		</div>

		<button type="button" class="btn btn-primary btn-lg" id="polls_vote_submission" onclick='af_submitMyVote(event)' class="et_pb_button">Odeslat hlasování</button>
	</form>
</div>
<?php get_footer(); ?>