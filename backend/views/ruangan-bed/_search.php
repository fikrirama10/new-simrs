<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RuanganBedSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ruangan-bed-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'idruangan') ?>

    <?= $form->field($model, 'idbed') ?>

    <?= $form->field($model, 'kodebed') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'idjenis') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
