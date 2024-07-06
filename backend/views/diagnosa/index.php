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
?>
<div class='box'>
<div class='box-header with-border'><h4>10 Besar Diagnosa Pasien (ICD X)</h4></div>
<div class='box-body'>
	<div class='row'>
		<div class='col-md-6'>
			<table class='table'>
				<tr>
					<td>Start</td>
					<td>End</td>
				</tr>
				<tr>
					<td><input type='date' id='start-tgl' value='<?= date('Y-m-01') ?>' class='form-control'></td>
					<td><input type='date' id='end-tgl' class='form-control' value='<?= date('Y-m-d') ?>'></td>
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
					<?php foreach($model as $m){ ?>
						<li class="<?= $m['active']?>" class=""><a href="#tab_<?= $m['id']?>" data-toggle="tab" aria-expanded="false"> <?= $m['nama']?></a></li>
					<?php } ?>
				</ul>
				<div class="tab-content">
				<?php foreach($model as $m){ ?>
					<div class="tab-pane  <?= $m['active']?>" id="tab_<?= $m['id']?>">
					
						<?php 
							$nama = array();
							$total = array();
							foreach($m['diagnosa'] as $md):
								array_push($nama,$md['nama']);
								array_push($total,$md['jumlah']);
							endforeach;
						?>
						<?= $this->render('_data',[
							'model' => $model,
							'jenis' => $m['id'],
							'awal' => $awal,
							'akhir' => $akhir,
							'nama' => $nama,
							'total' => $total,
						]) ?>
					</div>	
				<?php } ?>
				</div>
		
	
		
			</div>
		</div>
	</div>
</div>
</div>
</div>

<?php

$urlShowAll = Url::to(['diagnosa/show-diagnosa']);
$this->registerJs("	
	$('#end-tgl').on('change',function(){
		end = $('#end-tgl').val();
		start = $('#start-tgl').val();
		if(start == ''){
			alert('Silahkan isi parameter dengan lengkap');
		}else{
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'awal='+start+'&akhir='+end,
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
	$('#start-tgl').on('change',function(){
		end = $('#end-tgl').val();
		start = $('#start-tgl').val();
		if(start == ''){
			alert('Silahkan isi parameter dengan lengkap');
		}else{
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'awal='+start+'&akhir='+end,
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