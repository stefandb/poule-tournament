<div class="text-center">
	<div class="col-12 col-lg-12 col-sm-12">
		<ul class="pagination">
			<?php foreach ($pagination as $link) { ?>
				<li class=""><a href="?phase=<?php echo $link['link'] ?>"><?php _e($link['name'], "poule-system") ?></a></li>
			<?php } ?>
		</ul>    
	</div>
</div>

<?php foreach ($groups as $group) { ?>
	<div class="col-12 col-lg-12 col-sm-12">

		<div class="panel panel-default">

			<div class="panel-heading">
				<?php echo $group['group_name']; ?>
			</div>

			<table class="table table-hover">
				<thead>
					<tr>
						<th style="word-wrap:break-word" class="visible-lg visible-md"><?php _e('Start time', 'poule-system') ?></th>
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
						<td style="word-wrap:break-word" class="visible-lg visible-md"><?php echo $match['start_time']; ?></td>
						<td><div class="text-right"><?php echo $match['country1']; ?></div></td>
						<td>
							<div class="text-right success">
								<?php echo $match['score_1']; ?>
							</div>
						</td>
						<td class="text-center">-</td>
						<td>
							<div class="control-group success">
								<?php echo $match['score_2']; ?>
							</div>
						</td>
						<td><?php echo $match['country2']; ?></td>
					</tr>

					<tr <?php echo $match['hidden']; ?>>
						<td></td>
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