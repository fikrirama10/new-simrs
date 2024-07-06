<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Radiologi;
use common\models\LaboratoriumLayanan;
use common\models\RawatBayar;
/* @var $this yii\web\View */
/* @var $model common\models\RadiologiTindakan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="radiologi-tindakan-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($item, 'status')->hiddenInput(['maxlength' => true,'value'=>1])->label(false) ?>
    <?= $form->field($item, 'idpelayanan')->hiddenInput(['maxlength' => true,'value'=>$model->idlab])->label(false) ?>
    <?= $form->field($item, 'idpemeriksaan')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
    <?= $form->field($item, 'form')->textInput(['maxlength' => true])->label('Nama Item') ?>
    <?= $form->field($item, 'satuan')->textInput(['maxlength' => true])?>
    <?= $form->field($item, 'nilai_normallaki')->textInput(['maxlength' => true])->label('Nilai Normal Laki laki')?>
    <?= $form->field($item, 'nilai_normalp')->textInput(['maxlength' => true])->label('Nilai Normal Perempuan')?>
    <?= $form->field($item, 'urutan')->textInput(['maxlength' => true])->label('Urutan Hasil')?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
