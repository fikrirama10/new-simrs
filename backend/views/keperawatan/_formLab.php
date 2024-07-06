<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatStatus;
use yii\bootstrap\Modal;
use common\models\SoapDiagnosajenis;
use common\models\SoapRajalicdx;
use common\models\KategoriDiagnosa;
use common\models\RawatRujukan;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;
use yii\web\View;
$rrujuk = RawatRujukan::find()->where(['idrawat'=>$model->id])->one(); 
$urlTindakan = Yii::$app->params['baseUrl']."dashboard/rest/tarif?jenis=".$model->idjenisrawat;
$urlRadiologi = Yii::$app->params['baseUrl']."dashboard/rest/list-radiologi";
$urlLab =Yii::$app->params['baseUrl']."dashboard/rest/list-lab";
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
    var marckup =repo.tindakan;   
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
 <?php $form = ActiveForm::begin(); ?>
					<?= $form->field($soaplab, 'idrawat')->hiddeninput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
					<?= $form->field($soaplab, 'iddokter')->hiddeninput(['maxlength' => true,'value'=>$model->iddokter])->label(false) ?>
					<?= $form->field($soaplab, 'tgl_permintaan')->hiddeninput(['maxlength' => true,'value'=>date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))))])->label(false) ?>
					<?= $form->field($soaplab, 'iduser')->hiddeninput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
					<?= $form->field($soaplab, 'status')->hiddeninput(['maxlength' => true,'value'=>1])->label(false) ?>
					<?= $form->field($soaplab, 'idpemeriksaan')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Laboratorium .....'],
						'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => $urlLab,
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
					])->label('Laboratorium');?>
					<?= $form->field($soaplab, 'catatan')->textarea(['rows' => 6]) ?>
					
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm3']) ?>
			  </div>
			  <?php ActiveForm::end(); ?>