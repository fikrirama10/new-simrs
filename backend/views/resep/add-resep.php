<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use common\models\ObatTransaksiDetail;
use common\models\RawatResep;
use common\models\RawatResepDetail;
$list_resep = RawatResep::find()->where(['idrawat'=>$rawat->id])->one();
$this->title = $model->kode_kunjungan;
$this->params['breadcrumbs'][] = ['label' => 'Obat Transaksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="obat-transaksi-view">
	<div class="row">
		<div class="col-md-4">
			<div class='box'>
			<div class='box-header with-border'><h4>Data Pasien</h4></div>
				<div class='box-body'>
					<?= DetailView::widget([
						'model' => $pasien,
						'attributes' => [
							'no_rm',
							'nik',
							'no_bpjs',
							'nama_pasien',
							'tgllahir',
							'nohp',
							'usia_tahun',
						],
					]) ?>
					<hr>
					<?= DetailView::widget([
						'model' => $rawat,
						'attributes' => [
							'poli.poli',
							'jenisrawat.jenis',
							'bayar.bayar',
							'dokter.nama_dokter',
						],
					]) ?>
				</div>
				<div class='box-footer'>
					<a href='<?= Url::to(['resep/view?id='.$model->id]) ?>' class='btn btn-success'>Kembali</a>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class='box'>
			<div class='box-header with-border'><h4>Tambah Resep</h4></div>
				<div class='box-body'>
					<a class='btn btn-success'  data-toggle="modal" data-target="#mdResep">Buat Resep</a>
					<hr>
				
					<h3>Resep</h3>
					<?php foreach($tranobat as $to): ?>
					<?php if($to->status == 1){ ?>
					<?php $list_resep_all = RawatResepDetail::find()->where(['idresep'=>$to->idresep])->andwhere(['status'=>1])->all(); ?>
					<?php if(count($list_resep_all) > 0){ ?>
					<table class='table table-bordered'>
						<tr>
							<th>Merk</th>
							<th>Jumlah</th>
							<th>Signa</th>
							<th>Dosis</th>
							<th>Catatan</th>
							<th>Pilih</th>
						</tr>
						<?php foreach($list_resep_all as $lra){ ?>
						<tr>
							<td><?= $lra->merk->merk ?></td>
							<td><?= $lra->qty ?></td>
							<td><?= $lra->signa1 ?> x <?= $lra->signa2 ?></td>
							<td><?= $lra->dosis ?></td>
							<td><?= $lra->catatan ?></td>
							<td><a data-toggle="modal" data-target="#mdObat<?= $lra->id ?>" class='btn btn-info btn-xs'>Berikan</a></td>
						</tr>
						<?php } ?>
					</table>
					<?php 
						foreach($list_resep_all as $o):
							Modal::begin([
								'id' => 'mdObat'.$o->id,
								'header' => '<h3>Obat obatan</h3>',
								'size'=>'modal-lg',
								'options'=>[
									'data-url'=>'transaksi',
									'tabindex' => ''
								],
							]);

							echo '<div class="modalContent">'.$this->render('_formBeri', ['model'=>$model,'idobat'=>$o->id,'resep_detail'=>$resep_detail]).'</div>';
							 
							Modal::end();
							endforeach;
					?>
					<?php } ?>
					<?php } ?>
					<hr>
					<table class='table table-bordered'>
						<tr>
							<th colspan=7>Kode Resep <?= $to->kode_resep?></th>
							<th>
								<?php if($to->status == 1){ ?>
								<a href='<?= Url::to(['resep/batalkan-resep?id='.$to->id])?>' class='btn btn-warning btn-xs'>Batalkan</a>
								<?php }else{ ?>
								<a href='<?= Url::to(['resep/etiket?id='.$to->id])?>' target='_blank' class='btn btn-default btn-xs'>Etiket</a>
								<a target='_blank' href='<?= Url::to(['resep/faktur?id='.$to->id])?>' class='btn btn-default btn-xs'>Faktur</a>
								<?php } ?>
							</th>
						</tr>
						<?php if($to->status == 1){ ?>
						<tr>
							<th colspan=8><a data-toggle="modal" data-target="#mdAdd" class='btn btn-info btn-xs'>Tambah Obat</a></th>
						</tr>
						<?php } ?>
						
						<tr>
							<th>No</th>
							<th>Nama Obat (merk)</th>
							<th>Jumlah</th>
							<th>Harga</th>
							<th>Total</th>
							<th>Jenis</th>
							<th>#</th>
						<tr>
						<?php $no=1; $list_obat = ObatTransaksiDetail::find()->where(['idtrx'=>$to->id])->all(); $total = 0;
							foreach($list_obat as $lo):
							$total += $lo->total;							
						?>
						<tr>
							<td width=10><?= $no++ ?></td>
							<td><?= $lo->obat->nama_obat ?> (<?= $lo->bacth->merk ?>)</td>
							<?php if($to->status == 1){ ?>
							<td width=100><input readonly type='number' id='qty<?= $lo->id ?>' class='form-control' value='<?= $lo->qty ?>'></td>
							<?php }else{ ?>
							<td width=100><?= $lo->qty ?></td>
							<?php } ?>
							<td><?= Yii::$app->algo->IndoCurr(round($lo->harga))?></td>
							<td><?= Yii::$app->algo->IndoCurr(round($lo->total))?></td>
							<td><?= $lo->jenis->bayar ?></td>
						
							<td>
								<?php if($to->status == 1){ ?>
								<a href='<?= Url::to(['resep/batalkan?id='.$lo->id])?>' class='btn btn-danger btn-xs'>batalkan</a>
								<?php }else{ ?>
								<a href='<?= Url::to(['resep/retur?id='.$lo->id])?>' class='btn btn-danger btn-xs'>Retur</a>
								<?php } ?>
							</td>
						</tr>
						<?php 
							$urlEdit = Url::to(['resep/edit-item']);
							$this->registerJs("	
								$('#qty{$lo->id}').on('click',function() {
									$('#qty{$lo->id}').prop('readonly', false);
								});
								$('#qty{$lo->id}').on('keypress',function(e) {
									if(e.which === 13){
										$('#qty{$lo->id}').prop('readonly', true);
										jumlah = $('#qty{$lo->id}').val();
										$.ajax({
										type: 'GET',
											url: '{$urlEdit}',
											data: 'id='+{$lo->id}+'&jumlah='+jumlah,
											dataType: 'json',
											success: function (data) {
												var res = JSON.parse(JSON.stringify(data));
												if(res.code == 404){
													alert('Jumlah tidak boleh kurang dari 0');
												}else if(res.code == 400){
													alert('Stok kurang dari jumlah yang di inginkan');
													
												}else{
													location.reload();
												}

											},
											 
										});

									}
								});

							", View::POS_READY);

							?> 
						<?php endforeach; ?>
						<?php if($to->obat_racikan == 1){ ?>
						<tr>
							<td  colspan=4><span class='pull-right'>Jasa Racik Rp.</span></td>
							<td><?= Yii::$app->algo->IndoCurr(round($to->jasa_racik))?></td>
						</tr>
						<?php } ?>
						<tr>
							<td  colspan=4><span class='pull-right'>Total Harga Rp.</span></td>
							<td><?= Yii::$app->algo->IndoCurr(round($total+$to->jasa_racik))?></td>
						</tr>
					
					</table>
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

					echo '<div class="modalContent">'.$this->render('_formTambah', ['model'=>$model,'resep_detail'=>$resep_detail,'idresep'=>$to->id]).'</div>';
					 
					Modal::end();
					?>
					<?php if($to->status == 1){ ?>
					<hr>
					<a href='<?= Url::to(['resep/selesai?id='.$to->id])?>' id='selesai-confirm' class='btn btn-default btn-sm'>Selesai</a>
					<?php } ?>
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
					<?php endforeach; ?>
				</div>
				<div class='box-footer'>
					
				</div>				
			</div>
		</div>
	</div>
</div>
<?php 

Modal::begin([
	'id' => 'mdResep',
	'header' => '<h3>Buat Resep</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'.$this->render('_formResep', ['model'=>$model,'resep'=>$resep,'rawat'=>$rawat]).'</div>';
 
Modal::end();





?>