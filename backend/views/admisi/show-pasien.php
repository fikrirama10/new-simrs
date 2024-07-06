<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Rawat;
use common\models\RawatJenis;
use common\models\RawatBayar;
use common\models\Dokter;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
$bayar = RawatBayar::find()->all();
$jenis = RawatJenis::find()->where(['<>','id',2])->all();
$dokter = Dokter::find()->where(['<>','idpoli',''])->all();
?>
<?php if(!$pasien){ ?>
	<div class="form-group">
		<label class="col-sm-4 control-label"></label>
		<div class="col-sm-8">
			<table class='table table-bordered'>
				<tr>
					<th>Nama Pasien</th>
					<th>No BPJS</th>
					<th>Kepesertaan</th>
					<th>Jenis Kelamin</th>
				</tr>
				<tr>
					<td colspan=4>Data Tidak Ada</td>
				</tr>
			</table>
		</div>
	</div>
<?php }else{ ?>
	<?php $rawat = Rawat::find()->where(['idjenisrawat'=>2])->andwhere(['status'=>2])->andwhere(['no_rm'=>$pasien->no_rm])->count(); ?>
	
	<div class="form-group">
		<div class="col-sm-12">
			<table class='table table-bordered'>
				<tr>
					<th>Nama Pasien</th>
					<th>No BPJS</th>
					<th>Kepesertaan</th>
					<th>Jenis Kelamin</th>
				</tr>
				<tr>
					<td><?= $pasien->nama_pasien ?></td>
					<td><?= $pasien->no_bpjs ?></td>
					<td><?= $pasien->kepesertaan_bpjs ?></td>
					<td><?= $pasien->jenis_kelamin ?></td>
				</tr>
			</table>
		</div>
	</div>
	<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
		<div class="form-group">
		<label class="col-sm-4 control-label">Asal Pasien</label>
			<div class="col-sm-5">
				<select id='rawat-idjenisrawat' name='RawatSpri[idjenisrawat]'  class='form-control'>
					<option value=''>-- Pasien Dari --</option>
					<?php foreach($jenis as $j): ?>
						<option value='<?= $j->id?>'><?= $j->jenis?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
		<label class="col-sm-4 control-label">DPJP</label>
			<div class="col-sm-6">
				<select id='rawatspri-iddokter' name='RawatSpri[iddokter]'  class='form-control'>
					<option value=''>-- Dokter --</option>
					<?php foreach($dokter as $d): ?>
						<option value='<?= $d->id?>'><?= $d->nama_dokter?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
		<label class="col-sm-4 control-label">Jenis Bayar</label>
			<div class="col-sm-6">
				<select id='rawatspri-idbayar' name='RawatSpri[idbayar]' required class='form-control'>
					<option value=''>-- Jenis Bayar --</option>
					<?php foreach($bayar as $b): ?>
						<option value='<?= $b->id?>'><?= $b->bayar?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div  id='sep_pasien' class="form-group">
		<label class="col-sm-4 control-label">SEP</label>
			<div class="col-sm-6">
				<input id='rawatspri_sep' name='RawatSpri[sep]' type="checkbox" id="dokter"  value="1">
				<label for="vehicle1">Buat SEP ?</label><br>
			</div>
		</div>
		<div class="form-group">
		<label class="col-sm-4 control-label">Tgl Rencana Rawat</label>
			<div class="col-sm-6">
				<input type='date' id='rawatspri-tgl_rawat' name='RawatSpri[tgl_rawat]' class='form-control'>
			</div>
		</div>
		<div class="form-group">
		<label class="col-sm-4 control-label">Bayi Lahir</label>
			<div class="col-sm-6">
				<input id='rawatspri-bayi' name='RawatSpri[bayi_lahir]' type="checkbox" id="bayi"  value="1">
				<label for="vehicle1">Bayi Lahir ?</label><br>
			</div>
		</div>
		<div class="form-group">
		<label class="col-sm-4 control-label">Operasi</label>
			<div class="col-sm-6">
				<input id='rawatspri-operasi' name='RawatSpri[operasi]' type="checkbox" id="dokter"  value="1">
				<label for="vehicle1">Operasi ?</label><br>
			</div>
		</div>
		
		<div id='tindakan_operasi'>
			<div class="form-group">
			<label class="col-sm-4 control-label">Tindakan Operasi</label>
				<div class="col-sm-6">
					<textarea id='rawatspri-nama_tindakan' name='RawatSpri[nama_tindakan]' class='form-control'></textarea>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<button type="submit" class="btn btn-danger">Cancel</button>
			<button type="submit" id='confirm' class="btn btn-info pull-right ">Simpan</button>
		</div>
<?php } ?>
<?php 
$this->registerJs("
$('#userdetail-idgudang').hide();
$('#tindakan_operasi').hide();
$('#sep_pasien').hide();
$('#rawatspri-idbayar').on('change', function(event){
	bayar = $('#rawatspri-idbayar').val();
	if(bayar == 2 ){
		$('#sep_pasien').show();
		$('#rawatspri_sep').prop('checked', true); 
	}else{
		$('#sep_pasien').hide();
		$('#rawatspri_sep').prop('checked', false); 
		
	}
	
});
$('#rawatspri-operasi').on('change', function(event){
	$('#tindakan_operasi').show();
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