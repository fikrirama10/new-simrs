<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SettingSimrsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-simrs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'kode_rs') ?>

    <?= $form->field($model, 'nama_rs') ?>

    <?= $form->field($model, 'alamat_rs') ?>

    <?= $form->field($model, 'logo_rs') ?>

    <?php // echo $form->field($model, 'direktur_rs') ?>

    <?php // echo $form->field($model, 'idtema') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
