<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\ObatSuplier;
use common\models\RawatBayar;
use common\models\PermintaanObatRequest;
?>
<br>
<?php
	if(Yii::$app->user->identity->userdetail->idgudang == 1 || Yii::$app->user->identity->userdetail->managemen == 1)
	{
	$grid = [
					['class' => 'kartik\grid\SerialColumn'],
					
					'no_bacth',
					'merk',
					'harga_jual',
					'harga_beli',
					[
						'attribute' => 'stok_gudang',
						'width' => '150px',
						'hAlign' => 'right',
						'format' => ['decimal', 0],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
					[
						'attribute' => 'stok_apotek',
						'width' => '150px',
						'hAlign' => 'right',
						'format' => ['decimal', 0],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
										
					[
						'attribute' => 'idsuplier', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
						        $trx = PermintaanOBatRequest::find()->where(['idbacth'=>$model->id])->count();
						        if($trx > 0){
						            return $model->suplier->suplier.'- trx sudah ada';
						        }else{
						             return $model->suplier->suplier;
						        }
								
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(ObatSuplier::find()->all(), 'id', 'suplier'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Jenis'], // allows multiple authors to be chosen
						'format' => 'raw'
					],
					
					
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}',
						'buttons' => [
								
								'view' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-warning"><span class="fa fa-pencil"></span></span>', 
												Url::to(['obat/edit-bacth?id='.$model->id]));
									
								},
							
														
							
								
							],
					],
					
	
					
				];
	}else{
		$grid = [['class' => 'kartik\grid\SerialColumn'],
					
					'no_bacth',
					'merk',
					'harga_jual',
					'harga_beli',
					[
						'attribute' => 'stok_apotek',
						'width' => '150px',
						'hAlign' => 'right',
						'format' => ['decimal', 0],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
										
					[
						'attribute' => 'idsuplier', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->suplier->suplier;
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(ObatSuplier::find()->all(), 'id', 'suplier'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Jenis'], // allows multiple authors to be chosen
						'format' => 'raw'
					],

					
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}',
						'buttons' => [
								
								'view' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-primary"><span class="fa fa-folder-open"></span></span>', 
												$url);
									
								},
								
								
														
							
								
							],
					],
					
	
					
				];
	}
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
				
				
				],
				
				'columns' => $grid,
			]); ?>
		
	</div>