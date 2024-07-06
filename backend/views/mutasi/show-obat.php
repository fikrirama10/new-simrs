<?php
use yii\helpers\Url;
?>
<div class='box box-body'>
<a class='btn btn-default' target='_blank' href='<?= Url::to(['laporan-stok/print?start='.$start.'&end='.$end])?>'>Print</a>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Nama Obat</th>
		<th>Satuan</th>
		<th>Stok Awal</th>
		<th>Penerimaan</th>
		<th>Penggunaan</th>
		<th>Stok Akhir</th>
		<th>Total Harga</th>
	</tr>
	<?php $no=1; foreach($model as $m){ 
	$stokakhir = ($m['stokAwal'] - $m['mutasi_penggunaan']) + $m['mutasi_pemerimaan'];
	?>
	<?php if($m['mutasi_jumlah'] > 0){ 
	$satuanharga = $m['harga'] ;
	if($satuanharga < 0){
		$satuanharga = 0;
	}else{
		if (empty($satuanharga)) {
		  $satuanharga = 0;
		}else{
			$satuanharga = $m['harga'] ;
		}
		
	}
	?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $m['namaObat'] ?></td>
		<td><?= $m['satuan'] ?></td>
		<td><?= $m['stokAwal'] ?></td>
		<td><?= $m['mutasi_pemerimaan'] ?></td>
		<td><?= $m['mutasi_penggunaan'] ?></td>
		<td><?= $stokakhir ?></td>
		<td><?= $m['harga'] ?></td>
	</tr>
	<?php } ?>
	<?php } ?>
</table>