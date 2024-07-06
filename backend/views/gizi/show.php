<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
?>
<div class='col-md-12'>
	<div class='box'>
		<div class='box-body'>
			<?php if($model){ ?>
			<span class="label label-success">Data ditemukan</span><hr>
			<table class='table table-bordered'>
				<tr>
					<th>Idrawat</th>
					<th>No RM</th>
					<th>Nama</th>
					<th>Tgl Masuk</th>
					<th>Ruangan</th>
					<th>Kelas</th>
					<th>DPJP</th>
					<th>Penanggung</th>
				</tr>
				<?php foreach($model as $m){ ?>
				<tr>
					<td><a href='<?= Url::to(['gizi/view?id='.$m->id])?>' class='btn btn-default'><?= $m->idrawat ?></a></td>
					<td><?= $m->no_rm ?></td>
					<td><?= $m->pasien->nama_pasien ?></td>
					<td><?= $m->tglmasuk ?></td>
					<td><?= $m->ruangan->nama_ruangan ?></td>
					<td><?= $m->kelas->kelas ?></td>
					<td><?= $m->dokter->nama_dokter ?></td>
					<td><?= $m->bayar->bayar ?></td>
				</tr>
				<?php } ?>
			</table>
			<?php }else{echo'<span class="label label-danger">Data tidak ditemukan</span>';}?>
		</div>
	</div>
</div>
