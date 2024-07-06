<?php 
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\web\View;
?>
<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($detail, 'idpenerimaan')->hiddeninput(['value'=>$model->id])->label(false) ?>
	<?= $form->field($detail, 'idbarang')->hiddeninput(['value'=>$il->id])->label(false) ?>
	<?= $form->field($detail, 'harga')->hiddeninput(['value'=>$il->harga])->label(false) ?>
	<?= $form->field($detail, 'qty')->textinput()->label('Jumlah') ?>
	<?= Html::submitButton('+', ['class' => 'btn btn-success btn-sm']) ?>
<?php ActiveForm::end(); ?>