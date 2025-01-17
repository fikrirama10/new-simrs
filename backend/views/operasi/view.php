<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\Dokter;
use common\models\RawatCppt;
use common\models\RawatImplementasi;
use common\models\SoapDiagnosajenis;
use common\models\LaboratoriumHasil;
use common\models\RadiologiHasilDetail;
use common\models\RawatPermintaanPindah;
use common\models\RawatAwalinap;

$lab = LaboratoriumHasil::find()->where(['idrawat'=>$rawat->id])->all();
$rad = RadiologiHasilDetail::find()->where(['idrawat'=>$rawat->id])->all();
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
							<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $rawat->tglmasuk ?> </span>
						</li>
						<?php if($rawat->status != 2){ ?>
						<li class="list-group-item">
							<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $rawat->tglpulang ?> </span>
						</li>
						<?php } ?>
						<li class="list-group-item">
							<span class="fa fa-user-md"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $rawat->dokter->nama_dokter ?> </span>
						</li>
						<li class="list-group-item">
							<span class="fa fa-bed"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $rawat->ruangan->nama_ruangan ?> </span>
						</li>
						<li class="list-group-item">
							<span class="fa  fa-money"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $rawat->bayar->bayar ?> </span>
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
			<div class='box-header with-border'><h3>Operasi</h3></div>
			<div class='box-body'>
				<a href='<?= Url::to(['operasi/update/'.$model->id])?>' class='btn btn-default'>Tulis Laporan Pembedahan</a>
				<a data-toggle="modal" data-target="#mdTindakan" class='btn btn-warning'>Tindakan / Tarif Pembedahan</a>
				<hr>
				<div class='row'>
					<div class='col-md-12'>
						<?= DetailView::widget([
								'model' => $model,
								'attributes' => [
									'diagnosisprabedah',
									'laporan_pembedahan',
									'icd10',
									'icd9',
								],
						]) ?>
					</div>
					<div class='col-md-12'>
						<table class='table table-bordered'>
							<tr>
								<th>Tindakan</th>
								<th>Keterangan</th>
								<th>Harga</th>
								<th>BHP</th>
								<th>Dokter</th>
								<th>#</th>
							</tr>
							<?php foreach($tindakan_ok as $to){ ?>
							<tr>
								<td><?= $to->tarif->nama_tarif ?></td>
								<td><?= $to->keterangan_tindakan ?></td>
								<td><?= $to->tarif->tarif ?></td>
								<td><?= $to->harga_bhp ?></td>
								<td><?= $to->dokter->nama_dokter ?></td>
								<td><a href='<?= Url::to(['hapus-tindakan?id='.$to->id]) ?>' class='btn btn-danger btn-xs'>hapus</a> <a href='<?= Url::to(['tindakan-bhp?id='.$to->id]) ?>' class='btn btn-primary btn-xs'>BHP</a></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class='box'>
			<div class='box-header with-border'><h3>Penunjang</h3></div>
			<div class='box-body'>
				<div class='row'>
					<div class='col-md-6'>
						<h4>Laboratorium</h4>
						<table class='table table-bordered'>
							<tr>
								<th>Kode Lab</th>
								<th>Tgl Hasil</th>
							</tr>
							<?php foreach($lab as $lab){ ?>
							<tr>
								<td><a id="btlab<?= $lab->id?>"  class='btn btn-primary btn-xs'><?= $lab->labid?></a>
											<iframe src="<?= Url::to(['laboratorium/hasil-print?id='.$lab->id]) ?>" style="border:none; display:none;" id='myFrameLabel<?= $lab->id?>' title="Iframe Example">
											</iframe>
								<?php
											$this->registerJs("

											$('#btlab{$lab->id}').on('click',function(){
											let objFra = document.getElementById('myFrameLabel{$lab->id}');
											objFra.contentWindow.focus();
											objFra.contentWindow.print();
											});
											
											", View::POS_READY);
											?>
								</td>
								<td><?= $lab->tgl_hasil ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
					<div class='col-md-6'>
						<h4>Radiologi</h4>
						<table class='table table-bordered'>
							<tr>
								<th>Klinis</th>
								<th>Tindakan</th>
								<th>Tgl Hasil</th>
							</tr>
							<?php foreach($radi as $r){ ?>
							<tr>
								<td><a class='btn btn-xs btn-default' data-toggle="modal" data-target="#mdRad<?= $r->id?>"><?=  $r->klinis ?></td>
								<td><?=  $r->tindakan->nama_tindakan ?></td>
								<td><?=  $r->tgl_hasil ?></td>
							</tr>
							<?php } ?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
foreach($radi as $r){
		Modal::begin([
	'id' => 'mdRad'.$r->id,
	'header' => '<h3>Hasil Radiologi</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formHasilRad', ['model'=>$model,'r'=>$r]).'</div>';
 
Modal::end();
}
Modal::begin([
	'id' => 'mdTindakan',
	'header' => '<h3>Tindakan OK</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formOK', ['model'=>$model,'tindakan'=>$tindakan]).'</div>';
 
Modal::end();
 ?>