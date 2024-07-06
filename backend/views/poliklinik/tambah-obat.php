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

$urlObat= Yii::$app->params['baseUrl']."dashboard/rest/list-obat";
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.nama;   
    return marckup ;
};
var formatRepoSelection = function (repo) {
    return repo.nama || repo.text;
}

JS;
 
// Register the formatting script
$this->registerJs($formatJs, View::POS_HEAD);
 
// script to parse the results into the format expected by Select2
$resultsJs = <<< JS
function (data) {    
    return {
        results: data,
        
    };
}
JS;

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
	<!-- /.box-body -->
	</div>
	</div>
	
	<div class='col-md-9'>
		<div class='box'>
			<div class='box-header with-border'><h3>Tambah Obat</h3></div>
			<div class='box-body'>
				<table class='table table-bordered'>
					<tr>
						<th>Nomer Resep</th>
						<td><?= $model->kode_resep ?></td>
					</tr>
				</table>
				<hr>
				<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($resep_obat, 'idobat')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Obat .....'],
						'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => $urlObat,
						'dataType' => 'json',
						'delay' => 250,
						'data' => new JsExpression('function(params) { return {q:params.term};}'),
						'processResults' => new JsExpression($resultsJs),
						'cache' => true
						],
						'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
						'templateResult' => new JsExpression('formatRepo'),
						'templateSelection' => new JsExpression('formatRepoSelection'),
						],
					])->label('Obat / Alkes');?>
				
				<div id='show-bacth'></div>
				<?php ActiveForm::end(); ?>
				<hr>
				<?php if(count($obat_list) > 0){ ?>
					<table class='table table-bordered'>
						<tr>
							<th>No</th>
							<th>Merk</th>
							<th>Jumlah</th>
							<th>signa</th>
							<th>Dosis</th>
							<th>#</th>
						</tr>
						<?php $no=1; foreach($obat_list as $ol): ?>
						<tr>
							<td width=10><?= $no++ ?></td>
							<td><?= $ol->merk->merk ?></td>
							<td><?= $ol->qty ?></td>
							<td><?= $ol->signa1 ?> x <?= $ol->signa2?></td>
							<td><?= $ol->dosis ?></td>
							<td>
								<?php if($model->status == 1){ ?>
								<a class='btn btn-danger btn-xs' href='<?= Url::to(['hapus-obat?id='.$ol->id])?>'>Hapus</a>
								<?php } ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</table>
				<?php } ?>
			</div>
			<div class='box-footer'>
				<a href='<?= Url::to(['view?id='.$model->idrawat])?>' class='btn btn-warning'>Simpan</a>
			</div>
		</div>
	</div>
</div>
<?php
$urlShowAll = Url::to(['poliklinik/show-batch']);
$this->registerJs("
	$('#show-bacth').hide();
	$('#form-obat').hide();
	$('#rawatresepdetail-idobat').on('change',function(){
			id = $('#rawatresepdetail-idobat').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+id,
				beforeSend: function(){
				$('#loading').show();
				},
				success: function (data) {
					$('#show-bacth').show();
					$('#show-bacth').animate({ scrollTop: 0 }, 200);
					$('#show-bacth').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				$('#loading').hide();
				}
			});
		
	});

	
           
	

", View::POS_READY);


?>