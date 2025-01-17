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
use common\models\TransaksiBayar;
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
	

		
		<table class=''>
		<tr>
				<th width=100>Obat</th>
				<td width=400>
					
					<?= $form->field($resep_detail, 'idobat')->widget(Select2::classname(), [
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
				<td><input type='text' readonly id='merk-obat' class='form-control'></td>
				<td><input type='text' readonly name='ObatFarmasiDetail[harga]' id='harga-obat' class='form-control'>\</td>
			</tr>
			
			<tr>
				<th>Jumlah</th>
				<td  width=400><input type='text' id='soaprajalobat-jumlah' name='ObatFarmasiDetail[jumlah]'  class='form-control'></td>
			</tr>
			<tr>
				<th width=100>Signa</th>
				<td width=400>
			
					<div class="input-group">
					<input type='text' id='soaprajalobat-signa1' name='ObatFarmasiDetail[signa]' class='form-control' >
					</div>
			
				</td>
			</tr>
			<tr>
				<th width=100>Dosis</th>
				<td width=400>
			
					<div class="input-group">
					<input type='text' id='soaprajalobat-signa1' name='ObatFarmasiDetail[dosis]' class='form-control' >
					</div>
			
				</td>
			</tr>
			<tr>
				<th>Takaran</th>
				<td><?= $form->field($resep_detail, 'takaran')->dropDownList([ 'tablet' => 'tablet', 'kapsul' => 'kapsul', 'bungkus' => 'bungkus', 'tetes' => 'tetes', 'ml' => 'ml' ,'sendok takar 5ml' => 'sendok takar 5ml','sendok takar 15ml' => 'sendok takar 15ml', ])->label(false) ?></td>
			</tr>
			<tr>
				<th>Diminum</th>
				<td><?= $form->field($resep_detail, 'diminum')->dropDownList([ 'Sebelum Makan' => 'Sebelum Makan', 'Sesudah Makan' => 'Sesudah Makan', ])->label(false) ?></td>
			</tr>
			<tr>
				<th>Catatan</th>
				<td><?= $form->field($resep_detail, 'keterangan')->textarea(['maxlength' => true])->label(false) ?>
				<?= $form->field($resep_detail, 'idbacth')->hiddeninput(['id'=>'idbatch'])->label(false) ?>
				<?= $form->field($resep_detail, 'idresep')->hiddeninput(['value'=>$model->id])->label(false) ?>
				
				</td>
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
$urlShowAll = Url::to(['resep/show-batch']);
$this->registerJs("
	$('#obat-batch').hide();
	$('#lanjutan').hide();

	$('#obatfarmasidetail-idobat').on('change',function(){
			$('#obat-batch').show();
			id = $('#obatfarmasidetail-idobat').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+id,
				beforeSend: function(){
				$('#loading').show();
				},
				success: function (data) {
					if(data !== null){
						$('#obat-batch').show();
						$('#obat-batch').animate({ scrollTop: 0 }, 200);
						$('#obat-batch').html(data);						
						console.log(data);
					}else{
						$('#obat-batch').hide();
					}
					
				},
				complete:function(data){
				$('#loading').hide();
				}
			});
		
	});

	
           
	

", View::POS_READY);


?>