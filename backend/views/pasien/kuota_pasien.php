<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\Dokter;
use common\models\RawatCppt;
use common\models\RawatImplementasi;
use common\models\RawatPermintaanPindah;
use common\models\RawatAwalinap;
?>
<div class='box'>
<div class='box-header with-border'><h4>Kuota Pendaftaran Pasien</h4></div>
<div class='box-body'>
	<div class='row'>
		<div class='col-md-6'>
			<table class='table'>
				<tr>
					<td>Start</td>
				</tr>
				<tr>
					<td><input type='date' id='start-tgl' class='form-control'></td>
				</tr>
			</table>
		</div>
	</div>
	<div id='loading' style='display:none;'>
		<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
		</div>
	<div id='show-search'></div>
	
	<div id='show-index'>
	<div class='row'>
		<div class='col-md-12'>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<?php foreach($json as $j): ?>
					<li  class=""><a href="#tab_<?= $j['idpoli']?>" data-toggle="tab" aria-expanded="false"> <?= $j['namaPoli']?></a></li>
					<?php endforeach; ?>
				</ul>
				<div class="tab-content">
					
					<?php foreach($json as $j): ?>
						
						<div class="tab-pane" id="tab_<?= $j['idpoli']?>">
							<h3>Jadwal Dokter</h3>
							<table class='table table-bordered'>
								<tr>
									<th width=10>No</th>
									<th width=400>Dokter</th>
									<th>Kuota</th>
								</tr>
								<?php $no2=1; foreach($j['jadwalDokter'] as $jd){ ?>
								<tr>
									<td><?= $no2++ ?></td>
									<td><?= $jd['dokter']?></td>
									<td><?= $jd['kuota']?></td>
								</tr>
								<?php } ?>
							</table>
							<h3>Kuota Poli</h3>
							<table class='table table-bordered'>
								<tr>
									<th width=10>No</th>
									<th>Dokter</th>
									<th>Kuota</th>
									<th>Terdaftar</th>
									<th>Sisa</th>
								</tr>
								<?php $no=1; foreach($j['kuota'] as $k){ ?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $k['dokter'] ?></td>
									<td><?= $k['kuota'] ?></td>
									<td><?= $k['terdaftar'] ?></td>
									<td><?= $k['sisa'] ?></td>
								</tr>
								<?php } ?>
							</table>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- /.tab-content -->
			</div>
		</div>
		
	
		
	</div>
	</div>
</div>
</div>
<?php

$urlShowAll = Url::to(['pasien/show-jadwal']);
$this->registerJs("	
	$('#start-tgl').on('change',function(){
		start = $('#start-tgl').val();
		if(start == ''){
			alert('Silahkan isi parameter dengan lengkap');
		}else{
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'tgl='+start,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#show-index').hide();
					$('#show-search').show();
					$('#show-search').html(data);					
					console.log(data);					
				},	
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});
		}
	});

", View::POS_READY);




?>