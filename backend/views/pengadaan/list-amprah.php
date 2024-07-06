<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\BarangAmprahStatus;
/* @var $this yii\web\View */
/* @var $searchModel common\models\BarangAmprahSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Barang Amprah';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barang-amprah-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	
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
				
				'before'=>Html::a('<i class="fas fa-plus"></i> Permintaan Barang', ['create'], ['class' => 'btn bg-primary']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'kode_permintaan',
					'tgl_permintaan',
					'unit.unit',
					'keterangan',
					
					[
						'attribute' => 'status', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->amprah->status;
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(BarangAmprahStatus::find()->all(), 'id', 'status'), 
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
												Url::to(['pengadaan/view-amprah?id='.$model->id]));
									
								},
								
								
														
							
								
							],
					],
					
	
					
				],
			]); ?>


</div>
