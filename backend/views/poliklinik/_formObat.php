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
$urlObat= Yii::$app->params['baseUrl']."dashboard/rest/list-obat";
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
	
	<?= $form->field($soapobat, 'idrawat')->hiddeninput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
	<?= $form->field($soapobat, 'iddokter')->hiddeninput(['maxlength' => true,'value'=>$model->iddokter])->label(false) ?>
	<?= $form->field($soapobat, 'no_rm')->hiddeninput(['maxlength' => true,'value'=>$model->no_rm])->label(false) ?>

		<table class=''>
		<tr>
				<th width=100>Obat</th>
				<td width=400>
					
					<?= $form->field($soapobat, 'idobat')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Obat .....'],
						'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => $urlObat,
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
					])->label(false);?>
				</td>
			</tr>
			<tr>
				<td colspan =2>
					<div id='obat-batch'></div>
				</td>
			<tr>
		</table>
		<div id='lanjutan'>
		<table class=''>			
			<tr>
				<th>Merk Obat</th>
				<td><input type='text' id='merk-obat' class='form-control'><input type='hidden' id='soaprajalobat-idbatch' name='SoapRajalobat[idbatch]' class='form-control'></td>
			</tr>
			<tr>
				<th width=100>Signa</th>
				<td width=400>
			
					<div class="input-group">
					<input type='text' id='soaprajalobat-signa1' name='SoapRajalobat[signa1]' class='form-control' >
					<span class="input-group-addon" id="basic-addon1">X</span>
					<input type='text' id='soaprajalobat-signa2' name='SoapRajalobat[signa2]'  class='form-control' >
					</div>
			
				</td>
			</tr>
			<tr>
				<th>Jumlah</th>
				<td><input type='text' id='soaprajalobat-jumlah' name='SoapRajalobat[jumlah]'  class='form-control'></td>
			</tr>
			<tr>
				<th>Catatan</th>
				<td><?= $form->field($soapobat, 'catatan')->textarea(['maxlength' => true])->label(false) ?></td>
			</tr>
			<tr>
				<th colspan=2>-</th>
			</tr>
			<tr>
				<th colspan=2><?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm3']) ?></th>
			</tr>
		</table>
		<hr>
		</div>


<?php ActiveForm::end(); ?>
<?php
$urlShowAll = Url::to(['poliklinik/show-batch']);
$this->registerJs("
	$('#obat-batch').hide();
	$('#lanjutan').hide();
	$('#soaprajalobat-idobat').on('change',function(){
			$('#obat-batch').show();
			id = $('#soaprajalobat-idobat').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+id,
				beforeSend: function(){
				$('#loading').show();
				},
				success: function (data) {
					$('#obat-batch').show();
					$('#obat-batch').animate({ scrollTop: 0 }, 200);
					$('#obat-batch').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				$('#loading').hide();
				}
			});
		
	});

	
           
	

", View::POS_READY);


?>