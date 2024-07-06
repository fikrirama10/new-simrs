
<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\ObatSuplier;
use common\models\RawatBayar;
use common\models\Obat;
?>
<br>
<h3>Detail Obat / Alkes</h3>
<?php
	
		$grid = [['class' => 'kartik\grid\SerialColumn'],
					[
						'attribute' => 'idobat', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
								return "<a href='".Url::to(['obat/'.$model->idobat])."' class='btn btn-default btn-xs'>".$model->obat->nama_obat."</a>";
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(Obat::find()->all(), 'id', 'nama_obat'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Obat'], // allows multiple authors to be chosen
						'format' => 'raw'
					],
					'no_bacth',
					'merk',
					'stok_gudang',
					'stok_apotek',
					'harga_jual',
					'harga_beli',

					
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}',
						'buttons' => [
								
								'view' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-primary"><span class="fa fa-eye"></span></span>', 
												Url::to(['obat/'.$model->idobat]));
									
								},
								
								
														
							
								
							],
					],
	
					
				];
	
 ?>
<div class='box box-body'>
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
				'type'=>'success',
				'before'=>Html::a('<i class="fa fa-plus"></i> Tambah Obat', ['create'], ['class' => 'btn bg-primary']),
				
				
				],
				
				'columns' => $grid,
			]); ?>
		
	</div>

