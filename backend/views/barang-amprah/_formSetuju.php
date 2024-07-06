<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;

?>
<?php $form = ActiveForm::begin(['action' => ['/barang-amprah/edit-setuju?id='.$pl->id.'&idpermintaan='.$model->id],]); ?>
	<?= $form->field($pl, 'idpermintaan')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
	<?= $form->field($pl, 'qty_setuju')->textInput(['maxlength' => true])->label('Jumlah Disetujui') ?>
	<?= $form->field($pl, 'keterangan')->textArea(['maxlength' => true])->label('Keterangan') ?>
	<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>