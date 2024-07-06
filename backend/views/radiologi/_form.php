<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Radiologi;
use common\models\RadiologiPelayanan;
use common\models\RawatBayar;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\web\View;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model common\models\RadiologiTindakan */
/* @var $form yii\widgets\ActiveForm */
$urlTindakan = Yii::$app->params['baseUrl'].'dashboard/rest/tarif-rad';
$formatJs = <<< 'JS'
var formatTindakan = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.tindakan+'- Rp. '+repo.tarif;   
    return marckup ;
};
var formatRepoTindakan = function (repo) {
    return repo.tindakan || repo.text;
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

<div class="radiologi-tindakan-form box box-body">

    <?php $form = ActiveForm::begin(); ?>

     <?= $form->field($model, 'idrad')->dropDownList(ArrayHelper::map(Radiologi::find()->all(), 'id', 'radiologi'),['prompt'=>'- Jenis Radiologi -'])->label('Radiologi')?>
     <?= $form->field($model, 'idpelayanan')->dropDownList(ArrayHelper::map(RadiologiPelayanan::find()->all(), 'id', 'nama_pelayanan'),['prompt'=>'- Pelayanan Radiologi -'])->label('Pelayanan Radiologi ')?>

    <?= $form->field($model, 'nama_tindakan')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'idtindakan')->widget(Select2::classname(), [
								'name' => 'kv-repo-template',
								'options' => ['placeholder' => 'Tarif Tindakan .....'],
								'pluginOptions' => [
								'allowClear' => true,
								'minimumInputLength' => 3,
								'ajax' => [
								'url' => $urlTindakan,
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
							])->label('Tarif Tindakan');?>
							
    <?= $form->field($model, 'keterangan')->textarea(['rows' => 6]) ?>
	<?= $form->field($model, 'status')->widget(CheckboxX::classname(), [
						'initInputType' => CheckboxX::INPUT_CHECKBOX,
						'autoLabel' => false
					])->label(false); ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
