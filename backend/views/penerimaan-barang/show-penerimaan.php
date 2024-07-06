<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\PermintaanObatstatus;
use common\models\ObatSuplier;
use common\models\RawatBayar;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
$gridcolom = 
	[
			['class' => 'kartik\grid\SerialColumn'],
			
			'kode_penerimaan',
			[
			'attribute' => 'no_faktur', 
			'value' => function ($model, $key, $index, $widget) { 
				return "<a href='".Url::to(['penerimaan-barang/view?id='.$model->id])."' class='btn btn-default btn-xs'>".$model->no_faktur."</a>";	
			},
			
			'format' => 'raw'
			],
			'nilai_faktur',
			'tgl_faktur',
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
				'filterInputOptions' => ['placeholder' => 'Suplier'], // allows multiple authors to be chosen
				'format' => 'raw'
			],
			[
			'attribute' => 'status', 
			'value' => function ($model, $key, $index, $widget) { 
				if($model->status == 1){
					return "<a class='btn btn-default btn-xs'>Draf</a>";	
				}else{
					return "<a class='btn btn-success btn-xs'>Selesai</a>";	
				}
				
			},
			
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
$fullExportMenu = ExportMenu::widget([
	'dataProvider' => $dataProvider,
	'columns' => $gridcolom,
	'target' => ExportMenu::TARGET_BLANK,
	'pjaxContainerId' => 'kv-pjax-container',
	'exportContainer' => [
	'class' => 'btn-group mr-2'
	],
	'dropdownOptions' => [
	'label' => 'Full',
	'class' => 'btn btn-outline-secondary',
	'itemsBefore' => [
	'<div class="dropdown-header">Export All Data</div>',
	],
	],
	'exportConfig' => [
			  
					 ExportMenu::FORMAT_EXCEL => ['filename' => 'Data Penerimaan'],
				 ],
	'filename' => 'Data Penerimaan',
	]);
?>
<br>
<div class='box box-body'>
		<a href='<?= Url::to(['penerimaan-barang/cetak?bulan='.$bulan.'&tahun='.$tahun])?>' target='_blank' class='btn btn-warning btn-sm'>Cetak Data</a>
		<a href='<?= Url::to(['penerimaan-barang/cetak-data?bulan='.$bulan.'&tahun='.$tahun])?>' target='_blank' class='btn btn-success btn-sm'>Cetak Penerimaan</a>
		<h2>List Penerimaan Barang</h2><hr>
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
			'export' => [
					'label' => 'Page',
				],
			'exportContainer' => [
				'class' => 'btn-group mr-2'
			],
			'toolbar' => [
				'{export}',
				$fullExportMenu,
			],
				
				'columns' => $gridcolom,
			]); ?>
		
	</div>