<?php if (count($groups) != 0) { ?>
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
		<div class="wrap">
			<?php foreach ($groups as $group) { ?>
				<div>
					<h3>
						<?php echo $group['group_name'] ?>
					</h3>

					<table class="widefat" cellspacing="0">
						<thead>
							<tr>
								<th class="text-right">
									<?php _e('country 1', 'poule-system') ?>
								</th>
								<th class="text-right">
									<?php _e('score country 1', 'poule-system') ?>
								</th>
								<th class="text-center">-</th>
								<th style="width: 150px;">
									<?php _e('score country 2', 'poule-system') ?>
								</th>
								<th style="width: 150px;">
									<?php _e('country 2', 'poule-system') ?>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($group['matches'] as $match) { ?>
								<tr>
									<td style="word-wrap:break-word" class="text-right"><?php echo $match['country1'] ?></td>
									<td>
										<input type="number" min="0" max="20" width="25" name="score[<?php echo $match['match_id'] ?>][score_1]" class="<?php echo $errors[$match['match_id']]['score_1'] ?>" id="score_<?php echo $match['match_id'] ?>_1" value="<?php echo $match['score_1'] ?>" <?php echo $match['readonly'] ?> onkeyup="penalties('<?php echo $match['match_id'] ?>', '1')"/>
									</td>
									<td class="text-center">-</td>
									<td>
										<input type="number" min="0" max="20" width="25" name="score[<?php echo $match['match_id'] ?>][score_2]" class="<?php echo $errors[$match['match_id']]['score_2'] ?>" id="score_<?php echo $match['match_id'] ?>_2" value="<?php echo $match['score_2'] ?>" <?php echo $match['readonly'] ?> onkeyup="penalties('<?php echo $match['match_id'] ?>', '2')"/>
									</td>
									<td style="word-wrap:break-word"><?php echo $match['country2'] ?></td>
								</tr>
								<tr <?php echo $match['hidden'] ?>>
									<td></td>
									<td>
										<div class="control-group" id="penalties_<?php echo $match['match_id'] ?>_1">
											<input type="number" min="0" max="5" width="25" name="score[<?php echo $match['match_id'] ?>][penalty_1]" class="<?php echo $errors[$match['match_id']]['penalty_1'] ?>" <?php echo $match['readonly'] ?> value="<?php echo $match['penalty_1'] ?>"/>
										</div>
									</td>
									<td>-</td>
									<td>
										<div class="control-group" id="penalties_<?php echo $match['match_id'] ?>_2">
											<input type="number" min="0" max="5" width="25" name="score[<?php echo $match['match_id'] ?>][penalty_2]" class="<?php echo $errors[$match['match_id']]['penalty_2'] ?>" <?php echo $match['readonly'] ?> value="<?php echo $match['penalty_2'] ?>"/>
										</div>
									</td>
									<td></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>

		</div>

		<button class="button loco-download" data-loco="download" type="submit">
			<span><?php _e("save", 'poule-system') ?></span>
		</button>

	</form>
<?php } else { ?>
	<?php _e('no matches', 'poule-system') ?>
<?php } ?>