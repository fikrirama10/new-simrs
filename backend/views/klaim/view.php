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
$alamat = PasienAlamat::find()->where(['idpasien'=>$model->id])->all();
$status = PasienStatus::find()->where(['idpasien'=>$model->id])->one();


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
				<div class='col-md-4'>
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
					
				</div>
				<div class='col-md-8'>
		
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li  class="active"><a href="#tab_kunjungan" data-toggle="tab" aria-expanded="true">Data Kunjungan</a></li>
						</ul>
						<div class="tab-content">
							
						<!-- /.tab-pane -->
							<div class="tab-pane active" id="tab_kunjungan">
							<table class='table table-bordered'>
								<tr>
									<th>IdKunjungan</th>
									<th>Jenis Kunjungan</th>
									<th>Tgl Kunjungan</th>
									<th>Label</th>
								
								<?php foreach($list_kunjungan as $list){
								$pelayanan_rawat = Rawat::find()->where(['idkunjungan'=>$list->idkunjungan])->andwhere(['<>','status',5])->all();
									?>
								<tr>
									<td><a href='<?= Url::to(['kunjungan?id='.$list->id])?>' class='btn btn-default btn-xs'><b><?= $list->idkunjungan ?></b></a></td>
									<td>
										<?php if(count($pelayanan_rawat) < 1){echo"<a href=".Url::to(['pasien/batal-kunjungan?id='.$list->id])." class='btn btn-danger'>Batalkan Kunjungan</a>";}else{ ?>
										<?php foreach($pelayanan_rawat as $prawat): ?>
											<?php if($prawat->idjenisrawat == 1){ ?>
												<?php if($prawat->status == 5){ ?>
													<span class='badge bg-default'><del><?= $prawat->poli->poli?> (<?= $prawat->bayar->bayar?>)</del></span>
												<?php }else{ ?>
													<span class='badge bg-navy'><?= $prawat->poli->poli?> (<?= $prawat->bayar->bayar?>)</span>
												<?php } ?>
												
											<?php }else if($prawat->idjenisrawat == 2){ ?>
												<span class='badge bg-primary'>Rawat Inap (<?= $prawat->bayar->bayar?>)</span>
											<?php }else{ ?>
												<span class='badge bg-red'><?= $prawat->poli->poli?> (<?= $prawat->bayar->bayar?>)</span>
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
										<li><a href='' id='label<?= $list->id?>'>Print Label Pasien</a></li>
										<li><a href='' id='barcode<?= $list->id?>'>Print Barcode</a></li>
									   
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
							
							<?php if(count($list_kunjungan) > 10){ ?>
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
					<li  class="active" class=""><a href="#tab_1" data-toggle="tab" aria-expanded="false">Data Alamat</a></li>
					<li><a href="#tab_2" data-toggle="tab" aria-expanded="true">Data Anggota</a></li>
					<li class=""><a href="#tab_3" data-toggle="tab" aria-expanded="false">Tab 3</a></li>

					<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_1">
						<?php if(Yii::$app->user->identity->idpriv == 8){ ?>
							<a href='<?= Url::to(['pasien/tambah-alamat?id='.$model->id])?>' class='btn btn-sm btn-success'>+ Tambah</a>
						<?php } ?>
							<br>
							<table class='table table-bordered'>
								<tr>
									<th>No</th>
									<th>Alamat</th>
									<th>Edit</th>
								</tr>
								<?php $noa=1; foreach($alamat as $a): ?>
								<tr>
									<td><?= $noa++ ?></td>
									<td><?= $a->alamat ?>, <?= $a->kelurahan->nama ?> , <?= $a->kecamatan->nama ?> , <?= $a->kabupaten->nama ?> , <?= $a->provinsi->nama ?></td>
									<?php if(Yii::$app->user->identity->idpriv == 8){ ?>
									<td><a href='<?= Url::to(['pasien/edit-alamat?id='.$a->id])?>' class='btn btn-xs bg-yellow'>Edit</a> <a href='<?= Url::to(['pasien/delete-alamat?id='.$a->id])?>' class='btn btn-xs bg-red'>Delete</a></td>
									<?php } ?>
								</tr>
								<?php endforeach; ?>
							</table>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_2">
					<?php if($model->idpekerjaan > 0 && $model->idpekerjaan < 5){ ?>
						<table class='table table-hovered'>
							<tr>
								<th>NRP</th>
								<th>:</th>
								<td><?= $status->nrp?></td>
							</tr>
							<tr>
								<th>Pangkat</th>
								<th>:</th>
								<td><?= $status->pangkat?></td>
							</tr>
							<tr>
								<th>Kesatuan</th>
								<th>:</th>
								<td><?= $status->kesatuan?></td>
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
