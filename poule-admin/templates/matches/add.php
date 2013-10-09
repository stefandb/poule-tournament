<?php if ($phase_id != NULL) { ?>

	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">

		<div class='wrap'>
			<h2><?php _e('Add matches', 'poule-system'); ?></h2>

			<div id="wpt_settings_page" class="postbox-container" style="width: 100%">

				<div class="metabox-holder">

					<div class="ui-sortable meta-box-sortables">
						<div class="postbox">
							<h3><?php _e('Group name', 'poule-system') ?></h3>
							<div class="inside">
								<div class="<?php echo $errors['group_name']; ?>">
									<input type="text" name="group_name" class="<?php echo $errors['group_name']; ?>" placeholder="<?php _e('Groupname', 'poule-system'); ?>" value="<?php echo $user_input['group_name']; ?>"/>
								</div>
							</div>
						</div>

						<div class="postbox">
							<h3><?php _e('Group name', 'poule-system') ?></h3>

							<table class="widefat" cellspacing="0">
								<thead>
									<tr>
										<th><?php _e('starttime', 'poule-system'); ?></th>
										<th><?php _e('Country 1', 'poule-system'); ?></th>
										<th>-</th>
										<th><?php _e('Country 2', 'poule-system'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php for ($i = 0; $i < $phase_id['match_group']; $i++) { ?>
										<tr>
											<td>
												<div>
													<input type="text" class="<?php echo $errors[$i]['date']; ?>" name="match[<?php echo $i; ?>][date]" value="<?php echo $user_input['match'][$i]['date']; ?>"/>
												</div>
											</td>
											<td>
												<div>
													<select name="match[<?php echo $i; ?>][country1]" class="<?php echo $errors[$i]['country1']; ?>">
														<option value=""></option>
														<?php foreach ($countries as $country) { ?>
															<option value="<?php echo $country['id']; ?>" <?php echo $selected[$i]['country1'][$country['id']]; ?>><?php echo $country['name']; ?></option>
														<?php } ?>
													</select>
												</div>
											</td>
											<td>-</td>
											<td>
												<select name="match[<?php echo $i; ?>][country2]" class="<?php echo $errors[$i]['country2'] ?>">
													<option value=""></option>
													<?php foreach ($countries as $country) { ?>
														<option value="<?php echo $country['id'] ?>" <?php echo $selected[$i]['country2'][$country['id']] ?>><?php echo $country['name'] ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>

						</div>

					</div>

				</div>
			</div>

			<button type="submit" class="button-primary"><?php _e('Add', 'poule-system') ?></button>
			<a href="?page=matches" class="button-secondary"><?php _e('Cancel', 'poule-system') ?></a>

		</div>

	</form>

<?php } else { ?>

	<div class="wrap">
		<div id="message" class="error"><p><?php _e('Incorrect tournament phase', 'poule-system') ?></p></div>
	</div>
<?php } ?>