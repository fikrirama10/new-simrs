<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\web\View;
?>
<table class='table table-bordered'>
	<tr>
		<th>Laporan</th>
		<th>Lihat</th>
	</tr>
	<tr>
		<td>Rekap Laporan Penyerahan</td>
		<td><a class='btn btn-primary' href='<?= Url::to(['/gudang/rekap-atk?start='.$start.'&end='.$end])?>'>Lihat</a></td>
	</tr>
	<tr>
		<td>Rekap Laporan Penyerahan Group By Ruangan</td>
		<td><a class='btn btn-primary' href='<?= Url::to(['/gudang/rekap-atk-ruangan?start='.$start.'&end='.$end])?>'>Lihat</a></td>
	</tr>
	<tr>
		<td>Rekap Laporan Penyerahan Group By Tanggal</td>
		<td><a class='btn btn-primary' href='<?= Url::to(['/gudang/rekap-atk-tanggal?start='.$start.'&end='.$end])?>'>Lihat</a></td>
	</tr>
</table>