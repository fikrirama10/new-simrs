<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\DataBarangKategori;
use common\models\DataSatuan;
use kartik\checkbox\CheckboxX;
/* @var $this yii\web\View */
/* @var $model common\models\DataBarang */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-barang-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($dataBarang, 'nama_barang')->textInput(['maxlength' => true]) ?>

   <?= $form->field($dataBarang, 'idkategori')->dropDownList(ArrayHelper::map(DataBarangKategori::find()->all(), 'id', 'kategori'),['prompt'=>'- kategori barang -'])->label('Kategori')?>
   <?= $form->field($dataBarang, 'idsatuan')->dropDownList(ArrayHelper::map(DataSatuan::find()->all(), 'id', 'satuan'),['prompt'=>'- Satuan barang -'])->label('Satuan')?>


    <?= $form->field($dataBarang, 'harga')->textInput() ?>
    <?= $form->field($dataBarang, 'status')->widget(CheckboxX::classname(), [
		'initInputType' => CheckboxX::INPUT_CHECKBOX,
		'autoLabel' => false
	])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
