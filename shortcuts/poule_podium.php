<div class="col-12 col-lg-12 col-sm-12">

	<table class="table table-hover">
		<thead>
			<tr>
				<th>
					<?php _e('podium place', 'poule-system') ?>
				</th>
				<th >
					<?php _e('fullname', 'poule-system') ?>
				</th>
				<th>
					<?php _e('points', 'poule-system') ?>
				</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($podium as $place) { ?>
				<tr>
					<td><?php echo $place['place']; ?></td>
					<td>
						<?php echo $place['fullname']; ?>
					</td>
					<td><?php echo $place['score']; ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>

</div>