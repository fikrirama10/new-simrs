<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\ObatSuplier;
use common\models\PermintaanObat;
use common\models\ObatBacth;
use common\models\PermintaanObatRequest;
use common\models\Obat;
use common\models\RawatBayar;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\web\View;
use kartik\grid\GridView;
use yii\web\JsExpression;
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.nama;   
    return marckup ;
};
var formatRepoSelection = function (repo) {
    return repo.nama || repo.text;
}

JS;
 
// Register the formatting script
$this->registerJs($formatJs, View::POS_HEAD);
 
// script to parse the results into the format expected by Select2
$resultsJs = <<< JS
function (data) {    
    return {
        results: data,
        
    };
}
JS;
/* @var $this yii\web\View */
/* @var $model common\models\PermintaanObat */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Obats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class='row'>
	<div class='col-md-3'>
		<div class="permintaan-obat-view box box-body">
			<h4>Permintaan Obat</h4>
			
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'kode_permintaan',
					'tgl_permintaan',
					'user.userdetail.nama',
					'unit.unit',
					'keterangan:ntext',
				],
			]) ?>

		</div>
	<div class='box box-footer'>
		<a href='<?= Url::to(['permintaan-obat/form-permintaan?id='.$model->id])?>' target='_blank' class='btn btn-warning'>Print</a>
	</div>

	</div>
	<div class='col-md-9'>
			<div class='box box-body'>
	<h3>Permintaan Obat</h3>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,		
		'showPageSummary' => true,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

			[
					'attribute' => 'idobat', 
					'vAlign' => 'middle',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idobat == null){
								return $model->nama_obat."<span class='label label-success'>Baru</span>";
							}else{
								return $model->obat->nama_obat;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(PermintaanObatRequest::find()->all(), 'obat.id', 'obat.nama_obat'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Nama Obat'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					'merk.merk',
					'jumlah',
					'harga',
					[
						'attribute' => 'total',
						'width' => '150px',
						'hAlign' => 'right',
						'format' => ['decimal', 0],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
			[
				'attribute' => 'Pilih', 
				'format' => 'raw',
				'value' => function ($model, $key, $index) { 
						$obatPermintaan = PermintaanObat::findOne($model->idpermintaan);
						if($obatPermintaan->status == 2){
							$tombol = "<a id='btn".$model->id."' data-toggle='modal' data-target='#mdPermintaan".$model->id."' class='btn btn-default'>Pilih</a>";
						}else{
							$tombol='';
						}
						
						return $tombol;
						
						
				},
				
				
			],

		],
	]); ?>
</div>
	</div>
</div>


<?php 
foreach($permintaan_list as $pl):
	Modal::begin([
		'id' => 'mdPermintaan'.$pl->id,
		'header' => '<h3>Permintaan</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formBacth', ['model'=>$model,'pl'=>$pl
	,'detail_permintaan'=>$detail_permintaan]).'</div>';
	 
	Modal::end();
endforeach;
$this->registerJs("
	
	$('#confirm4').hide();
	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data?? , setelah data tersimpan maka stok akan langsung berpindah , pastikan semua jumlah telah sesuai.');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	$('#confirm-ajukan').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data?? , setelah data tersimpan maka data tidak dapat di edit kembali , pastikan pengajuan sudah benar !!.');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	
	

", View::POS_READY);

?>