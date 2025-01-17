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
			'attribute' => 'edit', 
			'value' => function ($model, $key, $index, $widget) { 
					$response = Yii::$app->bpjs->surat_kontrol($model['noSuratKontrol']);
					if($response['metaData']['code'] == 200){
						return "<a data-toggle='modal' data-target='#mdList".$model['noSuratKontrol']."' class='btn btn-info btn-xs'>Update</a>";
					}else{
						return '<span class="label label-success">Terpakai</span>';
					}
					
			},
			
			'format' => 'raw'
		],
		[
			'attribute' => 'Hapus', 
			'value' => function ($model, $key, $index, $widget) { 
					$response = Yii::$app->bpjs->surat_kontrol($model['noSuratKontrol']);
					if($response['metaData']['code'] == 200){
						return "<a id='confirm-".$model['noSuratKontrol']."' href=".Url::to(['surat-kontrol/delete-kontrol?id='.$model['noSuratKontrol']])." class='btn btn-danger btn-xs'>Delete</a>";
					}else{
						return '-';
					}
					
			},
			
			'format' => 'raw'
		],
		
		[
			'attribute' => 'noSuratKontrol', 
			'width' => '150px',
			'value' => function ($model, $key, $index, $widget) { 
					return '<a class="btn btn-default btn-xs">'.$model['noSuratKontrol'].'</a>';
					
			},
			
			'format' => 'raw'
		],
        'nama',
        'nama',
        'jnsPelayanan',
        'namaDokter',
        'tglRencanaKontrol',
        'namaJnsKontrol',
        'tglTerbitKontrol',
        'noSepAsalKontrol',
       // ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>

<?php
foreach($response['response']['list'] as $list){
	$this->registerJs("
		
		$('#confirm-{$list["noSuratKontrol"]}').on('click', function(event){
			age = confirm('Yakin Untuk menghapus {$list["noSuratKontrol"]}');
			if(age == true){
				 return true;
			} else {
				event.preventDefault();
			}
		});
		
	", View::POS_READY);
	
	Modal::begin([
		'id' => 'mdList'.$list['noSuratKontrol'],
		'header' => '<h3>Update Surat Kontrol / SPRI : '.$list['noSuratKontrol'].'</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);
	
	echo '<div class="modalContent">'. $this->render('_form-update', ['kontrol'=>$list['noSuratKontrol']]).'</div>';
	 
	Modal::end();

}
?>
