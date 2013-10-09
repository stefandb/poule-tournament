<div class='wrap'>
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">

		<h2><?php _e("Add country", "poule-system") ?></h2>

		<div id="wpt_settings_page" class="postbox-container" style="width: 100%">

			<div class="metabox-holder">

				<div class="ui-sortable meta-box-sortables">
					<div class="postbox">
						<h3><?php _e('Country name', 'poule-system') ?></h3>
						<div class="inside">

							<div id="wtt_authentication_display">
								<input type="text" name="country" size="20" class="<?php echo $error; ?>" value="<?php echo $_POST['country'];?>" placeholder="<?php _e('Country name', 'poule-system') ?>"/>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>

		<button type="submit" class="button-primary"><?php _e('Save', 'poule-system') ?></button>
		<a href="#" class="button-secondary"><?php _e('Cancel', 'poule-system') ?></a>

	</form>
</div>