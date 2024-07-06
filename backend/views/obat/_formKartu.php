<table class='table table-bordered'>
	<tr>
		<th>Tgl</th>
		<th>Jumlah</th>
		<th>Jenis</th>
	</tr>
	<?php foreach($json as $j){ ?>
	<tr>
		<td><?= $j['tgl']?></td>
		<td><?= $j['jumlah']?></td>
		<td><?=$j['jenis'] == 2 ? 'Masuk' : 'Keluar';?></td>
	</tr>
	<?php } ?>
</table>