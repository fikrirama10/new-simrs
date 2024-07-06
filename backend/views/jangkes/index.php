<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\PermintaanObatstatus;
use common\models\UserUnit;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List Permintaan Obat';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class='box box-body'>
		<h2><?= $this->title ?></h2><hr>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Poliklinik'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> '.$this->title.'</h3>',
				'type'=>'success',
				
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'kode_permintaan',
					'tgl_permintaan',
					'ruangan.ruangan',
					'keterangan',
				
					[
						'attribute' => 'status', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->permintaanstatus->status;
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(PermintaanObatstatus::find()->all(), 'id', 'status'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Status'], // allows multiple authors to be chosen
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
					
	
					
				],
			]); ?>
		
	</div>