<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\Rawat;
use common\models\RawatBayar;
use common\models\KategoriPenyakit;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
// if($bpjs['metaData']['code'] == 200){
// $kelas = RuanganKelas::find()->where(['between','id',$bpjs['response']['peserta']['hakKelas']['kode'],3])->all();
// }else{
$kelas = RuanganKelas::find()->where(['ket' => 1])->all();
//} 
$ruangan = Ruangan::find()->where(['id' => 0])->all();
$bayar = RawatBayar::find()->all();
$kat_penyakit = KategoriPenyakit::find()->all();
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
	<div class='col-md-4'>
		<div class='box'>
			<div class='box-header with-border'>
				<h4>Data Pasien</h4>
			</div>
			<div class='box-body'>
				<?= DetailView::widget([
					'model' => $pasien,
					'attributes' => [
						'no_rm',
						'nik',
						'no_bpjs',
						'nama_pasien',
						'tgllahir',
						'tempat_lahir',
						'nohp',
						'usia_tahun',
						'kepesertaan_bpjs',
						'pekerjaan.pekerjaan',
					],
				]) ?>
			</div>
		</div>
	</div>
	<div class='col-md-8'>
		<div class='box'>
			<div class='box-header with-border'>
				<h4>Rawat Inap</h4>
			</div>
			<div class='box-body'>
				<?php if ($model->status == 2) { ?>
					<div class="callout callout-success">
						<h4>SPRI Sudah digunakan</h4>

						<p>Pasien sudah masuk ruangan rawat inap menggunakan SPRI tersebut</p>
					</div>
					<a href='<?= Url::to(['admisi/']) ?>' class='btn btn-info'>Kembali</a>
				<?php } else { ?>

					<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>

					<div class="box-body">
						<div class="form-group">
							<label class="col-sm-4 control-label">Tgl Rujukan</label>
							<div class="col-sm-8">
								<input type='text' class='form-control' readonly value='<?= $model->tgl_rawat ?>'>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">DPJP Pemberi Surat SKDP/SPRI</label>
							<div class="col-sm-8">
								<input type='hidden' class='form-control' name='Rawat[iddokter]' readonly value='<?= $model->iddokter ?>'>
								<input type='hidden' class='form-control' name='Rawat[idpoli]' readonly value='<?= $model->idpoli ?>'>
								<input type='text' class='form-control' readonly value='<?= $model->dokter->nama_dokter ?>'>
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-4 control-label">No.SPRI</label>
							<div class="col-sm-8">
								<input type='text' name='Rawat[no_referal]' class='form-control' value='<?= $model->no_spri	?>'>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">No.Rujukan</label>
							<div class="col-sm-8">
								<input type='text' required name='Rawat[no_rujukan]' class='form-control' value=''>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Kategori Perawatan</label>
							<div class="col-sm-4">
								<select id='rawat-idjenisperawatan' name='Rawat[idjenisperawatan]' class='form-control'>
									<option value=''>-- Kategori Penyakit --</option>
									<?php foreach ($kat_penyakit as $kp) : ?>
										<option value='<?= $kp->id ?>'><?= $kp->kategori ?></option>
									<?php endforeach; ?>
								</select>

							</div>
							<div class="col-md-3"><input type="checkbox" id="operasi" name="Rawat[operasi]" value="1">
								<label for="operasi">Tindakan Bedah ?</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Kelas</label>
							<div class="col-sm-3">
								<select id='kelas' name='Rawat[idkelas]' class='form-control'>
									<option value=''>-- Kelas --</option>
									<?php foreach ($kelas as $k) : ?>
										<option value='<?= $k->id ?>'><?= $k->kelas ?></option>
									<?php endforeach; ?>
								</select>

							</div>

						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Ruangan</label>
							<div class="col-sm-3">
								<select id='rawat-idruangan' name='Rawat[idruangan]' class='form-control'>
									<?php foreach ($ruangan as $r) : ?>
										<option value='<?= $r->id ?>'><?= $r->nama_ruangan ?></option>
									<?php endforeach; ?>
								</select>

							</div>
							<span class="input-group-btn">
								<button type="button" id="show-ruangan" class="btn btn-success btn-sm btn-flat">Cek Tempat Tidur</button>
							</span>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label"></label>
							<div class="col-sm-8">
								<div id='loading' style='display:none;'>
									<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
								</div>
								<div id='ruangan-ajax'></div>
							</div>

						</div>
						<div id='admisi-ranap'>
							<div class="form-group">
								<label class="col-sm-4 control-label">Diagnosa ranap</label>
								<div class="col-sm-6" style='margin-left:15px;'>
									<?= $form->field($rawat, 'icdx')->widget(Select2::classname(), [
										'name' => 'kv-repo-template',
										'options' => ['placeholder' => 'Cari ICD X .....'],
										'pluginOptions' => [
											'allowClear' => true,
											'minimumInputLength' => 3,
											'ajax' => [
												'url' => "https://new-simrs.rsausulaiman.com/auth/listdiagnosa",
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
									])->label(false); ?>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label">Bed</label>
								<div class="col-sm-8">
									<input type='text' id='kodebed' class='form-control' readonly>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Penanggung</label>
								<div class="col-sm-4">
									<select id='bayar' name='Rawat[idbayar]' class='form-control'>
										<?php foreach ($bayar as $b) : ?>
											<option value='<?= $b->id ?>'><?= $b->bayar ?></option>
										<?php endforeach; ?>
									</select>
								</div>

							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label"></label>
								<div class="col-sm-4">
									<input type="checkbox" id="vehicle1" name="Rawat[anggota]" value="1">
									<label for="vehicle1">Anggota ?</label><br>
								</div>

							</div>
							<button type="submit" id='confirm2' class="btn btn-info pull-right ">Simpan</button>
						</div>

					</div>
					<!-- /.box-body -->
					<div class="box-footer">

					</div>
					<?= $form->field($rawat, 'idbed')->hiddeninput(['maxlength' => true, 'readonly' => true,])->label(false) ?>
					<?= $form->field($rawat, 'no_rm')->hiddeninput(['maxlength' => true, 'value' => $model->no_rm])->label(false) ?>
					<?= $form->field($rawat, 'idjenisrawat')->hiddeninput(['maxlength' => true, 'value' => 2])->label(false) ?>
					<?php ActiveForm::end(); ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

<?php
$urlKelas = Url::to(['pasien/list-ruangan']);
$urlShowRuangan = Url::to(['admisi/show-ruangan']);
$this->registerJs("
	$('#admisi-ranap').hide();
	$('#kelas').on('change',function(){
			kelas = $('#kelas').val();
			$.ajax({
				type: 'GET',
				url: '{$urlKelas}',
				data: 'id='+kelas,
				
				success: function (data) {
					$('#rawat-idruangan').html(data);
					
					console.log(data);
					
				},
				
			});
	});
	$('#show-ruangan').on('click',function(){
			$('#ruangan-ajax').hide();
			ruangan = $('#rawat-idruangan').val();
		bayi = '{$model->bayi_lahir}';
			$.ajax({
				type: 'GET',
				url: '{$urlShowRuangan}',
				data: 'id='+ruangan+'&bayi='+bayi,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#ruangan-ajax').show();
					$('#ruangan-ajax').animate({ scrollTop: 0 }, 200);
					$('#ruangan-ajax').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});
		
	});
	$('#confirm2').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
", View::POS_READY);


?>