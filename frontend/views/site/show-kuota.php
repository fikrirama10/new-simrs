<div class='col-md-12'>
<table class='table table-bordered'>
	<thead>
		<tr>
			<th>No</th>
			<th>Poliklinik</th>
			<th>Kuota Pasien</th>
			<th>Sisa</th>
		</tr>
	</thead>
	<?php if($kuota){ ?>
	<tbody>
		<?php $no=1; foreach($kuota as $k): ?>
		<tr>
			<td><?= $no++ ?></td>
			<td><?= $k['poli'] ?></td>
			<td><?= $k['jumlahkuota'] ?></td>
			<td>
				<?php if($k['jumlahsisa'] == 0) {echo $k['jumlahkuota'];}else{ ?>
				<?= $k['jumlahsisa'] ?>
				<?php } ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	<?php }else{ ?>
	<tbody>
		<tr>
			<td colspan=4>Tidak ada data</td>
		</tr>
	</tbody>
	<?php } ?>
</table>
</div>