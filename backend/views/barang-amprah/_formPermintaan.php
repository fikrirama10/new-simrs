<?php 
use common\models\DataBarang;
$obat = DataBarang::find()->where(['id'=>$pl->idbarang])->all();
?>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Nama Barang</th>
		<th>Stok</th>
		<th>Satuan</th>
	</tr>
	<?php $no=1; foreach($obat as $o): ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $o->nama_barang ?></td>
		<td><?= $o->stok ?></td>
		<td><?= $o->satuan->satuan ?></td>
	</tr>
	<?php endforeach; ?>
</table>