<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\SoapKeadaan;
use common\models\SoapKesadaran;
?>
<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($pindah, 'idrawat')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
		<?= $form->field($pindah, 'no_rm')->hiddenInput(['maxlength' => true,'value'=>$model->no_rm])->label(false) ?>
		<?= $form->field($pindah, 'idasal')->hiddenInput(['maxlength' => true,'value'=>$model->idruangan])->label(false) ?>
		<?= $form->field($pindah, 'idkelasasal')->hiddenInput(['maxlength' => true,'value'=>$model->idkelas])->label(false) ?>
		<?= $form->field($pindah, 'iduser')->hiddenInput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
		<?= $form->field($pindah, 'idkelastujuan')->dropDownList(ArrayHelper::map(RuanganKelas::find()->where(['ket'=>1])->all(), 'id', 'kelas'),['prompt'=>'- Kelas Rawat -','onchange'=>'$.get("'.Url::toRoute('pasien/list-ruangan/').'",{ id: $(this).val() }).done(function( data ) 
		{  $( "select#rawatpermintaanpindah-idtujuan" ).html( data );});		
		'])->label('Kelas Rawat')?>
		<?= $form->field($pindah, 'idtujuan')->dropDownList(ArrayHelper::map(Ruangan::find()->where(['id'=>0])->all(), 'id', 'nama_ruangan'),['prompt'=>'- Nama Ruangan -','required'=>true])->label('Nama Ruangan')?>
		<hr>
		<h6>Keadaan Umum</h6>
		<?= $form->field($pindah, 'keadaan')->radioList(ArrayHelper::map(SoapKeadaan::find()->all(), 'id','keadaan'),['required'=>true])->label(false)?>
		<h6>Kesadaran</h6>
		<?= $form->field($pindah, 'kesadaran')->radioList(ArrayHelper::map(Soapkesadaran::find()->all(), 'id','kesadaran'),['required'=>true])->label(false)?>
		<div class='row'>
				<div class='col-md-12'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Distole' name='RawatPermintaanPindah[distole]' id="rawatpermintaanpindah-distole">
					<input type='text' class='form-control' placeholder='Sistole' name='RawatPermintaanPindah[sistole]' id="rawatpermintaanpindah-sistole">
					<span class="input-group-addon" id="basic-addon1">mmHg</span>
					</div>
				</div>
			</div>
			<br>
			<div class='row'>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Suhu' name='RawatPermintaanPindah[suhu]' id="rawatpermintaanpindah-suhu">
					<span class="input-group-addon" id="basic-addon1">C</span>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Respirasi' name='RawatPermintaanPindah[respirasi]' id="rawatpermintaanpindah-respirasi">
					<span class="input-group-addon" id="basic-addon1">x / menit</span>
					</div>
				</div>
			
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='SpO2' name='RawatPermintaanPindah[spo2]' id="rawatpermintaanpindah-spo2">
					<span class="input-group-addon" id="basic-addon1">%</span>
					</div>
				</div>
				<div class='col-md-6'>
					<div class="input-group">
					<input type='text' class='form-control' placeholder='Nadi' name='RawatPermintaanPindah[nadi]' id="rawatpermintaanpindah-nadi">
					<span class="input-group-addon" id="basic-addon1">x / menit</span>
					</div>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
			</div>
<?php ActiveForm::end(); ?>

