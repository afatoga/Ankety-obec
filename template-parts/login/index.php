<div id="main-content">
    <div class="et-l et-l--body">
		<div class="et_builder_inner_content et_pb_gutters3">
			<div class="et_pb_section et_pb_section_0_tb_body et_section_regular page-header login-header">
				<div class="et_pb_row et_pb_row_0_tb_body">
					<div class="et_pb_column et_pb_column_4_4 et_pb_column_0_tb_body  et_pb_css_mix_blend_mode_passthrough et-last-child">
						<div class="et_pb_module et_pb_text et_pb_text_0_tb_body page-title  et_pb_text_align_left et_pb_bg_layout_light">
							<div class="et_pb_text_inner">
								Přihlášení
							</div>
						</div><!-- .et_pb_text -->
						<div class="et_pb_module et_pb_divider et_pb_divider_0_tb_body et_pb_divider_position_center et_pb_space">
							<div class="et_pb_divider_internal"></div>
						</div>
					</div><!-- .et_pb_column -->
				</div><!-- .et_pb_row -->		
			</div><!-- .et_pb_section -->

			
			
			<div class="et_pb_section et_pb_section_1_tb_body et_section_regular page-section">
				<?php if (!empty($args["alert"])): ?>
					<div class="et_pb_row et_pb_row_1_tb_body page-row alert <?php echo $args["alert"]["type"]; ?>">
						<div class="et_pb_module et_pb_text et_pb_text_align_left et_pb_bg_layout_light">
							<div class="et_pb_text_inner">
								<p>
									<?php echo $args["alert"]["message"]; ?>
								</p>
							</div>
						</div>
					</div>
				<?php endif; ?>
				<div class="et_pb_row et_pb_row_1_tb_body page-row">
					<div class="et_pb_column et_pb_column_4_4 et_pb_column_0  et_pb_css_mix_blend_mode_passthrough et-last-child">
						<div class="et_pb_module et_pb_login et_pb_login_0 clearfix  et_pb_text_align_left et_pb_bg_layout_dark">
							<div class=" et_pb_login_form">
								<form method="post" id="login_form">
									<div class="et_pb_contact_form_field">
										<label class="et_pb_contact_form_label" for="login">E-mail</label>
										<input id="login" type="email" class="input form-control" name="login" placeholder="E-mail" required data-pristine-required-message="Toto pole je povinné">
									</div>
									<div class="et_pb_contact_form_field">
										<label class="et_pb_contact_form_label" for="password">Heslo</label>
										<input id="password" type="password" class="input form-control" name="password" placeholder="Heslo" required data-pristine-required-message="Toto pole je povinné">
									</div>
									<button type="submit" name="login_form" class="et_pb_button">Přihlásit se</button>
									<p class="lost-pw">
										<a href="<?php echo get_site_url(null, "/" . $args["page_slug"]. "?akce=zapomenute-heslo"); ?>">Zapomněli jste heslo?</a>
										<a href="<?php echo site_url("/registrace", "https"); ?>">Nemáte ještě účet? Registrujte se.</a>
									</p>
								</form>
							</div>
						</div>
					</div><!-- .et_pb_column -->
				</div><!-- .et_pb_row -->				
			</div><!-- .et_pb_section -->
		</div><!-- .et_builder_inner_content -->
	</div><!-- .et-l -->
</div>