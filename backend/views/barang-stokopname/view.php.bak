<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\DataSatuan;
use common\models\BarangStokopnameDetail;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\BarangStokopname */
$idso = $model->id;
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Barang Stokopnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="barang-stokopname-view">


    
	<div class='row'>
		<div class='col-md-5'>
		<div class='box box-header'><a class='btn btn-primary' href='<?= Url::to(['/barang-stokopname'])?>'>Kembali</a></div>
		<div class='box box-body'>
		<?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'kode_so',
            'tgl_so',
            'keterangan',
        ],
    ]) ?>
	<hr>
			<?php if($model->status == 1){ ?>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data Barang'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Data Barang</h3>',
				'type'=>'danger',
				
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'nama_barang',
					[
						'attribute' => 'StokOpname', 
						'width' => '40px',
						'format' => 'raw',
						'value' => function ($model, $key, $index) { 
								$detail = BarangStokopnameDetail::find()->where(['status'=>0])->andwhere(['idbarang'=>$model->id])->count();
								if(!$detail){
									return "<input class='form-control' id='jumlahSetuju-".$model->id."' type='text'><input type='hidden' value='".$model->id."' id='idbarang".$model->id."'>";
								}else{
									return '<span class="label label-success">Sudah Terinput</span>';
								}
								
						},
						
						
					],
					
					[
					'attribute' => 'idsatuan', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idsatuan == null){
								return '-';
							}else{
								return $model->satuan->satuan;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(DataSatuan::find()->all(), 'id', 'satuan'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Satuan'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					
					
					// [
						// 'class' => 'yii\grid\ActionColumn',
						// 'template' => '{view}',
						// 'buttons' => [
								
								// 'view' => function ($url,$model) {
									
										// return Html::a(
												// '<span class="label label-primary"><span class="fa fa-folder-open"></span></span>', 
												// $url);
									
								// },
								
								
														
							
								
							// ],
					// ],
					
	
					
				],
			]); ?>
			<?php } ?>
		</div>
		</div>
		<div class='col-md-7'>
			<?php 
				if($model->status == 1){
					$tombol = Html::a('<i class="fas fa-redo"></i> Cocokan Stok', ['cocok-stok?id='.$model->id], ['class' => 'btn bg-navy','id'=>'confirm-cocok']);
				}else{
					$tombol = '';
				}
			?>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data Barang SO'],
				'dataProvider' => $dataProvider2,
				'filterModel' => $searchModel2,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Data Barang</h3>',
				'type'=>'success',
				'before'=>$tombol,
				
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'barang.nama_barang',
					[
					'attribute' => 'stokasal', 
					'vAlign' => 'middle',
					'width' => '100px',
					'value' => function ($model, $key, $index, $widget) { 
							return $model->stokasal.' '.$model->barang->satuan->satuan;
					},
					
					],
					[
					'attribute' => 'stokreal', 
					'vAlign' => 'middle',
					'width' => '100px',
					'value' => function ($model, $key, $index, $widget) { 
							return $model->stokreal.' '.$model->barang->satuan->satuan;
					},
					
					],
					[
					'attribute' => 'selisih', 
					'vAlign' => 'middle',
					'width' => '100px',
					'value' => function ($model, $key, $index, $widget) { 
							return $model->selisih.' '.$model->barang->satuan->satuan;
					},
					
					],

					
					[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{edit}{hapus}',
				'buttons' => [
						
						'edit' => function ($url,$model) {
							
								return Html::a(
										'<span class="label label-warning"><span class="fa fa-pencil"></span></span>', 
										Url::to(['barang-stokopname/edit?id='.$model->id]));
							
						},
						'hapus' => function ($url,$model) {
							
								return Html::a(
										'<span class="label label-danger"><span class="fa fa-trash"></span></span>', 
										Url::to(['barang-stokopname/hapus?id='.$model->id]));
							
						},
						
						
												
					
						
					],
			],
	
					
				],
			]); ?>
		</div>
	</div>
	
			<?php 
	foreach($barang as $pl){
	$urlEdit = Url::to(['barang-stokopname/barang-so']);
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
	}
	?> 
</div>
