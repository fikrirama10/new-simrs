<?php
use yii\helpers\Url;
use yii\web\View;
?>
<div class='row'>
	<div class='col-md-12'>
		<div class='box box-header'>Monitoring Dashboard pertanggal</div>
		<div class='box box-body'>
			<div class='row'>
				<div class='col-md-3'><input type='date' id='tgl' class='form-control'></div>
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

$urlPoli = Url::to(['monitoring-antrean/show-dashboard']);
$this->registerJs("
	$('#waktu').on('change',function(e) {					
		
		tgl = $('#tgl').val();
		waktu = $('#waktu').val();
		$.ajax({
			type: 'GET',
			url: '{$urlPoli}',
			data: 'tgl='+tgl+'&waktu='+waktu,
			
			success: function (data) {
				$('#dashboard').html(data);
				
				console.log(data);
				
			},
			
		});
	});
", View::POS_READY);
?>