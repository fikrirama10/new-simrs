<?php
use yii\helpers\Url;
use yii\web\View;
?>
<div class='row'>
	<div class='col-md-12'>
		<div class='box box-header'>Monitoring Dashboard Per Bulan</div>
		<div class='box box-body'>
			<div class='row'>
				<div class='col-md-3'>
					<select class='form-control' id='bulan'>
						<option value=''>-- Pilih Bulan --</option>
						<option value='01'>Januari</option>
						<option value='02'>Februari</option>
						<option value='03'>Maret</option>
						<option value='04'>April</option>
						<option value='05'>Mei</option>
						<option value='06'>Juni</option>
						<option value='07'>Juli</option>
						<option value='08'>Agustus</option>
						<option value='09'>September</option>
						<option value='10'>Oktober</option>
						<option value='11'>November</option>
						<option value='12'>Desember</option>
					</select>
				</div>
				<div class='col-md-3'>
					<select class='form-control' id='tahun'>
						<option value=''>-- Pilih Tahun --</option>
						<option value='2022'>2022</option>
						<option value='2023'>2023</option>
						<option value='2024'>2024</option>
					</select>
				</div>
				
				<div class='col-md-3'>
					<select class='form-control' id='waktu'>
						<option value=''>Pilih Waktu</option>
						<option value='rs'>Waktu RS</option>
						<option value='server'>Waktu Server</option>
					</select>
				</div>
				<div class='col-md-12'>
					<div id='dashboard'></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php

$urlPoli = Url::to(['monitoring-antrean/show-dashboard2']);
$this->registerJs("
	$('#waktu').on('change',function(e) {					
		
		bulan = $('#bulan').val();
		tahun = $('#tahun').val();
		waktu = $('#waktu').val();
		$.ajax({
			type: 'GET',
			url: '{$urlPoli}',
			data: 'bulan='+bulan+'&tahun='+tahun+'&waktu='+waktu,
			
			success: function (data) {
				$('#dashboard').html(data);
				
				console.log(data);
				
			},
			
		});
	});
", View::POS_READY);
?>