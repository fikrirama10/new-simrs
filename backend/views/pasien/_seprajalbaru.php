<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\Rawat;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

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

<div class='box'>
	<div class='box-header with-border'><h4><?= $rawat->jenisrawat->jenis?></h4></div>
	<div class='box-body'>
		<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
			<div class="form-group">
				<label class="col-sm-4 control-label">Spesialis/SubSpesialis</label>
				<div class="col-sm-8">
				<input type='text' class='form-control' readonly value='<?= $rawat->poli->poli ?>'>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">DPJP yang Melayani</label>
				<div class="col-sm-8">
				<input type='text' class='form-control' readonly value='<?= $rawat->dokter->nama_dokter ?>'>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Tgl.SEP
				</label>
				<div class="col-md-4">
				<div class="input-group">
					<input type="text" class="form-control" id="txttglsep" value="<?= date('Y-m-d',strtotime($rawat->tglmasuk))?>" maxlength="10" disabled="">
					<span class="input-group-addon">
						<span class="fa fa-calendar">
						</span>
					</span>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">PPK Asal Rujukan</label>
				<div class="col-sm-8">
				<input type='text' class='form-control' readonly value='<?= $rujukan['rujukan']['provPerujuk']['nama'] ?>'>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Tgl.Rujukan
				</label>
				<div class="col-md-4">
				<div class="input-group date ">
					<input type="text" class="form-control" id="txttglsep" value="<?= $rujukan['rujukan']['tglKunjungan'] ?>" maxlength="10" disabled="">
					<span class="input-group-addon">
						<span class="fa fa-calendar">
						</span>
					</span>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">No. Rujukan</label>
				<div class="col-sm-8">
				<input type='text' class='form-control' readonly value='<?= $rujukan['rujukan']['noKunjungan']?>'>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-4 control-label">Diagnosa</label>
				<div class="col-sm-6" style='margin-left:15px;'>
						<?= $form->field($rawat, 'icdx')->widget(Select2::classname(), [
							'name' => 'kv-repo-template',
							'value' => $rujukan['rujukan']['diagnosa']['nama'],
							'options' => ['placeholder' =>$rujukan['rujukan']['diagnosa']['nama'] ],
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
						])->label(false);?>
					</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Keluhan</label>
				<div class="col-sm-8">
				<textarea class='form-control' readonly placeholder='<?= $rujukan['rujukan']['keluhan'] ?>'></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Catatan</label>
				<div class="col-sm-8">
				<textarea class='form-control' placeholder='ketik catatan apabila ada'></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label">Status Kecelakaan </label>
				<div class="col-sm-6">
					<select id='kecelakaan' name='Rawat[jaminan]' required class='form-control'>											
							<option value=''>-- Silahkan pilih --</option>
							<option value='0'>Bukan Kecelakaan</option>
							<option value='1'>Kecelakaan Lalu lintas dan bukan kecelakaan Kerja</option>
							<option value='2'>Kecelakaan Lalu lintas dan kecelakaan Kerja</option>
							<option value='3'>Kecelakaan Kerja</option>
					</select>					
				</div>

			</div>
			<div id='suplesi' class="form-group">
			 
					<div class='col-md-12'>
						<div style=' width:100%; padding-top:10px; padding-left:10px; padding-bottom:10px; min-height:100px; overflow:hidden; background:#f5f5f5;'>
							<div class="form-group">
							  <label class="col-sm-4 control-label">Tanggal Kejadian</label>
							  <div class="col-sm-4">
									<input type='date' name='RawatJaminanBpjs[tglkejadian]' id='tglkejadian'  class='form-control' max='<?= date('Y-m-d',strtotime('+7 hour'))?>'>
							  </div>
								
							</div>
							<div class="form-group">
								  <label class="col-sm-4 control-label">No. LP</label>
								  <div class="col-sm-7">
										<input type='text' name='RawatJaminanBpjs[noLp]' class='form-control' >
								  </div>										
							</div>
							<?php $provinsi = Yii::$app->vclaim->get_provinsi(); ?>
							<div class="form-group">
								  <label class="col-sm-4 control-label">Lokasi Kejadian</label>
								  <div class="col-sm-7">
										<select id='provinsi' name='RawatJaminanBpjs[propinsi]' class='form-control'>
												<option value=''>-- Silahkan Pilih --</option>
											<?php foreach($provinsi['list'] as $prov){ ?>
												<option value='<?= $prov['kode']?>'><?= $prov['nama']?></option>
											<?php } ?>
										</select>
								  </div>										
							</div>
							<div class="form-group">
								  <label class="col-sm-4 control-label"></label>
								  <div class="col-sm-7">
										<select id='idkabupaten' name='RawatJaminanBpjs[kabupaten]'  class='form-control'>
											
										</select>
								  </div>										
							</div>
							<div class="form-group">
								  <label class="col-sm-4 control-label"></label>
								  <div class="col-sm-7">
										<select id='idkecamatan' name='RawatJaminanBpjs[kecamatan]'   class='form-control'>
											
										</select>
								  </div>										
							</div>
							
							<div class="form-group">
								<label class="col-sm-4 control-label"></label>
								<div class="col-sm-7">
									<textarea class='form-control' name='RawatJaminanBpjs[keterangan]' placeholder='ketik keterangan kejadian' rows=4></textarea>
									<input type='hidden' value=0 name='RawatJaminanBpjs[suplesi]'  id='suplesi_bpjs'>
									<input type='hidden' value=0 name='RawatJaminanBpjs[nosep_suplesi]'  id='nosep_suplesi'>
								</div>
							</div>
							</div>
					</div>
			</div>
			
			<input type='hidden' id='tglkejadian_value'>
			<input type='hidden' id='jaminan_value'>
			<input type='hidden' id='provinsi_value'>
			<input type='hidden' id='idkab_value'>
			<input type='hidden' id='idkec_value'>
			
			<div class="box-footer">
				<a href='<?= Url::to(['pasien/'.$pasien->id])?>' class='btn btn-danger pull-left'>Batal</a>
				<button type="submit" id='confirm2' class="btn btn-info pull-right ">Simpan</button>
			</div>
		<?php ActiveForm::end(); ?>
	</div>
</div>