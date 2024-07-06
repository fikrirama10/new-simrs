<?php
use common\models\UserUnit;
use common\models\UnitRuangan;
?>


<div class='header upper'>
	<b>PEMBELIAN OBAT HARIAN BULAN <?= date('F Y',strtotime($start))?></b>
</div>
<table>
	<tr>
		<th>TGL</th>
		<th width=220>NAMA PBF</th>
		<th>NAMA BARANG</th>
		<th>JUMLAH</th>
		<th>SATUAN</th>
		<th>HARGA SATUAN</th>
		<th>DISK %</th>
		<th>HARGA JUMLAH</th>
		<th>POTONGAN</th>
		<th>TOTAL</th>
		<th>PPN</th>
		<th>YANG DIBAYAR</th>
	</tr>
	<?php foreach($model as $m){ 
	$hitung = count($m['obat'])+1;
	?>
	<tr>
		<td rowspan=<?= $hitung ?>><?=$m['tgl']?></td>
		<td rowspan=<?= $hitung ?>><?=$m['suplier']?></td>		
	</tr>
		<?php foreach($m['obat'] as $o){ ?>
			<tr>
				<td><?= $o['barang']?></td>
				<td align=center><?= $o['jumlah']?></td>
				<td align=center><?= $o['satuan']?></td>
				<td align=right><?= Yii::$app->algo->IndoCurr(round($o['harga']))?></td>
				<td align=right><?= $o['diskon']?>%</td>
				<td align=right><?= Yii::$app->algo->IndoCurr(round($o['total']))?></td>
				<td align=right><?= Yii::$app->algo->IndoCurr(round($o['total'] - $o['total_diskon']))?></td>
				<td align=right><?= Yii::$app->algo->IndoCurr(round($o['total_diskon']))?></td>
				<td align=right><?= Yii::$app->algo->IndoCurr(round($o['ppn']))?></td>
				<td align=right><?= Yii::$app->algo->IndoCurr(round($o['total_diskon'] + $o['ppn']))?></td>
			</tr>
		<?php } ?>
	<?php } ?>
</table>
<br>

