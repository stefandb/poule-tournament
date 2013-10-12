<h2><?php echo $user_info->display_name; ?></h2>
<div class="col-12 col-lg-12 col-sm-12">
	<h3 class="nav-tab-wrapper">
		<?php foreach ($pagination as $page) { ?>
			<a class="nav-tab <?php echo $page['active']; ?>" href="?page=podium&function=userscore&user=<?php echo $_GET['user']; ?>&phase=<?php echo $page['link']; ?>"><?php echo $page['name']; ?></a>
		<?php } ?>
	</h3>

	<?php if (count($groups) != 0) { ?>
		<div class="row">

			<div class="col-12 col-lg-6 col-sm-12">

				<div class="wrap">
					<div id="wpt_settings_page" class="postbox-container" style="width: 100%">

						<div class="metabox-holder">
							<div class="ui-sortable meta-box-sortables">
								<?php foreach ($groups as $group) { ?>
									<div class="postbox">
										<h3>
											<?php echo $group['group_name'] ?>
										</h3>

										<div class="table-responsive">
											<table class="widefat" cellspacing="0">
												<thead>
													<tr>
														<th class="text-right">
															<?php _e('Country 1', 'poule-system') ?>
														</th>
														<th class="text-right">
															<?php _e('Score', 'poule-system') ?>
														</th>
														<th class="text-center">-</th>
														<th style="width: 150px;">
															<?php _e('Score', 'poule-system') ?>
														</th>
														<th style="width: 150px;">
															<?php _e('Country 2', 'poule-system') ?>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($group['matches'] as $match) { ?>
														<tr>
															<td style="word-wrap:break-word" class="text-right"><?php echo $match['country1'] ?></td>
															<td>
																<?php echo $match['score_1'] ?>
															</td>
															<td class="text-center">-</td>
															<td>
																<?php echo $match['score_2'] ?>
															</td>
															<td style="word-wrap:break-word"><?php echo $match['country2'] ?></td>
														</tr>
														<tr <?php echo $match['hidden'] ?>>
															<td></td>
															<td colspan="3">
																<div class="text-center">
																	<?php echo $match['penalty_country']; ?>
																</div>
															</td>
															<td></td>
														</tr>
													<?php } ?>
												</tbody>
											</table>

										</div>
										
									</div>
								<?php } ?>

							</div>

						</div>

					</div

				</div>

			</div>

		</div>

		<a href="?page=podium" class='button button-controls'><?php _e('back', 'poule-system'); ?></a>
	<?php } else { ?>

		<div id="message" class="error"><p><?php _e('No matches', 'poule-system') ?></p></div>

	<?php } ?>

</div>