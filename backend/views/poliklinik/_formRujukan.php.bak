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

    <?php $form = ActiveForm::begin(); ?>
	<?= $form->field($rujukan, 'idrawat')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
	<?= $form->field($rujukan, 'no_rm')->hiddenInput(['maxlength' => true,'value'=>$model->no_rm])->label(false) ?>
	<?= $form->field($rujukan, 'idjenisrawat')->hiddenInput(['maxlength' => true,'value'=>$model->idjenisrawat])->label(false) ?>
	<?= $form->field($rujukan, 'idpoli')->hiddenInput(['maxlength' => true,'value'=>$model->idpoli])->label(false) ?>
	<?= $form->field($rujukan, 'iddokter')->hiddenInput(['maxlength' => true,'value'=>$model->iddokter])->label(false) ?>
	<?= $form->field($rujukan, 'idbayar')->hiddenInput(['maxlength' => true,'value'=>$model->iddokter])->label(false) ?>
	<?= $form->field($rujukan, 'tujuan_rujuk')->textInput(['required'=>true])?>
	<?= $form->field($rujukan, 'diagnosa_klinis')->textInput(['required'=>true])->label('Diagnosa Rujuk')?>
	<?= $form->field($rujukan, 'tgl_kunjungan')->hiddenInput(['maxlength' => true,'value'=>$model->tglmasuk])->label(false) ?>
	<?=	$form->field($rujukan, 'tgl_rujuk')->widget(DatePicker::classname(),['type' => DatePicker::TYPE_COMPONENT_APPEND,
		'pluginOptions' => [
		'autoclose'=>true,
		'format' => 'yyyy-mm-dd',
		'required'=>true
	]])?>
	<?= $form->field($rujukan, 'alasan_rujuk')->textarea(['rows' => 2,'required'=>true])?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
