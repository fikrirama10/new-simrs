<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
sleep(3);
?>
<hr>
<?php if($data_json['metadata']['code'] == 401){ ?>
	<div class="alert alert-danger" role="alert">
		<?= $data_json['metadata']['message'] ?>
	</div>
<?php }else{ ?>
	
	<div class="alert alert-info" role="alert">
		<?= $data_json['metadata']['message'] ?>
	</div>
	<div class='row'>
		<div class='col-md-8'>
			 <?php $form = ActiveForm::begin(); ?>
				<?= $form->field($model, 'no_rm')->textInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true])->label('Nomer Rekam medis')?>
				<?= $form->field($model, 'nama')->textInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true])->label('Nama Lengkap')?>
				<?= $form->field($model, 'no_telpon')->textInput(['maxlength' => true,'class'=>'form-control input-sm','required'=>true])->label('No HP / Telpon , (Pastikan nomer aktif karena untuk nomer antian akan di hubungi lewat Telpon)',['class'=>'text-danger'])?>
				<?= $form->field($model, 'email')->textInput(['maxlength' => true,'class'=>'form-control input-sm'])?>
				<div class="form-grup">
					<?= Html::submitButton($model->isNewRecord ? 'Daftar' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary btn-sm' : 'btn btn-primary btn-lg']) ?>
				</div>
		</div>
		<div class="col-md-4">
			
			<img width='100%' src='https://simrs.rsausulaiman.com/frontend/images/katber.jpg'>
		</div>
	</div>
<?php } ?>