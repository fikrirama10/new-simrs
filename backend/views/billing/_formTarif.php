<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\ArrayHelper;
use common\models\TindakanKategori;
use common\models\RawatJenis;
use common\models\Poli;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\TarifKattindakan;
use common\models\TarifSearch;
use common\models\Tarif;

$searchModel = new TarifSearch();
$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

?>

<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data TARIF'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				//'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Data TARIF</h3>',
				'type'=>'success',
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['/billing/'.$model->id], ['class' => 'btn bg-navy']),
				
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'nama_tarif',
					'tarif',
					
					[
					'attribute' => 'idkategori', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idkategori == null){
								return '-';
							}else{
								$tindakan = TindakanKategori::find()->where(['id'=>$model->idkategori])->one();
								return $tindakan->kategori;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(TindakanKategori::find()->all(), 'id', 'kategori'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Jenis Tarif'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					[
					'attribute' => 'idpoli', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idpoli == null){
								return '-';
							}else{
								$poli = Poli::findOne($model->idpoli);
								return $poli->poli;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(Poli::find()->all(), 'id', 'poli'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Poli'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					
					
					
					[
							'attribute' => 'Pilih', 
							'format' => 'raw',
							'value' => function ($model, $key, $index) { 
									return "<a id='btn".$model->id."' class='btn btn-default'>+</a><input type='hidden' value='".$model->id."' id='input".$model->id."'>";
							},
							
							
						],
					
				],
			]); ?>
			