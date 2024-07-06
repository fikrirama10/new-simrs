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
	 'options' => ['style' => 'font-size:10px;'],
	// 'layout'=>"{items}",	
    // 'filterModel' => $filter,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],	
		
		[
			'attribute' => 'noSep', 
			'value' => function ($model, $key, $index, $widget) { 
				return "<a href='".Url::to(['data-kunjungan-bpjs/view?id='.$model['noSep'].'&poli='.$model['poli']])."' class='btn btn-default btn-xs'>".$model['noSep']."</a>";	
			},
			
			'format' => 'raw'
		],
		// 'noSepUpdating',
		// [
			// 'attribute' => 'jnsPelayanan', 
			// 'label' => 'RI/RJ', 
			// 'value' => function ($model, $key, $index, $widget) { 
				// if($model['jnsPelayanan'] == 1){
					// return 'RI';
				// }else{
					// return 'RJ';
				// }
			// },
			
			// 'format' => 'raw'
		// ],
		'tglSep',
		'jnsPelayanan',
		'noKartu',
		'nama',
		'diagnosa',
		'poli',
       // ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>

<?php
// foreach($response['response']['list'] as $list){
	// $this->registerJs("
		
		// $('#confirm-{$list["noSep"]}').on('click', function(event){
			// age = confirm('Yakin Untuk menghapus {$list["noSep"]}');
			// if(age == true){
				 // return true;
			// } else {
				// event.preventDefault();
			// }
		// });
		
	// ", View::POS_READY);
	
	// Modal::begin([
		// 'id' => 'mdList'.$list['noSep'],
		// 'header' => '<h5>Update  : '.$list['noSep'].'</h5>',
		// 'size'=>'modal-md',
		// 'options'=>[
			// 'data-url'=>'transaksi',
			// 'tabindex' => ''
		// ],
	// ]);
	
	// echo '<div class="modalContent">'. $this->render('show-data', ['sep'=>$list['noSep']]).'</div>';
	 
	// Modal::end();

// }
?>
