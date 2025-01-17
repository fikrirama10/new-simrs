<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\UnitRuangan;
/* @var $this yii\web\View */
/* @var $model common\models\AmprahGudangobat */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="amprah-gudangobat-form">
	<div class='row'>
		<div class='col-md-6'>
		<div class='box box-body'>
			<?php $form = ActiveForm::begin(); ?>
				<?=	$form->field($model, 'tgl_permintaan')->widget(DatePicker::classname(),['type' => DatePicker::TYPE_COMPONENT_APPEND,'pluginOptions' => ['autoclose'=>true,'format' => 'yyyy-mm-dd']])->label('Tgl Pemberian')?>

				 <?= $form->field($model, 'idpeminta')->dropDownList(ArrayHelper::map(UnitRuangan::find()->all(), 'id', 'ruangan'),['prompt'=>'- Ruangan -'])->label('Ruangan')?>

				<?= $form->field($model, 'keterangan')->textInput(['maxlength' => true]) ?>

			<div class="form-group">
				<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
			</div>

			<?php ActiveForm::end(); ?>
		</div>
		</div>
	</div>
    

</div>
