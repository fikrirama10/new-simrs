<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;
$urlLab = Yii::$app->params['baseUrl']."dashboard/rest/list-lab";
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
    var marckup =repo.tindakan+' - Rp.'+ repo.tarif;   
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
	<?= $form->field($tambahSoap, 'idrawat')->hiddeninput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
	<?= $form->field($tambahSoap, 'iddokter')->hiddeninput(['maxlength' => true,'value'=>$model->iddokter])->label(false) ?>
	<?= $form->field($tambahSoap, 'tgl_permintaan')->hiddeninput(['maxlength' => true,'value'=>date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d'))))])->label(false) ?>
	<?= $form->field($tambahSoap, 'iduser')->hiddeninput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
	<?= $form->field($tambahSoap, 'status')->hiddeninput(['maxlength' => true,'value'=>1])->label(false) ?>
	<?= $form->field($tambahSoap, 'idpemeriksaan')->widget(Select2::classname(), [
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
	<?= $form->field($tambahSoap, 'catatan')->textarea(['rows' => 6]) ?>

	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm']) ?>
	</div>
<?php ActiveForm::end(); ?>