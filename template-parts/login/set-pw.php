<div id="main-content">
    <div class="et-l et-l--body">
		<div class="et_builder_inner_content et_pb_gutters3">
			<div class="et_pb_section et_pb_section_0_tb_body et_section_regular page-header login-header">
				<div class="et_pb_row et_pb_row_0_tb_body">
					<div class="et_pb_column et_pb_column_4_4 et_pb_column_0_tb_body  et_pb_css_mix_blend_mode_passthrough et-last-child">
						<div class="et_pb_module et_pb_text et_pb_text_0_tb_body page-title  et_pb_text_align_left et_pb_bg_layout_light">
							<div class="et_pb_text_inner">
								Nastavení nového hesla
							</div>
						</div><!-- .et_pb_text -->
						<div class="et_pb_module et_pb_divider et_pb_divider_0_tb_body et_pb_divider_position_center et_pb_space">
							<div class="et_pb_divider_internal"></div>
						</div>
					</div><!-- .et_pb_column -->
				</div><!-- .et_pb_row -->		
			</div><!-- .et_pb_section -->
			
			<div class="et_pb_section et_pb_section_1_tb_body et_section_regular page-section">
				<div class="et_pb_row et_pb_row_1_tb_body page-row">
					<div class="et_pb_column et_pb_column_4_4 et_pb_column_0  et_pb_css_mix_blend_mode_passthrough et-last-child">
						<div class="et_pb_module et_pb_login et_pb_login_0 clearfix  et_pb_text_align_left et_pb_bg_layout_dark">
							<div class=" et_pb_login_form">
								<form method="post" name="reset_password">
									<div class="et_pb_contact_form_field">
										<label class="et_pb_contact_form_label" for="password">Zadejte nové heslo</label>
										<input type="password" id="password" class="input form-control" name="password" placeholder="Nové heslo" required minlength="6" data-pristine-minlength-message="Heslo musí obsahovat minimálně 6 znaků" data-pristine-required-message="Zvolte si heslo">
									</div>
									<div class="et_pb_contact_form_field">
										<label class="et_pb_contact_form_label" for="password_retyped">Zopakujte nové heslo</label>
										<input type="password" id="password_retyped" class="input form-control" name="password_retyped" placeholder="Zopakujte nové heslo" data-pristine-equals="#password" data-pristine-equals-message="Hesla se neshodují">
									</div>
									<button type="submit" onclick='af_passResetForm_onSubmit(event)' class="et_pb_button">Uložit</button>
								</form>
							</div>
						</div>
					</div><!-- .et_pb_column -->
				</div><!-- .et_pb_row -->				
			</div><!-- .et_pb_section -->
		</div><!-- .et_builder_inner_content -->
	</div><!-- .et-l -->
</div>

<script>
    function af_passResetForm_onSubmit(event) {
		event.preventDefault()
		const form = document.querySelector("form[name='reset_password']")
		const pristine = new Pristine(form)
		const valid = pristine.validate()

		if (!valid) return false
		form.submit()
	}
</script>