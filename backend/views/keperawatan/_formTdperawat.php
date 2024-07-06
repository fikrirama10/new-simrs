<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\Soapkesadaran;
use common\models\Dokter;
use common\models\DataDatang;
use kartik\time\TimePicker;
use kartik\select2\Select2;
$urlTindakan =Yii::$app->params['baseUrl']."dashboard/rest/tarif";
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

<table>
	<tr>
		<td width=340>
			<?= $form->field($tindakan, 'idtindakan')->widget(Select2::classname(), [
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
			])->label(false);?>
		</td>

		<td><?= Html::submitButton('+', ['class' => 'btn btn-success btn-sm','id'=>'confirm-spri']) ?></td>
		
	</tr>
</table>
<?php ActiveForm::end(); ?>