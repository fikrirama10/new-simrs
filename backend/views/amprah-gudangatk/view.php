<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use common\models\Obat;
use common\models\AmprahGudangatkDetail;
/* @var $this yii\web\View */
/* @var $model common\models\AmprahGudangobat */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Amprah Gudangobats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="amprah-gudangobat-view">
	<div class='row'>
		<div class='col-md-6'>
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
				<a class='btn btn-primary btn-xs' href='<?= Url::to(['/amprah-gudangatk'])?>'>Kembali</a>
				<?php if($model->status == 1){ ?>
				<a class='btn btn-danger btn-xs' href='<?= Url::to(['/amprah-gudangatk/batalkan?id='.$model->id])?>'>Batalkan</a>
				<?php } ?>
				<hr>
				<?php if($model->status == 1){ ?>
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					'filterModel' => $searchModel,
					'columns' => [
						['class' => 'yii\grid\SerialColumn'],
						
						'nama_barang',
						'stok',
						'satuan.satuan',
						[
							'attribute' => 'Jumlah', 
							'format' => 'raw',
							'value' => function ($model, $key, $index) { 
									if($model->stok > 1){
										$detail = AmprahGudangatkDetail::find()->where(['status'=>0])->andwhere(['idbarang'=>$model->id])->count();
										if(!$detail){
											return "<input class='form-control' id='jumlahSetuju-".$model->id."' type='number'><input type='hidden' value='".$model->id."' id='idbarang".$model->id."'>";
										}else{
											return '<span class="label label-success">Sudah Terinput</span>';
										}										
									}else{
										return '-';
									}
							},
							
							
						],

					],
				]); ?>
				<?php } ?>
				</div>
				<div class='box-footer'></div>
			
			</div>
		</div>
		<div class='col-md-6'>
			<div class='box box-body'>
				<table class='table table-bordered'>
					<tr>
						<th width=20>No</th>
						<th>Nama Barang</th>
						<th>Jumlah</th>
						<th width=20>#</th>
					</tr>
					
					<?php $no=1; foreach($amprah as $a){ ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $a->barang->nama_barang ?> </td>
						<td><?= $a->jumlah ?> <?= $a->barang->satuan->satuan ?></td>
						<td>
							<?php if($model->status == 1){ ?>
							<a href='<?= Url::to(['/amprah-gudangatk/hapus?id='.$a->id])?>' class='btn btn-danger btn-xs'>Hapus</a>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					
				</table>
				<hr>
				<?php if(count($amprah) > 0){ ?>
					<?php if($model->status == 1){ ?>
					<a id='confirm' href='<?= Url::to(['/amprah-gudangatk/serahkan?id='.$model->id])?>' class='btn btn-success btn-sm'>Serahkan</a>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php 
	foreach($barang as $pl){
	$urlEdit = Url::to(['amprah-gudangatk/barang-keluar']);
	//$urlHarga = Url::to(['jangkes/edit-harga']);
	$this->registerJs("
	
		$('#jumlahSetuju-{$pl->id}').on('keypress',function(e) {
			if(e.which === 13){
				$('#jumlahSetuju-{$pl->id}').prop('readonly', true);
				jumlah = $('#jumlahSetuju-{$pl->id}').val();
				id = $('#idbarang{$pl->id}').val();
				$.ajax({
				type: 'GET',
					url: '{$urlEdit}',
					data: 'id='+id+'&jumlah='+jumlah+'&idso='+{$model->id},
					dataType: 'json',
					success: function (data) {
						var res = JSON.parse(JSON.stringify(data));
						if(res == 404){
							alert('Jumlah jumlah stok kurang dari permintaan');
							location.reload();
						}else{
							//alert(res);
							location.reload();
						}

					},
					 
				});

			}
		});

	", View::POS_READY);
	}
	?> 
	
	<?php
	$this->registerJs("

	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	
           
	

", View::POS_READY);
	?>