<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\DokterSpesialis;
use common\models\ObatSuplier;
use kartik\checkbox\CheckboxX;
use kartik\file\FileInput;
use kartik\date\DatePicker;
use yii\web\View;
use yii\web\JsExpression;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model common\models\BarangPenerimaan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="barang-penerimaan-form box box-body">
	<div class='row'>
		<div class='col-md-6'>
			 <?php $form = ActiveForm::begin(); ?>

			<?= $form->field($model, 'no_faktur')->textInput(['maxlength' => true]) ?>

			<?=	$form->field($model, 'tgl')->widget(DatePicker::classname(),[
					'type' => DatePicker::TYPE_COMPONENT_APPEND,
					'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'yyyy-mm-dd'
					]
					])->label('Tgl Faktur')?>

			<?= $form->field($model, 'idsuplier')->dropDownList(ArrayHelper::map(ObatSuplier::find()->all(), 'id', 'suplier'),['prompt'=>'- Suplier -'])->label('Suplier')?>

			<div class="form-group">
				<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
			</div>

			<?php ActiveForm::end(); ?>

		</div>
	</div>
   
</div>
