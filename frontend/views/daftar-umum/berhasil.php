<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
?>
<div class='row'>
	<div class='col-md-12'>
		<div class="alert alert-success" role="alert">
			Pendaftaran berhasil silahkan konfirmasi data berikut untuk mendapat nomer antrian
		</div>
		<table class='table table-bordered'>
			<tr>
				<th width='20%'>Nama Pasien</th>
				<td width='3%'>:</td>
				<td><?= $model->nama?></td>
			</tr>
			<tr>
				<th width='20%'>Penanggung</th>
				<td width='3%'>:</td>
				<td>UMUM</td>
			</tr>
			<tr>
				<th width='20%'>No Registrasi Online</th>
				<td width='3%'>:</td>
				<td><?= $model->kodedaftar ?></td>
			</tr>
			<tr>
				<th width='20%'>Tgl Kunjungan</th>
				<td width='3%'>:</td>
				<td><?= $model->tgl_daftar ?></td>
			</tr>
			<tr>
				<th width='20%'>Poli Tujuan</th>
				<td width='3%'>:</td>
				<td><?= $data_json2['response']['namapoli']?></b></td>
			</tr>
			<tr>
				<th width='20%'>No Tlp</th>
				<td width='3%'>:</td>
				<td><?= $model->no_telpon ?></td>
			</tr>
			<tr>
				<th width='20%'>Email</th>
				<td width='3%'>:</td>
				<td><?= $model->email ?></td>
			</tr>			
		</table>
		<a class='btn btn-primary' id='confirm' href='<?= Url::to(['/daftar-umum/konfirmasi?id='.base64_encode($model->kodedaftar)])?>'> Konfirmasi </a>
		<a class='btn btn-danger' href='<?= Url::to(['/daftar-umum/batalkan='.base64_encode($model->kodedaftar)])?>' > Batalkan </a>
	</div>
</div>
<?php 
$this->registerJs(" 
$('#confirm').on('click', function(event){
	age =  confirm('Yakin untuk melanjutkan?', );
	if(age == true){
       return true;
    } else {
        event.preventDefault();
    }
});

", View::POS_READY);
?>

