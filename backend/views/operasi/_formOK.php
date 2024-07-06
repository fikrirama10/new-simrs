<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\Dokter;
use common\models\RawatBayar;
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
$urlTarif = Yii::$app->params['baseUrl']."dashboard/rest/tarif-ok";
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

<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($tindakan, 'idtindakan')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Tindakan OK .....'],
						'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => $urlTarif,
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
					])->label('Tindakan OK');?>
					<?= $form->field($tindakan, 'keterangan_tindakan')->textArea(['maxlength' => true]) ?>
					 <?= $form->field($tindakan, 'iddokter')->dropDownList(ArrayHelper::map(Dokter::find()->all(), 'id', 'nama_dokter'),['prompt'=>'- Dokter Operator -'])->label('Dokter Operator')?>
					 <?= $form->field($tindakan, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Jenis Bayar -'])->label('Jenis Bayar')?>
	<?= $form->field($tindakan, 'idok')->hiddeninput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
	<?= $form->field($tindakan, 'idrawat')->hiddeninput(['maxlength' => true,'value'=>$model->idrawat])->label(false) ?>
	<?= $form->field($tindakan, 'no_rm')->hiddeninput(['maxlength' => true,'value'=>$model->iddokter])->label(false) ?>
	<hr><hr>
	  <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>