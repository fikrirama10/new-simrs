<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\ObatSuplier;
use common\models\RawatBayar;
	$grid = [
			['class' => 'kartik\grid\SerialColumn'],
			
			'nama_barang',
			'harga',
			'satuan.satuan',
			[	
				'attribute' => 'Pilih', 
				'format' => 'raw',
				'value' => function ($model, $key, $index) { 
				return "<a class='btn btn-default btn-xs' data-toggle='modal' data-target='#mdObat".$model->id."'>+ Pilih</a><input type='hidden' '>";
				},						
							
			],
			
			

			
		];
$this->title = $model->no_faktur;
$this->params['breadcrumbs'][] = ['label' => 'Barang Penerimaans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="box box-header"><h4>Pembelian Barang ATK</h4></div>
<div class="barang-penerimaan-view box box-body">
	<div class='row'>
		<div class='col-md-5'>
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					
					'no_faktur',
					'tgl',
					'suplier.suplier',
					'total_penerimaan',
				],
			]) ?>
			<a href='<?= Url::to(['/barang-penerimaan'])?>' class='btn btn-primary btn-xs'>Kembali</a>
			<?php if($model->status == 1){ ?>
			<a href='<?= Url::to(['/barang-penerimaan/batalkan?id='.$model->id])?>' class='btn btn-danger btn-xs'>Batalkan</a>
			<a class='btn btn-warning btn-xs' data-toggle='modal' data-target='#mdTambah'>+ Barang Baru</a>
			<?php } ?>
			<hr>
			<?php if($model->status == 1){ ?>
			<?= GridView::widget([
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'bordered' =>false,
				'pjax'=>true,
	
				
				'columns' => $grid,
			]); ?>
		<?php } ?>
		</div>
		<div class='col-md-7'>
		<table class='table table-bordered'>
				<tr>
					<th>No</th>
					<th>Nama Barang</th>
					<th>Banyaknya</th>
					<th>Harga</th>
					<th>Total</th>
					<th>#</th>
				</tr>
				<?php $no=1; foreach($list_terima as $lb){ ?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= $lb->barang->nama_barang ?></td>
					<td><?= $lb->qty ?></td>
					<td><?= $lb->harga ?></td>
					<td><?= $lb->total ?></td>
					<td><a href='<?= Url::to(['barang-penerimaan/hapus-item?id='.$lb->id])?>' class='btn btn-xs btn-danger'>hapus</a></td>
				</tr>
				<?php } ?>
			</table>
			<hr>
			<?php if(count($list_terima) > 0){ ?>
			<a class='btn btn-success' href='<?= Url::to(['/barang-penerimaan/selesai?id='.$model->id])?>'>Selesai</a>
			<?php } ?>
		</div>
	</div>
    <div class='row'>
		<div class='col-md-12'>
			
		</div>
	</div>
</div>

<?php
	foreach($barang as $il):
	Modal::begin([
	'id' => 'mdObat'.$il->id,
	'header' => $il->nama_barang,
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

	echo '<div class="modalContent">'. $this->render('_formBarang', ['il'=>$il,'model'=>$model,'detail' => $detail,]).'</div>';
	 
	Modal::end();
	endforeach;

Modal::begin([
	'id' => 'mdTambah',
	'header' => 'Tambah Barang',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formTambah', ['model'=>$model,'detail' => $detail,'dataBarang' => $dataBarang]).'</div>';
 
Modal::end();
?>