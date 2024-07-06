<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ObatTransaksi */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obat-transaksi-form">

    <?php $form = ActiveForm::begin(); ?>
	 <?= $form->field($resep, 'idrawat')->hiddenInput(['value' => $model->id])->label(false) ?>
	 <?= $form->field($resep, 'no_rm')->hiddenInput(['value' => $model->no_rm])->label(false) ?>
	 <?= $form->field($resep, 'iddokter')->hiddenInput(['value' => $model->iddokter])->label(false) ?>
	 <?= $form->field($resep, 'idjenisrawat')->hiddenInput(['value' => $model->idjenisrawat])->label(false) ?>
	<label>Tgl Resep</label>
	<input type='date' class='form-control' name="RawatResep[tgl_resep]">
	<br>
    <div class="form-group">
        <?= Html::submitButton('Buat', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
