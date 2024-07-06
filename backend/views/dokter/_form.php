<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\DokterSpesialis;
use kartik\checkbox\CheckboxX;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\web\View;
use yii\web\JsExpression;
use kartik\select2\Select2;
$urlDpjp = "http://localhost/simrs2021/dashboard/rest/dpjp";
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

<div class="dokter-form box box-body">
	<div class='row'>
	<div class='col-md-6'>
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nama_dokter')->textInput(['maxlength' => true,'required'=>true]) ?>
	<div class='row'>
		<div class='col-md-6'>
			<?= $form->field($model, 'jenis_kelamin')->dropDownList([ 'L' => 'L', 'P' => 'P', ], ['prompt' => '']) ?>
		</div>
		<div class='col-md-6'>
			<?=	$form->field($model, 'tgl_lahir')->widget(DatePicker::classname(),[
			'type' => DatePicker::TYPE_COMPONENT_APPEND,
			'pluginOptions' => [
			'autoclose'=>true,
			'format' => 'yyyy-mm-dd'
			]
			])->label('Tgl Lahir')?>
		</div>
	</div>
	
    
	<?= $form->field($model, 'bpjs')->widget(CheckboxX::classname(), [
		'initInputType' => CheckboxX::INPUT_CHECKBOX,
		'autoLabel' => false
	])->label(false); ?>
	<div id='dpjp'>
	<?= $form->field($model, 'kode_dpjp')->widget(Select2::classname(), [
		'name' => 'kv-repo-template',
		'options' => ['placeholder' => 'Dokter DPJP.....'],
		'pluginOptions' => [
		'allowClear' => true,
		'minimumInputLength' => 3,
		'ajax' => [
		'url' => $urlDpjp,
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
	])->label('Kode Dokter BPJS');?>
	</div>
	<div class='row'>
		<div class='col-md-6'>
			<?= $form->field($model, 'idspesialis')->dropDownList(ArrayHelper::map(DokterSpesialis::find()->all(), 'id', 'spesialis'),['prompt'=>'- Spesialis -'])->label('Spesialis')?>
		</div>
		<div class='col-md-6'>
			<?= $form->field($model, 'idpoli')->dropDownList(ArrayHelper::map(Poli::find()->all(), 'id', 'poli'),['prompt'=>'- Poli -'])->label('Poli')?>
		</div>
	</div>
	
	

	

    <?= $form->field($model, 'foto')->widget(FileInput::classname(), [
		'options' => ['accept' => 'Image/*'],
		'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif','png']]]);
	?>	
	<?= $form->field($model, 'status')->widget(CheckboxX::classname(), [
		'initInputType' => CheckboxX::INPUT_CHECKBOX,
		'autoLabel' => false
	])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
<?php 
$this->registerJs("
	$('#dpjp').hide();
	$('#dokter-bpjs').on('change', function(event){
	$('#dpjp').show();
	});
", View::POS_READY);
?>