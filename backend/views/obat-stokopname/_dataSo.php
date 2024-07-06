<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\ObatSuplier;
use common\models\RawatBayar;
?>
<?php

	$grid = [
			['class' => 'kartik\grid\SerialColumn'],
			
			'obat.nama_obat',
			'bacth.no_bacth',
			'merk',
			'stok_asal',
			'jumlah',
			'selisih',
			'total',
			'keterangan',
			'klarifikasi',
			
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{view}{hapus}{klarifikasi}',
				'buttons' => [
						
						'view' => function ($url,$model) {
							
								return Html::a(
										'<span class="label label-warning"><span class="fa fa-pencil"></span></span>', 
										Url::to(['obat-stokopname/edit?id='.$model->id]));
							
						},
						'hapus' => function ($url,$model) {
							
								return Html::a(
										'<span class="label label-danger"><span class="fa fa-trash"></span></span>', 
										Url::to(['obat-stokopname/hapus?id='.$model->id]));
							
						},
						'klarifikasi' => function ($url,$model) {
							
								return Html::a(
										'<span class="label label-primary"><span class="fa fa-eye"></span></span>', 
										Url::to(['obat-stokopname/klarifikasi?id='.$model->id]));
							
						},
						
						
												
					
						
					],
			],
			

			
		];
 ?>

			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Poliklinik'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'showPageSummary' => true,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i></h3>',
				'type'=>'primary',
				'after'=>Html::a('<i class="fas fa-pencil"></i> Cocokan Stok', ['obat-stokopname/stok-adjustmen?id='.$model->id], ['class' => 'btn bg-navy']),
				
				
				],
				
				'columns' => $grid,
			]); ?>
		
		