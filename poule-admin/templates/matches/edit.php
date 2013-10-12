<div class="wrap">
	<?php if (!isset($_POST['delete'])) { ?>

		<?php if ($phase_id != NULL && $update == null && $group != null) { ?>

			<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">


				<div id="wpt_settings_page" class="postbox-container" style="width: 100%">

					<div class="metabox-holder">

						<div class="ui-sortable meta-box-sortables">

							<div class="postbox">
								<h3><?php _e('Group name', 'poule-system') ?></h3>
								<div class="inside">

									<div class="<?php echo $errors['group_name'] ?>">
										<input type="text" name="group_name" class="<?php echo $errors['group_name'] ?>" placeholder="<?php _e('Groupname', 'poule-system') ?>" value="<?php echo $group['group_name']; ?>"/>
									</div>

								</div>
							</div>

							<div class="postbox">
								<h3><?php _e('Matches', 'poule-system') ?></h3>

								<table class="widefat" cellspacing="0">
									<thead>
										<tr>
											<th><?php _e('starttime', 'poule-system') ?></th>
											<th><?php _e('Country 1', 'poule-system') ?></th>
											<th>-</th>
											<th><?php _e('Country 2', 'poule-system') ?></th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($matches as $match) { ?>
											<tr>
												<td>
													<div>
<!--														<input type="text" class="<?php echo $errors[$i]['date'] ?>" name="match[<?php echo $match['match_id']; ?>][date]" value="<?php echo $match['datefull'] ?>"/>-->

														<input type="date" ng-model="dateString" class="<?php echo $errors[$i]['date']; ?>" name="match[<?php echo $match['match_id']; ?>][date]" value="<?php echo $match['date']; ?>"/>
														<input type="time" class="<?php echo $errors[$i]['time']; ?>" name="match[<?php echo $match['match_id']; ?>][time]" value="<?php echo $match['time']; ?>"/>
													</div>
												</td>
												<td>
													<div>
														<select name="match[<?php echo $match['match_id']; ?>][country1]" class="<?php echo $errors[$i]['country1'] ?>">
															<option value=""></option>
															<?php foreach ($match['country_1'] as $country) { ?>
																<option value="<?php echo $country['id'] ?>" <?php echo $country['selected'] ?> ><?php echo $country['name'] ?></option>
															<?php } ?>
														</select>
													</div>
												</td>
												<td>-</td>
												<td>
													<select name="match[<?php echo $match['match_id']; ?>][country2]" class="<?php echo $errors[$i]['country2'] ?>">
														<option value=""></option>
														<?php foreach ($match['country_2'] as $country) { ?>
															<option value="<?php echo $country['id'] ?>" <?php echo $country['selected'] ?> ><?php echo $country['name'] ?></option>
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

				<input type="submit" class="button-primary" name="save" value="<?php _e('Save', 'poule-system') ?>"/>
				<input type="submit" class="button-primary" name="delete" value="<?php _e('delete', 'poule-system') ?>"/>
				<a href="#" class="button-secondary"><?php _e('Cancel', 'poule-system') ?></a>

			</form>

		<?php } else if($phase_id != NULL && $update === TRUE && $group != null) { ?>

			<div id="message" class="error"><p><?php _e('Incorrect tournament phase', 'poule-system') ?></p></div>

		<?php }else{ ?>
			<?php if($group == null){ ?>
			
			<div id="message" class="error"><p> <?php _e('Error in the url', 'poule-system')?> </p></div>
			
			<?php }else{ ?>
			<div id="message" class="updated"><p><?php _e('changes are saved', 'poule-system') ?></p></div>
			<?php } ?>
		<?php } ?>
	<?php } else { ?>

		<div id="message" class="updated"><p><?php _e('Group is deletet', 'poule-system') ?></p></div>

	<?php } ?>
</div>