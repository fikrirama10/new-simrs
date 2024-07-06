<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BarangStokopname */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="barang-stokopname-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'keterangan')->textArea(['maxlength' => true , 'rows'=>4]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
