<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Gizi */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Gizis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class='row'>
		<div class='col-md-3'>
		<div class="box box-widget widget-user">
			<div class="widget-user-header bg-purple-active">
				<h4 class="widget-user-username" id="lblnama"><?= Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan) ?>. <?= $pasien->nama_pasien?></h4>
				<p class="widget-user-desc" id="lblnoka"><?= $pasien->no_rm?></p>
				<input type="hidden" id="txtkelamin" value="L">
				<input type="hidden" id="txtkdstatuspst" value="0">
			</div>
	
	<!-- /.box-body -->
	
	<!-- /.box -->
	<!-- About Me Box -->
	
	<!-- /.box-header -->
	<div class="box-body">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a title="Profile Peserta" href="#tab_1" data-toggle="tab"><span class="fa fa-user"></span></a></li>
				
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<span class="fa fa-sort-numeric-asc"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->nik?></span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-credit-card"></span>  <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->no_bpjs?></span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->tgllahir ?>  (<?= $pasien->usia_tahun?>th)</span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->tglmasuk ?> </span>
						</li>
						<?php if($model->status != 2){ ?>
						<li class="list-group-item">
							<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->tglpulang ?> </span>
						</li>
						<?php } ?>
						<li class="list-group-item">
							<span class="fa fa-user-md"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->dokter->nama_dokter ?> </span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-bed"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->ruangan->nama_ruangan ?> </span>
						</li>
						<li class="list-group-item">
							<span class="fa  fa-money"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $model->bayar->bayar ?> </span>
						</li>
					
					</ul>
				</div>
				<!-- /.tab-pane -->
			
			</div>
			<!-- /.tab-content -->
		</div>
		<div id="divriwayatKK" style="display: none;">
			<button type="button" id="btnRiwayatKK" class="btn btn-danger btn-block"><span class="fa fa-th-list"></span> Pasien Memiliki Riwayat KLL/KK/PAK <br><i>(klik lihat data)</i></button>
		</div>
	</div>
	<div class='box-footer'><a class='btn btn-success' href='<?= Url::to(['index'])?>'>Kembali</a></div>
	<!-- /.box-body -->
</div>
	</div>
	<div class='col-md-9'>
		<div class='box'>
			<div class='box-header with-border'><h4>Gizi Pasien</h4></div>
			<div class='box-body'>
				<?php $form = ActiveForm::begin(); ?>
				<?= $form->field($gizi, 'diit')->textarea(['rows' => 4]) ?>
				<div class="form-group">
					<?= Html::submitButton('Tambah diit', ['class' => 'btn btn-success']) ?>
				</div>

				<?php ActiveForm::end(); ?>
				<hr>
				<h5>List Diit</h5>
				<table class='table table-bordered'>
					<tr>
						<th width=20>No</th>
						<th>Diit</th>
						<th width=300>Action</th>
					</tr>
					<?php $no=1; foreach($diit as $d){ ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $d->diit ?></td>
						<td>
							
							<a href='<?= Url::to(['label?id='.$d->id]) ?>' target='_blank' class='btn btn-primary btn-xs'>label</a>
							<a href='<?= Url::to(['hapus?id='.$d->id]) ?>' class='btn btn-danger btn-xs'>hapus</a>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
</div>
