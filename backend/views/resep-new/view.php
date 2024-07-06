<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use common\models\ObatTransaksiDetail;
use common\models\ObatRacik;
use common\models\ObatRacikDetail;
use yii\bootstrap\Modal;
?>

<div class='row'>
	<div class='col-md-8'>
		<div class='box'>
			<div class='box-header with-border'><h4>Data Pasien</h4></div>
			<div class='box-body'>
				<table>
					<tr>
						<td><b>Nama Pasien</b> <input type='text' value='<?= $model->pasien->nama_pasien?>' disabled></td>
						<td><b>Tgl Lahir</b> <input type='text' value='<?= $model->pasien->tgllahir?>' disabled></td>
						
						<td><b>Usia</b> <input type='text' value='<?= $model->pasien->usia_tahun?>th <?= $model->pasien->usia_bulan?>bln <?= $model->pasien->usia_hari?>hr' disabled></td>
					</tr>
					<tr>
						<td><b>Tgl Kunjungan</b> <input type='text' value='<?= $model->tglmasuk?>' disabled></td>
						<td><b>Jenis Kunjungan</b> <input type='text' value='<?= $model->jenisrawat->jenis?>' disabled></td>
						<td><b>Ruangan</b> <input type='text' value='<?= $model->ruangan->nama_ruangan?>' disabled></td>
					</tr>
					<tr>
						<td><b>Dokter</b> <input type='text' value='<?= $model->dokter->nama_dokter?>' disabled></td>
						<td><b>Poli</b> <input type='text' value='<?= $model->poli->poli?>' disabled></td>
						<td><b>Penanggung</b> <input type='text' value='<?= $model->bayar->bayar?>' disabled></td>
					</tr>
				</table>
			</div>
			<div class='box-footer'>
				<a href='<?= Url::to(['/resep-new'])?>' class='btn btn-primary pull-right'>Kembali</a> 
				<a href='<?= Url::to(['/resep-new/histori-resep?id='.$model->no_rm])?>' class='btn btn-info pull-left'>Histori Resep</a>
			</div>
		</div>
		<div class='box box-primary'>
			<div class='box-header with-border'><h4>Buat Resep</h4></div>
			<div class='box-body'>
				<?php $form = ActiveForm::begin(); ?>
					<div class='row'>
						<div class='col-md-4'>
							<label>Tgl Resep</label>
							<input type='date' class='form-control' required name="ObatTransaksi[tgl]">
							<input type='hidden' class='form-control' value='<?= $model->id?>' required name="ObatTransaksi[idrawat]">
							<input type='hidden' class='form-control' value='<?= $transaksi->id?>' required name="ObatTransaksi[idtrx]">
							<input type='hidden' class='form-control' value='<?= $transaksi->no_rm?>' required name="ObatTransaksi[no_rm]">
							<input type='hidden' class='form-control' value='<?= $model->idjenisrawat?>' required name="ObatTransaksi[idjenisrawat]">
						</div>
						<div class='col-md-4'>
							<label></label><br>
							<button class='btn btn-sm btn-warning'>Tambah Resep</button>
						</div>
					</div>
				<?php ActiveForm::end(); ?>
				<hr>
				<div class='row'>
					<div class='col-md-12'>
						<?php foreach($dataresep as $dr){ ?>
						<table class='table table-bordered'>
							<tr>
								<th  colspan=3>Kode Resep : <?= $dr->kode_resep ?></th>
								<th colspan=4>
									<?php if($dr->status == 1){ ?>
										<a href='<?= Url::to(['resep/batalkan-resep?id='.$dr->id])?>' class='btn btn-danger btn-xs'>Batalkan</a>
										<a href='<?= Url::to(['resep/etiket?id='.$dr->id])?>' target='_blank' class='btn btn-default btn-xs'>Etiket</a>
										<a target='_blank' href='<?= Url::to(['resep/faktur?id='.$dr->id])?>' class='btn btn-default btn-xs'>Faktur</a>
									<?php } ?>
								</th>
							</tr>
							<?php if($dr->status == 1){ ?>
							<tr>
								<th colspan=9>
									<a data-toggle="modal" data-target="#mdAdd" class='btn btn-info btn-xs'>Tambah Obat</a>
								</th>
							</tr>
							<tr style='font-size:10px;'>
								<td width=10>No</td>
								<td width=10>Racik</td>
								<td>Obat</td>
								<td>Signa</td>
								<td>Jumlah</td>
								<td>Harga</td>
								<td>Total</td>
								<td>#</td>
							</tr>
							<?php $obat_detail = ObatTransaksiDetail::find()->where(['idtrx'=>$dr->id])->all();
								$no = 1;
								$total_harga = 0;
								foreach($obat_detail as $od){
									$total_harga += $od->total;
								?>
								<tr>
									<td><?= $no++ ?></td>
									<td><a href='<?= Url::to(['resep-new/racik?id='.$od->id.'&idresep='.$dr->id])?>' class='btn btn-default btn-xs'>Racik</a></td>
									<td>
										<?= $od->nama_obat ?> (<?= $od->signa?> <?= $od->diminum ?> <?php if($od->diminum != ''){echo'makan';} ?>  <?= $od->keterangan?>)
										<?php if($od->idbayar == 1){echo'<span class="label label-default">Pribadi</span>';}else if($od->idbayar == 2){echo'<span class="label label-success">BPJS</span>';}else if($od->idbayar == 3){echo'<span class="label label-warning">Kronis</span>';}else{echo'<span class="label label-warning">Covid</span>';}  ?>
									</td>
									<td><?= $od->satuan_obat ?></td>
									<td><?= $od->qty ?></td>
									<td><?= $od->harga ?></td>
									<td><?= $od->total ?></td>
									<td><a href='<?= Url::to(['resep/batalkan?id='.$od->id])?>' class='btn btn-danger btn-xs'>Hapus</a></td>
								</tr>	
								<?php } ?>
								<?php $racikan = ObatRacikDetail::find()->where(['idresep'=>$dr->id])->andwhere(['status'=>1])->all(); 
								if(count($racikan) > 0){
								?>
								<tr style='background:#ddd; font-size:9px;'>
									<td colspan=3>
										
										<table class='table'>
											<tr style='height:5px;'>
												<th colspan=7>Racikan Obat</th>
											</tr>
											<tr>
												<th width=10>No</th>
												<th>Nama Obat</th>
												<th>Jumlah</th>
												<th>#</th>
											</tr>
											<?php 
											$noracik=1;
											foreach($racikan as $r):
											?>
											<tr>
												<td><?= $noracik++ ?></td>
												<td><?= $r->nama_obat?></td>
												<td><?= $r->jumlah?></td>
												<td><a href='<?= Url::to(['resep-new/hapus-obat?id='.$r->id])?>' class='label label-danger'>hapus</a></td>
											</tr>
											<?php endforeach ; ?>
										</table>
										<a href='<?= Url::to(['resep-new/racik-obat?id='.$dr->id.'&idrawat='.$model->id])?>' class='btn btn-primary btn-xs pull-right'>Racik Obat</a>
									</td>
								</tr>
								<?php } ?>
								<?php $dataracik = ObatRacik::find()->where(['idresep'=>$dr->id])->andwhere(['status'=>1])->all(); 
								if(count($dataracik) > 0){
								?>
								<tr>
									<td colspan=7>
										<table class='table table-bordered'>
											<tr>
												<th colspan=2>Racikan</th>	
												<th>Jasa</th>
											</tr>
											<?php foreach($dataracik as $drr): 
											$racikann = ObatRacikDetail::find()->where(['idracik'=>$drr->id])->andwhere(['status'=>2])->all(); 
											?>
											<tr>
												<td width=10><a href='<?= Url::to(['resep-new/hapus-racik?id='.$drr->id])?>' class='btn btn-danger btn-xs'>x</a></td>
												<td><?= $drr->kode_racik ?> (<?php foreach($racikann as $rnn){echo $rnn->nama_obat.',';} ?>)</td>
												<td>10000</td>
											</tr>
											<?php endforeach ?>
										</table>
									</td>	
									
								</tr>
								<?php } ?>

							<?php } ?>
							<tr>
								<th colspan=6><span class='pull-right'>Jasa Racik</span></th>
								<td><span class='pull-left'><?php $total_racik = count($dataracik) * 10000; echo $total_racik; ?></span></td>
							</tr>
							<tr>
								<th colspan=6><span class='pull-right'>Total Harga</span></th>
								<td><span class='pull-left'><?php  echo $total_harga + $total_racik; ?></span></td>
							</tr>
							<tr>
								<td colspan=7><a id='selesai-confirm' href='<?= Url::to(['resep-new/selesai?id='.$dr->id])?>' class='btn btn-success pull-right'>Selesai</a></td>
							</tr>
						</table>
						<?php 
						$this->registerJs("							
							$('#selesai-confirm').on('click', function(event){
								age = confirm('Yakin Untuk menyimpan data?? , setelah data tersimpan maka stok akan langsung berkurang, dan data tidak bisa di edit, pastikan semua jumlah telah sesuai.');
								if(age == true){
									 return true;
								} else {
									event.preventDefault();
								}
							});
						", View::POS_READY);

						?>
						<?php
							Modal::begin([
								'id' => 'mdAdd',
								'header' => '<h3>Tambah Obat</h3>',
								'size'=>'modal-lg',
								'options'=>[
									'data-url'=>'transaksi',
									'tabindex' => ''
								],
							]);

							echo '<div class="modalContent">'.$this->render('_formTambah', ['model'=>$model,'resep_detail'=>$resep_detail,'idresep'=>$dr->id]).'</div>';
							 
							Modal::end();
						?>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class='col-md-4'>
		<div class='box box-success'>
			<div class='box-header with-border'><h4>List Resep</h4></div>
			<div class='box-body'>
				<?php foreach($list_resep as $lr): ?>
				<div id="divHistori" class="list-group">
					<span class="list-group-item">
						<h5 class="list-group-item-heading"><b><u><?= $lr->kode_resep?></u></b></h5>  
						<h6 class="list-group-item-heading"><i><?= $lr->tgl?> - <?= $lr->jam?></i></h6>  
						<?php $data_obat = ObatTransaksiDetail::find()->where(['idtrx'=>$lr->id])->all();	
						foreach($data_obat as $data_obat):
						?>
						<p class="list-group-item-text"><small>- <?= $data_obat->nama_obat?> <?php if($data_obat->idbayar == 1){echo'<span class="label label-default">Pribadi</span>';}else if($data_obat->idbayar == 2){echo'<span class="label label-success">BPJS</span>';}else if($data_obat->idbayar == 3){echo'<span class="label label-warning">Kronis</span>';}else{echo'<span class="label label-warning">Covid</span>';}  ?> : <?= $data_obat->qty ?> <?= $data_obat->satuan_obat?> (<?= $data_obat->signa ?> <?= $data_obat->diminum?> <?php if($data_obat->diminum != ''){echo'makan';} ?>) <?= $data_obat->keterangan?></small></p>
						<?php endforeach;?>
						<br>
						<h6 class="list-group-item-heading"><b>Rp. <?= $lr->total_harga + $lr->jasa_racik ?></u></h6>
						<p class="list-group-item-text">
						</p>
							<a href='<?= Url::to(['resep/faktur?id='.$lr->id])?>' target='_blank' class="btn btn-xs btn-primary">Faktur</a>
							<a href='<?= Url::to(['resep/etiket?id='.$lr->id])?>'target='_blank' class="btn btn-xs btn-success">Etiket</a>
							<a href='<?= Url::to(['resep-new/retur?id='.$lr->id])?>' class="btn btn-xs btn-danger">Retur</a>
						
						<p></p>
					</span>
				</div>
				<?php endforeach;?>
			</div>
	</div>
	</div>
</div>

