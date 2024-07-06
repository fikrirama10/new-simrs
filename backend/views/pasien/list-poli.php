<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\DokterStatus;
use common\models\RawatStatus;
use common\models\RawatBayar;
use common\models\RawatJenis;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
$gridcolom = [
	['class' => 'kartik\grid\SerialColumn'],
					
					'no_rm',
					'no_sep',
					'pasien.nama_pasien',
										[
					'attribute' => 'tglmasuk', 
					 
					'value' => function ($model, $key, $index, $widget) { 
							return $model->tglmasuk;
							
					},
					'filterType' => GridView::FILTER_DATE ,
					 'filterWidgetOptions' => ([       
						'attribute' => 'tglmasuk',
						//'presetDropdown' => true,
						'convertFormat' => false,
						'pluginOptions' => [
						  'format' => 'yyyy-mm-dd',
						   'autoclose' => true,
						//'todayHighlight' => true
						],
						
					  ]),
					'format' => 'raw'
					],
					[
					'attribute' => 'idjenisrawat', 
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idjenisrawat == null){
								return '-';
							}else{
								return $model->jenisrawat->jenis;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(RawatJenis::find()->all(), 'id', 'jenis'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Jenis Rawat'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					[
					'attribute' => 'idpoli', 
					'vAlign' => 'middle',
					'width' => '100px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idpoli == null){
								return '-';
							}else{
								return $model->poli->poli;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(Poli::find()->all(), 'id', 'poli'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Poliklinik'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					[
					'attribute' => 'idbayar', 
					'vAlign' => 'middle',
					'width' => '100px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idbayar == null){
								return '-';
							}else{
								return $model->bayar->bayar;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Jenis Bayar'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					[
					'attribute' => 'status', 
					'vAlign' => 'middle',
					'width' => '100px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->status == null){
								return '-';
							}else{
								return $model->rawatstatus->status;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(RawatStatus::find()->all(), 'id', 'status'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Status Pasien'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					'keterangan',
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view-pasien}',
						'buttons' => [
								
								'view-pasien' => function ($url,$model) {
													
										return Html::a(
												'<span class="btn btn-primary btn-xs">Lihat Pasien</span>', 
												$url,['target'=>'_blank']);
									
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
			  
					 ExportMenu::FORMAT_EXCEL => ['filename' => 'Laporan Kunjungan Harian'],
				 ],
	'filename' => 'Laporan Kunjungan Harian',
	]);
?>
<br>
<div class='box box-body'>
		<h2>List Pasien Poli</h2><hr>
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