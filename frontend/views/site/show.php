<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
sleep(3);
?>
<?php if($data_json['metadata']['code'] == 400){ ?>
	<hr>
	<div class="alert alert-danger" role="alert">
		Data Pasien tidak ditemukan
	</div>
<?php }else{ ?>
	<hr>
	<?php if($data_json['response']['NoBpjs'] == null){ ?>
	<div class="alert alert-warning" role="alert">
		Mohon maaf data belum lengkap silahkan lengkapi ke bagian pendaftaran
	</div>
	<?php }else{ ?>
	<?php if($data_json['response']['Status'] != 0 ){ ?>
	<div class="alert alert-danger" role="alert">
		<?= $data_json['response']['StatusT'] ?>
	</div>
	<?php }else{ ?>
	<div class="alert alert-success" role="alert">
		Data Pasien ditemukan
	</div>
	<a id="a-all"  class="btn btn-xs btn-warning">Lanjut Daftar</a><hr>
	<?php } ?>
	<?php } ?>
	
	<table class='table table-striped'>
		<tbody>
		<tr>
			<th width='20%'>Nama Pasien</th>		
			<td width='5%'>:</td>		
			<td><?= $data_json['response']['Nama']?></td>		
		</tr>
		<tr>
			<th>Tgl Lahir</th>
			<td>:</td>		
			<td><?= $data_json['response']['TglLahir']?> (<?= $data_json['response']['Usia'] ?> Th)</td>		
		</tr>
		</tr>
		<tr>
			<th>No Tlp</th>
			<td width='5%'>:</td>		
			<td><?= $data_json['response']['NoTlp']?></td>		
			
		</tr>
		<tr>
			<th>Email</th>
			<td width='5%'>:</td>		
			<td><?= $data_json['response']['Email']?></td>		
		</tr>
		<tr>
			<th>Alamat</th>
			<td width='5%'>:</td>		
			<td><?= $data_json['response']['Alamat']?></td>		
		</tr>
		<tr>
			<th>NIK</th>
			<td width='5%'>:</td>		
			<td><?= $data_json['response']['NIK']?></td>		
		</tr>
		<tr>
			<th>NO BPJS</th>
			<td width='5%'>:</td>		
			<td><?= $data_json['response']['NoBpjs']?></td>		
		</tr>
		</tbody>
	</table>
<?php
$urlShowAll = Url::to(['site/daftar-poli?kode=']);
$kode = base64_encode(date('is').$data_json['response']['NoRM'].'-'.date('Ymd').$data_json['response']['Id']);
$this->registerJs("
	
	$('#a-all').on('click',function(){
	
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+poli+'&tgl='+tgl,
				success: function (data) {

					 window.location = 'daftar-poli?kode='+'{$kode}';
					
				},
			});
		
	});
", View::POS_READY);
?>
<?php } ?>