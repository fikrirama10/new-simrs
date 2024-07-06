<?php
use common\models\PenerimaanBarangDetail;
?>
<div class='header upper'>
	<b>REKAP FAKTUR</b>
</div>

<table>
	<tr>
		<th>No</th>
		<th>Faktur</th>
		<th>Barang</th>
		<th>Harga Satuan</th>
		<th>Qty</th>
		<th>Total Harga</th>
	</tr>
	<?php $no=1; foreach($model as $m){ 
	$detail = PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$m->id])->all();
	?>
	<tr>
		<td rowspan=<?= count($detail) + 1?>><?= $no++ ?></td>
		<td rowspan=<?= count($detail) + 1?>><b><?= $m->no_faktur ?> <hr> (<?= $m->suplier->suplier ?>) (<?= $m->tgl_faktur ?>)</b></td>
		
	</tr>
		<?php foreach($detail as $d){ ?>
			<tr>
				<td><?= $d->obat->nama_obat?></td>
				<td><?= $d->harga?></td>
				<td><?= $d->jumlah?></td>
				<td><?= $d->total?></td>
			</tr>
		<?php } ?>
	<tr>
		<th colspan=5>Total Faktur</th>
		<th><?= $m->nilai_faktur?></th>
	</tr>
	<?php } ?>
	
</table>