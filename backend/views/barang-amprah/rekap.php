<?php
use common\models\UnitRuangan;
$unit = UnitRuangan::find()->all();
?>

<div class='header'>
	<div class='header-satu'>
	RSAU dr.SISWANTO
	</div>
	
</div>
<div class='header upper'>
	<b>RENCANA KEBUTUHAN PEMBELIAN BARANG & ATK <?= date('F Y',strtotime('+1 month',strtotime($start)))?></b>
</div>
<table>
	<tr>
		<th rowspan=2 >N0</th>
		<th rowspan=2 width=220>Nama Barang</th>
		<th rowspan=2 >Satuan</th>
		<th colspan=<?= count($unit) ?> >Ruangan</th>
		<th rowspan=2 >Stok Gudang</th>
		<th rowspan=2 >Krg</th>
		<th rowspan=2 >beli</th>
		<th rowspan=2 >Harga Rp</th>
		<th rowspan=2 >Harga Jumlah Rp</th>
	</tr>
	<tr>
		<?php foreach($unit as $u): ?>
		<th><?= substr($u->ket ,0,4);?></th>
		<?php endforeach;  ?>
	</tr>
	<?php $biaya=0; $no=1; foreach($model as $m){ 
		$biaya += $m['beli']*$m['harga'];
	?>
	<?php if($m['beli'] < 1){ ?>
	<tr style='background:
#e0e0e0
;'>
	<?php }else{echo'<tr>';}?>
		<td><?= $no++ ?></td>
		<td><?= $m['nama_barang']?></td>
		<td><?= $m['satuan']?></td>
		<?php foreach($m['unit'] as $mu){ ?>
		<td><?= $mu['jumlah']?></td>
		<?php } ?>
		<td><?= $m['stok']?></td>
		<td><?= $m['beli']?></td>
		<td><?= $m['beli']?></td>
		<td><?= Yii::$app->algo->IndoCurr($m['harga'])?></td>
		<td><?= Yii::$app->algo->IndoCurr($m['beli']*$m['harga'])?></td>
	</tr>
	<?php } ?>
	<tr >
		<th colspan=29>Total</th>
		<th><?=  Yii::$app->algo->IndoCurr($biaya) ?></th>
	</tr>
</table>
<br>
<div style='width:100%; float:left;  text-align:center;'>
	<div style='width:30%; float:left; text-align:center;'>
		Mengetahui,<br>
	Kepala RSAU dr.Siswanto
	<br><br><br><br><br>
	dr.Mohammad Romidon, Sp.B<br>
	Letkol Kes NRP 529216
	</div>
	<div style='width:30%; float:right; text-align:center;'>
		Karanganyar,__ <?= date('F Y',strtotime($start))?><br><br>
	Kajangkes<br><br><br><br>
	Diyah Harini K.W., Amd. Farm<br>
	Mayor Kes NRP 511215
	</div>
	
</div>
