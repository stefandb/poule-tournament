<div class="wrap">
	<h3 class="nav-tab-wrapper">
		<?php foreach ($pagination as $page) { ?>
			<a class="nav-tab <?php echo $page['active']; ?>" href="?page=matches&phase=<?php echo $page['link']; ?>"><?php echo $page['name']; ?></a>
		<?php } ?>
	</h3>

	<?php if (count($groups) != 0) { ?>


		<div id="wpt_settings_page" class="postbox-container" style="width: 100%">

			<div class="metabox-holder">
				<div class="ui-sortable meta-box-sortables">
					<?php foreach ($groups as $group) { ?>

						<div class="postbox">

							<h3>
								<a href="?page=matches&function=edit&phase=<?php echo $phase; ?>&group=<?php echo $group['group_id'] ?>">
									<?php echo $group['group_name'] ?>
								</a>
							</h3>

							<table class="widefat" cellspacing="0">
								<thead>
									<tr>
										<th style="word-wrap:break-word; width: 150px;">
											<?php _e('starttime', 'poule-system') ?>
										</th>
										<th class="text-right">
											<?php _e('country 1', 'poule-system') ?>
										</th>
										<th class="text-center">-</th>
										<th style="width: 150px;">
											<?php _e('country 2', 'poule-system') ?>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($group['matches'] as $match) { ?>
										<tr>
											<td style="word-wrap:break-word"><?php echo $match['starttime'] ?></td>
											<td style="word-wrap:break-word" class="text-right"><?php echo $match['country1'] ?></td>
											<td class="text-center">-</td>
											<td style="word-wrap:break-word"><?php echo $match['country2'] ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>

	<?php } else { ?>

		<div id="message" class="error"><p><?php _e('No matches', 'poule-system') ?></p></div>

	<?php } ?>

	<a class="button-secondary" href="?page=matches&function=add&phase=<?php echo $phase; ?>" title="All Attendees"><?php _e('Add Match', 'poule-system') ?></a>
	<a class="button-secondary" href="?page=matches&function=auto&phase=<?php echo $phase; ?>" title="All Attendees"><?php _e('Auto Match', 'poule-system') ?></a>

</div>