<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\RawatBayar;
/* @var $this yii\web\View */
/* @var $model common\models\PermintaanObat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permintaan-obat-form box box-body">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'keterangan')->textarea(['rows' => 6])->label('Catatan Permintaan') ?>\
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
