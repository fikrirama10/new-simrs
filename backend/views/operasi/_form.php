<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\Dokter;
use kartik\select2\Select2;
use common\models\DokterSpesialis;
use kartik\checkbox\CheckboxX;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\web\View;
use yii\web\JsExpression;
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

var formatTindakan = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.nama;   
    return marckup ;
};
var formatRepoTindakan = function (repo) {
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

/* @var $this yii\web\View */
/* @var $model common\models\Operasi */
/* @var $form yii\widgets\ActiveForm */
?>


<div class="operasi-form">

    <?php $form = ActiveForm::begin(); ?>

  

    <?=	$form->field($model, 'tgl_ok')->widget(DatePicker::classname(),[
			'type' => DatePicker::TYPE_COMPONENT_APPEND,
			'pluginOptions' => [
			'autoclose'=>true,
			'format' => 'yyyy-mm-dd'
			]
			])->label('Tgl Operasi')?>

    <?= $form->field($model, 'iddokter')->dropDownList(ArrayHelper::map(Dokter::find()->all(), 'id', 'nama_dokter'),['prompt'=>'- Dokter Operator -'])->label('Dokter Operator')?>

    <?= $form->field($model, 'diagnosisprabedah')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'laporan_pembedahan')->textarea(['rows' => 6]) ?>


    <?= $form->field($model, 'icd10')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Cari ICD X .....'],
						'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => "https://simrs.rsausulaiman.com/apites/listdiagnosa",
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
					])->label('ICD X');?>
					
	<?= $form->field($model, 'icd9')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Cari ICD IX .....'],
						'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => "https://simrs.rsausulaiman.com/apites/listprosedur",
						'dataType' => 'json',
						'delay' => 250,
						'data' => new JsExpression('function(params) { return {q:params.term};}'),
						'processResults' => new JsExpression($resultsJs),
						'cache' => true
						],
						'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
						'templateResult' => new JsExpression('formatTindakan'),
						'templateSelection' => new JsExpression('formatRepoTindakan'),
						],
					])->label('Procedure ICD 9');?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
