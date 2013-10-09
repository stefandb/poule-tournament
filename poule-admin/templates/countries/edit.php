<div class='wrap'>
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">

		<h2><?php _e("Edit country", "poule-system") ?></h2>

		<?php if ($country != NULL): ?>

			<div id="wpt_settings_page" class="postbox-container" style="width: 100%">

				<div class="metabox-holder">

					<div class="ui-sortable meta-box-sortables">
						<div class="postbox">
							<h3><?php _e('Country name', 'poule-system') ?></h3>
							<div class="inside wpt-settings">

								<div id="wtt_authentication_display">
									<input type="text" name="country" size="20" class="<?php echo $error; ?>" value="<?php echo $country['name'] ?>" placeholder="<?php _e('Country name', 'poule-system') ?>"/>
								</div>

							</div>
						</div>
					</div>

				</div>
			</div>

			<div class="panel-heading">
				<input type="submit" class="button-primary" name="save" value="<?php _e('Save', 'poule-system') ?>"/>
				<input type="submit" class="button-primary" name="delete" value="<?php _e('delete', 'poule-system') ?>"/>
				<a href="#" class="button-secondary"><?php _e('Cancel', 'poule-system') ?></a>
			</div>
		<?php else: ?>
			<div id="message" class="error"><p> <?php _e('Country do not exist', 'poule-system') ?> </p></div>;
		<?php endif; ?>
	</form>
</div>