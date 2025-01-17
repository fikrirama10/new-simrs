<?php
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\UnitRuangan;
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $searchModel common\models\AmprahGudangobatSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Amprah Obat';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amprah-gudangobat-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data SPRI'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Amprah Obat</h3>',
				'type'=>'success',
				
				'before'=>Html::a('Create Amprah', ['create'], ['class' => 'btn btn-success']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'idamprah',
					'tgl_permintaan',
					'tgl_penyerahan',
					
					[
					'attribute' => 'idpeminta', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idpeminta == null){
								return '-';
							}else{
								return $model->ruangan->ruangan;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(UnitRuangan::find()->all(), 'id', 'ruangan'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Ruangan'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					[
					'attribute' => 'status', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->status == 1){
								return '<span class="label label-default">Draf</label>';
							}else{
								return '<span class="label label-success">Selesai</label>';
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
					
	
					
				],
			]); ?>


</div>
