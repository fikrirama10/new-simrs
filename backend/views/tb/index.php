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
use common\models\PasienAlamat;
use kartik\export\ExportMenu;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
$gridcolom = [
	['class' => 'kartik\grid\SerialColumn'],
					
					'no_rm',
					'pasien.nama_pasien',
					'pasien.no_bpjs',
						[
					'attribute' => 'Nik', 
					 
					'value' => function ($model, $key, $index, $widget) { 
						return '.'.$model->pasien->nik.'.';
					},
					
					],
					
					[
					'attribute' => 'usia', 
					 
					'value' => function ($model, $key, $index, $widget) { 
						return $model->pasien->usia_tahun.' th '.$model->pasien->usia_bulan.' bln '.$model->pasien->usia_hari.' hari';
					},
					
					],
					[
					'attribute' => 'Alamat', 
					 
					'value' => function ($model, $key, $index, $widget) { 
						$pasien = PasienAlamat::find()->where(['idpasien'=>$model->pasien->id])->one();
						return $pasien->alamat;
					},
					
					],
					[
					'attribute' => 'tgl_kunjungan', 
					 
					'value' => function ($model, $key, $index, $widget) { 
							return $model->tgl_kunjungan;
							
					},
					'filterType' => GridView::FILTER_DATE ,
					 'filterWidgetOptions' => ([       
						'attribute' => 'tgl_kunjungan',
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
					'icdx',
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
			  
					 ExportMenu::FORMAT_EXCEL => ['filename' => 'Laporan Pasien TB'],
				 ],
	'filename' => 'Laporan Pasien TB',
	]);
?>
<br>
<div class='box box-body'>
		<h2>List Pasien TB</h2><hr>
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