<div class="wrap">
	
	<?php if ( get_option( 'users_can_register' ) ) : ?>
		<?php echo apply_filters( 'register', sprintf( '<a href="%s">%s</a>', esc_url( wp_registration_url() ), __( 'Register' ) ) ); ?>
	<?php endif; ?>
	
	<div id="wpt_settings_page" class="postbox-container" style="width: 100%">

		<div class="metabox-holder">

			<div class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Set type of tournament','poule-system')?></h3>
					<div class="inside wpt-settings">
						<form action="" method="post">
							<div id="wtt_authentication_display">
								<fieldset class="options">
									<label><?php _e('tournament type is WK','poule-system')?></label>
									<input type="checkbox" name="tournament" <?php echo $set_checked; ?> value='WK'/>
								</fieldset>
								
								<div>

									<input type="submit" name="submit" class="button-primary" value="<?php _e('save','poule-system')?>">

								</div>
							</div>
						</form>
					</div>
				</div>
			</div>


			<div class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('reset all', 'poule-system');?></h3>
					<div class="inside">
						
						<a href='?page=poulesettings&function=resetall' class="button-primary"><?php _e('Reset all','poule-system');?></a>
						
					</div>
				</div>
			</div>
			
			<div class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('reset admin score', 'poule-system');?></h3>
					<div class="inside">
						
						<a href='?page=poulesettings&function=resetall' class="button-primary"><?php _e('Reset all','poule-system');?></a>
						
					</div>
				</div>
			</div>
			
			<div class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('reset user score', 'poule-system');?></h3>
					<div class="inside">
						
						<a href='?page=poulesettings&function=resetall' class="button-primary"><?php _e('Reset all','poule-system');?></a>
						
					</div>
				</div>
			</div>

			<form method="post" action="">
				<fieldset>
					<input type="hidden" name="submit-type" value="check-support">
					<div>
						<input type="hidden" id="_wpnonce" name="_wpnonce" value="df78e83be8">
						<input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php?page=wp-to-twitter/wp-to-twitter.php">
						<input type="hidden" name="_wp_http_referer" value="/wp-admin/options-general.php?page=wp-to-twitter/wp-to-twitter.php">
					</div>
				</fieldset>
			</form>		
		</div>
	</div>
</div>
