<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PersonelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="personel-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode_personel') ?>

    <?= $form->field($model, 'nik') ?>

    <?= $form->field($model, 'no_bpjs') ?>

    <?= $form->field($model, 'nrp_nip') ?>

    <?php // echo $form->field($model, 'nama_lengkap') ?>

    <?php // echo $form->field($model, 'alamat') ?>

    <?php // echo $form->field($model, 'idjabatan') ?>

    <?php // echo $form->field($model, 'idpangkat') ?>

    <?php // echo $form->field($model, 'foto') ?>

    <?php // echo $form->field($model, 'no_tlp') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'idjenis') ?>

    <?php // echo $form->field($model, 'mulai_bergabung') ?>

    <?php // echo $form->field($model, 'akhir_bergabung') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
