<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BarangPenerimaanSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="barang-penerimaan-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'no_faktur') ?>

    <?= $form->field($model, 'tgl') ?>

    <?= $form->field($model, 'idsuplier') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'total_penerimaan') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
