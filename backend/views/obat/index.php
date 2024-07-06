<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\ObatJenis;
use common\models\ObatSatuan;
use common\models\ObatBacth;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Daftar Obat';
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
				
				'before'=>Html::a('<i class="fa fa-plus"></i> Tambah Obat', ['create'], ['class' => 'btn bg-primary']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					[
						'attribute' => 'nama_obat', 
						'vAlign' => 'middle',
						'format' => 'raw',
						'value' => function ($model, $key, $index, $widget) { 
							return Html::a('<b>'.$model->nama_obat.'</b>', ['view', 'id' => $model->id]);
						},
						
					],
					'satuan.satuan',
					[
						'attribute' => 'idjenis', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->jenis->jenis;
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(ObatJenis::find()->all(), 'id', 'jenis'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Jenis'], // allows multiple authors to be chosen
						'format' => 'raw'
					],
					[
						'attribute' => 'stok_apotek', 
						'vAlign' => 'middle',
						'width' => '100px',
						'value' => function ($model, $key, $index, $widget) { 
							return $model->stok_apotek;
						},
						
					],
					[
						'attribute' => 'stok_gudang', 
						'vAlign' => 'middle',
						'width' => '100px',
						'value' => function ($model, $key, $index, $widget) { 
							return $model->stok_gudang;
						},
						
					],
					[
						'attribute' => 'harga_beli', 
						'vAlign' => 'middle',
						'width' => '100px',
						'value' => function ($model, $key, $index, $widget) { 
							return $model->harga_beli;
						},
						
					],
					[
						'attribute' => 'harga_jual', 
						'vAlign' => 'middle',
						'width' => '100px',
						'value' => function ($model, $key, $index, $widget) { 
							return $model->harga_jual;
						},
						
					],
					
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}{delete}',
						'buttons' => [
								
								'view' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-primary"><span class="fa fa-folder-open"></span></span>', 
												$url);
									
								},
								'delete' => function ($url,$model) {
										return Html::a(
												'<span class="label label-danger"><span class="fa fa-trash"></span></span>', 
												$url,
												[
												'title' => Yii::t('yii', 'Delete'),
												'data-confirm' => Yii::t('yii', 'Are you sure to delete this item?'),
												'data-method' => 'post',
												]);
								},
							
											
							
								
							],
					],
					
	
					
				],
			]); ?>
		
	</div>