<div class="col-12 col-lg-6 col-sm-12">
	<div class="wrap">

		<h2><?php _e('Countries', 'poule-system') ?></h2>
		
		<table class="widefat" cellspacing="0">
			<thead>
				<tr class="alternate">
					<th class="row-title">
						<?php _e('Number', 'poule-system') ?>
					</th>
					<th class="row-title">
						<?php _e('Country name', 'poule-system') ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($countries as $country) {?>
				<tr class="alternate">
					<td><?php echo $country['id'];?></td>
					<td>
						<a href="admin.php?page=countries&function=edit&country=<?php echo $country['id'];?>">
							<?php echo $country['name'];?>
						</a>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
		
		<a class="button-secondary" href="?page=countries&function=add" title="All" Attendees><?php _e('Add', 'poule-system') ?></a>
		
	</div>
</div>