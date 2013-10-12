<div class="wrap">
	<?php if ($phase_error === FALSE) { ?>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<div id="wpt_settings_page" class="postbox-container" style="width: 100%">

				<div class="metabox-holder">

					<div class="ui-sortable meta-box-sortables">
						<?php foreach ($groups as $group) { ?>
							<div class="postbox">
								<h3><input type="text" name="group[<?php echo $group['row']; ?>][name]" class="form-control" placeholder="<?php _e('group name', 'poule-system'); ?>"/></h3>


								<table class="widefat" cellspacing="0">
									<thead>
										<tr>
											<th><?php _e('Start time', 'poule-system'); ?></th>
											<th><?php _e('Country 1', 'poule-system'); ?></th>
											<th class="text-center">-</th>
											<th><?php _e('Country 2', 'poule-system'); ?></th>
										</tr>
									</thead>
									<tbody>
									<td>
<!--										<input data-format="dd-MM-yyyy hh:mm:ss" class="form-control" type="text" name="group[<?php echo $group['row']; ?>][date]"/>-->
										<input type="date" ng-model="dateString" class="<?php echo $errors[$i]['date']; ?>" name="group[<?php echo $group['row']; ?>][date]" value="<?php echo $match['date']; ?>"/>
										<input type="time" class="<?php echo $errors[$i]['time']; ?>" name="group[<?php echo $group['row']; ?>][time]" value="<?php echo $match['time']; ?>"/>
									</td>
									<td>
										<select name="group[<?php echo $group['row']; ?>][country_1]" class="form-control">
											<option value=""></option>
											<?php foreach ($group['countries_options'] as $options) { ?>
												<optgroup label="<?php echo $options['group_name']; ?>">
													<option value="<?php echo $options['id']; ?>_0"><?php _e('first', 'poule-system') ?> <?php echo $options['group_name']; ?></option>
													<option value="<?php echo $options['id']; ?>_1"><?php _e('second', 'poule-system') ?> <?php echo $options['group_name']; ?></option>
												</optgroup>
											<?php } ?>
										</select>
									</td>
									<td class="text-center">
										-
									</td>
									<td>
										<select name="group[<?php echo $group['row']; ?>][country_2]" class="form-control">
											<option value=""></option>
											<?php foreach ($group['countries_options'] as $options) { ?>
												<optgroup label="<?php echo $options['group_name']; ?>">
													<option value="<?php echo $options['id']; ?>_0"><?php _e('first', 'poule-system') ?> <?php echo $options['group_name']; ?></option>
													<option value="<?php echo $options['id']; ?>_1"><?php _e('second', 'poule-system') ?> <?php echo $options['group_name']; ?></option>
												</optgroup>
											<?php } ?>
										</select>
									</td>
									</tbody>
								</table>

							</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<button type="submit" class="button-primary"><?php _e('add', 'poule-system') ?></button>
			<a href="poule/admin_matches/<?php echo $phase_name; ?>" class="button-secondary"><?php _e('Cancel', 'poule-system') ?></a>

		</form>
	<?php }else{ ?>
		
		<div id="message" class="error"><p><?php _e('This function can not be used on this moment', 'poule-system') ?></p></div>
	
	<?php } ?>
</div>