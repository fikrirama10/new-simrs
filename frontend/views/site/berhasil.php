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
				<td><?= $data_json['response']['Nama']?></td>
			</tr>
			<tr>
				<th width='20%'>Tgl Lahir</th>
				<td width='3%'>:</td>
				<td><?= $data_json['response']['TglLahir']?> (<?= $data_json['response']['Usia']?> Th)</td>
			</tr>
			<tr>
				<th width='20%'>Penanggung</th>
				<td width='3%'>:</td>
				<td>BPJS</td>
			</tr>
			<tr>
				<th width='20%'>No Registrasi Online</th>
				<td width='3%'>:</td>
				<td><?= $daftar->noregistrasi ?></td>
			</tr>
			<tr>
				<th width='20%'>Tgl Kunjungan</th>
				<td width='3%'>:</td>
				<td><?= $daftar->tglberobat ?></td>
			</tr>
			<tr>
				<th width='20%'>Poli Tujuan</th>
				<td width='3%'>:</td>
				<td><?= $data_json2['response']['namapoli']?> <b>( <?= $data_json2['response']['nama']?> )</b></td>
			</tr>
			<tr>
				<th width='20%'>No Tlp</th>
				<td width='3%'>:</td>
				<td><?= $daftar->notlp ?></td>
			</tr>
			<tr>
				<th width='20%'>Email</th>
				<td width='3%'>:</td>
				<td><?= $daftar->email ?></td>
			</tr>			
		</table>
		<a class='btn btn-primary' id='confirm' href='<?= Url::to(['/site/konfirmasi?id='.base64_encode($daftar->noregistrasi)])?>'> Konfirmasi </a>
		<a class='btn btn-danger' href='<?= Url::to(['/site/batal?id='.base64_encode($daftar->noregistrasi)])?>' > Batalkan </a>
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

