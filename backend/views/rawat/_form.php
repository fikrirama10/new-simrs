<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Rawat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rawat-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'idrawat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idkunjungan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idjenisrawat')->textInput() ?>

    <?= $form->field($model, 'no_rm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idpoli')->textInput() ?>

    <?= $form->field($model, 'iddokter')->textInput() ?>

    <?= $form->field($model, 'idruangan')->textInput() ?>

    <?= $form->field($model, 'idkelas')->textInput() ?>

    <?= $form->field($model, 'idbayar')->textInput() ?>

    <?= $form->field($model, 'no_sep')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_rujukan')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'no_suratkontrol')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tglmasuk')->textInput() ?>

    <?= $form->field($model, 'tglpulang')->textInput() ?>

    <?= $form->field($model, 'los')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'no_antrian')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cara_datang')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cara_keluar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'kunjungan')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
