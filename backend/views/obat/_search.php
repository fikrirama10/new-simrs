<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ObatSeacrh */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="obat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'nama_obat') ?>

    <?= $form->field($model, 'kandungan') ?>

    <?= $form->field($model, 'min_stokgudang') ?>

    <?= $form->field($model, 'min_stokapotek') ?>

    <?php // echo $form->field($model, 'idjenis') ?>

    <?php // echo $form->field($model, 'idsatuan') ?>

    <?php // echo $form->field($model, 'narkotika') ?>

    <?php // echo $form->field($model, 'psikotropika') ?>

    <?php // echo $form->field($model, 'antibiotik') ?>

    <?php // echo $form->field($model, 'fornas') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
