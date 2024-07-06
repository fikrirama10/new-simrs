<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RawatSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rawat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idrawat') ?>

    <?= $form->field($model, 'idkunjungan') ?>

    <?= $form->field($model, 'idjenisrawat') ?>

    <?= $form->field($model, 'no_rm') ?>

    <?php // echo $form->field($model, 'idpoli') ?>

    <?php // echo $form->field($model, 'iddokter') ?>

    <?php // echo $form->field($model, 'idruangan') ?>

    <?php // echo $form->field($model, 'idkelas') ?>

    <?php // echo $form->field($model, 'idbayar') ?>

    <?php // echo $form->field($model, 'no_sep') ?>

    <?php // echo $form->field($model, 'no_rujukan') ?>

    <?php // echo $form->field($model, 'no_suratkontrol') ?>

    <?php // echo $form->field($model, 'tglmasuk') ?>

    <?php // echo $form->field($model, 'tglpulang') ?>

    <?php // echo $form->field($model, 'los') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'no_antrian') ?>

    <?php // echo $form->field($model, 'cara_datang') ?>

    <?php // echo $form->field($model, 'cara_keluar') ?>

    <?php // echo $form->field($model, 'kunjungan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
