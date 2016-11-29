<?php $options = ccAddonPack_get_option(); ?>
<div class="wrap">
	<h2>CC Addon Pack <?php __( 'Setting', 'cc-addon-pack' ); ?></h2>
	<?php if ( isset($_REQUEST['settings-updated']) && false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php _e( 'Settings saved.', 'cc-addon-pack' ); ?></strong></p></div>
	<?php endif; ?>

	<form method="post" action="options.php">
		<?php settings_fields( 'ccAddonPack_options_fields' ); ?>

		<div class="tab-v2">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#base" data-toggle="tab"><?php _e( 'Base Setting', 'cc-addon-pack' ); ?></a></li>
				<li><a href="#design" data-toggle="tab"><?php _e( 'Design Setting', 'cc-addon-pack' ); ?></a></li>
				<li><a href="#toppage" data-toggle="tab"><?php _e( 'Toppage Setting', 'cc-addon-pack' ); ?></a></li>
				<li><a href="#contact" data-toggle="tab"><?php _e( 'Contact Setting', 'cc-addon-pack' ); ?></a></li>
				<li><a href="#ga" data-toggle="tab"><?php _e( 'GA & SEO Setting', 'cc-addon-pack' ); ?></a></li>
				<li><a href="#sns" data-toggle="tab"><?php _e( 'SNS Setting', 'cc-addon-pack' ); ?></a></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane fade in active" id="base">
					<table class="form-table">
						<tr>
							<th><?php _e( 'Emoji', 'cc-addon-pack' ); ?></th>
							<td>
								<p><input type="checkbox" name="ccAddonPack_options[disabled_emoji]" id="disabled_emoji" value="true" <?php checked( $options['disabled_emoji'] == 'true' ); ?> /><?php _e( 'Disabled emoji', 'cc-addon-pack' ); ?></p>
								<span>* <?php _e( 'If you want to delete the pictogram for the code that is automatically inserted, you activate this item.', 'cc-addon-pack' ); ?></span>
							</td>
						</tr>
						<tr>
							<th>Bootstrap</th>
							<td>
								<p><input type="checkbox" name="ccAddonPack_options[active_bootstrap]" id="active_bootstrap" value="true" <?php checked( $options['active_bootstrap'] == 'true' ); ?> /><?php _e( 'Print Bootstrap css', 'cc-addon-pack' ); ?></p>
								<span>* <?php _e( 'If your using theme has already including Bootstrap, you deactivate this item.', 'cc-addon-pack' ); ?></span>
							</td>
						</tr>
						<tr>
							<th>FontAwesome</th>
							<td>
								<p><input type="checkbox" name="ccAddonPack_options[active_fontawesome]" id="active_fontawesome" value="true" <?php checked( $options['active_fontawesome'] == 'true' ); ?> /><?php _e( 'Print link fontawesome', 'cc-addon-pack' ); ?></p>
								<span>* <?php _e( 'If your using theme has already including FontAwesome, you deactivate this item.', 'cc-addon-pack' ); ?></span>
							</td>
						</tr>
					</table>
				</div>

				<div class="tab-pane fade in" id="design">
					<table class="form-table">
						<tr>
							<th><?php _e( 'Favicon', 'cc-addon-pack' ); ?></th>
							<td>
								<input type="text" name="ccAddonPack_options[favicon]" id="favicon" value="<?php echo esc_attr($options['favicon']); ?>" />
								<button id="media_favicon" class="media_btn"><?php _e( 'Select Image', 'cc-addon-pack' ); ?></button>
							</td>
						</tr>
						<tr>
							<th><?php _e( 'Default Thumbnail Image', 'cc-addon-pack' ); ?></th>
							<td>
								<span id="default_thumbnail_image" class="img-thumbnail">
									<?php echo wp_get_attachment_image($options['default_thumbnail']); ?>
								</span>
								<input type="hidden" name="ccAddonPack_options[default_thumbnail]" id="default_thumbnail" value="<?php echo esc_attr($options['default_thumbnail']); ?>" />
								<button id="media_default_thumbnail" class="media_btn"><?php _e( 'Select Image', 'cc-addon-pack' ); ?></button>
							</td>
						</tr>
					</table>
				</div>

				<div class="tab-pane fade in" id="toppage">
					<table class="form-table">
						<tr>
							<th rowspan="5"><?php _e( 'Topic Area', 'cc-addon-pack' ); ?> 1</th>
							<td>
								<p><?php _e( 'Title', 'cc-addon-pack' ); ?></p>
								<input type="text" name="ccAddonPack_options[topic1_title]" id="topic1_title" value="<?php echo esc_attr($options['topic1_title']); ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<p><?php _e( 'English Title', 'cc-addon-pack' ); ?></p>
								<input type="text" name="ccAddonPack_options[topic1_subtitle]" id="topic1_subtitle" value="<?php echo esc_attr($options['topic1_subtitle']); ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<p><?php _e( 'Description', 'cc-addon-pack' ); ?></p>
								<textarea cols="80" rows="3" name="ccAddonPack_options[topic1_desc]" id="topic1_desc"><?php echo esc_attr($options['topic1_desc']); ?></textarea>
							</td>
						</tr>
						<tr>
							<td>
								<p><?php _e( 'Link URL', 'cc-addon-pack' ); ?></p>
								<input type="text" name="ccAddonPack_options[topic1_link]" id="topic1_link" value="<?php echo esc_attr($options['topic1_link']); ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<p><?php _e( 'Image URL', 'cc-addon-pack' ); ?></p>
								<input type="text" name="ccAddonPack_options[topic1_img]" id="topic1_img" value="<?php echo esc_attr($options['topic1_img']); ?>" />
								<button id="media_topic1_img" class="media_btn"><?php _e( 'Select Image', 'cc-addon-pack' ); ?></button>
							</td>
						</tr>
						<tr>
							<th rowspan="5"><?php _e( 'Topic Area', 'cc-addon-pack' ); ?> 2</th>
							<td>
								<p><?php _e( 'Title', 'cc-addon-pack' ); ?></p>
								<input type="text" name="ccAddonPack_options[topic2_title]" id="topic2_title" value="<?php echo esc_attr($options['topic2_title']); ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<p><?php _e( 'English Title', 'cc-addon-pack' ); ?></p>
								<input type="text" name="ccAddonPack_options[topic2_subtitle]" id="topic2_subtitle" value="<?php echo esc_attr($options['topic2_subtitle']); ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<p><?php _e( 'Description', 'cc-addon-pack' ); ?></p>
								<textarea cols="80" rows="3" name="ccAddonPack_options[topic2_desc]" id="topic2_desc"><?php echo esc_attr($options['topic2_desc']); ?></textarea>
							</td>
						</tr>
						<tr>
							<td>
								<p><?php _e( 'Link URL', 'cc-addon-pack' ); ?></p>
								<input type="text" name="ccAddonPack_options[topic2_link]" id="topic2_link" value="<?php echo esc_attr($options['topic2_link']); ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<p><?php _e( 'Image URL', 'cc-addon-pack' ); ?></p>
								<input type="text" name="ccAddonPack_options[topic2_img]" id="topic2_img" value="<?php echo esc_attr($options['topic2_img']); ?>" />
								<button id="media_topic2_img" class="media_btn"><?php _e( 'Select Image', 'cc-addon-pack' ); ?></button>
							</td>
						</tr>
					</table>
				</div>

				<div class="tab-pane fade in" id="contact">
					<table class="form-table">
						<tr>
							<th><?php _e( 'Address', 'cc-addon-pack' ); ?></th>
							<td>
								<textarea cols="60" rows="3" name="ccAddonPack_options[contact_address]" id="contact_address"><?php echo esc_attr($options['contact_address']); ?></textarea>
							</td>
						</tr>
						<tr>
							<th>E-Mail</th>
							<td>
								<input type="text" name="ccAddonPack_options[contact_email]" id="contact_email" value="<?php echo esc_attr($options['contact_email']); ?>" />
							</td>
						</tr>
						<tr>
							<th>TEL</th>
							<td>
								<input type="text" name="ccAddonPack_options[contact_tel]" id="contact_tel" value="<?php echo esc_attr($options['contact_tel']); ?>" />
							</td>
						</tr>
						<tr>
							<th>FAX</th>
							<td>
								<input type="text" name="ccAddonPack_options[contact_fax]" id="contact_fax" value="<?php echo esc_attr($options['contact_fax']); ?>" />
							</td>
						</tr>
					</table>
				</div>

				<div class="tab-pane fade in" id="ga">
					<table class="form-table">
						<tr>
							<th><?php _e( 'Google Analytics', 'cc-addon-pack' ); ?></th>
							<td>
								<span><?php _e( 'Please fill in the Google Analytics ID from the Analytics embed code used in the site.', 'cc-addon-pack' ); ?></span>
								<p>UA-<input type="text" name="ccAddonPack_options[gaId]" id="gaId" value="<?php echo esc_attr($options['gaId']); ?>" /></p>
							</td>
						</tr>
						<tr>
							<th><?php _e( 'Meta Keyword', 'cc-addon-pack' ); ?></th>
							<td>
								<p><input type="checkbox" name="ccAddonPack_options[active_meta_keyword]" id="active_meta_keyword" value="true" <?php checked( $options['active_meta_keyword'] == 'true' ); ?> /><?php _e( 'Print Meta keyword', 'cc-addon-pack' ); ?></p>
								<span><?php _e( 'Keywords for meta tag. This words will set Meta Keyword with post keywords. if you want multiple keywords, enter with separator of ",".','cc-addon-pack' ); ?></span>
								<p><input type="text" name="ccAddonPack_options[common_keywords]" id="common_keywords" value="<?php echo esc_attr($options['common_keywords']); ?>" /></p>
								<span>* <?php _e( 'For each page individual keyword is enter at the edit screen of each article.', 'cc-addon-pack' ); ?></span>
							</td>
						</tr>
						<tr>
							<th><?php _e( 'Meta Description', 'cc-addon-pack' ); ?></th>
							<td>
								<p><input type="checkbox" name="ccAddonPack_options[active_meta_description]" id="active_meta_description" value="true" <?php checked( $options['active_meta_description'] == 'true' ); ?> /><?php _e( 'Print Meta description', 'cc-addon-pack' ); ?></p>
								<p><textarea cols="80" rows="3" name="ccAddonPack_options[common_description]" id="common_description"><?php echo esc_attr($options['common_description']); ?></textarea></p>
								<span>* <?php _e( 'For each page individual description is enter at the edit screen of each article.', 'cc-addon-pack' ); ?></span>
							</td>
						</tr>
					</table>
				</div>

				<div class="tab-pane fade in" id="sns">
					<table class="form-table">
						<tr>
							<th><?php _e( 'Facebook Page URL', 'cc-addon-pack' ); ?></th>
							<td>
								<input type="text" name="ccAddonPack_options[fburl]" id="fburl" value="<?php echo esc_attr($options['fburl']); ?>" />
							</td>
						</tr>
						<tr>
							<th><?php _e( 'Twitter Account', 'cc-addon-pack' ); ?></th>
							<td>
								@ <input type="text" name="ccAddonPack_options[twId]" id="twId" value="<?php echo esc_attr($options['twId']); ?>" />
							</td>
						</tr>
						<tr>
							<th><?php _e( 'OG Tag', 'cc-addon-pack' ); ?></th>
							<td>
								<p><input type="checkbox" name="ccAddonPack_options[active_og]" id="active_og" value="true" <?php checked( $options['active_og'] == 'true' ); ?> /><?php _e( 'Print OG Tag', 'cc-addon-pack' ); ?></p>
							</td>
						</tr>
						<tr>
							<th><?php _e( 'OGP Image', 'cc-addon-pack' ); ?></th>
							<td>
								<input type="text" name="ccAddonPack_options[ogimg]" id="ogimg" value="<?php echo esc_attr($options['ogimg']); ?>" />
								<button id="media_ogimg" class="media_btn"><?php _e( 'Select Image', 'cc-addon-pack' ); ?></button>
							</td>
						</tr>
					</table>
				</div>

			</div><!-- .tab-content -->
		</div> <!-- .tab-v2 -->

		<?php submit_button(); ?>

	</form>
</div>