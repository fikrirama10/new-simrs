<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
?>
<?php if($bpjs == null){ ?>
<div class='row'>
		<div class="alert alert-warning" role="alert">
			Mohon maaf anda belum terdaftar sebagai pasien BPJS di sistem kami , silahkan registrasi langsung ke bagian pendaftaran
		</div>
</div>
<?php }else{ ?>
<div id='daftar-bpjs'>
<div class='row'>
	<div class='col-md-12'>
		<div class="alert alert-success" role="alert">
			Untuk pendaftaran BPJS di harap sudah memiliki surat rujukan dari FASKES 1 / Surat Kontrol
		</div>
	</div>
	<div class='col-md-5'>
		<div class='form-group'>
			<label>Nomor Rujukan </label>
			<input class='form-control' type ='text' id='pasien-rujukan'>
			<input class='form-control' type ='hidden' id='pasien-nobpjs' value='<?= $bpjs ?>'>
		</div>
	</div>
	<div class='col-md-3'>
			<div class='form-group'>
				<label>Jenis Rujukan</label>
				<select id="pasien-jenis" class="form-control" aria-invalid="false">
					<option value="">-Pilih Jenis Rujukan-</option>
					<option value="1">Rujukan</option>
					<option value="2">Surat Kontrol</option>
				</select>
			</div>
		</div>		
</div>
</div>
	<div id='loader' style='display:none; margin:20px auto;'>
	<center><img src='https://www.suchirayuhospital.com/img/rolling.gif'></center>
	</div>
	<div id='pasien-bpjs'  style='display:none;'>	
	</div>

<?php
$urlShowAll = Url::to(['site/show-bpjs']);
$this->registerJs("
	
	$('#pasien-jenis').on('change',function() {
		$('#pasien-bpjs').hide();
			poli = $('#pasien-rujukan').val();
			tgl = $('#pasien-jenis').val();
			bpjs = $('#pasien-nobpjs').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+poli+'&tgl='+tgl+'&bpjs='+bpjs,
				 beforeSend: function(){
				// Show image container
				$('#loader').show();
			   },
				success: function (data) {
						$('#daftar-bpjs').hide();
					$('#pasien-bpjs').show();
					$('#pasien-bpjs').html(data);
					
					console.log(data);
					
				},
			   complete:function(data){
				// Hide image container
				$('#loader').hide();
			   }
			});
		
	});


	
           
	

", View::POS_READY);
?>
<?php } ?>