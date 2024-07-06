<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Ruangan;
use common\models\RuanganJenis;
use common\models\RuanganKelas;
use common\models\RuanganGender;
use common\models\RawatJenis;
use kartik\checkbox\CheckboxX;
use kartik\file\FileInput;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\RuanganBed */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ruangan-bed-form box box-body">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'idruangan')->dropDownList(ArrayHelper::map(Ruangan::find()->all(), 'id', 'nama_ruangan'),['prompt'=>'- Nama Ruangan -','readonly'=>true])->label('Ruangan')?>
	<?= $form->field($model, 'idjenis')->dropDownList(ArrayHelper::map(RuanganJenis::find()->all(), 'id', 'ruangan_jenis'),['prompt'=>'- Jenis Ruangan -','required'=>true])->label('Jenis Ruangan')?>	
	<?= $form->field($model, 'status')->widget(CheckboxX::classname(), [
		'initInputType' => CheckboxX::INPUT_CHECKBOX,
		'autoLabel' => false
	])->label(false); ?>
    <div class="form-group">
        <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
