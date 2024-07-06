<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\RuanganJenis;
use common\models\RuanganKelas;
use common\models\RuanganGender;
use common\models\RawatJenis;
use kartik\checkbox\CheckboxX;
use kartik\file\FileInput;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $model common\models\Ruangan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ruangan-form box box-body">

    <?php $form = ActiveForm::begin(); ?>

     <?= $form->field($model, 'idjenis')->dropDownList(ArrayHelper::map(RuanganJenis::find()->all(), 'id', 'ruangan_jenis'),['prompt'=>'- Jenis Ruangan -','required'=>true])->label('Jenis Ruangan')?>

    <?= $form->field($model, 'nama_ruangan')->textInput(['maxlength' => true]) ?>


    <?= $form->field($model, 'gender')->dropDownList(ArrayHelper::map(RuanganGender::find()->all(), 'id', 'gender'),['prompt'=>'- Jenis Ruangan -','required'=>true])->label('Ruangan Untuk')?>
    <?= $form->field($model, 'idkelas')->dropDownList(ArrayHelper::map(RuanganKelas::find()->all(), 'id', 'kelas'),['prompt'=>'- Kelas Ruangan -','required'=>true])->label('Kelas Ruangan')?>


    <?= $form->field($model, 'status')->widget(CheckboxX::classname(), [
		'initInputType' => CheckboxX::INPUT_CHECKBOX,
		'autoLabel' => false
	])->label(false); ?>

    <?= $form->field($model, 'keterangan')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
