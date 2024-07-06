<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\UnitRuangan;
use common\models\AmprahGudangobatDetail;
/* @var $this yii\web\View */
/* @var $model common\models\AmprahGudangobat */
/* @var $form yii\widgets\ActiveForm */
$detail = AmprahGudangobatDetail::findOne($iddetail);
?>

<div class="amprah-gudangobat-form">
	<div class='row'>
		<div class='col-md-6'>
		<div class='box box-body'>
			<?php $form = ActiveForm::begin(); ?>
				<?= $form->field($tarik, 'idamprah')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
				<?= $form->field($tarik, 'nama_obat')->hiddenInput(['maxlength' => true,'value'=>$detail->obat->nama_obat])->label(false) ?>
				<?= $form->field($tarik, 'idobat')->hiddenInput(['maxlength' => true,'value'=>$detail->idobat])->label(false) ?>
				<?= $form->field($tarik, 'iddetail')->hiddenInput(['maxlength' => true,'value'=>$detail->id])->label(false) ?>
				<?= $form->field($tarik, 'idbacth')->hiddenInput(['maxlength' => true,'value'=>$detail->idbacth])->label(false) ?>
				<?= $form->field($tarik, 'jumlah_asal')->textInput(['maxlength' => true,'value'=>$detail->jumlah_diserahkan,'readonly'=>true]) ?>
				<?= $form->field($tarik, 'jumlah')->textInput(['maxlength' => true])->label('Jumlah di tarik') ?>
				<?= $form->field($tarik, 'keterangan')->textArea(['maxlength' => true,]) ?>

			<div class="form-group">
				<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
			</div>

			<?php ActiveForm::end(); ?>
		</div>
		</div>
	</div>
    

</div>