<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\Radiologi;
use common\models\RadiologiPelayanan;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Layanan Radiologi';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class='box box-body'>
		<h2><?= $this->title ?></h2><hr>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data Dokter'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Data '.$this->title.'</h3>',
				'type'=>'success',
				'before'=>Html::a('<i class="fas fa-redo"></i> Tambah Layanan', ['create'], ['class' => 'btn bg-info']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'nama_tindakan',
					'tarif.tarif',
					
					[
					'attribute' => 'idpelayanan', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
						return $model->pelayanan->nama_pelayanan;
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(RadiologiPelayanan::find()->all(), 'id', 'nama_pelayanan'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Layanan Radiologi'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					[
					'attribute' => 'idrad', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
						return $model->rad->radiologi;
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(Radiologi::find()->all(), 'id', 'radiologi'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Radiologi'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					'keterangan',
					[
						'attribute' => 'Status',
						'format' => 'raw',
						'value' => function ($model, $key, $index) { 
							if($model->status == 1){
								return 'Aktif';
							}else{
								return 'Non Aktif';
							}
						},
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