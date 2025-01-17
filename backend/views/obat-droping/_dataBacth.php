<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\ObatSuplier;
use common\models\RawatBayar;
?>
<br>
<?php

	$grid = [
					['class' => 'kartik\grid\SerialColumn'],
					
					'no_batch',
					'merk',
					'ed',
					
					[
						'attribute' => 'stok',
						'width' => '150px',
						'hAlign' => 'right',
						'format' => ['decimal', 0],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
					
										
					
					
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}',
						'buttons' => [
								
								'view' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-warning"><span class="fa fa-pencil"></span></span>', 
												Url::to(['obat-droping/edit-bacth?id='.$model->id]));
									
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
				
				
				],
				
				'columns' => $grid,
			]); ?>
		
	</div>