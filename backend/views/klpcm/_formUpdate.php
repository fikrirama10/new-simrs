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
			<div class="box-header with-border"><h4>KLPCM</h4></div>
			<div class="box-body">
			<?php $form = ActiveForm::begin([
					 'type' => ActiveForm::TYPE_HORIZONTAL,
						'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_SMALL]
				]); ?>
			<div class="form-group">
				<label class="col-sm-4 control-label">No RM</label>
				<div class="col-sm-5">
					<?= $form->field($klpcm, 'no_rm')->textInput(['readonly'=>true])->label(false) ?>
				</div>
				
			</div>
			
			<div class="form-group">
					<label class="col-sm-4 control-label">ICD 10</label>
					<div class="col-sm-5" style='margin-bottom:-20px;' >
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
						])->label(false);?>
						  

					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Kategori Diagnosa</label>
					<div class="col-sm-4" style='margin-bottom:-20px;'>
						 <?= $form->field($klpcm, 'kat_diagnosa')->dropDownList(ArrayHelper::map(KategoriDiagnosa::find()->all(), 'id', 'jenisdiagnosa'),['prompt'=>'- kategori Diagnosa -'])->label(false)?>
						
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
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"></label>
					<div class="col-sm-5">
						<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
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