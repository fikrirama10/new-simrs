<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\date\DatePicker;
$kontrol = strtotime($data_json['response']['tglRencanaKontrol']);
$tglini = strtotime(date('Y-m-d',strtotime('+7 hour',strtotime(date('Y-m-d')))));
$hasil =  $kontrol - $tglini;
$selisih = floor($hasil/86400)+1;
sleep(3);
?>

<?php if($data_json['metaData']['code'] == 201){ ?>

		<div class='col-md-12'>
			<div class="alert alert-warning" role="alert">
				<?= $data_json['metaData']['message'] ?>
			</div>
		</div>

<?php }else{ ?>
<?php if($selisih < 1){ ?>
<div class="alert alert-danger" role="alert">
			<p>Tanggal Rencana Kontrol sudah terlewat , silahkan datang langsung ke bagian pendaftaran untuk konfirmasi</p>
		</div>
<?php }else{ ?>
	<?php if($data_json['response']['flagKontrol'] == 'True'){?>
		<div class="alert alert-danger" role="alert">
			<p>Nomor Surat Kontrol Sudah digunakan</p>
		</div>
	<?php }else{ ?>
		<div class="alert alert-info" role="alert">
			<h4>Pasien Kontrol setelah kunjungan rawat inap</h4>
			<p><b style='color:red;'>Catatan : PENDAFTARAN MAKSIMAL H-1 SEBELUM JADWAL KUNJUNGAN , jika lebih dari H-1 , atau kuota poli penuh silahkan datang langsung ke bagian pendaftaran</b></p>
		</div>
		<div class='row'>
		<div class='col-md-6'>
			<table class='table table-bordered'>
				<tr>
					<th>Nomor S.Kontrol</th>
					<th>:</th>
					<td><?= $data_json['response']['noSuratKontrol'] ?></td>
				</tr>
				<tr>
					<th>Rencana Kontrol</th>
					<th>:</th>
					<td><?= $data_json['response']['tglRencanaKontrol'] ?></td>
				</tr>
				<tr>
					<th>Poli Tujuan</th>
					<th>:</th>
					<td><?= $data_json2['response']['namapoli'] ?></td>
				</tr>
				<tr>
					<th>Dokter</th>
					<th>:</th>
					<td><?= $data_json['response']['namaDokter'] ?></td>
				</tr>
			</table>
			<label> Tanggal Berobat </label>
			<input id='pasien-tgl' type='date' class='form-control'><br>
			<input id='pasien-dokter' type='hidden' value='<?= $data_dokter2['response']['id'] ?>' class='form-control'><br>
			<input id='pasien-rujukan' type='hidden' value='<?= $data_json['response']['noSuratKontrol'] ?>' class='form-control'><br>
			<input type='hidden' value='<?= $jenis ?>' id='rujukan-jenis'>
			<input type='hidden' value='<?= $bpjs?>' id='rujukan-bpjs'>
		</div>
		<div class='col-md-6'>
			<div id='loading' style='margin:auto; display:none;'>
				<center><img src='https://simrs.rsausulaiman.com/frontend/images/unnamed.gif'></center>
			</div>
			<div id='pasien-periksa'></div>
		</div>
		</div>
		
	<?php }
	<?php
		$urlShowAll = Url::to(['site/cek-dokter']);
		$this->registerJs("

		$('#pasien-tgl').on('change',function() {
			dokter = $('#pasien-dokter').val();
			tgl = $('#pasien-tgl').val();
			bpjs = $('#rujukan-bpjs').val();
			rujukan = $('#pasien-rujukan').val();
			jenis = $('#rujukan-jenis').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+dokter+'&tgl='+tgl+'&bpjs='+bpjs+'&rujukan='+rujukan+'&jenis='+jenis,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#pasien-periksa').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});

		});
		", View::POS_READY);
		?>
	<?php } ?>
	
<?php } ?>