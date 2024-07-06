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
		<th>HARGA JUMLAH</th>
	</tr>
	<?php $total=0; foreach($model as $m){ 
	$hitung = count($m['obat'])+1;
	?>
	<tr>
		<td rowspan=<?= $hitung ?>><?=$m['tgl']?></td>
		<td rowspan=<?= $hitung ?>><?=$m['suplier']?></td>		
	</tr>
		<?php $total_s=0; foreach($m['obat'] as $o){ 
		$total_s += $o['total'];
		?>
			<tr>
				<td><?= $o['barang']?></td>
				<td align=center><?= $o['jumlah']?></td>
				<td align=center><?= $o['satuan']?></td>
				<td align=right><?= Yii::$app->algo->IndoCurr(round($o['harga']))?></td>
				<td align=right><?= Yii::$app->algo->IndoCurr(round($o['total']))?></td>
			</tr>
		<?php }
		$total += $total_s;
		?>
		<tr>
	    <th colspan=6>Nilai Faktur</th>
	     <th colspan=0><?= Yii::$app->algo->IndoCurr(round($total_s))?></th>
	</tr>
	<?php } ?>
	<tr>
	    <th colspan=7>-</th>
	</tr>
	<tr>
	    <th colspan=6>Total Faktur</th>
	     <th colspan=0><?= Yii::$app->algo->IndoCurr(round($total))?></th>
	</tr>
</table>
<br>

