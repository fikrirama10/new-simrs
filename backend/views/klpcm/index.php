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
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class='box box-body'>
		<h2>KLPCM</h2><hr>
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
				
				'before'=>Html::a('<i class="fa fa-plus"></i> Klpcm', ['create'], ['class' => 'btn bg-success']),
				'after'=>Html::a('<i class="fa fa-redo"></i> Laporan', ['klpcm-laporan'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'rawat.idrawat',
					'no_rm',
					'pasien.nama_pasien',
					[
						'attribute' => 'tgl_kunjungan', 
						 'width' => '100px',
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
					[
					'attribute' => 'idjenisrawat', 
					'vAlign' => 'middle',
					'width' => '180px',
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
						'attribute' => 'kelengkapan', 
						 'width' => '100px',
						'value' => function ($model, $key, $index, $widget) { 
								if($model->kelengkapan == 1){
									return "<span class='fa fa-check'></span>";
								}else{
									return "<span class='fa fa-close'></span>";
								}
								
						},
						
						'format' => 'raw'
					],
					[
						'attribute' => 'keterbacaan', 
						 'width' => '100px',
						'value' => function ($model, $key, $index, $widget) { 
								if($model->keterbacaan == 1){
									return "<span class='fa fa-check'></span>";
								}else{
									return "<span class='fa fa-close'></span>";
								}
								
						},
						
						'format' => 'raw'
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}{update}',
						'buttons' => [
								
								'view' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-primary"><span class="fa fa-folder-open"></span></span>', 
												$url);
									
								},
								'update' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-warning"><span class="fa fa-pencil"></span></span>', 
												$url);
									
								},
								
								
														
							
								
							],
					],
					
	
					
				],
			]); ?>
		
	</div>