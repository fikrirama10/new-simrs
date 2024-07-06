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
use common\models\RawatBayar;
use kartik\time\TimePicker;
use kartik\select2\Select2;
$rawat_bayar = RawatBayar::find()->all();
$urlTindakan = Yii::$app->params['baseUrl']."dashboard/rest/tarif-penunjang";
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
<?= $form->field($tarif_rinci, 'idrawat')->hiddeninput(['maxlength' => true,'readonly'=>true,'value'=>$model->id])->label(false) ?>
	<table>
	<tr>
		<td width=340>
			<?= $form->field($tarif_rinci, 'idtarif')->widget(Select2::classname(), [
			'name' => 'kv-repo-template',
			'options' => ['placeholder' => 'Tindakan Penunjang .....'],
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
		<td width=140>
			<select class='form-control' id='jenis-bayar' name='bayar' required>
				<option value=0 >Jenis Bayar</option>
				<?php foreach($rawat_bayar as $rb): ?>
				<option value='<?= $rb->id ?>'><?= $rb->bayar ?></option>
				<?php endforeach; ?>
				
			</select>
		</td>
		
		<td><?= Html::submitButton('+', ['class' => 'btn btn-success btn-sm','id'=>'confirm']) ?></td>
	</tr>
</table>
<?php ActiveForm::end(); ?>
<?php 
$this->registerJs("
	
	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	
           
	

", View::POS_READY);


?>