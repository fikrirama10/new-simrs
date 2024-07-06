<?php 
use common\models\ObatMutasi;
?>
<table class='table table-bordered'>
	<tr>
		<th width=10>No</th>
		<th colspan=2>Nama Obat</th>
		<th>Satuan</th>
		<th>Qty</th>
	</tr>
	<?php foreach($model as $m): 
		$obat = ObatMutasi::find()->where(['idobat'=>$m->idobat])->groupBy('idbacth')->all();
	?>
	<tr>
		<td></td>
		<td colspan=2><?= $m->obat->nama_obat?></td>
		<td>satuan</td>
		<td></td>
	</tr>
		<?php foreach($obat as $o): ?>
		<tr>
			<td></td>
			<td width=100><?= $o->bacth->no_bacth?></td>
			<td><?= $o->bacth->merk?></td>
		</tr>
		<?php endforeach; ?>
	<?php endforeach; ?>
</table>
