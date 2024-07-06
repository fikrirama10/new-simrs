<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\DokterSpesialis;
use common\models\UserUnit;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class='box box-body'>
		<h2>User</h2><hr>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data User'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Data User</h3>',
				'type'=>'success',
				'before'=>Html::a('<i class="fas fa-redo"></i> Tambah User', ['create'], ['class' => 'btn bg-info']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'kode_user',
					'nama',
					'email',
					'nohp',
					[
					'attribute' => 'idunit', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idunit == null){
								return '-';
							}else{
								return $model->unit->unit;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(UserUnit::find()->all(), 'id', 'unit'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Unit'], // allows multiple authors to be chosen
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