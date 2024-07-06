<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\checkbox\CheckboxX;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\KategoriDiagnosa;

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
<div class="row">
	<div class="col-sm-12">
		<div class="box">
			<div class="box-header with-border">
				<h4>KLPCM</h4>
			</div>
			<div class="box-body">
				<?php $form = ActiveForm::begin([
					'type' => ActiveForm::TYPE_HORIZONTAL,
					'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
				]); ?>
				<div class="form-group">
					<label class="col-sm-4 control-label">No RM</label>
					<div class="col-sm-3">
						<input type='text' name="RawatSpri[no_rm]" id='no_rm' class='form-control'>
					</div>
					<span class="col-sm-2 input-group-btn">
						<button type="button" id="show-rm" class="btn btn-info btn-sm btn-flat">Cek RM</button>
					</span>
				</div>

				<div class="form-group">
					<label class="col-sm-4 control-label"></label>
					<div class="col-sm-8">

					</div>
				</div>
				<div id='ruangan-ajax'></div>
				<div id='icdx'>
					<div class="form-group">
						<label class="col-sm-4 control-label">ICD 10</label>
						<div class="col-sm-5" style='margin-bottom:-20px;'>
							<?= $form->field($klpcm, 'icdx')->widget(Select2::classname(), [
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
						<label class="col-sm-4 control-label">Kategori Diagnosa</label>
						<div class="col-sm-4" style='margin-bottom:-20px;'>
							<?= $form->field($klpcm, 'kat_diagnosa')->dropDownList(ArrayHelper::map(KategoriDiagnosa::find()->all(), 'id', 'jenisdiagnosa'), ['prompt' => '- kategori Diagnosa -', 'required' => true])->label(false) ?>

						</div>

					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Jenis Pelayanan</label>
						<div class="col-sm-4" style='margin-bottom:-20px;'>
							<?= $form->field($klpcm, 'jenis_pelayanan')->dropDownList(['Bedah' => 'Bedah', 'Non Bedah' => 'Non Bedah', 'Kebidanan' => 'Kebidanan', 'Psikiatrik' => 'Psikiatrik', 'Anak' => 'Anak',], ['prompt' => '-- Jenis Pelayanan  --', 'required' => true])->label(false) ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Jenis Kunjungan Pasien</label>
						<div class="col-sm-4" style='margin-bottom:-20px;'>
							<?= $form->field($klpcm, 'jenis_pasien')->dropDownList(['Non Rujukan' => 'Non Rujukan', 'Rujukan' => 'Rujukan', 'Dirawat' => 'Dirawat', 'Dirujuk' => 'Dirujuk', 'Mati di UGD' => 'Mati di UGD', 'DOA' => 'DOA',], ['prompt' => '-- Jenis Pelayanan  --', 'required' => true])->label(false) ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"></label>
						<div class="col-sm-2">
							<?= $form->field($klpcm, 'keterbacaan')->widget(CheckboxX::classname(), [
								'initInputType' => CheckboxX::INPUT_CHECKBOX,
								'autoLabel' => false
							])->label(false); ?>

						</div>
						<div class="col-sm-2">
							<?= $form->field($klpcm, 'kelengkapan')->widget(CheckboxX::classname(), [
								'initInputType' => CheckboxX::INPUT_CHECKBOX,
								'autoLabel' => false
							])->label(false); ?>

						</div>

						<div class="col-sm-4">
							<?= $form->field($klpcm, 'obat')->widget(CheckboxX::classname(), [
								'initInputType' => CheckboxX::INPUT_CHECKBOX,
								'autoLabel' => false
							])->label('apakah ada obat ?'); ?>

						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label"></label>
						<div class="col-sm-5">
							<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
						</div>
					</div>

				</div>
				<?= $form->field($klpcm, 'tgl_kunjungan')->hiddenInput(['maxlength' => true])->label(false) ?>
				<?= $form->field($klpcm, 'iddokter')->hiddenInput(['maxlength' => true])->label(false) ?>
				<?= $form->field($klpcm, 'idjenisrawat')->hiddenInput(['maxlength' => true])->label(false) ?>
				<?= $form->field($klpcm, 'no_rm')->hiddenInput(['maxlength' => true])->label(false) ?>
				<?= $form->field($klpcm, 'idrawat')->hiddenInput(['maxlength' => true])->label(false) ?>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>
<?php


$urlShowPasien = Url::to(['klpcm/show-rm']);
$this->registerJs("
$('#icdx').hide();
	$('#show-rm').on('click',function(){
			$('#ruangan-ajax').hide();
			rm = $('#no_rm').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowPasien}',
				data: 'id='+rm,
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

	
           
	

", View::POS_READY);


?>