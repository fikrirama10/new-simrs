<?php 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\web\View;
?>
<div class='row'>
<div class='col-md-12'>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Judul</th>
		<th>Lihat</th>
	</tr>
	<tr>
		<td>1</td>
		<td>Rekap Pembelian Bekkes & Alkes</td>
		<td><a target='_blank' href='<?= Url::to(['penerimaan-barang/print-rekap?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
	<tr>
		<td>2</td>
		<td>Rekap Pembelian Barang & ATK</td>
		<td><a target='_blank' href='<?= Url::to(['penerimaan-barang/print-rekap-atk?start='.$start.'&end='.$end])?>' class='btn btn-info'>Lihat</a></td>
	</tr>
</table>
</div>
</div>