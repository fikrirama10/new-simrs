<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DataBarangKategori */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="data-barang-kategori-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kategori')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
