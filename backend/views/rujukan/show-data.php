<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
?>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>No.SEP / No.Rawat</th>
		<th>Poliklinik</th>
		<th>Tgl.Kunjungan</th>
		<th>Dokter</th>
		<th>Diagnosa</th>
		<th>Nama</th>
		<th>No.BPJS</th>
	</tr>
	<?php if(count($model) < 1){ ?>
	<tr>
		<td colspan=8>Data tidak ditemukan</td>
	</tr>
	<?php }else{ ?>
		<?php $no=1; foreach($model as $m): 
		
		?>
		<tr>
			<td><?= $no++ ?></td>
			<td>
				<?php if($m->no_sep != null){ ?>
				<a href='<?= Url::to(['rujukan/rujukan?id='.$m->id])?>' class='btn btn-default btn-xs'><?= $m->no_sep ?></a>
				<?php }else{ ?>
				<a href='<?= Url::to(['rujukan/rujukan-umum?id='.$m->id])?>' class='btn btn-default btn-xs'><?= $m->idrawat ?></a>
				<?php } ?>
			</td>
			<td><?= $m->poli->poli?></td>
			<td><?= date('Y-m-d',strtotime($m->tglmasuk))?></td>
			<td><?= $m->dokter->nama_dokter?></td>
			<td><?= $m->icdx?></td>
			<td><?= $m->pasien->nama_pasien?></td>
			<td><?= $m->pasien->no_bpjs?></td>
			<td>
				<a class='btn btn-success btn-xs' href='<?= Url::to(['rujukan/rujukan?id='.$m->id]) ?>'>Rujukan Online BPJS</a>
				<a class='btn btn-info btn-xs' href='<?= Url::to(['rujukan/rujukan-umum?id='.$m->id])?>'>Rujukan Manual</a>
			</td>
		</tr>
		<?php endforeach; ?>
	<?php } ?>
</table>
