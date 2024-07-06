<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\TindakanKategori;
use common\models\Jenispenerimaan;
use common\models\JenispenerimaanDetail;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tindakan';
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
				'before'=>Html::a('<i class="fas fa-redo"></i> Tambah Tindakan', ['create'], ['class' => 'btn bg-info']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'kode_tindakan',
					'nama_tindakan',
					[
						'attribute' => 'idpenerimaan', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
							return $model->penerimaan->jenispenerimaan;
								
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(Jenispenerimaan::find()->all(), 'id', 'jenispenerimaan'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Jenis Penerimaan'], // allows multiple authors to be chosen
						'format' => 'raw'
					],
					[
						'attribute' => 'idjenispenerimaan', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
							return $model->detail->namapenerimaan;
								
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(JenispenerimaanDetail::find()->all(), 'id', 'namapenerimaan'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Detail Penerimaan'], // allows multiple authors to be chosen
						'format' => 'raw'
					],
					[
						'attribute' => 'idjenistindakan', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
							return $model->tindakan->kategori;
								
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(TindakanKategori::find()->all(), 'id', 'kategori'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Tindakan Kategori'], // allows multiple authors to be chosen
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