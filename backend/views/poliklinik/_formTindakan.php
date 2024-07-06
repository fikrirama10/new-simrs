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
use common\models\RawatBayar;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;
use yii\web\View;
$rawat_bayar = RawatBayar::find()->all();
$urlTindakan = Yii::$app->params['baseUrl']."dashboard/rest/tarif-rawat?idjenis=".$model->idjenisrawat;
// $urlRadiologi = "http://localhost/simrs2021/dashboard/rest/list-radiologi";
// $urlLab = "http://localhost/simrs2021/dashboard/rest/list-lab";
$formatJs = <<< 'JS'


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
	<?= $form->field($soaptindakan, 'idrawat')->hiddeninput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
	<?= $form->field($soaptindakan, 'idbayar')->hiddeninput(['maxlength' => true,'value'=>$model->idbayar])->label(false) ?>
	<?= $form->field($soaptindakan, 'tgltindakan')->hiddeninput(['maxlength' => true,'value'=>date('Y-m-d')])->label(false) ?>
	<?= $form->field($soaptindakan, 'idkunjungan')->hiddeninput(['maxlength' => true,'value'=>$model->idkunjungan])->label(false) ?>
	<?= $form->field($soaptindakan, 'iduser')->hiddeninput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
	<?= $form->field($soaptindakan, 'idtindakan')->widget(Select2::classname(), [
		'name' => 'kv-repo-template',
		'options' => ['placeholder' => 'Tindakan Dokter .....'],
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
	])->label('Tindakan Dokter');?>
	<select class='form-control' id='jenis-bayar' name='bayar' required>
		<option >Jenis Bayar</option>
		<?php foreach($rawat_bayar as $rb): ?>
		<option value='<?= $rb->id ?>'><?= $rb->bayar ?></option>
		<?php endforeach; ?>
		
	</select>
	<hr>
	<?= $form->field($soaptindakan, 'keterangan')->textinput(['maxlength' => true]) ?>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
<?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm2']) ?>
</div>
<?php ActiveForm::end(); ?>