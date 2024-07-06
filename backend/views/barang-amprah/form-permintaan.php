<h3><center>Permintaan Barang Ruangan <?= $model->ruangan->ruangan?> 
</center></h3>
<h5><center><?= $model->kode_permintaan ?> / <?= $model->tgl_permintaan ?></center></h5>
<h4>Barang Permintaan</h4>
<table>
	<tr>
		<th>No</th>
		<th>Nama Obat / Alkes</th>
		<th>Satuan</th>
		<th>Permintaan</th>
		<th>Harga</th>
		<th>Total</th>
	</tr>
	<?php $no2=1; foreach($model_list as $r): ?>
	<tr>
		<td><?= $no2++ ?></td>
		<td><?= $r->nama_barang ?></td>
		<td><?php if($r->baru != 1){ ?><?= $r->barang->satuan->satuan ?> <?php } ?></td>
		<td align='center'><?= $r->qty?></td>
		<td><?= $r->harga?></td>
		<td><?= $r->total?></td>
	</tr>
	<?php endforeach; ?>
	<tr>
	   <th colspan=5>Total</th>
	   <th><?= $model->total?></th>
	</tr>
</table>
<br>
<div style='float:left; width:50%; text-align:center;'>
	<p>Kepala Ruangan</p><br><br><br>
	...........................
</div>
<div style='float:left; width:50%; text-align:center;'>	<br>Kajangkes
	<br><br><br><br><br>
	...........................
</div>

