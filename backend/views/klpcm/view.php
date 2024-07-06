<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Klpcm */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Klpcms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="klpcm-view">
	<div class='box'>
		<div class='box-header with-border'>Klpcm Pasien</div>
		<div class='box-body'>
			<div id="divHistori" class="list-group">
				<span class="list-group-item">
					<h5 class="list-group-item-heading"><b><u><?= $rawat->idrawat?> - <?= $rawat->no_rm ?> - <?= $rawat->pasien->nama_pasien?></u></b></h5>  
					<small>
					<?= $rawat->jenisrawat->jenis?> - <?= $rawat->poli->poli?> - <?= $rawat->bayar->bayar?>
					</small>
					<p class="list-group-item-text"><small><?= $rawat->dokter->nama_dokter ?></small></p>
					<p class="list-group-item-text"><small><?= $rawat->tglmasuk ?></small></p>
					<p class="list-group-item-text"><small><b><?= $rawat->icdx ?></b></small></p>
					<p class="list-group-item-text"><small></small></p>
					<p class="list-group-item-text"><small></small></p>
					<p class="list-group-item-text"><small></small></p>
					
				</span>
				
			</div>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li  class="active" class=""><a href="#tab_usia" data-toggle="tab" aria-expanded="false">Kelengkapan</a></li>
					<li class=""><a href="#tab_agama" data-toggle="tab" aria-expanded="false">Upload Dokumen</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_usia">
						<?= $this->render('_formKelengkapan',[
							'model' => $model,
							'klpcm' => $klpcm,
							'klpcm_list' => $klpcm_list,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_agama">
						<?= $this->render('_formUpload',[
							'model' => $model,
							'klpcm_dokumen'=>$klpcm_dokumen,
							'dokumen'=>$dokumen
						]) ?>
					</div>

				</div>
				<!-- /.tab-content -->
			</div>
		</div>
		<div class='box-footer'>
			<a class='btn btn-warning' href='<?= Url::to(['klpcm/selesai?id='.$model->id])?>'>Selesai</a>
		</div>
	</div>
</div>
