<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
?>
	<?php $form = ActiveForm::begin(); ?>
	<label>Dokter Spesialis</label>
	<select class="form-control" id='daftarumumiddokter' name="DaftarUmum[iddokter]">
		<option value='0'>-- Pilih Dokter --</option>
		<?php foreach($data_json as $dokter): ?>
			<option value='<?= $dokter['id']?>'><?= $dokter['nama']?></option>
		<?php endforeach; ?>
	</select>
	<br>
	<label> Tanggal Berobat (Daftar Minimal H-1)</label>
	<input id='pasien-tgl' type='date' class='form-control' name="DaftarUmum[tgl_daftar]"><br>
	<a id="show-jadwal" class="btn btn-success" ><span class="fa fa-search" style="width: 20px;"></span>CEK JADWAL</a>
	<div id='loading' style='margin:auto; display:none;'>
		<center><img src='https://simrs.rsausulaiman.com/frontend/images/unnamed.gif'></center>
	</div>
	<div id='dokterhasil'></div>
<?php
$urlShowAll = Url::to(['daftar-umum/cek-dokter']);
$this->registerJs("
	
	$('#show-jadwal').on('click',function() {
		$('#dokterhasil').hide();
			dokter = $('#daftarumumiddokter').val();
			tgl = $('#pasien-tgl').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+dokter+'&tgl='+tgl,
				beforeSend: function(){
				//Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#dokterhasil').show();
					$('#dokterhasil').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				//Hide image container
				$('#loading').hide();
				}
			});
		
	});


	
           
	

", View::POS_READY);
?>