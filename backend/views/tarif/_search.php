<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TarifSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarif-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode_tarif') ?>

    <?= $form->field($model, 'nama_tarif') ?>

    <?= $form->field($model, 'idkategori') ?>

    <?= $form->field($model, 'idjenisrawat') ?>

    <?php // echo $form->field($model, 'kat_tindakan') ?>

    <?php // echo $form->field($model, 'idpoli') ?>

    <?php // echo $form->field($model, 'idkelas') ?>

    <?php // echo $form->field($model, 'medis') ?>

    <?php // echo $form->field($model, 'paramedis') ?>

    <?php // echo $form->field($model, 'petugas') ?>

    <?php // echo $form->field($model, 'apoteker') ?>

    <?php // echo $form->field($model, 'gizi') ?>

    <?php // echo $form->field($model, 'bph') ?>

    <?php // echo $form->field($model, 'sewakamar') ?>

    <?php // echo $form->field($model, 'sewaalat') ?>

    <?php // echo $form->field($model, 'makanpasien') ?>

    <?php // echo $form->field($model, 'laundry') ?>

    <?php // echo $form->field($model, 'cs') ?>

    <?php // echo $form->field($model, 'opsrs') ?>

    <?php // echo $form->field($model, 'nova_t') ?>

    <?php // echo $form->field($model, 'perekam_medis') ?>

    <?php // echo $form->field($model, 'tarif') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
