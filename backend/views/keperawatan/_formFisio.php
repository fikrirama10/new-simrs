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
// $urlTindakan = "http://localhost/simrs2021/dashboard/rest/tarif";
$urlRadiologi = Yii::$app->params['baseUrl']."dashboard/rest/fisio";
// $urlLab = "http://localhost/simrs2021/dashboard/rest/list-lab";
$formatJs = <<< 'JS'


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

?>
	<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($fisio, 'idrawat')->hiddeninput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
		<?= $form->field($fisio, 'iddokterpeminta')->hiddeninput(['maxlength' => true,'value'=>$model->iddokter])->label(false) ?>
		<?= $form->field($fisio, 'tgl')->hiddeninput(['maxlength' => true,'value'=>date('Y-m-d H:i:s',strtotime('+6 hour',strtotime(date('Y-m-d H:i:s'))))])->label(false) ?>
		<?= $form->field($fisio, 'status')->hiddeninput(['maxlength' => true,'value'=>1])->label(false) ?>
		<?= $form->field($fisio, 'idbayar')->hiddeninput(['maxlength' => true,'value'=>$model->idbayar])->label(false) ?>
		<?= $form->field($fisio, 'idtindakan')->widget(Select2::classname(), [
			'name' => 'kv-repo-template',
			'options' => ['placeholder' => 'Fisio .....'],
			'pluginOptions' => [
			'allowClear' => true,
			'minimumInputLength' => 3,
			'ajax' => [
			'url' => $urlRadiologi,
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
		])->label('Fisio');?>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
<?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm2']) ?>
</div>
<?php ActiveForm::end(); ?>