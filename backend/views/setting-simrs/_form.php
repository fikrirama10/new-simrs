<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\SettingSimrs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="setting-simrs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'kode_rs')->textInput(['maxlength' => true,'required'=>true]) ?>

    <?= $form->field($model, 'nama_rs')->textInput(['maxlength' => true,'required'=>true]) ?>
    <?= $form->field($model, 'direktur_rs')->textInput(['maxlength' => true,'required'=>true]) ?>
    <?= $form->field($model, 'no_tlp')->textInput(['maxlength' => true,'required'=>true]) ?>

    <?= $form->field($model, 'alamat_rs')->textarea(['rows' => 6,'required'=>true]) ?>
	<div class='row'>
		<div class='col-md-6'>
			<?= $form->field($model, 'logo_rs')->widget(FileInput::classname(), [
				'options' => ['accept' => 'Image/*'],
				'pluginOptions'=>['allowedFileExtensions'=>['jpg','gif','png']]]);
			?>	
		</div>
	</div>
    

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
