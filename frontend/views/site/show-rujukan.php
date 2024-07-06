<?php 
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use kartik\date\DatePicker;

$tglrujukan = strtotime($data_json['response']['rujukan']['tglKunjungan']);
$tglhariini = strtotime(date('Y-m-d',strtotime('+6 hour',strtotime(date('Y-m-d')))));
$hasil =  $tglhariini - $tglrujukan;
$selisih = floor($hasil/86400)+1
?>

<?php if($data_json['metaData']['code'] == 201){ ?>
	<div class='col-md-12'>
		<div class="alert alert-warning" role="alert">
			<?= $data_json['metaData']['message'] ?>
		</div>
	</div>
<?php }else{ ?>
	<?php if($data_json['response']['rujukan']['peserta']['noKartu'] != $bpjs){ ?>
	<div class='col-md-12'>
		<div class="alert alert-danger" role="alert">
			Nomor surat kontrol tidak sesuai dengan data anda
		</div>
	</div>	
	<?php }else{ ?>
	
	<?php if($selisih > 90){?>
	<div class='row'>
		<div class='col-md-12'>
			<div class="alert alert-danger" role="alert">
				Masa Rujukan Habis , Silahkan gunakan rujukan terbaru dari faskes 1
			</div>
		</div>	
	</div>	
	<?php }else{ ?>
	<?php if($daftar > 0){ ?>
	<div class='row'>
		<div class='col-md-12'>
			<div class="alert alert-danger" role="alert">
				Rujukan Sudah digunakan mendaftar, silahkan menggunakan nomer surat kontrol untuk mendaftar selanjutnya,<br> <a href='<?= Url::to(['site/terdaftar?jenis=1&rujukan='.$data_json['response']['rujukan']['noKunjungan']])?>'>Lihat Disini</a>
			</div>
		</div>	
	</div>	
	<?php }else{ ?>
	<div class='row'>
		<div class='col-md-12'>
			<table class='table table-borderless'>
				<tbody>
					<tr>
						<th>Nomer Rujukan</th>
						<td>:</td>
						<td><?= $data_json['response']['rujukan']['noKunjungan']?></td>
					</tr>
					<tr>
						<th>Poli Rujukan</th>
						<td>:</td>
						<td><?= $data_json['response']['rujukan']['poliRujukan']['nama']?></td>
					</tr>
					<tr>
						<th>Perujuk</th>
						<td>:</td>
						<td><?= $data_json['response']['rujukan']['provPerujuk']['nama']?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class='col-md-12'>
			<div class="alert alert-warning" role="alert">
				PENDAFTARAN MAKSIMAL H-1 SEBELUM JADWAL KUNJUNGAN
			</div>
		</div>
		<div class='col-md-12'>
			<div class='row'>
				<div class='col-md-5'>
					<div class='form-group'>
						<input type='hidden' value='<?= $jenis ?>' id='rujukan-jenis'>
						<input type='hidden' value='<?= $bpjs?>' id='rujukan-bpjs'>
						<input type='hidden' value='<?= $data_json['response']['rujukan']['noKunjungan']?>' id='rujukan-nomor'>
						<input type='hidden' value='<?= $data_json['response']['rujukan']['poliRujukan']['kode']?>' id='kode-dokter'>
						<label>Dokter yang tersedia</label>
						<select id="dokter-jenis" class="form-control" aria-invalid="false">
							<option value="">-Pilih Dokter-</option>
							<?php foreach($data_dokter as $dokter){ ?>
							<option value='<?= $dokter['id']?>'><?= $dokter['nama']?></option>
							<?php } ?>
						</select>
						<hr>
						<label> Tanggal Berobat </label>
						<input id='pasien-tgl' type='date' class='form-control'><br>
						<a id="show-jadwal" class="btn btn-success" ><span class="fa fa-search" style="width: 20px;"></span>CEK JADWAL</a>
					</div>
				</div>
				<div class='col-md-7'>
					<div id='loading' style='margin:auto; display:none;'>
						<center><img src='https://simrs.rsausulaiman.com/frontend/images/unnamed.gif'></center>
					</div>
					<div id='pasien-dokter'></div>
				</div>
			</div>
		</div>		
	</div>
	<?php } ?>
	<?php } ?>
<?php
$urlShowAll = Url::to(['site/cek-dokter']);
$this->registerJs("
	
	$('#show-jadwal').on('click',function() {
		$('#pasien-dokter').hide();
			dokter = $('#dokter-jenis').val();
			tgl = $('#pasien-tgl').val();
			bpjs = $('#rujukan-bpjs').val();
			rujukan = $('#rujukan-nomor').val();
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
					$('#pasien-dokter').show();
					$('#pasien-dokter').html(data);
					
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