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
		<?= $form->field($model, 'anamnesa')->textarea(['maxlength' => true,'rows'=>6,'required'=>true])->label() ?>
		<?= $form->field($rawat, 'status')->hiddeninput(['maxlength' => true, 'value'=>$rawat->status])->label(false) ?>
		
		<div class='row'>
			<div class='col-md-6'><?= $form->field($model, 'distole')->textinput(['maxlength' => true,'required'=>true])->label('Diastole') ?></div>
			<div class='col-md-6'><?= $form->field($model, 'sistole')->textinput(['maxlength' => true,'required'=>true])->label('Sistole') ?></div>
		</div>
		<div class='row'>
			<div class='col-md-3'><?= $form->field($model, 'suhu')->textinput(['maxlength' => true]) ?></div>
			<div class='col-md-3'><?= $form->field($model, 'respirasi')->textinput(['maxlength' => true]) ?></div>
			<div class='col-md-3'><?= $form->field($model, 'saturasi')->textinput(['maxlength' => true]) ?></div>
			<div class='col-md-3'><?= $form->field($model, 'nadi')->textinput(['maxlength' => true]) ?></div>
		</div>
		<div class='row'>
			<div class='col-md-12'><?= $form->field($model, 'tinggi')->textinput(['maxlength' => true])->label('Tinggi Badan') ?></div>
			<div class='col-md-12'><?= $form->field($model, 'berat')->textinput(['maxlength' => true])->label('Berat Badan') ?></div>
		</div>

	<div class="form-group">
		<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
	</div>
<?php ActiveForm::end(); ?>