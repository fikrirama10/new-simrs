<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;
?>
<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($operasi, 'diagnosisprabedah')->textArea(['required'=>true,'rows'=>4])->label('Diagnosis Pra Bedah') ?>
	<?= Html::submitButton('Kirim', ['class' => 'btn btn-success','id'=>'confirm2']) ?>
<?php ActiveForm::end(); ?>