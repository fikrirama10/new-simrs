<?php
use yii\helpers\Url;
?>
<table class='table table-bordered'>
	<tr>
		<th width=300>Total Tagihan</th>
		<td><?= $json['total'] ?></td>
	</tr>
	<tr>
		<th width=300>Total Tagihan Tidak di tanggung</th>
		<td><?= $json['rekapTagihanUmum'] ?></td>
	</tr>
</table>
<a href='<?= Url::to(['billing/selesai?id='.$model->id]);?>' class='btn btn-success'>Simpan</a>