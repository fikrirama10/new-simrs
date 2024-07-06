<?php
use common\models\Poli;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\web\View;
use yii\bootstrap\Modal;
use dosamigos\chartjs\ChartJs;
$poli = Poli::find()->all();


?>
<h2>Laporan Kunjungan Rawat Jalan</h2>
<div class='box'>
	<div class='box-header with-border'>
		<div class="col-sm-12">
				<div class="row">
					<div class="col-sm-3">						
						<label>Bulan</label>
						<select class="form-control" name="Bulan" id="bulan">
						  <option value="01">Januari</option>
						  <option value="02">Februari</option>
						  <option value="03">Maret</option>
						  <option value="04">April</option>
						  <option value="05">Mei</option>
						  <option value="06">Juni</option>
						  <option value="07">Juli</option>
						  <option value="08">Agustus</option>
						  <option value="09">September</option>
						  <option value="10">Oktober</option>
						  <option value="11">November</option>
						  <option value="12">Desember</option>
						</select>
					</div>
					<div class="col-sm-3">
						<label>Tahun</label>
						<select class="form-control" name="Tahun" id="tahun">
						  <option value="2023">2023</option>
						  <option value="2022">2022</option>
						  <option value="2021">2021</option>
						  <option value="2020">2020</option>
						  <option value="2019">2019</option>
						</select>
					</div>
					<div class="col-sm-3">
						<label>Poliklinik</label>
						<select class="form-control" name="Poli" id="poli">
							<option value =0>-- Pilih Poliklinik --</option>
							<?php foreach($poli as $p){ ?>
								<option value='<?= $p->id?>'><?= $p->poli?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			
			<div class="col-sm-12">
				<div class='row'>
					<div class='col-md-12'>
						<br>
						<a class='btn btn-info' id='cek'>Cek</a>
					</div>
				</div>
			</div>
	</div>
	<div class='box-body'>
		<div id='loading' style='display:none;'>
			<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
		</div>
		<div id='laporan'></div>
	</div>
</div>
<?php 
$urlShowAll = Url::to(['poliklinik/show-laporan']);
$this->registerJs("	
	$('#cek').on('click',function(){
		$('#laporan').hide();
		start = $('#bulan').val();
		end = $('#tahun').val();
		poli = $('#poli').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'bulan='+start+'&tahun='+end+'&poli='+poli,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#laporan').show();
					$('#laporan').animate({ scrollTop: 0 }, 200);
					$('#laporan').html(data);
					
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
