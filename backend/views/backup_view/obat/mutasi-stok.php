<?php
	use common\models\ObatBacth;
	use common\models\ObatJenismutasi;
	use common\models\ObatSubjenismutasi;
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\helpers\Url;
	use kartik\date\DatePicker;
	use yii\widgets\Pjax;
	use yii\web\View;
	$bacth = ObatBacth::find()->where(['idobat'=>$id])->all();
	$jenis_mutasi = ObatJenismutasi::find()->all();
	$sub_mutasi = ObatSubjenismutasi::find()->where(['id'=>0])->all();
?>
<div class='row'>
	<div class='col-sm-8'>
		<div class="box">
			<div class="box-header with-border">
				<h3>Mutasi Stok</h3>
			</div>
			<div class="box-body">
				<div class='row'>
					<div class='col-md-2'>
						<select id='bacth' class='form-control'>
							<option value='0'>-- Merk Obat --</option>
							<?php foreach($bacth as $b): ?>
								<option value='<?= $b->id?>'><?= $b->merk ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class='col-md-2'>
						<input type='date' id='awal' class='form-control' >
					</div>
					<div class='col-md-2'>
						<input type='date' id='akhir' class='form-control' >
					</div>
					<div class='col-md-2'>
						<select id='jenis_mutasi' class='form-control'>
							<option value='0'>-- Jenis Mutasi --</option>
							<?php foreach($jenis_mutasi as $jm): ?>
								<option value='<?= $jm->id?>'><?= $jm->jenis_mutasi ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class='col-md-2'>
						<select id='sub_mutasi' class='form-control'>
							<option value='0'>-- Sub Mutasi --</option>
							<?php foreach($sub_mutasi as $sm): ?>
								<option value='<?= $sm->id?>'><?= $sm->subjenis ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<br>
				<a class='btn btn-success' id='cek'>Cek</a>
				<hr>
				<div id='data-awal'>
				<table class='table table-bordered'>
					<tr>
						<th width='10'>No</th>
						<th>Tgl</th>
						<th>Merk</th>
						<th>Jumlah</th>
						<th>Stok Awal</th>
						<th>Stok Akhir</th>
						<th>Jenis Mutasi</th>
						<th>Sub Jenis Mutasi</th>
					</tr>
					
					<?php $no=1; foreach($json as $j): ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $j['tgl'] ?></td>
						<td><?= $j['merk'] ?></td>
						<td><?= $j['jumlah'] ?></td>
						<td><?= $j['stokAwal'] ?></td>
						<td><?= $j['stokAkhir'] ?></td>
						<td><?= $j['jenisMutasi'] ?></td>
						<td><?= $j['subMutasi'] ?></td>
					</tr>
					<?php endforeach; ?>
					
				</table>
				</div>
				<div id='data-ajax'></div>
			</div>
		</div>
	</div>
	<div class='col-sm-6'></div>
</div>
<?php 
$urlSub = Url::to(['obat/list-sub']);
$urlShowAll = Url::to(['obat/show-mutasi']);
$this->registerJs("	
	$('#data-awal').show();
	$('#data-ajax').hide();
	$('#jenis_mutasi').on('change',function(){
			kelas = $('#jenis_mutasi').val();
			$.ajax({
				type: 'GET',
				url: '{$urlSub}',
				data: 'id='+kelas,
				
				success: function (data) {
					$('#sub_mutasi').html(data);
					
					console.log(data);
					
				},
				
			});
	});
	$('#cek').on('click',function(){
		$('#show-informasi').hide();
		akhir = $('#akhir').val();
		awal = $('#awal').val();
		idobat = '{$id}';
		asal = '{$asal}';
		idbacth = $('#bacth').val();
		jenis = $('#jenis_mutasi').val();
		subjenis = $('#sub_mutasi').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'awal='+awal+'&akhir='+akhir+'&id='+idobat+'&idbacth='+idbacth+'&asal='+asal+'&jenis='+jenis+'&subjenis='+subjenis,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#data-awal').hide();
					$('#data-ajax').show();
					$('#data-ajax').animate({ scrollTop: 0 }, 200);
					$('#data-ajax').html(data);
					
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