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
		</table>
		<div id='lanjutan'>
		<table class=''>			
			<tr>
				<th>Stok Obat</th>
				<td><input type='text' readonly id='stok-obat' class='form-control'></td>
				<th>Harga</th>
				<td><input type='text' readonly id='harga-obat' class='form-control'></td>
			</tr>
			
			<tr>
				<th>Jumlah</th>
				<td  width=400><input type='text' id='soaprajalobat-jumlah' name='ObatTransaksiDetail[qty]'  class='form-control'></td>
			</tr>
			<tr>
				<th width=100>Signa</th>
				<td width=400>
			
					<div class="input-group">
					<input type='text' id='soaprajalobat-signa1' name='ObatTransaksiDetail[signa]' class='form-control' >
					</div>
			
				</td>
			</tr>
			<tr>
				<th width=100>Dosis</th>
				<td width=400>
			
					<div class="input-group">
					<input type='text' id='soaprajalobat-signa1' name='ObatTransaksiDetail[dosis]' class='form-control' >
					</div>
			
				</td>
			</tr>
			<tr>
				<th>Takaran</th>
				<td><?= $form->field($resep_detail, 'takaran')->dropDownList([''=>'', 'tablet' => 'tablet', 'kapsul' => 'kapsul', 'bungkus' => 'bungkus', 'tetes' => 'tetes', 'ml' => 'ml' ,'sendok takar 5ml' => 'sendok takar 5ml','sendok takar 15ml' => 'sendok takar 15ml','Oles'=>'Oles' ])->label(false) ?></td>
			</tr>
			<tr>
				<th>Diminum</th>
				<td><?= $form->field($resep_detail, 'diminum')->dropDownList([ ''=>'','Sebelum' => 'Sebelum Makan', 'Sesudah' => 'Sesudah Makan', ])->label(false) ?></td>
			</tr>
			<tr>
				<th>Catatan</th>
				<td><?= $form->field($resep_detail, 'keterangan')->textarea(['maxlength' => true])->label(false) ?>
					<?= $form->field($resep_detail, 'idbayar')->dropDownList(ArrayHelper::map(TransaksiBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Jenis Obat -','required'=>true])->label(false)?>
				<?= $form->field($resep_detail, 'idbatch')->hiddeninput(['id'=>'idbatch'])->label(false) ?>
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
$urlShowAll = Url::to(['resep-new/get-stok']);
$this->registerJs("
	$('#obat-batch').hide();
	$('#lanjutan').hide();

	$('#soaprajalobat-jumlah').on('keyup',function(){
		jumlah = $('#soaprajalobat-jumlah').val();
		stok = $('#stok-obat').val();
		hasil = stok - jumlah;
		if(hasil < 0){
			alert('Stok Kurang'+hasil);
			$('#soaprajalobat-jumlah').val('');
		}
		
	});
	$('#obattransaksidetail-idobat').on('change',function(){
			id = $('#obattransaksidetail-idobat').val();
			
			$.ajax({
				type: 'POST',
					url: '{$urlShowAll}',
					data: {id: id},
					dataType: 'json',
					success: function (data) {
						if(data == 201){
							alert('Stok Obat Kosong');
							$('#lanjutan').hide();
						}else{
							var res = JSON.parse(JSON.stringify(data));
							$('#lanjutan').show();
							$('#stok-obat').val(res.stok_apotek);
							$('#harga-obat').val(res.harga_jual);
						}
					},
					error: function (exception) {
						alert(exception);
					}
				});	
			});
	
	
           
	

", View::POS_READY);


?>