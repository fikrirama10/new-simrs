<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\TindakanKategori;
use common\models\RawatJenis;
use common\models\Poli;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\TarifKattindakan;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'TARIF';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class='box box-body'>
		<h2>TARIF</h2><hr>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data TARIF'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Data TARIF</h3>',
				'type'=>'success',
				'before'=>Html::a('<i class="fas fa-redo"></i> Tambah TARIF', ['create'], ['class' => 'btn bg-info']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'kode_tarif',
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
					'attribute' => 'idkelas', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idkelas == null){
								return '-';
							}else{
								
								return $model->kelas->kelas;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(RuanganKelas::find()->all(), 'id', 'kelas'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Jenis Kelas'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					[
					'attribute' => 'kat_tindakan', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->kat_tindakan == null){
								return '-';
							}else{
								
								return $model->tindakan->tindakan;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(TarifKattindakan::find()->all(), 'id', 'tindakan'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Jenis Tindakan'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					[
					'attribute' => 'idruangan', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idruangan == null){
								return '-';
							}else{
								
								return '-';
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(Ruangan::find()->all(), 'id', 'nama_ruangan'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Ruangan'], // allows multiple authors to be chosen
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