<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use common\models\Obat;
use yii\bootstrap\Modal;
use common\models\AmprahGudangobatDetail;
/* @var $this yii\web\View */
/* @var $model common\models\AmprahGudangobat */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tarik', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class='row'>
		<div class='col-md-4'>
			<div class='box'>
				<div class='box-header'></div>
				<div class='box-body'>
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'idamprah',
						'tgl_permintaan',
						'tgl_penyerahan',
						'ruangan.ruangan',
						'keterangan',
					],
				]) ?>
				<br>
				<a class='btn btn-primary btn-xs' href='<?= Url::to(['/amprah-gudangobat/view?id='.$model->id])?>'>Kembali</a>
				</div>
			</div>
		</div>
		<div class='col-md-8'>
			<div class='box box-body'>
				<table class='table table-bordered'>
					<tr>
						<th width=20>No</th>
						<th>Nama Obat</th>
						<th>Jumlah</th>
						<th width=20>#</th>
					</tr>
					
					<?php $no=1; foreach($detail as $a){ ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $a->obat->nama_obat ?> <a></a></td>
						<td><?= $a->jumlah_diserahkan ?> <?= $a->obat->satuan->satuan ?></td>
						<td>							
							<a data-toggle="modal" data-target="#mdAdd<?= $a->id?>" class='btn btn-info btn-xs'  class='btn btn-danger btn-xs'>Tarik</a>
						</td>
					</tr>
					<?php } ?>
					
				</table>
				<hr>
				<a href='<?= Url::to(['amprah-gudangobat/tarik-semua?id='.$model->id])?>' class='btn btn-danger'>Tarik Semua</a>
				
			</div>
			<hr>
			<div class='box'>
				<div class='box-header with-border'><h4>Obat ditarik</h4></div>
				<div class='box-body'>
					<table class='table table-bordered'>
						<tr style='font-size:10px;'>
							<th width=10>No</th>
							<th>Nama Obat</th>
							<th>Jumlah Asal</th>
							<th>Jumlah ditarik</th>
						</tr>
						<?php $no2=1; foreach($detail_tarik as $dt){ ?>
						<tr>
							<td><?= $no2++ ?></td>
							<td><?= $dt->nama_obat ?></td>
							<td><?= $dt->jumlah_asal ?></td>
							<td><?= $dt->jumlah ?></td>
							<td>
								<?php if($dt->status == 1){?>
									<a href='<?= Url::to(['amprah-gudangobat/hapus-tarik?id='.$dt->id])?>' class='btn btn-xs btn-warning'>hapus</a>
								<?php } ?>
							</td>
						</tr>
						<?php } ?>
					</table>
					<hr>
					<?php if($detail_tarik){ ?>
						<a href='<?= Url::to(['amprah-gudangobat/tarik-obat?id='.$model->id])?>' id='confirm' class='btn btn-success'>Tarik Obat</a>
					<?php } ?>
				</div>
			</div>
			
		</div>
</div>
<?php
	foreach($detail as $a){
		Modal::begin([
			'id' => 'mdAdd'.$a->id,
			'header' => '<h3>'.$a->obat->nama_obat.'</h3>',
			'size'=>'modal-lg',
			'options'=>[
				'data-url'=>'transaksi',
				'tabindex' => ''
			],
		]);

		echo '<div class="modalContent">'.$this->render('_formTarik', ['model'=>$model,'iddetail'=>$a->id,'tarik'=>$tarik]).'</div>';
		 
		Modal::end();
	}
	$this->registerJs("
		$('#confirm').on('click', function(event){
			age = confirm('Yakin Untuk menyimpan data , data yang telah disimpan tidak bisa dikembalikan lagi , stok obat akan kembali masuk ke gudang');
			if(age == true){
				 return true;
			} else {
				event.preventDefault();
			}
		});
	", View::POS_READY);
?>
