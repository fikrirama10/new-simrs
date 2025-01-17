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
$this->title = $model->kode_resep;
$this->params['breadcrumbs'][] = ['label' => 'Resep', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="obat-farmasi-view">
	<div class="row">
		<div class="col-md-4">
			<div class='box'>
			<div class='box-header with-border'><h4>Data Pasien</h4></div>
				<div class='box-body'>
					<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'nama_pasien',
							'usia',
							'alamat',
							'no_tlp',
							'nrp',
						],
					]) ?>
				</div>
				<div class='box-footer'>
					<a href='<?= Url::to(['index']) ?>' class='btn btn-success'>Kembali</a>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class='box'>
				<div class='box-header with-border'><h4>Obat</h4></div>
				<div class='box-body'>
					<?php if($model->status == 1){ ?>
						<a class='btn btn-info btn-xs' data-toggle="modal" data-target="#mdAdd" class='btn btn-info btn-xs'>Tambah Obat</a>
					<?php }else{ ?>
						<a href='<?= Url::to(['resep/etiket?id='.$model->id])?>' target='_blank' class='btn btn-default btn-xs'>Etiket</a>
						<a href='<?= Url::to(['resep-luar/faktur?id='.$model->id])?>' target='_blank' class='btn btn-default btn-xs'>Faktur</a>
					<?php } ?>
					<hr>
					<table class='table table-bordered'>
						<tr>
							<th>No</th>
							<th>Nama Obat</th>
							<th>Qty</th>
							<th>harga</th>
							<th>total</th>
							<th>Signa</th>
							<th>Dosis</th>
							<th>#</th>
						</tr>
						<?php $no=1; foreach($detail as $d): ?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= $d->obat->nama_obat ?></td>
							<td><?= $d->jumlah ?> <?= $d->obat->satuan->satuan ?></td>
							<td><?= $d->harga ?> </td>
							<td><?= $d->total ?> </td>
							<td><?= $d->signa ?></td>
							<td><?= $d->dosis ?> <?= $d->takaran?></td>
							<?php if($model->status == 1){ ?>
							<td><a href='<?= Url::to(['resep-luar/batalkan?id='.$d->id])?>' class='btn btn-danger btn-xs'>batalkan</a></td>
							<?php } ?>
						</tr>
						<?php endforeach; ?>
						<?php if($model->obat_racik == 1){ ?>
						<tr>
							<th colspan=4><span class='pull-right'>Biaya Racik Rp.</span></th>
							<td>
								<?php if($model->idjenis == 1){ ?>
									<?= Yii::$app->algo->IndoCurr($model->jasa_racik)?>
								<?php }else{echo'0,00';}?>
							</td>
						<tr>
						<?php } ?>
						<tr>
							<th colspan=4><span class='pull-right'>Total Harga Rp.</span></th>
							<td>
								<?php if($model->idjenis == 1){ ?>
									<?= Yii::$app->algo->IndoCurr($model->total_harga + $model->jasa_racik)?>
								<?php }else{echo'0,00';}?>								
							</td>
						<tr>
					</table><BR>
					<?php if($model->status == 1){ ?>
					<a href='<?= Url::to(['resep-luar/selesai?id='.$model->id])?>' id='selesai-confirm' class='btn btn-default btn-sm'>Selesai</a>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>

</div>
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

echo '<div class="modalContent">'.$this->render('_formTambah', ['model'=>$model,'resep_detail'=>$resep_detail,'idresep'=>$model->id]).'</div>';
 
Modal::end();
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