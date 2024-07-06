<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\Dokter;
use common\models\RawatJenis;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RESEP';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="obat-farmasi-index">

   <div class='box box-body'>
		<h2>RESEP</h2><hr>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'RESEP'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Data RESEP</h3>',
				'type'=>'success',
				
				'before'=>Html::a('<i class="fas fa-redo"></i> Buat RESEP ', ['create'], ['class' => 'btn bg-red']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					[
					'attribute' => 'tgl', 
					 
					'value' => function ($model, $key, $index, $widget) { 
							return $model->tgl;
							
					},
					'filterType' => GridView::FILTER_DATE ,
					 'filterWidgetOptions' => ([       
						'attribute' => 'tgl',
						//'presetDropdown' => true,
						'convertFormat' => false,
						'pluginOptions' => [
						  'format' => 'yyyy-mm-dd',
						//'todayHighlight' => true
						],
						
					  ]),
					'format' => 'raw'
					],
					'kode_resep',	
					'nama_pasien',	
					'nrp',	
					'usia',	
					'alamat',	
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
