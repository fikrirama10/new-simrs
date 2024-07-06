<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
/* @var $this yii\web\View */
/* @var $model common\models\BarangAmprah */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Barang Amprahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
	<br>
	<div class='row'>
		<div class='col-md-4'>
			<div class='box box-body'>
			<h3>Pengajuan Barang</h3><hr>
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
			</div>
			<div class='box box-footer'>
				<a target='_blank' href='<?= Url::to(['barang-amprah/print-setuju?id='.$model->id])?>' class='btn btn-warning'>Print</a>
			</div>
		</div>
		<div class='col-md-8'>
			<div class='box box-body'>
				<h3>Daftar Pengajuan Barang</h3><hr>
				<table class='table table-bordered'>
					<tr>
						<th width=10>No</th>
						<th width=220>Nama Barang</th>
						<th>Jumlah Permintaan</th>
						<th>Harga</th>
						<th>Jumlah Disetujui</th>
						<th>Total Pengajuan</th>
						<th>Total Disetujui</th>
						<th>Status</th>
						<th>#</th>
					</tr>
					<?php $no=1; $total_setuju=0; $total=0; foreach($detail as $d){ 
					       $total_setuju += $d->harga * $d->qty_setuju;
					       $total += $d->total;
					?>
					<tr>
						<td><?= $no++ ?></td>
						<td>
							<?= $d->nama_barang ?>
							<?php if($d->baru == 1){ ?>
								<span class='label label-success'>Baru</span>
							<?php } ?>
						</td>
						<td><?= $d->qty ?> <?php if($d->baru != 1){ ?><?= $d->barang->satuan->satuan ?> <?php } ?> </td>
						<td><input readonly id='harga-<?= $d->id ?>' type='text' value='<?= $d->harga ?>'></td>
						<td><input readonly id='jumlahSetuju-<?= $d->id ?>' type='text' value='<?= $d->qty_setuju ?>'></td>
						<td><?= Yii::$app->algo->IndoCurr($d->total) ?></td>
						<td><?= Yii::$app->algo->IndoCurr($d->harga * $d->qty_setuju) ?></td>
						<td><?= $d->statuss->status ?></td>
						<td>
							<a  data-toggle='modal' data-target='#mdTolak<?= $d->id ?>' class='btn btn-xs btn-danger'>Tolak</a>
							<a  data-toggle='modal' data-target='#mdPermintaan<?= $d->id ?>' class='btn btn-xs btn-primary'>Lihat Stok</a>
						</td>
					</tr>
<?php $urlEdit = Url::to(['barang-amprah/edit-setuju']);
	$urlHarga = Url::to(['barang-amprah/edit-harga']);
	$this->registerJs("
		$('#jumlahSetuju-{$d->id}').on('dblclick',function() {
			$('#jumlahSetuju-{$d->id}').prop('readonly', false);
		});		
		$('#jumlahSetuju-{$d->id}').on('click',function() {
			$('#jumlahSetuju-{$d->id}').prop('readonly', true);
			$('.jumlahSetuju').prop('readonly', true);
		});		
		$('#jumlahSetuju-{$d->id}').on('keypress',function(e) {
			if(e.which === 13){
				$('#jumlahSetuju-{$d->id}').prop('readonly', true);
				jumlah = $('#jumlahSetuju-{$d->id}').val();
				$.ajax({
				type: 'GET',
					url: '{$urlEdit}',
					data: 'id='+{$d->id}+'&jumlah='+jumlah,
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
		
		$('#harga-{$d->id}').on('dblclick',function() {
			$('#harga-{$d->id}').prop('readonly', false);
		});		
		$('#harga-{$d->id}').on('click',function() {
			$('#harga-{$d->id}').prop('readonly', true);
			$('.harga').prop('readonly', true);
		});		
		$('#harga-{$d->id}').on('keypress',function(e) {
			if(e.which === 13){
				$('#harga-{$d->id}').prop('readonly', true);
				jumlah = $('#harga-{$d->id}').val();
				$.ajax({
				type: 'GET',
					url: '{$urlHarga}',
					data: 'id='+{$d->id}+'&jumlah='+jumlah,
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
					    <td colspan = 5></td>
					    <td><?= Yii::$app->algo->IndoCurr($total) ?></td>
					    <td><?= Yii::$app->algo->IndoCurr($total_setuju) ?></td>
					</tr>
				</table>
				<br>
				<a href='<?= Url::to(['barang-amprah/selesai-pengajuan?id='.$model->id]) ?>' class='btn btn-primary'>Selesai</a>
		</div>
	</div>
			</div>
	
<?php

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

	echo '<div class="modalContent">'. $this->render('_formPermintaan', ['model'=>$model,'pl'=>$pl]).'</div>';
	 
	Modal::end();
	Modal::begin([
		'id' => 'mdSetuju'.$pl->id,
		'header' => '<h3>Permintaan Setujui</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formSetuju', ['model'=>$model,'pl'=>$pl]).'</div>';
	 
	Modal::end();
	Modal::begin([
		'id' => 'mdTolak'.$pl->id,
		'header' => '<h3>Permintaan Tolak</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formTolak', ['model'=>$model,'pl'=>$pl]).'</div>';
	 
	Modal::end();
endforeach;

?>