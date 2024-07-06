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
<?php $form = ActiveForm::begin(['action' => ['/keperawatan/edit-cppt?id='.$cl->id.'&idrawat='.$model->id],]); ?>
<?= $form->field($cl, 'idrawat')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
<?= $form->field($cl, 'no_rm')->hiddenInput(['maxlength' => true,'value'=>$model->no_rm])->label(false) ?>
<?= $form->field($cl, 'idpetugas')->hiddenInput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
<table class='table table-bordered'>
	<tr>
		<th width=200 align=right>Profesi (PPA)</td>
		<td colspan=2 ><?= $form->field($cl, 'profesi')->dropDownList([ 'Dokter' => 'Dokter', 'Bidan' => 'Bidan', 'Perawat' => 'Perawat', ], ['prompt' => ''])->label(false) ?></td>
	</tr>
	<tr>
		<th rowspan=5 align=right>Hasil Asesmen Pasien (SOAP)</td>		
	</tr>
	<tr>
		<th width=10>S</td>
		<td><?= $form->field($cl, 'subjektif')->textArea(['rows' => 4])->label(false) ?></td>
	</tr>
	<tr>
		<th width=10>O</td>
		<td><?= $form->field($cl, 'objektif')->textArea(['rows' => 4])->label(false) ?></td>
	</tr>
	<tr>
		<th width=10>A</td>
		<td><?= $form->field($cl, 'asesmen')->textArea(['rows' => 4])->label(false) ?></td>
	</tr>
	<tr>
		<th width=10>P</td>
		<td><?= $form->field($cl, 'plan')->textArea(['rows' => 4])->label(false) ?></td>
	</tr>
	<tr>
		<td colspan=3><?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?></td>
	</tr>
</table>
<?php ActiveForm::end(); ?>