<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\UserPrivilages;
use common\models\Dokter;
use common\models\Gudang;
use common\models\Poli;
use common\models\UserUnit;
use common\models\UnitRuangan;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\UserDetail */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
	<div class='col-md-6'>
		<div class="box box-body">

    <?php $form = ActiveForm::begin(); ?>
	<div class='row'>
		
		<div class='col-md-4'><?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?></div>
		<div class='col-md-4'><?= $form->field($model, 'nama')->textInput(['maxlength' => true]) ?></div>
		<div class='col-md-4'>  <?= $form->field($model, 'nohp')->textInput(['maxlength' => true]) ?></div>
	</div>
	<?= $form->field($model, 'alamat')->textarea(['rows' => 6]) ?>
	
	
	<div class='row'>
		
	</div>

    <?= $form->field($model, 'jenis_kelamin')->dropDownList([ 'L' => 'L', 'P' => 'P', ], ['prompt' => '']) ?>
	<?= $form->field($model, 'idruangan')->dropDownList(ArrayHelper::map(UnitRuangan::find()->all(), 'id', 'ruangan'),['prompt'=>'- Pilih Ruangan -',])->label("Pilih Ruangan")?>
	<?= $form->field($model, 'idunit')->dropDownList(ArrayHelper::map(UserUnit::find()->all(), 'id', 'unit'),['prompt'=>'- Pilih Unit -',])->label("Pilih Unit")?>
	<?= $form->field($model, 'idpoli')->dropDownList(ArrayHelper::map(Poli::find()->all(), 'id', 'poli'),['prompt'=>'- Pilih Poli -',])->label("Pilih Poli")?>
	<input type="checkbox" id="dokter" name="UserDetail[dokter]" value="1">
	<label for="vehicle1">Dokter ?</label><br>
    <?= $form->field($model, 'iddokter')->dropDownList(ArrayHelper::map(Dokter::find()->all(), 'id', 'nama_dokter'),['prompt'=>'- Pilih Nama Dokter -',])->label(false)?>
      

    

</div>
	</div>
	<div class='col-md-6'>
	<div class='box box-body'>
		<?= $form->field($user, 'idpriv')->dropDownList(ArrayHelper::map(UserPrivilages::find()->all(), 'id', 'privilages'),['prompt'=>'- Pilih Hak Akses -',])->label("Hak Akses")?>
		<input type="checkbox" id="gudang" name="UserDetail[gudang]" value="1">
		<label for="vehicle1">Penaggung jawab obat/alkes ?</label><br>
		<?= $form->field($model, 'idgudang')->dropDownList(ArrayHelper::map(Gudang::find()->all(), 'id', 'nama_gudang'),['prompt'=>'- Pilih Gudang -',])->label(false)?>
		<?= $form->field($user, 'username')->textInput(['maxlength' => true]) ?>
		<?php $user->password_hash = ($user->password_hash)? $user->password_repeat : "" ?>
		<?= $form->field($user, 'password_hash')->passwordInput(['maxlength' => true]) ?>
		<?= $form->field($user, 'password_repeat')->passwordInput(['maxlength' => true]) ?>
		<div class="form-group">
		<?= Html::submitButton('Save', ['class' => 'btn btn-success' ,'id'=>'confirm']) ?>
		</div>
	</div>
	</div>
	<?php ActiveForm::end(); ?>
</div>

<?php 
$this->registerJs("
$('#userdetail-idgudang').hide();
$('#userdetail-iddokter').hide();
$('#dokter').on('change', function(event){
	$('#userdetail-iddokter').show();
});
$('#gudang').on('change', function(event){
	$('#userdetail-idgudang').show();
});
$('#confirm').on('click', function(event){
	age = confirm('Yakin Untuk menyimpan data');
	if(age == true){
		 return true;
	} else {
		event.preventDefault();
	}
});
", View::POS_READY);
?>