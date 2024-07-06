<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\DataPekerjaan;
use yii\helpers\Url;
use yii\web\View;
?>
<hr>
<?php if(count($rujukan) < 1){ ?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-barcode"></i> Data Tidak Ditemukan</h4>
	</div>
<?php }else{ ?>
	<table class="table table-bordered">
		<tr>
			<th>Nama Pasien</th>
			<th>Poli Asal</th>
			<th>Jenis Rawat</th>
			<th>Dokter</th>
			<th>Tujuan Rujuk</th>
			<th>Alasan Rujuk</th>
			<th>Diagnosa</th>
		</tr>
		<?php foreach($rujukan as $r){ ?>
		<tr>
			<td><a href='<?= Url::to(['pasien/create-rujukan?id='.$r->id])?>' class='btn btn-default btn-sm'><?= $r->pasien->nama_pasien ?></a></td>
			<td><?= $r->poli->poli ?></td>
			<td><?= $r->jenisrawat->jenis ?></td>
			<td><?= $r->dokter->nama_dokter ?></td>
			<td><?= $r->tujuan_rujuk ?></td>
			<td><?= $r->alasan_rujuk ?></td>
			<td><?= $r->diagnosa_klinis ?></td>
		</tr>
		<?php } ?>
	</table>
<?php } ?>