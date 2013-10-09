<div class="col-12 col-lg-6 col-sm-12">
	<div class="wrap">

		<h2><?php _e('Podium', 'poule-system') ?></h2>

		<table class="widefat" cellspacing="0">
			<thead>
				<tr class="alternate">
					<th class="row-title">
						<?php _e('podium place', 'poule-system') ?>
					</th>
					<th class="row-title">
						<?php _e('fullname', 'poule-system') ?>
					</th>
					<th class="row-title">
						<?php _e('points', 'poule-system') ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($podium as $place) { ?>
					<tr class="alternate">
						<td><?php echo $place['place']; ?></td>
						<td>
							<a href="admin.php?page=podium&function=userscore&user=<?php echo $place['user_id']; ?>&phase=group">
								<?php echo $place['fullname']; ?>
							</a>
						</td>
						<td><?php echo $place['score']; ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>

		

	</div>
</div>