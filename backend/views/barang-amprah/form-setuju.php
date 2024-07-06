<h3><center>Permintaan Barang Ruangan <?= $model->ruangan->ruangan?>

</center></h3>
<h5><center><?= $model->kode_permintaan ?> / <?= $model->tgl_permintaan ?></center></h5>
<h4>Barang Permintaan</h4>
<table>
	<tr>
		<th>No</th>
		<th>Nama Obat / Alkes</th>
		<th>Satuan</th>
		<th>Jumlah Permintaan</th>
		<th>Estimasi Harga</th>
		<th>Jumlah di setujui</th>
		<th>Total</th>
	</tr>
	<?php $no2=1; $total =0; foreach($model_list as $r): 
		$total += $r->total_setuju;
	?>
	<tr>
		<td><?= $no2++ ?></td>
		<td>
			<?php if($r->baru == 1){echo $r->nama_barang;}else{echo  $r->barang->nama_barang;} ?>
		</td>
		<td>
			<?php if($r->baru == 1){echo '-';}else{echo $r->barang->satuan->satuan;} ?>
		</td>
		<td align='center'><?= $r->qty?></td>
		<td align='center'><?= $r->harga?></td>
		<td align='center'><?= $r->qty_setuju?></td>
		<td align='center'><?= $r->total_setuju?></td>
	</tr>
	<?php endforeach; ?>
	<tr>
		<td colspan=6>Total Harga</td>
		<td><?= $total ?></td>
	</tr>
</table>
<br>
<div style='float:left; width:50%; text-align:center;'>
	<p>Kepala Ruangan</p><br><br><br>
	...........................
</div>
<div style='float:left; width:50%; text-align:center;'>
	<br>Kajangkes
	<br><br><br><br><br>
	...........................
</div>

