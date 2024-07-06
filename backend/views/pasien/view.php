<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\Rawat;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

$alamat = PasienAlamat::find()->where(['idpasien' => $model->id])->all();
$status = PasienStatus::find()->where(['idpasien' => $model->id])->one();

$pesertabpjs = Yii::$app->vclaim->get_pesertanobpjs($model->no_bpjs, date('Y-m-d'));
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Pasiens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="pasien-view">
	<div class='box box-primary'>
		<div class='box-header with-border'>
			<h3>Data Pasien </h3>
		</div>
		<div class='box-body'>

			<div class='row'>
				<div class='col-md-3'>
					<?php if ($pesertabpjs['metaData']['code'] == 200) { ?>
						<?php if ($pesertabpjs['response']['peserta']['statusPeserta']['kode'] > 0) { ?>
							<div class="callout callout-danger">
								<p><b><?= $pesertabpjs['response']['peserta']['statusPeserta']['keterangan'] ?></b></p>
							</div>
							<input type='hidden' value='<?= $pesertabpjs['response']['peserta']['statusPeserta']['kode'] ?>' id='kodepremi'>
							<input type='hidden' value='<?= $pesertabpjs['response']['peserta']['statusPeserta']['keterangan'] ?>' id='ketpremi'>
						<?php } ?>
					<?php } ?>
					<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'no_rm',
							'nik',
							'no_bpjs',
							'nama_pasien',
							'tgllahir',
							'tempat_lahir',
							'nohp',
							'usia_tahun',
							'kepesertaan_bpjs',
							'pekerjaan.pekerjaan',
						],
					]) ?>
					<?php if (Yii::$app->user->identity->idpriv == 8) { ?>
						<a href='<?= Url::to(['pasien/edit-pasien?id=' . $model->id]) ?>' class='btn btn-xs bg-primary'>Edit</a>
						<a href='<?= Url::to(['pasien/form-pasien?id=' . $model->id]) ?>' class='btn btn-xs bg-yellow' target='_blank'>Print Form</a>
						<a href='<?= Url::to(['pasien/barcode-kartu?id=' . $model->id]) ?>' class='btn btn-xs bg-navy' target='_blank'>Print Label Depan</a>
						<a href='<?= Url::to(['pasien/barcode-kartu-label?id=' . $model->id]) ?>' class='btn btn-xs bg-info' target='_blank'>Print Label Dalam</a>
					<?php } ?>
					<hr>
				</div>
				<div class='col-md-9'>

					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li class="active" class=""><a href="#tab_histori" data-toggle="tab" aria-expanded="false">Histori Pasien</a></li>
							<li><a href="#tab_kunjungan" data-toggle="tab" aria-expanded="true">Data Kunjungan</a></li>
							<li><a href="#tab_bpjs" data-toggle="tab" aria-expanded="true">Histori BPJS</a></li>
							<!-- <li><a href="#tab_lama" data-toggle="tab" aria-expanded="true">Histori SIMRS Lama</a></li> -->

							<li class="pull-left"><a data-toggle="modal" data-target="#mdPasien" id='confirm' class="btn btn-xs btn-danger"><i class="fa fa-plus"></i></a></li>
						</ul>
						<div class="tab-content">
							
							<div class="tab-pane" id="tab_bpjs">
								<?php
								$awal = date('Y-m-d', strtotime('-89 day +7 hour'));
								$akhir = date('Y-m-d', strtotime('+7 hour'));
								$monitor = Yii::$app->monitoring->get_historipel($model->no_bpjs, $awal, $akhir);
								?>
								<?php if ($monitor['metaData']['code'] == 200) { ?>
									<?php
									$mino = array_slice($monitor['response']['histori'], 0, 3);
									foreach ($mino as $mr) { ?>
										<div id="divHistori" class="list-group">
											<span class="list-group-item">
												<h5 class="list-group-item-heading"><b><u><?= $mr['noSep'] ?></u></b></h5>
												<small>
													<?php if ($mr['jnsPelayanan'] == 1) {
														echo 'Rawat Inap';
													} else {
														echo 'Rawat Jalan';
													} ?>
												</small>
												<p class="list-group-item-text"><small></small></p>
												<p class="list-group-item-text"><small><?= $mr['poli'] ?></small></p>
												<p class="list-group-item-text"><small><?= $mr['tglSep'] ?></small></p>
												<p class="list-group-item-text"><small></small></p>
												<p class="list-group-item-text"><small><?= $mr['diagnosa'] ?></small></p>
												<p class="list-group-item-text"><small><?= $mr['ppkPelayanan'] ?></small></p>
												<br>
												<p class="list-group-item-text">
												<div class="btn-group">
													<?php if ($mr['poli'] == 'PENYAKIT DALAM') {
														$poli = 'INT';
													} else if ($mr['poli'] == 'INSTALASI GAWAT DARURAT') {
														$poli = 'IGD';
													} else {
														$poli = $mr['poli'];
													} ?>
													<a target='_blank' href='<?= Url::to(['data-kunjungan-bpjs/view?id=' . $mr['noSep'] . '&poli=' . $poli]) ?>' class="btn btn-xs btn-warning"><i class="fa fa-eye"></i> Lihat</a>
												</div>
												</p>
											</span>

										</div>
									<?php } ?>
									<button type="button" id="btnHistori" data-toggle="modal" data-target="#historiBpjs" class="btn btn-xs btn-default btn-block"><span class="fa fa-cubes"></span> Histori</button>
								<?php } ?>
							</div>
							<div class="tab-pane active" id="tab_histori">
								<table class="table table-bordered">
									<tr>
										<th width=10>No</th>
										<th>Jenis Rawat</th>
										<th>Nama Poli / Spesialis</th>
										<th>Tgl Masuk</th>
										<th>Tgl Pulang</th>
										<th>Status</th>
										<th>Keterangan</th>
										<th>#</th>
									</tr>
									<?php $no = 1;
									foreach ($list_rawat as $lr) : ?>
										<?php if ($lr->status == 5) {
											echo '<tr style="background-color:#ffcece; color:#f00;">';
										} else { ?>
											<tr>
											<?php } ?>
											<td><?= $no++ ?></td>
											<td><?= $lr->jenisrawat->jenis ?>(<?= $lr->bayar->bayar ?>)</td>
											<td><?= $lr->poli->poli ?> (<?= $lr->dokter->nama_dokter ?>)</td>
											<td><?= $lr->tglmasuk ?></td>
											<td><?= $lr->tglpulang ?></td>
											<td><?= $lr->rawatstatus->status; ?></td>
											<td><?= $lr->keterangan; ?></td>
											<?php if ($lr->status == 5) {
												echo '<td>-</td>';
											} else { ?>
												<td>
													<?php if ($lr->idbayar == 2) { ?>
														<?php if ($lr->no_sep == null) { ?>
															<?php if ($lr->idjenisrawat == 3) { ?>
																<a href='<?= Url::to(['pasien/sep-ugd?id=' . $lr->id]) ?>' style='font-size:10px;' class='btn btn-xs btn-danger'>Buat SEP UGD</a>
															<?php } else { ?>
																<?php if ($lr->kunjungan == 3) { ?>
																	<a href='<?= Url::to(['pasien/sep-post-ranap?id=' . $lr->id]) ?>' style='font-size:10px;' class='btn btn-xs bg-info'>SEP POST RANAP</a>
																<?php } else { ?>
																	<a data-toggle="modal" data-target="#mdRawat<?= $lr->id ?>" style='font-size:10px;' class='btn btn-xs btn-success'>Buat SEP</a>
																<?php } ?>
															<?php } ?>
														<?php } else { ?>
															<a href='<?= Url::to(['pasien/print-sep?id=' . $lr->id]) ?>' target='_blank' style='font-size:10px;' class='btn btn-xs btn-warning'>Print SEP</a>
														<?php } ?>

													<?php } ?>
													<?php if ($lr->idjenisrawat != 2) { ?>
														<a data-toggle="modal" data-target="#mdDetail<?= $lr->id ?>" class='btn btn-xs btn-primary' style='font-size:10px;'>Detail</a>
													<?php } ?>
													<?php if ($lr->idjenisrawat == 2) { ?>
														<a href='<?= Url::to(['pasien/gelang?id=' . $lr->id]) ?>' class='btn btn-success btn-xs' style='font-size:10px;'>Print Gelang</a>
													<?php } ?>
													<a href='<?= Url::to(['pasien/edit-rawat?id=' . $lr->id]) ?>' class='btn bg-aqua btn-xs' style='font-size:10px;'>Edit</a>
													<a href='<?= Url::to(['pasien/batal-rawat?id=' . $lr->id]) ?>' class='btn bg-maroon btn-xs' style='font-size:10px;'>Batalkan</a>
												</td>
											<?php } ?>
											</tr>
										<?php endforeach;  ?>
								</table>
								<?php if (count($list_rawat) > 10) { ?>
									<a class='btn btn-default'>Lihat Semua</a>
								<?php } ?>
							</div>
							<!-- /.tab-pane -->
							<div class="tab-pane" id="tab_kunjungan">
								<table class='table table-bordered'>
									<tr>
										<th>IdKunjungan</th>
										<th>Jenis Kunjungan</th>
										<th>Tgl Kunjungan</th>
										<th>Label</th>

										<?php foreach ($list_kunjungan as $list) {
											$pelayanan_rawat = Rawat::find()->where(['idkunjungan' => $list->idkunjungan])->andwhere(['<>', 'status', 5])->all();
										?>
									<tr>
										<td><a href='<?= Url::to(['pasien/rawat-kunjungan?id=' . $list->id]) ?>' class='btn btn-default btn-xs'><b><?= $list->idkunjungan ?></b></a></td>
										<td>
											<?php if (count($pelayanan_rawat) < 1) {
												echo "<a href=" . Url::to(['pasien/batal-kunjungan?id=' . $list->id]) . " class='btn btn-danger'>Batalkan Kunjungan</a>";
											} else { ?>
												<?php foreach ($pelayanan_rawat as $prawat) : ?>
													<?php if ($prawat->idjenisrawat == 1) { ?>
														<?php if ($prawat->status == 5) { ?>
															<span class='badge bg-default'><del><?= $prawat->poli->poli ?> (<?= $prawat->bayar->bayar ?>)</del></span>
														<?php } else { ?>
															<span class='badge bg-navy'><?= $prawat->poli->poli ?> (<?= $prawat->bayar->bayar ?>)</span>
														<?php } ?>

													<?php } else if ($prawat->idjenisrawat == 2) { ?>
														<span class='badge bg-primary'>Rawat Inap (<?= $prawat->bayar->bayar ?>)</span>
													<?php } else { ?>
														<span class='badge bg-red'><?= $prawat->poli->poli ?> (<?= $prawat->bayar->bayar ?>)</span>
													<?php } ?>

												<?php endforeach; ?>
										</td>
										<td><?= $list->tgl_kunjungan ?></td>
										<td>
											<div class="btn-group">
												<button type="button" class="btn btn-info btn-flat btn-xs">Print Label</button>
												<button type="button" class="btn btn-info btn-xs btn-flat dropdown-toggle" data-toggle="dropdown">
													<span class="caret"></span>
													<span class="sr-only">Toggle Dropdown</span>
												</button>
												<ul class="dropdown-menu" role="menu">
													<li><a href='' id='label<?= $list->id ?>'>Print Label Pasien</a></li>
													<li><a href='' id='barcode<?= $list->id ?>'>Print Barcode</a></li>

												</ul>
											</div>

										</td>
									</tr>
									<?php
												$urlLabel = Url::to(['pasien/label-pasien?id=']);
												$urlBarcode = Url::to(['pasien/barcode-pasien?id=']);
												$this->registerJs("

										$('#label{$list->id}').on('click', function(event){
											age =  prompt('Jumlah yang akan di cetak?', );
											if(age > 0){									   window.open('{$urlLabel}{$list->id}&jumlah='+age+'', '_blank');
											} else {
												alert('Harap Masukan Jumlah');
											}
										});
										
										$('#barcode{$list->id}').on('click', function(event){
											age =  prompt('Jumlah yang akan di cetak?', );
											if(age > 0){									   window.open('{$urlBarcode}{$list->id}&jumlah='+age+'', '_blank');
											} else {
												alert('Harap Masukan Jumlah');
											}
										});
										

									", View::POS_READY);
									?>

								<?php } ?>
							<?php } ?>
								</table>

								<?php if (count($list_kunjungan) > 10) { ?>
									<a class='btn btn-default'>Lihat Semua</a>
								<?php } ?>
							</div>
							<!-- /.tab-pane -->
							<div class="tab-pane" id="tab_3">

							</div>
							<!-- /.tab-pane -->
						</div>
						<!-- /.tab-content -->
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class='row'>
		<div class='col-md-12'>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active" class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">Data Alamat</a></li>
					<li><a href="#tab_2" data-toggle="tab" aria-expanded="true">Data Anggota</a></li>
					<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Tab 3</a></li>

					<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						<?php if (Yii::$app->user->identity->idpriv == 8) { ?>
							<a href='<?= Url::to(['pasien/tambah-alamat?id=' . $model->id]) ?>' class='btn btn-sm btn-success'>+ Tambah</a>
						<?php } ?>
						<br>
						<table class='table table-bordered'>
							<tr>
								<th>No</th>
								<th>Alamat</th>
								<th>Edit</th>
							</tr>
							<?php $noa = 1;
							foreach ($alamat as $a) : ?>
								<tr>
									<td><?= $noa++ ?></td>
									<td><?= $a->alamat ?>, </td>
									<?php if (Yii::$app->user->identity->idpriv == 8) { ?>
										<td><a href='<?= Url::to(['pasien/edit-alamat?id=' . $a->id]) ?>' class='btn btn-xs bg-yellow'>Edit</a> <a href='<?= Url::to(['pasien/delete-alamat?id=' . $a->id]) ?>' class='btn btn-xs bg-red'>Delete</a></td>
									<?php } ?>
								</tr>
							<?php endforeach; ?>
						</table>
					</div>
					<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_2">
						<?php if ($model->idpekerjaan > 0 && $model->idpekerjaan < 5) { ?>
							<table class='table table-hovered'>
								<tr>
									<th>NRP</th>
									<th>:</th>
									<td><?= $status->nrp ?></td>
								</tr>
								<tr>
									<th>Pangkat</th>
									<th>:</th>
									<td><?= $status->pangkat ?></td>
								</tr>
								<tr>
									<th>Kesatuan</th>
									<th>:</th>
									<td><?= $status->kesatuan ?></td>
								</tr>
							</table>
						<?php } ?>
					</div>
					<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_3">

					</div>
					<!-- /.tab-pane -->
				</div>
				<!-- /.tab-content -->
			</div>
		</div>
	</div>



</div>

<?php
$urlShowAll = Url::to(['pasien/show-dokter']);
$urlShowLayanan = Url::to(['pasien/show-layanan']);
$urlShowAntrian = Url::to(['pasien/show-antrian']);
$this->registerJs("

	$('#confirm').on('click', function(event){
		kode = $('#kodepremi').val();
		ket = $('#ketpremi').val();
		if(kode > 0){			
			age = confirm('Peserta '+ket+' , lanjutkan input kunjungan peserta ?');
				if(age == true){
					$('#mdPasien').modal('hide');
					return true;
				} else {					
					 $('#mdPasien').modal('show');
					event.preventDefault();
			}
		}
		// age = confirm('Konfirmasi Untuk membuat Kunjungan Baru');
		// if(age == true){
			 // return true;
		// } else {
			// event.preventDefault();
		// }
	});
	$('#tampillayanan').hide();
	$('#confirm2').on('click', function(event){
		age = confirm('Konfirmasi Untuk membuat Kunjungan Baru');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});
	$('#rawat-idpoli').on('change',function(){
		$('#pasien-ajax').hide();
		$('#tampillayanan').hide();
	});
	$('#rawat-idjenisrawat').on('change',function(){
		$('#pasien-ajax').hide();
		$('#tampillayanan').hide();
	});
	$('#rawat-idbayar').on('change',function(){
		$('#pasien-ajax').hide();
		$('#tampillayanan').hide();
	});
	$('#rawat-tglmasuk').on('change',function(){
		$('#pasien-ajax').hide();
		$('#tampillayanan').hide();
	});
	$('#iddokter').on('change',function(){
		alert('aaa');
	});
	$('#cek-antrian').on('click',function(){
	    kode = $('#kode_antrian').val();
	    nomer = $('#no_antrian').val();
	   // alert(kode+'-'+nomer);
	    $.ajax({
			type: 'GET',
			url: '{$urlShowAntrian}',
		
			success: function (data) {
			   console.log(data)
			}
		});
	})
	$('#show-pelayanan').on('click',function(){
		bayar = $('#rawat-idbayar').val();
		$('#pasien-ajax').hide();
		jenis = $('#rawat-idjenisrawat').val();
		poli = $('#rawat-idpoli').val();
		kunjungan = $('#rawat-tglmasuk').val();
		$.ajax({
			type: 'GET',
			url: '{$urlShowLayanan}',
			data: 'poli='+poli+'&bayar='+bayar+'&jenis='+jenis+'&kunjungan='+kunjungan,
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
	$('#show-all').on('click',function(){
			$('#pasien-ajax').hide();
			poli = $('#rawat-idpoli').val();
			rm = $('#rawat-no_rm').val();
			jenis = $('#rawat-idjenisrawat').val();
			kunjungan = $('#rawat-tglmasuk').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+poli+'&rm='+rm+'&jenis='+jenis+'&kunjungan='+kunjungan,
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
Modal::begin([
	'id' => 'mdPasien',
	'header' => '<h3>Kunjungan Pasien</h3>',
	'size' => 'modal-lg',
	'options' => [
		'data-url' => 'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">' . $this->render('_form-rajal2', ['model' => $model, 'pelayanan' => $pelayanan]) . '</div>';

Modal::end();

Modal::begin([
	'id' => 'historiBpjs',
	'header' => '<h5>Histori Peserta</h5>',
	'size' => 'modal-lg',
	'options' => [
		'data-url' => 'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">' . $this->render('_historiBpjs', ['model' => $model]) . '</div>';

Modal::end();

foreach ($list_rawat as $lr) :
	if($lr->idbayar == 2){
		if($lr->status != 5){
			if($lr->no_sep == NULL){
				Modal::begin([
					'id' => 'mdRawat' . $lr->id,
					'header' => '<h3>Buat SEP Pasien</h3>',
					'size' => 'modal-lg',
					'options' => [
						'data-url' => 'transaksi',
						'tabindex' => ''
					],
				]);
			
				echo '<div class="modalContent">' . $this->render('_formRawat', ['model' => $model, 'lr' => $lr]) . '</div>';
			
				Modal::end();
		
				Modal::begin([
					'id' => 'mdKontrol' . $lr->id,
					'header' => '<h3>Buat Surat Kontrol</h3>',
					'size' => 'modal-lg',
					'options' => [
						'data-url' => 'transaksi',
						'tabindex' => ''
					],
				]);
			
				echo '<div class="modalContent">' . $this->render('_formKonrol', ['model' => $model, 'lr' => $lr, 'kontrol' => $kontrol]) . '</div>';
			
				Modal::end();
			}
			
		}
		
	}
	

	// Modal::begin([
	// 	'id' => 'mdDetail' . $lr->id,
	// 	'header' => '<h3>Detail Kunjungan</h3>',
	// 	'size' => 'modal-lg',
	// 	'options' => [
	// 		'data-url' => 'transaksi',
	// 		'tabindex' => ''
	// 	],
	// ]);

	// echo '<div class="modalContent">' . $this->render('_formDetail', ['model' => $model, 'lr' => $lr]) . '</div>';

	// Modal::end();

	

	// Modal::begin([
	// 	'id' => 'mdDetail' . $lr->id,
	// 	'header' => '<h3>Detail Kunjungan</h3>',
	// 	'size' => 'modal-lg',
	// 	'options' => [
	// 		'data-url' => 'transaksi',
	// 		'tabindex' => ''
	// 	],
	// ]);

	// echo '<div class="modalContent">' . $this->render('_formDetail', ['model' => $model, 'lr' => $lr]) . '</div>';

	// Modal::end();
endforeach;
?>