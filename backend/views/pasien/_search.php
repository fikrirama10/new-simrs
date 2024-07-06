<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PasienSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pasien-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'no_rm') ?>

    <?= $form->field($model, 'nik') ?>

    <?= $form->field($model, 'no_bpjs') ?>

    <?= $form->field($model, 'nama_pasien') ?>

    <?php // echo $form->field($model, 'tgl_lahir') ?>

    <?php // echo $form->field($model, 'tempat_lahir') ?>

    <?php // echo $form->field($model, 'usia_tahun') ?>

    <?php // echo $form->field($model, 'usia_bulan') ?>

    <?php // echo $form->field($model, 'usia_hari') ?>

    <?php // echo $form->field($model, 'idpekerjaan') ?>

    <?php // echo $form->field($model, 'idagama') ?>

    <?php // echo $form->field($model, 'idgolongan_darah') ?>

    <?php // echo $form->field($model, 'idpendidikan') ?>

    <?php // echo $form->field($model, 'idhubungan') ?>

    <?php // echo $form->field($model, 'kepesertaan_bpjs') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
