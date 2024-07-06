<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use common\models\RawatBayar;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model common\models\UsulPesan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="usul-pesan-form">
	<div class='row'>
		<div class='col-md-6'>
			<?php $form = ActiveForm::begin(); ?>
			<div class='box'>
				<div class='box-header with-border'><h4>Pengajuan Usul Pesan</h4></div>
				<div class='box-body'>
					

					<?=	$form->field($model, 'tgl_up')->widget(DatePicker::classname(),[
						'type' => DatePicker::TYPE_COMPONENT_APPEND,
						'pluginOptions' => [
						'autoclose'=>true,
						'format' => 'yyyy-mm-dd',
						'required'=>true

						]
						])->label('Tgl Pengajuan')?>
					<?= $form->field($model, 'jenis_up')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Jenis Pesanan -','required'=>true])->label('Jenis Pesanan')?>
					<?= $form->field($model, 'catatan')->textArea(['maxlength' => true,'required'=>true]) ?>
					
					
				
				</div>
				<div class='box-footer with-border'>
					<div class="form-group">
						<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
					</div>
				</div>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
    

</div>
