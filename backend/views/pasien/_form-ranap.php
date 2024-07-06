<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatJenis;
use common\models\Poli;
use common\models\Rawat;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\KategoriPenyakit;
$rawat = Rawat::find()->where(['idkunjungan'=>$kunjungan->idkunjungan])->all();
?>
<?php $form = ActiveForm::begin(); ?>
		
		<?= $form->field($pelayanan, 'idmasuk')->hiddeninput(['maxlength' => true,])->label(false) ?>
		<label for='inputdata-ranap'>Asal masuk</label>
		<input type='text' class='form-control' readonly id='inputdata-ranap'>
		<?= $form->field($pelayanan, 'idkunjungan')->hiddenInput(['maxlength' => true,'value'=>$kunjungan->idkunjungan])->label(false) ?>
		<?= $form->field($pelayanan, 'no_rm')->hiddenInput(['maxlength' => true,'value'=>$kunjungan->no_rm])->label(false) ?>
		<div id='form-ranap'>
		<?= $form->field($pelayanan, 'idkelas')->dropDownList(ArrayHelper::map(RuanganKelas::find()->where(['ket'=>1])->all(), 'id', 'kelas'),['prompt'=>'- Kelas Rawat -','onchange'=>'$.get("'.Url::toRoute('pasien/list-ruangan/').'",{ id: $(this).val() }).done(function( data ) 
		{  $( "select#rawat-idruangan" ).html( data );});		
		'])->label('Kelas Rawat')?>
		<?= $form->field($pelayanan, 'idruangan')->dropDownList(ArrayHelper::map(Ruangan::find()->where(['id'=>0])->all(), 'id', 'nama_ruangan'),['prompt'=>'- Nama Ruangan -','required'=>true])->label('Nama Ruangan')?>
		<?= $form->field($pelayanan, 'idjenisrawat')->hiddeninput(['maxlength' => true,'value'=>2,])->label(false) ?>
	<a id="show-ruangan" class="btn btn-primary" ><span class="fa fa-search" style="width: 20px;"></span>Cek Ruangan</a>
	</div>
	<div id='loading' style='display:none;'>
	<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
	</div>
	<div id='ruangan-ajax'></div>
<?php ActiveForm::end(); ?>