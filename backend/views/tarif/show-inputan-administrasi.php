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
					<td><?= $form->field($model, 'perekam_medis')->textInput() ?></td>				
					<td><?= $form->field($model, 'bph')->textInput() ?></td>
					<td><?= $form->field($model, 'opsrs')->textInput() ?></td>
					
				</tr>
			</table>
			
			
			
	</div>
</div>	