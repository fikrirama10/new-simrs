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
use common\models\RawatBayar;
use kartik\date\DatePicker;
?>
<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
<?= $form->field($pelayanan, 'no_rm')->hiddenInput(['value'=>$model->no_rm])->label(false) ?>
    	<div class="form-group" >
			<label class="col-md-3 col-sm-3  control-label">No Antrian</label>
			<div class="col-md-2 col-sm-2 col-xs-6" style='margin-left:-15px;'>
				 <select name="kode_antrian" class="form-control" id="kode_antrian">
        <option value="1">A</option>
        <option value="2">B</option>
        <option value="3">C</option>
    </select>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6" style='margin-left:-15px;'>
				<input type="number" name="no_antrian" class="form-control" id="no_antrian">
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6" style='margin-left:-15px;'>
				<button class="btn btn-info btn-sm" type="button" id="cek-antrian">Cek Antrian</button>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6" style='margin-left:-15px;'>
				<input type="text" name="taks1" id="taks1">
				<input type="text" name="taks2" id="taks2">
			</div>
		</div>
	<div class="form-group" >
		<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tgl Layanan</label>
		<div class="col-md-3 col-sm-3 col-xs-12" style='margin-bottom:-40px;'>
			
			<?=	$form->field($pelayanan, 'tglmasuk')->widget(DatePicker::classname(),[
				'type' => DatePicker::TYPE_COMPONENT_APPEND,
				'pluginOptions' => [
				'autoclose'=>true,
				'format' => 'yyyy-mm-dd',
				'value' => date('Y-m-d'),
				'minDate'=>date('Y-m-d',strtotime('-7 days')),
				'todayHighlight' => true,
				'required'=>true,
				]
			])->label(false)?>
		</div>
		<div class="col-md-4 col-sm-4 col-xs-12" style='margin-bottom:-40px;'>
			<?= $form->field($pelayanan, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Pilih Bayar -','required'=>true])->label('Penanggung',['class'=>'label-class'])->label(false)?>
		</div>
	</div>
	
	<div class="form-group">
		<label class="col-md-3 col-sm-3 col-xs-12 control-label">Jenis Rawat</label>
		<div class="col-md-4 col-sm-4 col-xs-12" style='margin-bottom:-40px;'>
			<?= $form->field($pelayanan, 'idjenisrawat')->dropDownList(ArrayHelper::map(RawatJenis::find()->where(['ket'=>1])->all(), 'id', 'jenis'),['prompt'=>'- Jenis Rawat -','onchange'=>'$.get("'.Url::toRoute('pasien/listpoli/').'",{ id: $(this).val() }).done(function( data ) 
		{
		  $( "select#rawat-idpoli" ).html( data );
		});		
		'])->label(false)?>
		</div>
		<div class="col-md-3 col-sm-3 col-xs-12" style='margin-bottom:-40px;'>
			<?= $form->field($pelayanan, 'idpoli')->dropDownList(ArrayHelper::map(Poli::find()->where(['id'=>0])->all(), 'id', 'poli'),['prompt'=>'- Nama Poli -','required'=>true])->label(false)?>
		</div>
		
	</div>
	
	<div class="form-group">
		<label class="col-md-3 col-sm-3 col-xs-12 control-label"></label>
		<div class="col-md-7 col-sm-7 col-xs-12"  style='margin-left:-15px;'>
			<a id="show-pelayanan" class="btn btn-success btn-xs" ><span class="fa fa-search"></span>Cek dokter</a>
		</div>
	</div>
	<div id='loading' style='display:none;'>
		<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
	</div>
	<div id='pasien-ajax'></div>
	
	<div id='tampillayanan'>
		<div class="form-group" >
			<label class="col-md-3 col-sm-3 col-xs-12 control-label">Kunjungan</label>
			<div class="col-md-6 col-sm-6 col-xs-12" style='margin-left:-15px;'>
				<select id='kunjungan' name='Rawat[kunjungan]' class='form-control'>
					<option value=''>-- Pilih Kunjungan --</option>
					<option value='1'>Kunjungan Baru</option>
					<option value='2'>Kunjungan Ulang</option>
					<option value='3'>Post Ranap</option>
				</select>
			</div>
		</div>
		<div class="form-group" >
			<label class="col-md-3 col-sm-3  control-label">Anggota ?</label>
			<div class="col-md-1 col-sm-1 col-xs-6" style='margin-left:-15px;'>
				<input type="checkbox" id="vehicle1" name="Rawat[anggota]" value="1">
			</div>
			<label class="col-md-1 col-sm-1 control-label">Online?</label>
			<div class="col-md-2 col-sm-2 col-xs-6" style='margin-left:-15px;'>
				<input type="checkbox" id="vehicle1" name="Rawat[anggota]" value="1">
			</div>
		</div>
		<div class="form-group" >
			<label class="col-md-3 col-sm-3  control-label"></label>
			<div class="col-md-6 col-sm-6 col-xs-6" style='margin-left:-15px;'>
				<?= Html::submitButton('Simpan', ['class' => 'btn btn-primary btn-sm','id'=>'confirm2']) ?>
			</div>
			
		</div>
		
	</div>
<?php ActiveForm::end(); ?>