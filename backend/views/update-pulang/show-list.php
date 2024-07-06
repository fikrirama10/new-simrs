<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\bootstrap\Modal;
use kartik\export\ExportMenu;

$gridcolom = [
	['class' => 'kartik\grid\SerialColumn'],
	[
		'attribute' => 'noSep',
		'value' => function ($model, $key, $index, $widget) {
			return "<a data-toggle='modal' data-target='#mdList" . $model['noSep'] . "'  class='btn btn-default btn-xs'>" . $model['noSep'] . "</a>";
		},

		'format' => 'raw'
	],
	'noSepUpdating',
	[
		'attribute' => 'jnsPelayanan',
		'label' => 'RI/RJ',
		'value' => function ($model, $key, $index, $widget) {
			if ($model['jnsPelayanan'] == 1) {
				return 'RI';
			} else {
				return 'RJ';
			}
		},

		'format' => 'raw'
	],
	'ppkTujuan',
	'noKartu',
	'nama',
	'tglSep',
	'tglPulang',
	'status',
	'tglMeninggal',
	'noSurat',
	'keterangan',
	'user',
];

$fullExportMenu = ExportMenu::widget([
	'dataProvider' => $dataProvider,
	'columns' => $gridcolom,
	'target' => ExportMenu::TARGET_BLANK,
	'pjaxContainerId' => 'kv-pjax-container',
	'exportContainer' => [
		'class' => 'btn-group mr-2'
	],
	'dropdownOptions' => [
		'label' => 'Full',
		'class' => 'btn btn-outline-secondary',
		'itemsBefore' => [
			'<div class="dropdown-header">Export All Data</div>',
		],
	],
	'exportConfig' => [
		ExportMenu::FORMAT_EXCEL => ['filename' => 'Laporan Kunjungan Bulanan'],
	],
	'filename' => 'Laporan Kunjungan Bulanan',
]);
?>
<?= GridView::widget([
	'panel' => ['type' => 'default', 'heading' => 'Poliklinik'],
	'dataProvider' => $dataProvider,
	//'filterModel' => $searchModel,
	'hover' => true,
	'bordered' => false,
	'pjax' => true,
	'panel' => [
		'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> ' . $this->title . '</h3>',
		'type' => 'success',

	],
	'export' => [
		'label' => 'Page',
	],
	'exportContainer' => [
		'class' => 'btn-group mr-2'
	],
	'toolbar' => [
		'{export}',
		$fullExportMenu,
	],

	'columns' => $gridcolom,
]); ?>
	<?php
	// 	 GridView::widget([
	//     'dataProvider'=>$dataProvider,
	// 	'pjax'=>true,
	// 	 'options' => ['style' => 'font-size:10px;'],
	// 	// 'layout'=>"{items}",	
	//     // 'filterModel' => $filter,
	//     'columns' => [
	//         ['class' => 'yii\grid\SerialColumn'],	

	// 		[
	// 			'attribute' => 'noSep', 
	// 			'value' => function ($model, $key, $index, $widget) { 
	// 				return "<a data-toggle='modal' data-target='#mdList".$model['noSep']."'  class='btn btn-default btn-xs'>".$model['noSep']."</a>";	
	// 			},

	// 			'format' => 'raw'
	// 		],
	// 		'noSepUpdating',
	// 		[
	// 			'attribute' => 'jnsPelayanan', 
	// 			'label' => 'RI/RJ', 
	// 			'value' => function ($model, $key, $index, $widget) { 
	// 				if($model['jnsPelayanan'] == 1){
	// 					return 'RI';
	// 				}else{
	// 					return 'RJ';
	// 				}
	// 			},

	// 			'format' => 'raw'
	// 		],
	// 		'ppkTujuan',
	// 		'noKartu',
	// 		'nama',
	// 		'tglSep',
	// 		'tglPulang',
	// 		'status',
	// 		'tglMeninggal',
	// 		'noSurat',
	// 		'keterangan',
	// 		'user',
	//        // ['class' => 'yii\grid\ActionColumn'],
	//     ],
	// ]);
	?>

<?php
foreach ($response['response']['list'] as $list) {
	$this->registerJs("
		
		$('#confirm-{$list["noSep"]}').on('click', function(event){
			age = confirm('Yakin Untuk menghapus {$list["noSep"]}');
			if(age == true){
				 return true;
			} else {
				event.preventDefault();
			}
		});
		
	", View::POS_READY);

	Modal::begin([
		'id' => 'mdList' . $list['noSep'],
		'header' => '<h5>Update  : ' . $list['noSep'] . '</h5>',
		'size' => 'modal-md',
		'options' => [
			'data-url' => 'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">' . $this->render('show-data', ['sep' => $list['noSep']]) . '</div>';

	Modal::end();
}
?>
