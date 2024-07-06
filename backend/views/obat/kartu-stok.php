<?php
	use common\models\ObatBacth;
	use yii\helpers\Html;
	use yii\grid\GridView;
	use yii\helpers\Url;
	use kartik\date\DatePicker;
	use yii\widgets\Pjax;
	use yii\web\View;
	$bacth = ObatBacth::find()->where(['idobat'=>$id])->all();
?>
<div class='row'>
	<div class='col-sm-6'>
		<div class="box">
			<div class="box-header with-border">
				<h3>Kartu Stok</h3>
			</div>
			<div class="box-body">
				<div class='row'>
					<div class='col-md-3'>
						<select id='bacth' class='form-control'>
							<option value='0'>-- Merk Obat --</option>
							<?php foreach($bacth as $b): ?>
								<option value='<?= $b->id?>'><?= $b->merk ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class='col-md-3'>
						<input type='date' id='awal' class='form-control' >
					</div>
					<div class='col-md-3'>
						<input type='date' id='akhir' class='form-control' >
					</div>
					<div class='col-md-3'>
						<select id='jenis' class='form-control'>
							<option value='0'>-- Jenis --</option>
							<option value='2'>Masuk</option>
							<option value='1'>Keluar</option>
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
						<th>Jenis</th>
					</tr>
					
					<?php $no=1; foreach($json as $j): ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $j['tgl'] ?></td>
						<td><?= $j['merk'] ?></td>
						<td><?= $j['jumlah'] ?></td>
						<td><?= $j['jenis'] ?></td>
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
$urlShowAll = Url::to(['obat/show-kartu']);
$this->registerJs("	
	$('#data-awal').show();
	$('#data-ajax').hide();
	$('#cek').on('click',function(){
		$('#show-informasi').hide();
		akhir = $('#akhir').val();
		awal = $('#awal').val();
		idobat = '{$id}';
		asal = '{$asal}';
		idbacth = $('#bacth').val();
		jenis = $('#jenis').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'awal='+awal+'&akhir='+akhir+'&id='+idobat+'&idbacth='+idbacth+'&asal='+asal+'&jenis='+jenis,
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