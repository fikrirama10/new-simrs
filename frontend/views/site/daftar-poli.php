<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
$this->title = 'Pendaftaran Rawatjalan';
?>
<?php if($data_json['metadata']['code'] == 400){ ?>
	<hr>
	<div class="alert alert-danger" role="alert">
		Data Pasien tidak ditemukan
	</div>
<?php }else{ ?>
<?php $form = ActiveForm::begin(); ?>

	<h3>Data Pasien</h3>
	<hr>
	
	<table class='table table-striped'>
		<tbody>
		<tr>
			<th width='20%'>Nama Pasien</th>		
			<td width='5%'>:</td>		
			<td><?= $data_json['response']['Nama']?></td>		
		</tr>
		<tr>
			<th>Tgl Lahir</th>
			<td>:</td>		
			<td><?= $data_json['response']['TglLahir']?> (<?= $data_json['response']['Usia'] ?> Th)</td>		
		</tr>
		<tr>
			<th>NIK</th>
			<td width='5%'>:</td>		
			<td><?= $data_json['response']['NIK']?></td>		
		</tr>
		<tr>
			<th>NO BPJS</th>
			<td width='5%'>:</td>		
			<td><?= $data_json['response']['NoBpjs']?></td>		
		</tr>
		</tbody>
	</table>
	<div class='row'>
		<div class='col-md-7'>
			<div class='form-group'>
				<label>Jenis Berobat</label>
				<select id="pasien-poli" class="form-control" aria-invalid="false">
					<option value="">-Pilih Jenis Berobat-</option>
					<option value="4">Umum</option>
					<option value="5">BPJS</option>
				</select>
				<input type='hidden' id='nobpjs' name='nobpjs' value='<?= $data_json['response']['NoBpjs'] ?>'>
			</div>
		</div>		
	</div>
	<div class='row'>
				<div class='col-md-12'>
					
					<div id='pasien-ajax'  style='display:none;'>	
					</div>
				</div>
			</div>
<?php ActiveForm::end(); ?>
<?php
$urlShowUmum = Url::to(['site/daftar-umum']);
$urlShowBpjs = Url::to(['site/daftar-bpjs']);
$this->registerJs("
	
	$('#pasien-poli').on('change',function() {
			$('#pasien-ajax').hide();
			var polii = $('#pasien-poli').val();
			nobpjs = $('#nobpjs').val();
			poli = $('#pasien-poli').val();
			if(polii == 4){
				$.ajax({
				type: 'GET',
				url: '{$urlShowUmum}',
				
				data: 'id='+poli+'&nobpjs='+nobpjs,
			
				success: function (data) {
						
					$('#pasien-ajax').show();
					$('#pasien-ajax').html(data);
					
					console.log(data);
					
				},
			  
				});
			}else{
				$.ajax({
				type: 'GET',
				url: '{$urlShowBpjs}',
				data: 'id='+poli+'&nobpjs='+nobpjs,
				 beforeSend: function(){
				// Show image container
				$('#loader').show();
			   },
				success: function (data) {
					$('#pasien-ajax').show();
					$('#pasien-ajax').html(data);
					
					console.log(data);
					
				},
			   complete:function(data){
				// Hide image container
				$('#loader').hide();
			   }
			});
			}
			
		
	});


	
           
	

", View::POS_READY);
?>
<?php } ?>