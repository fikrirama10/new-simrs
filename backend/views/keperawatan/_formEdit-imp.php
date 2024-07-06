<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\DokterSpesialis;
use kartik\checkbox\CheckboxX;
use kartik\time\TimePicker;
use kartik\date\DatePicker;
?>

<div class="dokter-form box box-body">

    <?php $form = ActiveForm::begin(['action' => ['/keperawatan/edit-imp?id='.$il->id.'&idrawat='.$model->id],]); ?>
	<?= $form->field($il, 'idrawat')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
	<?= $form->field($il, 'no_rm')->hiddenInput(['maxlength' => true,'value'=>$model->no_rm])->label(false) ?>
	<?= $form->field($il, 'idpetugas')->hiddenInput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
	
	<?= $form->field($il, 'implementasi')->textArea(['rows' => 5])->label('Implementasi Kemerawatan') ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
