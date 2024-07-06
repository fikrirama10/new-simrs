<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatStatus;
?>

	<?php $form = ActiveForm::begin(); ?>

	<?= $form->field($model, 'anamnesa')->textarea(['maxlength' => true,'rows'=>4,'required'=>true])->label() ?>
	<?= $form->field($model, 'planing')->textarea(['maxlength' => true,'rows'=>4,'required'=>true])->label() ?>
	<?= $form->field($rawat, 'status')->dropDownList(ArrayHelper::map(RawatStatus::find()->where(['ket'=>1])->all(), 'id', 'status'),['prompt'=>'--Cara Keluar--','required'=>true])->label('Keluar')?>
	<div class="form-group">
		<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
	</div>
	<?php ActiveForm::end(); ?>
			