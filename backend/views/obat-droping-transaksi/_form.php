<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\ObatDropingJenis;
/* @var $this yii\web\View */
/* @var $model common\models\ObatDropingTransaksi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obat-droping-transaksi-form">
	<div class='row'>
		<div class='col-md-6'>
		<div class='box box-body'>
		<?php $form = ActiveForm::begin(); ?>
	
			<?= $form->field($model, 'idjenis')->dropDownList(ArrayHelper::map(ObatDropingJenis::find()->all(), 'id', 'jenis'),['prompt'=>'- Jenis Transaksi -','required'=>true])->label('Jenis')?>

			<?= $form->field($model, 'ketrangan')->textarea(['rows' => 6]) ?>
			<label>Tgl Transaksi</label>
			<input type='date' class='form-control' name="ObatDropingTransaksi[tgl]">
			<hr>

			<div class="form-group">
				<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
			</div>

		<?php ActiveForm::end(); ?>
		</div>
		</div>
	</div>
    

</div>
