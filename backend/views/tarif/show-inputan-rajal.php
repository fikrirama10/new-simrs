<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View ;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div class='box'>
	<div class='box-header with-border'><h4>Rincian Tarif</h4></div>
	<div class='box-body'>
		<?php $form = ActiveForm::begin(); ?>
			<table class='table table-bordered'>
				<tr>
					<td><?= $form->field($model, 'medis')->textInput() ?></td>
					<td><?= $form->field($model, 'paramedis')->textInput() ?></td>
					<td><?= $form->field($model, 'petugas')->textInput() ?></td>
					<td><?= $form->field($model, 'apoteker')->textInput() ?></td>
					<td><?= $form->field($model, 'gizi')->textInput() ?></td>
					<td><?= $form->field($model, 'bph')->textInput() ?></td>
					<td><?= $form->field($model, 'sewaalat')->textInput() ?></td>
					<td><?= $form->field($model, 'opsrs')->textInput() ?></td>
					<td><?= $form->field($model, 'nova_t')->textInput() ?></td>
				</tr>
			</table>
			
	</div>
</div>