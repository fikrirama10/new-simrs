<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
?>
<?= GridView::widget([
	'panel' => ['type' => 'default', 'heading' => 'List Rujukan'],
	'dataProvider' => $dataProvider,
	// 'filterModel' => $searchModel,
	'hover' => true,
	'bordered' =>false,
	'pjax'=>true,	
	'columns' => [
		['class' => 'kartik\grid\SerialColumn'],
		
		[
			'attribute' => 'no_rujukan', 
			'vAlign' => 'middle',
			'width' => '100px',
			'value' => function ($model, $key, $index, $widget) { 
				return '<a href="'.Url::to(['rujukan/view-rujukan?id='.$model->id]).'" class="btn btn-default btn-xs">'.$model->no_rujukan.'</a>';					
			},
			
			'format' => 'raw'
		],
		[
			'attribute' => 'tgl_rujuk', 
			'vAlign' => 'middle',
			'width' => '120px',
			'value' => function ($model, $key, $index, $widget) { 
				return date('d-m-Y',strtotime($model->tgl_rujuk));					
			},
			
			'format' => 'raw'
		],
		'jenisrawat.jenis',
		'no_sep',
		'pasien.no_bpjs',
		'pasien.nama_pasien',
		'faskes_tujuan',
		
		
	],
]); ?>