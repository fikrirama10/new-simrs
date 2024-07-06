<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use common\models\DataBarang;
/* @var $this yii\web\View */
/* @var $model common\models\BarangAmprah */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Barang Amprah', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class='row'>
		<div class='col-md-4'>
			<div class='box box-body'>
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'tgl_permintaan',
						'unit.unit',
						'amprah.status',
						'keterangan:ntext',
					],
				]) ?>
				<br>
				<?php if($model->status == 2){ ?>
				
					<a id='batalkan' class='btn btn-danger'href='<?= Url::to(['/barang-amprah/batalkan?id='.$model->id])?>'>Batalkan</a>
				<?php }else if($model->status == 1 ){ ?>
					<a id='batalkan' class='btn btn-danger'href='<?= Url::to(['/barang-amprah/batalkan?id='.$model->id])?>'>Batalkan</a>
					<a id='batalkan' class='btn btn-primary'href='<?= Url::to(['/barang-amprah/update?id='.$model->id])?>'>Edit</a>
				<?php } ?>
				<a class='btn btn-success'href='<?= Url::to(['/pengadaan/list-amprah'])?>'>Kembali</a>
				<a target='_blank' href='<?= Url::to(['barang-amprah/print-setuju?id='.$model->id])?>' class='btn btn-warning'>Print</a>
				<hr>
				
				
				
			</div>
		</div>
		<div class='col-md-8'>
			<div class='box box-body'>
				<?php $form = ActiveForm::begin(); ?>
				
				<div class="form-group">
					<?= Html::submitButton('Save', ['class' => 'btn btn-success disable']) ?>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
			<div class='box box-body'>
				<table class='table table-bordered'>
					<tr>
						<th width=10>No</th>
						<th>Nama Barang</th>
						<th>Jumlah</th>
						<th>Estimasi Harga</th>
						<th>Disetujui</th>
						<th>Total</th>
						<th>Stok Gudang</th>
						<th>#</th>
					</tr>
					<?php $total =0; $no=1; foreach($detail as $pl){ 
						$total += $pl->total_setuju;
						$dataBarang = DataBarang::findOne($pl->idbarang);
					?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $pl->nama_barang ?>
							<?php if($pl->baru == 1){ ?>
								<span class='label label-success'>Baru</span>
							<?php } ?>
						</td>
						<td><?= $pl->qty ?>  <?php if($pl->baru != 1){ ?><?= $pl->barang->satuan->satuan ?> <?php } ?> </td>
						<td><?= $pl->harga ?></td>
						<td>
							<?php if($pl->status == 2){ ?>
							<input type='number' id='jumlahQty-<?= $pl->id ?>' readonly value='<?= $pl->qty_setuju ?>' ></input>
							<?php }else{echo $pl->qty_setuju ;} ?>
						</td>
						<td><?= $pl->total_setuju ?></td>
						<td>
						<?php if($pl->baru != 1){ ?>
							<?= $dataBarang->stok ?>
							<?= $dataBarang->satuan->satuan ?>
						<?php } ?>
						</td>
						<td>
						<?php if($pl->status == 2){ ?>
							<a href='<?= Url::to(['pengadaan/amprah-berikan?id='.$pl->id]) ?>' class='btn btn-xs btn-success'>Berikan</a> 
							<a  data-toggle='modal' data-target='#mdPermintaan<?= $pl->id ?>' class='btn btn-xs btn-primary'>Lihat Stok</a>
						<?php }else{ ?>
							<a href='<?= Url::to(['pengadaan/amprah-koreksi?id='.$pl->id]) ?>' class='btn btn-xs btn-success'>Koreksi</a> 
						<?php } ?>
						</td>
					</tr>
					<?php 
					$urlEdit = Url::to(['pengadaan/edit-item-amprah']);
					$this->registerJs("
						$('#jumlahQty-{$pl->id}').on('dblclick',function() {
							$('#jumlahQty-{$pl->id}').prop('readonly', false);
						});		
						$('#jumlahQty-{$pl->id}').on('click',function() {
							$('#jumlahQty-{$pl->id}').prop('readonly', true);
							$('.jumlahQty').prop('readonly', true);
						});		
						$('#jumlahQty-{$pl->id}').on('keypress',function(e) {
							if(e.which === 13){
								$('#jumlahQty-{$pl->id}').prop('readonly', true);
								jumlah = $('#jumlahQty-{$pl->id}').val();
								$.ajax({
								type: 'GET',
									url: '{$urlEdit}',
									data: 'id='+{$pl->id}+'&jumlah='+jumlah,
									dataType: 'json',
									success: function (data) {
										var res = JSON.parse(JSON.stringify(data));
										if(res.code == 404){
											alert('Jumlah tidak boleh kurang dari 0');
											location.reload();
										}else{
											location.reload();
										}

									},
									 
								});

							}
						});

					", View::POS_READY);

					?> 
					
					<?php } ?>
					<tr>
					<th colspan =5><span class='pull-right'>Total Biaya</span></th>
					<td><?= $total?></td>
					
				</tr>
				</table>
				
				<br>
				<?php if($model->status == 3){ ?>
					<a href='<?= Url::to(['pengadaan/amprah-selesai?id='.$model->id])?>' class='btn btn-primary' id='confirm-berikan'>Selesai</a>
				<?php } ?>
				
				<?php if(count($detail) > 0){ ?>
					<?php if($model->status == 1){ ?>
						<a href='<?= Url::to(['barang-amprah/ajukan?id='.$model->id])?>' class='btn btn-primary' id='confirm'>Ajukan</a>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
	
</div>
<?php 

$this->registerJs("
	
	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk mengajukan barang?? , setelah data tersimpan maka data tidak dapat di edit kembali , pastikan pengajuan sudah benar !!.');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	
});
$('#confirm-berikan').on('click', function(event){
		age = confirm('Yakin Untuk berikan barang?? , setelah data tersimpan maka data tidak dapat di edit kembali , pastikan pengajuan sudah benar !!.');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	
});


	$('#batalkan').on('click', function(event){
		age = confirm('Yakin Untuk membatalkan pengajuan??');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	
	

", View::POS_READY);


foreach($detail as $pl):
	Modal::begin([
		'id' => 'mdPermintaan'.$pl->id,
		'header' => '<h3>Permintaan</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formBarang', ['model'=>$model,'pl'=>$pl]).'</div>';
	 
	Modal::end();
endforeach;


?>