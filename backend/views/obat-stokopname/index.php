<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\ObatStokopnamePeriode;
use common\models\Gudang;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ObatStokOpnameSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Obat Stok Opnames';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obat-stok-opname-index">
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
				
				'before'=>Html::a('<i class="fa fa-plus"></i> Stok Opname Obat', ['create'], ['class' => 'btn bg-primary']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'kode_so',
					'tgl_so',
					
					[
						'attribute' => 'idperiode', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->periode->periode;
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(ObatStokopnamePeriode::find()->all(), 'id', 'periode'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Periode'], // allows multiple authors to be chosen
						'format' => 'raw'
					],
					[
						'attribute' => 'idgudang', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->gudang->nama_gudang;
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(Gudang::find()->all(), 'id', 'nama_gudang'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Gudang'], // allows multiple authors to be chosen
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
</div>
