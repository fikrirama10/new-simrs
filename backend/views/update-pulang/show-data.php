<?php
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
//$response = Yii::$app->bpjs->surat_kontrol($kontrol);
$carapulang = Yii::$app->bpjs->get_carakeluar();
// print_r($response);
?>
<?php $form = ActiveForm::begin([
	'action' => ['update-pulang/pulang?id='.$sep],
	'method' => 'post',
	'options' => [
		'enctype' => 'multipart/form-data',
		'class' => 'form-horizontal'
	]
]); ?>
<div class="form-group">
	<label class="col-md-5 col-sm-5 col-xs-12 control-label">Status Pulang</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<select id='carapulang' required name='carapulang' class='form-control'>
			<?php foreach($carapulang['response']['list'] as $pulang){ ?>
				<option value='<?= $pulang['kode']?>'><?= $pulang['nama']?></option>
			<?php } ?>
		</select>
	</div>
</div>
<div id='meninggal'>
	<div class='form-group'>
		<label class="col-md-5 col-sm-5 col-xs-12 control-label">Tgl.Meninggal</label>
		<div class="col-md-6 col-sm-6 col-xs-12">
			<input type='date' class='form-control' id='tglmeninggal' name='tglmeninggal'>
		</div>
	</div>
	<div class='form-group'>
		<label class="col-md-5 col-sm-5 col-xs-12 control-label">No.Surat Ket.Meninggal </label>
		<div class="col-md-6 col-sm-6 col-xs-12">
			<input type='text' class='form-control' id='nosurat' name='nosurat'>
		</div>
	</div>
</div>
<div class='form-group'>
	<label class="col-md-5 col-sm-5 col-xs-12 control-label">Tgl.Pulang</label>
	<div class="col-md-6 col-sm-6 col-xs-12">
		<input type='date' class='form-control' required id='tglpulang' name='tglpulang'>
	</div>
</div>
<div class="form-group">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<button class='btn btn-success'>Simpan</button>
	</div>
</div>
<?php ActiveForm::end(); ?>
<?php 
$urlMeninngal = Url::to(['update-pulang/show-meninggal']);
$this->registerJs("
	$('#meninggal').hide();
	$('#carapulang').on('change',function(e) {
		pulang = $('#carapulang').val();
		if(pulang == 4){
			$('#meninggal').show();
			$('#nosurat').prop('required',true);
			$('#tglmeninggal').prop('required',true);
		}else{
			$('#meninggal').hide();
			$('#nosurat').prop('required',false);
			$('#tglmeninggal').prop('required',false);
		}
	});
", View::POS_READY);

?>