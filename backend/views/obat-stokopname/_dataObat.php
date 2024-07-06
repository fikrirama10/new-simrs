
<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\ObatSuplier;
use common\models\RawatBayar;
use common\models\Obat;
?>
<br>
<?php
	
		$grid = [['class' => 'kartik\grid\SerialColumn'],
					[
						'attribute' => 'idobat', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->obat->nama_obat;
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(Obat::find()->all(), 'id', 'nama_obat'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Obat'], // allows multiple authors to be chosen
						'format' => 'raw'
					],
					'merk',
					'no_bacth',

					
					[
							'attribute' => 'Pilih', 
							'format' => 'raw',
							'value' => function ($model, $key, $index) { 
									return "<a class='btn btn-default' data-toggle='modal' data-target='#mdObat".$model->id."'>+ Pilih</a><input type='hidden' '>";
							},
							
							
						],
					
	
					
				];
	
 ?>
<div class='box box-body'>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Poliklinik'],
				'dataProvider' => $dataBatch,
				'filterModel' => $searchBacth,
				'showPageSummary' => true,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i></h3>',
				'type'=>'success',
				
				
				],
				
				'columns' => $grid,
			]); ?>
		
	</div>

<?php
	foreach($obat as $il):
	Modal::begin([
	'id' => 'mdObat'.$il->id,
	'header' => $il->merk,
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

	echo '<div class="modalContent">'. $this->render('_formSo', ['il'=>$il,'model'=>$model,'detail' => $detail,]).'</div>';
	 
	Modal::end();
	endforeach;
?>