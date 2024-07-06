<h3><center>Permintaan Obat /Alkes RUANGAN <?= $model->ruangan->ruangan?> 

</center></h3>
<h5><center><?= $model->kode_permintaan ?> / <?= $model->tgl_permintaan ?></center></h5>
<table>
	<tr>
		<th>No</th>
		<th>Nama Obat / Alkes</th>
		<th>Satuan</th>
		<th>Jumlah Permintaan</th>
		<th>Harga</th>
		<th>Total</th>
	</tr>
	<?php $no=1; $total=0; foreach($request as $r): 
	    $total += $r->total_setuju;
	?>
	
	<tr>
		<td><?= $no++ ?></td>
		<td>
			<?php if($r->baru == 1){echo $r->nama_obat;}else{echo $r->obat->nama_obat;}?>
		</td>
		<td><?php if($r->baru == 1){echo '-';}else{echo $r->obat->satuan->satuan;}?></td>
		<td><?= $r->jumlah ?></td>
		<td><?= $r->harga ?></td>
		<td><?= $r->total ?></td>
	</tr>
	<?php endforeach; ?>
	<tr>
	    <td colspan=5>Sub Total</td>
	    <td><?= $total?></td>
	</tr>
</table>
<br><div style='float:left; width:50%; text-align:center;'>
	<p>Kepala Ruangan</p><br><br><br>
	...........................
</div>
<div style='float:left; width:50%; text-align:center;'>	<br>Kajangkes
	<br><br><br><br><br>
	...........................
</div>
