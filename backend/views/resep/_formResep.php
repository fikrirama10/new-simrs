<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\RawatResep;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use kartik\checkbox\CheckboxX;
/* @var $this yii\web\View */
/* @var $model common\models\ObatTransaksi */
/* @var $form yii\widgets\ActiveForm */
$resepp = RawatResep::find()->where(['idrawat'=>$rawat->id])->andwhere(['status'=>1])->all();
?>


<div class="obat-transaksi-form">

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($resep, 'idresep')->dropDownList(ArrayHelper::map($resepp, 'id', 'kode_resep'),['prompt'=>'- Pilih Resep -'])->label('RESEP DOKTER')?>
	<div id='show-bacth'></div>
	<label>Tgl Resep</label>
	<input type='date' class='form-control' name="ObatTransaksi[tgl]">
	<br>
	  <?= $form->field($resep, 'obat_racikan')->widget(CheckboxX::classname(), [
		'initInputType' => CheckboxX::INPUT_CHECKBOX,
		'autoLabel' => false
	])->label(false); ?>
	<?= $form->field($resep, 'jumlahracik')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton('Buat', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
$urlShowAll = Url::to(['resep/show-resep']);
$this->registerJs("
	$('#obattransaksi-idresep').on('change',function(){
			id = $('#obattransaksi-idresep').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+id,
				beforeSend: function(){
				$('#loading').show();
				},
				success: function (data) {
					$('#show-bacth').show();
					$('#show-bacth').animate({ scrollTop: 0 }, 200);
					$('#show-bacth').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				$('#loading').hide();
				}
			});
		
	});

	
           
	

", View::POS_READY);
?>