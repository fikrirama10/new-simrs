<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use common\models\RawatBayar;
use common\models\Rawat;
use common\models\Tarif;
use common\models\Dokter;
use common\models\ObatTransaksiDetail;
$bpjs = ObatTransaksiDetail::find()->joinWith(['transaksi as tran'])->where(['tran.id'=>$model->id])->andwhere(['idbayar'=>2])->sum('obat_transaksi_detail.total');
$kronis = ObatTransaksiDetail::find()->joinWith(['transaksi as tran'])->where(['tran.id'=>$model->id])->andwhere(['idbayar'=>3])->sum('obat_transaksi_detail.total');
$umum = ObatTransaksiDetail::find()->joinWith(['transaksi as tran'])->where(['tran.id'=>$model->id])->andwhere(['idbayar'=>1])->sum('obat_transaksi_detail.total');
$total_resep = $bpjs + $kronis;
$tarif = Tarif::find()->all();
$rawat = Rawat::find()->joinWith(['kunjungans as kunjungan'])->where(['kunjungan.id'=>$model->idkunjungan])->andwhere(['<>','rawat.status',5])->all();
?>
<div class='row'>
	<div class='col-md-4'>
	    			<div class='box'>
			<div class='box-header with-border'>
				<h4>Tarif Pasien</h4>
			</div>
			<div class='box-body'>
				<?php if($model->status == 1){ ?>
					<a href='<?= Url::to(['billing/rincian?id='.$model->id])?>' class='btn btn-warning'>Hitung Rincian</a>
				<?php } ?>
				<table class='table table-bordered'>
					<tr>
						<th>No</th>
						<th>Tindakan</th>
						<th>Tarif</th>
						<th>Bayar</th>
						
					</tr>
					<?php $perkiraan=0; $no=1; foreach($tarif_trx as $tt):
					    $perkiraan += $tt->tarif;
					?>
					<tr>
						<td width=10><?= $no++?></td>
						<td><?= $tt->tindakan->nama_tarif?></td>
						<td><?= $tt->tarif?></td>
						<td><?= $tt->bayar->bayar ?></td>
					</tr>
					<?php endforeach;?>
					<tr>
					    <td colspan = 3>Perkiraan Biaya</td>
					    <td><?= $perkiraan?></td>
					</tr>
				</table>
				<br>
				
			</div>
		</div>
			<div class='box'>
			<div class='box-header with-border'>
				<h4>Data Pasien</h4>
				 <a href='<?= Url::to(['index'])?>' class='btn btn-primary btn-xs'>Kembali</a>
			</div>
			<div class='box-body'>
				<?= DetailView::widget([
					'model' => $pasien,
					'attributes' => [
						'no_rm',
						'nama_pasien',
						'nohp',
					],
				]) ?>
				
				<hr>
				<?php foreach($rawat as $r){ ?>
				<table class='table table-bordered'>
					<tr>
						<th>Jenis Rawat</th>
						<th>Dokter</th>
						<th>Bayar</th>
					</tr>
					<tr>
						<td><?= $r->jenisrawat->jenis ?></td>
						<td><?= $r->dokter->nama_dokter ?></td>
						<td><?= $r->bayar->bayar ?></td>
					</tr>
				</table>
				<?php } ?>
				<hr>
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'tgltransaksi',
						'idtransaksi',
						'tgl_masuk',
						'tgl_keluar',
					],
				]) ?>
			</div>
			<div class='box-footer'>
			   <?php if(Yii::$app->user->identity->userdetail->managemen == 1){ ?>
				<?php if($model->hide != 1){ ?>
					<a class='btn btn-danger btn-xs ' href='<?= Url::to(['billing/hide?id='.$model->id])?>'>Hide</a>
				<?php }else{ ?>
					<a class='btn btn-warning btn-xs ' href='<?= Url::to(['billing/unhide?id='.$model->id])?>'>Unhide</a>
				<?php } ?>
				
			   <?php } ?>
			</div>
			</div>

	</div>
	<div class='col-md-8'>

		<div class='box'>
			<div class='box-header with-border'>
				<h4>Rincian Tarif</h4>
				<a class='btn btn-success btn-xs ' href='<?= Url::to(['billing/tarif-manual?id='.$model->id])?>'>Manual</a>
			</div>
			<div class='box-body'>
			<?php if($model->status == 1){ ?>
			<?php $form = ActiveForm::begin(); ?>
					<div class='row'>
						<div class='col-md-2 col-xs-2 '><span class='pull-right pd-top'>Tarif</span></div>
						<div class='col-md-6 col-xs-6'>
							<div class="input-group">
								<input type="text" readonly id="nama-tarif" name="" class="form-control">
								<a id="manual" data-toggle="modal" data-target="#mdTarif" class="input-group-addon btn btn-success btn-sm" >...</span></a>								
							</div>
						</div>
					</div>
					<div class='row'>
						<div class='col-md-2 col-xs-2 '><span class='pull-right pd-top'>Harga Rp.</span></div>
						<div class='col-md-3 col-xs-3'>
								<input type="text" readonly id="harga-tarif" name="" class="form-control">		
												
						</div>
						<div class='col-md-1 col-xs-1 '><span class='pull-right pd-top'>Jumlah</span></div>
						<div class='col-md-2 col-xs-2'>
						
								<input type="text"  required id="jumlah-tarif" name="TransaksiDetailBill[jumlah]" class="form-control">		
												
						</div>
						
					</div>
					<div class='row'>
						<div class='col-md-2 col-xs-2 '><span class='pull-right pd-top'>Bayar</span></div>
						<div class='col-md-4 col-xs-4'>
							<?= $form->field($tambahan, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Pilih Bayar -','required'=>true])->label(false)?>
						</div>						
					</div>
					<div class='row'>
						<div class='col-md-2 col-xs-2 '><span class='pull-right pd-top'>Dokter</span></div>
						<div class='col-md-4 col-xs-4'>
							<?= $form->field($tambahan, 'iddokter')->dropDownList(ArrayHelper::map(Dokter::find()->all(), 'id', 'nama_dokter'),['prompt'=>'- Pilih Dokter -'])->label(false)?>
						</div>						
					</div>
					<div class='row'>
						<div class='col-md-2 col-xs-2 '><span class='pull-right pd-top'></span></div>
						<div class='col-md-4 col-xs-4'>
							<div class="input-group">
								<button id='confirm'>Tambah</button>					
							</div>
						</div>
					</div>
					<?= $form->field($tambahan, 'idtransaksi')->hiddeninput(['value'=>$model->id])->label(false)?>
					<?= $form->field($tambahan, 'tarif')->hiddeninput()->label(false)?>
				
					<?= $form->field($tambahan, 'idtarif')->hiddeninput()->label(false)?>
					<?= $form->field($tambahan, 'tindakan')->hiddeninput()->label(false)?>
					<?php foreach($tarif as $o){
				$urlGet = Url::to(['billing/get-tarif']);
					$this->registerJs("
					$('#btn{$o->id}').on('click',function(){
						$('#mdTarif').modal('hide');
						id = $('#input{$o->id}').val();
							$.ajax({
							type: 'POST',
							url: '{$urlGet}',
							data: {id: id},
							dataType: 'json',
							success: function (data) {
								if(data !== null){
									var res = JSON.parse(JSON.stringify(data));					
									$('#nama-tarif').val(res.nama_tarif);
									$('#harga-tarif').val(res.tarif);
									$('#transaksidetailbill-idtarif').val(res.id);
									$('#transaksidetailbill-tarif').val(res.tarif);
									$('#transaksidetailbill-tindakan').val(res.nama_tarif);
									$('#jumlah-tarif').focus();
								}else{
									alert('data tidak ditemukan');
								}
							},
							error: function (exception) {
								alert(exception);
							}
						});	
					}) ;


				", View::POS_READY);
				}?>
			<?php ActiveForm::end(); ?>
			<?php } ?>
			<hr>
			<?php $form = ActiveForm::begin(); ?>
				<table class='table table-bordered'>
					<tr>
						<th>No</th>
						<th>Tindakan</th>
						<th>Tarif</th>
						<th>Jumlah</th>
						<th>Total</th>
						<th>Bayar</th>
						<th>#</th>
					</tr>
					<?php $no=1; foreach($rincian_tarif as $rt): ?>
					<tr>
						<td width=10><?= $no++?></td>
						<td><?= $rt['nama_tarif'] ?></td>
						<td><?= Yii::$app->algo->IndoCurr(round($rt['harga']))?></td>
						<td><?= $rt['jumlah'] ?> x</td>
						<td><?= $rt['total'] ?></td>
						<td><?= $rt['bayar']?></td>
						<td> 
						<?php if($model->status == 1){ ?>
						<a href='<?= Url::to(['billing/hapus-rincian?idtrx='.$rt['idtrx'].'&idtarif='.$rt['nama_tarif'].'&idbayar='.$rt['idbayar']])?>' class='btn btn-danger btn-xs'>Hapus</a>
						<?php } ?>
						</td>
					</tr>
					
					<?php endforeach;?>
					<tr>
						<td style="background:#000" colspan=5></td>
					</tr>
				    <tr>
						<th  colspan=4>Transaksi Resep</th>
					</tr>
					<tr>
						<th>No</th>
						<th>Jenis Resep</th>
						<th>Haraga Resep</th>
					</tr>
					<tr>
						<td>1</td>
						<td>BPJS</td>
						<td><?= Yii::$app->algo->IndoCurr(round($bpjs))?></td>
					</tr>
					<tr>
						<td>2</td>
						<td>Kronis</td>
						<td><?= Yii::$app->algo->IndoCurr(round($kronis))?></td>
					</tr>
					<tr>
						<td>3</td>
						<td>Umum</td>
						<td><?= Yii::$app->algo->IndoCurr(round($umum)) ?></td>
					</tr>
					
					
					<tr>
						<td style="background:#000" colspan=5></td>
					</tr>
					<tr>
						<th align='right' colspan=2>Total Ditanggung BPJS</th>
						<th><?= Yii::$app->algo->IndoCurr(round($rincian_tarif_bpjs + $model_obat_bpjs))?></th>
					</tr>
					<tr>
						<th align='right' colspan=2>Total Tidak ditanggung</th>
						<th><?= $rincian_tarif_umum + $model_obat_bayar ?> <input id='total'type='hidden' value='<?= $rincian_tarif_umum + $model_obat_bayar ?>'></th>
					</tr>
					<?php if($model->status == 1){ ?>
					<tr>
						<th align='right' colspan=2>Tgl Pulang</th>
						<th><input name='Transaksi[tgl_keluar]' id='keluar'type='date' value='<?= $model->tgl_keluar?>' required></th>
					</tr>
					<tr>
						<th align='right' colspan=2>Diskon Rp</th>
						<th><input name='Transaksi[diskon]' id='diskon'type='text'></th>
					</tr>
					<tr>
						<th align='right' colspan=2>Total Bayar Rp</th>
						<th><input value=0  name='Transaksi[total_bayar]' type='text' id='bayar' readonly><button id='confirm'>Bayar</button></th>
					</tr>
					<?php }else{ ?>
					<tr>
						<td colspan=4><a target='_blank' href='<?= Url::to(['billing/faktur?id='.$model->id])?>' class='btn btn-success'>Print Faktur</a> <a href='<?= Url::to(['billing/edit-faktur?id='.$model->id])?>' class='btn btn-primary'>Edit Faktur</a></td>
					</tr>
					<?php } ?>
				</table>
				<?= $form->field($model, 'total')->hiddeninput(['value'=>$rincian_tarif_bpjs + $model_obat_bpjs + $rincian_tarif_umum + $model_obat_bayar])->label(false) ?>
				<?= $form->field($model, 'total_ditanggung')->hiddeninput(['value'=>$rincian_tarif_bpjs + $model_obat_bpjs ])->label(false) ?>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>



<?php 
$this->registerJs("
	$('#diskon').on('keyup',function(){
			jumlah = $('#total').val();	
			diskon = $('#diskon').val();
			subtotal = jumlah - diskon;
			$('#bayar').val(subtotal);
		});
	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk menyelesaikan transaksi?? , Transaksi yang sudah selesai tidak dapat di edit lagi .....');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

", View::POS_READY);

// foreach($model_obat as $pl):
// 	Modal::begin([
// 		'id' => 'mdResep'.$pl->id,
// 		'header' => '<h3>Rincian Obat</h3>',
// 		'size'=>'modal-lg',
// 		'options'=>[
// 			'data-url'=>'transaksi',
// 			'tabindex' => ''
// 		],
// 	]);

// 	echo '<div class="modalContent">'. $this->render('_formObat', ['model'=>$model,'pl'=>$pl]).'</div>';
	 
// 	Modal::end();

// endforeach;

Modal::begin([
		'id' => 'mdTarif',
		'header' => '<h3>Tambah Tindakan</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => '-1'
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formTarif', ['model'=>$model,'tambahan'=>$tambahan]).'</div>';
	 
	Modal::end();

	Modal::begin([
		'id' => 'mdTarifM',
		'header' => '<h3>Tambah Tarif Manual</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => '-1'
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formManual', ['model'=>$model,'tambah_dua'=>$tambah_dua]).'</div>';
	 
	Modal::end();

?>