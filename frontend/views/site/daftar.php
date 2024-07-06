<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
$this->title = 'Data Pasien';
?>

<h3>Pendaftaran Online</h3>
<hr>
<div class="body-content">
	<div class="row">
		<div class="col-md-8 col-sm-12">
			<div class='row'>
				<div class='col-md-12'>
				<div class="alert alert-info" role="alert">
				Untuk mendaftar silahkan masukan nomer
				rekam medis dan tgl lahir sesuai dengan
				kartu berobat yang dimiliki
			</div>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-5'>
					<div class='form-group'>
						<label>Nomor Rekammedis</label>
						<input class='form-control' type ='text' id='pasien-poli'>
					</div>
				</div>	
				<div class='col-md-5'>
				<label> Tanggal Lahir (Bulan / Tanggal / Tahun)</label>
				<input id='pasien-tgl' type='date' class='form-control'>
				<br>
				</div>
				<div class='col-md-2'>
				<label style='color:#fff;'>`</label>
				<br>
				<a id="show-all" class="btn btn-success" ><span class="fa fa-search" style="width: 20px;"></span>Cari</a></div>
				
			</div>
			<div class='row'>
				<div class='col-md-12'>
					<div id='loading' style='display:none;'>
						<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
					</div>
					<div id='pasien-ajax'>	
					</div>
				</div>
			</div>
		</div>
		
		<br>
		<div class="col-md-4">
			
			<img width='100%' src='https://simrs.rsausulaiman.com/frontend/images/katber.jpg'>
		</div>
	</div>
</div>
<?php
$urlShowAll = Url::to(['site/show']);
$this->registerJs("
	
	$('#show-all').on('click',function(){
			$('#pasien-ajax').hide();
			poli = $('#pasien-poli').val();
			tgl = $('#pasien-tgl').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+poli+'&tgl='+tgl,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#pasien-ajax').show();
					$('#pasien-ajax').animate({ scrollTop: 0 }, 200);
					$('#pasien-ajax').html(data);
					
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