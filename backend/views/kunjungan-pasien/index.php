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
<div class='box-header with-border'><h4>Statistik Pasien</h4></div>
<div class='box-body'>
	<div class='row'>
		<div class='col-md-6'>
			<table class='table'>
				<tr>
					<td>Start</td>
					<td>End</td>
				</tr>
				<tr>
					<td><input type='date' id='start-tgl' class='form-control'></td>
					<td><input type='date' id='end-tgl' class='form-control'></td>
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
					<li  class="active" class=""><a href="#tab_usia" data-toggle="tab" aria-expanded="false"> Jenjang Usia</a></li>
					<li class=""><a href="#tab_agama" data-toggle="tab" aria-expanded="false"> Agama</a></li>
					<li class=""><a href="#tab_etnis" data-toggle="tab" aria-expanded="false"> Etnis</a></li>
					<li class=""><a href="#tab_pendidikan" data-toggle="tab" aria-expanded="false"> Pendidikan</a></li>
					<li class=""><a href="#tab_kelurahan" data-toggle="tab" aria-expanded="false"> Kelurahan</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_usia">
						<?= $this->render('_dataUsia',[
							'model' => $model,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_agama">
						<?= $this->render('_dataAgama',[
							'model' => $model,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_etnis">
						<?= $this->render('_dataEtnis',[
							'model' => $model,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_pendidikan">
						<?= $this->render('_dataPendidikan',[
							'model' => $model,
						]) ?>
					</div>
					<div class="tab-pane" id="tab_kelurahan">
						<?= $this->render('_dataKelurahan',[
							'model' => $model,
						]) ?>
					</div>
				</div>
				<!-- /.tab-content -->
			</div>
		</div>
		
	
		
	</div>
	</div>
</div>
</div>
<?php

$urlShowAll = Url::to(['kunjungan-pasien/show-kunjungan']);
$this->registerJs("	
	$('#end-tgl').on('change',function(){
		end = $('#end-tgl').val();
		start = $('#start-tgl').val();
		rawat = '{$rawat}';
		if(start == ''){
			alert('Silahkan isi parameter dengan lengkap');
		}else{
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'start='+start+'&end='+end+'&rawat='+rawat,
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