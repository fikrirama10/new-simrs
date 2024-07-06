<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use common\models\ObatJenis;
use yii\helpers\ArrayHelper;
use common\models\ObatSatuan;
/* @var $this yii\web\View */
/* @var $model common\models\ObatDroping */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
	<div class="col-md-6">
		<div class="obat-form box box-body">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nama_obat')->textInput(['maxlength' => true])->label('Nama Obat / Alkes') ?>

    <?= $form->field($model, 'idjenis')->dropDownList(ArrayHelper::map(ObatJenis::find()->all(), 'id', 'jenis'),['prompt'=>'- Jenis Obat -','required'=>true])->label('Jenis')?>

   <?= $form->field($model, 'idsatuan')->dropDownList(ArrayHelper::map(ObatSatuan::find()->all(), 'id', 'satuan'),['prompt'=>'- Jenis Satuan -','required'=>true])->label('Satuan')?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
</div>
