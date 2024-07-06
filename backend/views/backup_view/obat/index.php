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
					
					'nama_obat',
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
						'attribute' => 'stok', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
							if(Yii::$app->user->identity->userdetail->managemen == 1){
								$b1 = ObatBacth::find()->where(['idobat'=>$model->id])->sum('stok_gudang');
								$b2 = ObatBacth::find()->where(['idobat'=>$model->id])->sum('stok_apotek');
								$bacth = $b1 + $b2;
							}else{
								if(Yii::$app->user->identity->userdetail->idgudang == 1){
									$bacth = ObatBacth::find()->where(['idobat'=>$model->id])->sum('stok_gudang');
								}else{
									$bacth = ObatBacth::find()->where(['idobat'=>$model->id])->sum('stok_apotek');
								}
								
							}
							return $bacth;
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