<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\bootstrap\Modal;
?>

	<?= GridView::widget([
    'dataProvider'=>$dataProvider,
	'pjax'=>true,
    // 'filterModel' => $filter,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],	
		
        [
			'attribute' => 'noRujukan', 
			'value' => function ($model, $key, $index, $widget) { 
				return "<a href='".Url::to(['rujukan-faskes/edit-rujukan?norujukan='.$model['noRujukan']])."' class='btn btn-default btn-xs'>".$model['noRujukan']."</a>";	
			},
			
			'format' => 'raw'
		],
		
		'tglRujukan',
		[
			'attribute' => 'jnsPelayanan', 
			'label' => 'RI/RJ', 
			'value' => function ($model, $key, $index, $widget) { 
				return $model['jnsPelayanan'];
			},
			
			'format' => 'raw'
		],
		'noSep',
		'noKartu',
		'nama',
		'namaPpkDirujuk',
       // ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>

