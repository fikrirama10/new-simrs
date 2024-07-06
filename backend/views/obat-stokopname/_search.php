<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ObatStokOpnameSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obat-stok-opname-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode_so') ?>

    <?= $form->field($model, 'tgl_so') ?>

    <?= $form->field($model, 'idperiode') ?>

    <?= $form->field($model, 'jam_mulai') ?>

    <?php // echo $form->field($model, 'jam_selesai') ?>

    <?php // echo $form->field($model, 'lama') ?>

    <?php // echo $form->field($model, 'selisih') ?>

    <?php // echo $form->field($model, 'keterangan') ?>

    <?php // echo $form->field($model, 'iduser') ?>

    <?php // echo $form->field($model, 'idgudang') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
