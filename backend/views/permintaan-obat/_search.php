<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PermintaanObatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permintaan-obat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode_permintaan') ?>

    <?= $form->field($model, 'tgl_permintaan') ?>

    <?= $form->field($model, 'iduser_peminta') ?>

    <?= $form->field($model, 'iduser_persetujuan') ?>

    <?php // echo $form->field($model, 'keretangan') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'total_biaya') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
