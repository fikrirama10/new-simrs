<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TindakanSeach */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tindakan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama_tindakan') ?>

    <?= $form->field($model, 'kode_tindakan') ?>

    <?= $form->field($model, 'idjenistindakan') ?>

    <?= $form->field($model, 'idpoli') ?>

    <?php // echo $form->field($model, 'idbayar') ?>

    <?php // echo $form->field($model, 'idpenerimaan') ?>

    <?php // echo $form->field($model, 'idjenispenerimaan') ?>

    <?php // echo $form->field($model, 'tarif') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'tarif_bpjs') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
