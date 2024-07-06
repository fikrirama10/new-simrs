<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
$this->title = 'Kuota Pasien';
?>

<div class='container'>
	
	<div class='row'>
		<div class='col-md-12'>
			<h4>Jadwal Poliklinik RSAU LANUD SULAIMAN </h4>
			<hr>
		</div>
	</div>
	<div class='row'>
		<div class='col-md-12'>
			<table class='table table-bordered'>
				<thead>
					<tr>
						<th style="text-align:center; line-height:50px;" width=50 >No</th>
						<th style="text-align:center; line-height:50px;" width=220>Poliklinik</th>
						<th style="text-align:center; line-height:50px;">Dokter</th>
						<th style="text-align:center; line-height:50px;">Jadwal</th>
					
					</tr>		
				</thead>
				<tbody>
					<tr>
						<td colspan=4>
							<div class="alert alert-warning" role="alert">
								<b>Pendaftaran Poliklinik dimulai jam 06:30 - 08:00</b>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=4>
							Keterangan : <span style='background:green;' class="badge bg-primary">Dokter Ada</span> <span style='background:red;' class="badge bg-primary">Dokter Libur</span>
						</td>
					</tr>
					
					<?php
					if($poli){
					$no2=1; foreach($poli as $p): ?>
					<tr>
						<td rowspan="<?= $p['jdokter'] + 1 ?>"><?= $no2++?></td>
						<td rowspan="<?= $p['jdokter'] + 1 ?>"><?= $p['poli']?></td>
					</tr>
					
					<?php foreach($p['dokter'] as $d): ?>
					<tr>
						<td><?= $d['dokter'] ?></td>
						<td>
							<?php foreach($d['jadwal'] as $j): ?>
								<?php if($j['status']==1){?>
								<span style='background:green;' class="badge bg-primary"><?= $j['hari'] ?></span>
								<?php }else{ ?>
								<span style='background:red;' class="badge bg-primary"><?= $j['hari'] ?></span>
								<?php } ?>
							<?php endforeach; ?>
						</td>
					</tr>
					<?php endforeach; ?>
					<?php endforeach; ?>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div class='row'>
			<div class='col-md-12'>
				<h4>Cek ketersedian kuota poliklinik</h4>
				<hr>
			</div>
		</div>
	<div class='row'>
		<div class='col-md-5'>
			<label> Tanggal Poliklinik</label>
			<input id='tgl' type='date' class='form-control'><br>
		</div>
		<div class='col-md-2'>
			<label style='color:#fff;'>`</label><br>
			<a id="show-all" class="btn btn-success" ><span class="fa fa-search" style="width: 20px;"></span>Cari</a>
		</div>
	</div>
	<div class='row'>
		<div id='inday'>
		<div class='col-md-12'>
			<table class='table table-bordered'>
				<thead>
					<tr>
						<th>No</th>
						<th>Poliklinik</th>
						<th>Kuota Pasien</th>
						<th>Sisa</th>
					</tr>
				</thead>
				<?php if($kuota){?>
				<tbody>
					<?php $no=1; foreach($kuota as $k): ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $k['poli'] ?></td>
						<td><?= $k['jumlahkuota'] ?></td>
						<td>
							<?php if($k['jumlahsisa'] == 0) {echo $k['jumlahkuota'];}else{ ?>
							<?= $k['jumlahsisa'] ?>
							<?php } ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
				<?php }else{ ?>
				<tbody>
					<tr>
						<td colspan=4></td>
					</tr>
				</tbody>
				<?php } ?>
			</table>
		</div>
		</div>
		<div id='bytgl'>
		</div>
	</div>
</div>
<?php
$urlShowAll = Url::to(['site/show-kuota']);
$this->registerJs("
	
	$('#show-all').on('click',function(){
			$('#bytgl').hide();
			tgl = $('#tgl').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'tgl='+tgl,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#inday').hide();
					$('#bytgl').show();
					$('#bytgl').html(data);
					
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