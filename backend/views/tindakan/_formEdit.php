<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\RawatBayar;
use kartik\money\MaskMoney;
?>
	<?php $form = ActiveForm::begin(['action' => ['/tindakan/edit-tarif?id='.$tn->id],]); ?>
		<?= $form->field($tn, 'idtindakan')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
		<?= $form->field($tn, 'idtarif')->hiddenInput(['maxlength' => true,'value'=>$model->idtarif])->label(false) ?>
		<?=  $form->field($tn, 'tarif')->widget(MaskMoney::classname(), [
				'pluginOptions' => [
					'prefix' => 'Rp ',
					'allowNegative' => false,
					'allowEmpty' => false,
				]
		]); ?>
		<?= $form->field($tn, 'persentase_dokter')->textInput(['maxlength' => true]) ?>
		<?= $form->field($tn, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Pembayaran -'])->label('Pembayaran')?>
		

		<div class="form-group">
			<?= Html::submitButton('Save', ['class' => 'btn btn-success','id'=>'confirm']) ?>
		</div>

    <?php ActiveForm::end(); ?>