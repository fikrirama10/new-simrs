<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\BarangAmprahStatus;
/* @var $this yii\web\View */
/* @var $searchModel common\models\BarangAmprahSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pembelian Barang Atk';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="barang-amprah-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	
    <?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Pembelian Barang'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> '.$this->title.'</h3>',
				'type'=>'success',
				
				'before'=>Html::a('<i class="fas fa-plus"></i> Pembelian Barang', ['create'], ['class' => 'btn bg-primary']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'no_faktur',
					'tgl',
					'total_penerimaan',
					'suplier.suplier',
			
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
