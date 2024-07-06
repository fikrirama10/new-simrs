<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Dokter;
use common\models\ObatSuplier;
use common\models\RawatBayar;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
$suplier = ObatSuplier::find()->all();
$bayar = RawatBayar::find()->all();
?>
<?php if($up){ ?>
	<table class='table table-bordered'>
		<tr>
			<th>No UP</th>
			<th>Tgl UP</th>
			<th>Nilai UP</th>
		</tr>
		<tr>
			<td><?= $up->kode_up ?></td>
			<td><?= $up->tgl_up ?></td>
			<td><?= $up->total_harga ?></td>
		</tr>
	</table>
	<hr>
	<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
	<div class="form-group">
		<label class="col-sm-2 control-label">No Faktur</label>
		<div class="col-sm-6">
			<input type='text' required name="PenerimaanBarang[no_faktur]" id='up' class='form-control'>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Suplier</label>
		<div class="col-sm-6">
			<select required name="PenerimaanBarang[idsuplier]" id='up' class='form-control'>
				<option>--Pilih Suplier--</option>
				<?php foreach($suplier as $s): ?>
				<option value='<?= $s->id?>'><?= $s->suplier ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Tgl Faktur</label>
		<div class="col-sm-6">
			<input type='date' name="PenerimaanBarang[tgl_faktur]" id='up' class='form-control'>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-2 control-label">Jenis Bayar</label>
		<div class="col-sm-6">
			<select required name="PenerimaanBarang[idbayar]" id='up' class='form-control'>
				<option>--Pilih Bayar--</option>
				<?php foreach($bayar as $b): ?>
				<option value='<?= $b->id?>'><?= $b->bayar ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	
	<div class="box-footer">
	<button type="submit" class="btn btn-danger">Cancel</button>
	<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
  </div>
<?php }else{ ?>
<div class="alert alert-danger alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
	<h4><i class="icon fa fa-ban"></i> Data Tidak ditemukan !</h4>
	Periksa kembali nomer UP yang di input
</div>
<?php } ?>