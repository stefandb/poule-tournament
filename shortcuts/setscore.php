<div class="text-center">
	<div class="col-12 col-lg-12 col-sm-12">
		<ul class="pagination">
			<?php foreach ($pagination as $link) { ?>
				<li class=""><a href="?phase=<?php echo $link['link'] ?>"><?php _e($link['name'], "poule-system") ?></a></li>
			<?php } ?>
		</ul>    
	</div>
</div>

<?php if ($phase_id != null && count($groups) != 0) { ?>
	<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">	
		<div class="row">
			<?php foreach ($groups as $group) { ?>
				<div class="col-12 col-lg-6 col-sm-12">

					<div class="panel panel-default">

						<div class="panel-heading">
							<?php echo $group['group_name']; ?>
						</div>

						<table class="table table-hover">
							<thead>
								<tr>
									<th class="text-right"><?php _e('Country 1', 'poule-system') ?></th>
									<th class="text-right"><?php _e('Score', 'poule-system') ?></th>
									<th class="text-center">-</th>
									<th><?php _e('Score', 'poule-system') ?></th>
									<th><?php _e('Country 2', 'poule-system') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($group['matches'] as $match) { ?>
									<tr>
										<td><div class="text-right"><?php echo $match['country1']; ?></div></td>
										<td>
											<div class="form-group <?php echo $error[$match['match_id']]['score_1']; ?>">
												<input type="number" min="0" max="20" class="form-control text-right" <?php echo $match['readonly'] ?> name="score[<?php echo $match['match_id'] ?>][score_1]" id="score_<?php echo $match['match_id'] ?>_1" value="<?php echo $match['score_1'] ?>" onkeyup="penalties('<?php echo $match['match_id'] ?>', '1')"/>
											</div>
										</td>
										<td>-</td>
										<td>
											<div class="form-group <?php echo $error[$match['match_id']]['score_2']; ?>">
												<input type="number" min="0" max="20" class="form-control" <?php echo $match['readonly'] ?> name="score[<?php echo $match['match_id'] ?>][score_2]" id="score_<?php echo $match['match_id'] ?>_2" value="<?php echo $match['score_2'] ?>" onkeyup="penalties('<?php echo $match['match_id'] ?>', '2')"/>
											</div>
										</td>
										<td><?php echo $match['country2'] ?></td>
									</tr>
									<tr id="penalties_<?php echo $match['match_id'] ?>" class="" <?php echo $match['hidden'] ?>>
										<td></td>
										<td colspan="3">
											<div class="text-center">
												<select name="score[<?php echo $match['match_id'] ?>][penalty]" class="form-control" <?php echo $match['readonly'] ?>>
													<option></option>
													<?php echo $match['readonly'] ?>
													<?php foreach ($match['countries'] as $country) { ?>
														<option value="<?php echo $country['country_id'] ?>" <?php echo $country['selected'] ?>><?php echo $country['country'] ?></option>
													<?php } ?>
												</select>
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
		<div class="row">

			<div class="col-12">

				<div class="form-actions">
					<input type="submit" class="btn btn-primary" value="<?php _e('save', 'poule-system'); ?>"/>
					<a href="../own_score/{phase}" class="btn btn-default"><?php _e('back', 'poule-system'); ?></a>
				</div>

			</div>

		</div>

	</form>
<?php } else { ?>
	
	<div class="alert alert-danger">no matches</div>

<?php } ?>
