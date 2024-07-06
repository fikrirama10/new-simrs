<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use common\models\Radiologi;
use common\models\LaboratoriumLayanan;
use common\models\RawatBayar;
$urlPenunjang = Yii::$app->params['baseUrl'].'dashboard/rest/tarif-lab';
$formatJs = <<< 'JS'
var formatTindakan = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.tindakan+' - Rp. '+repo.tarif;   
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

     <?= $form->field($model, 'idlab')->dropDownList(ArrayHelper::map(LaboratoriumLayanan::find()->all(), 'id', 'nama_layanan'),['prompt'=>'- Jenis Pelayanan -'])->label('Jenis Pelayanan')?>

    <?= $form->field($model, 'nama_pemeriksaan')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'idtarif')->widget(Select2::classname(), [
								'name' => 'kv-repo-template',
								'options' => ['placeholder' => 'Tarif .....'],
								'pluginOptions' => [
								'allowClear' => true,
								'minimumInputLength' => 3,
								'ajax' => [
								'url' => $urlPenunjang,
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
							])->label('Tarif');?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
