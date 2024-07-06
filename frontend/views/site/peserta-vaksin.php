<?php
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\web\JsExpression;
use kartik\select2\Select2;
use yii\web\View;
use common\models\BarangMasukdetail;
use common\models\Obat;
$this->params['breadcrumbs'][] = ['label' => 'Barang Masuk', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$no = 1;
$gridcolom = [
		['class' => 'kartik\grid\SerialColumn'],
		'tglvaksin',
		[
			'attribute' => 'No Register',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return '"'.$model->noregister.'"';
			},
		],
		'vaksin',
		[
			'attribute' => 'antrian',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return substr($model->noantrian,9);
			},
		],
		[
			'attribute' => 'NIK',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return '"'.$model->nik.'"';
			},
		],
		'nama',
		[
			'attribute' => 'Jenis Kelamin',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return "L / P";
			},
		],
		[
			'attribute' => 'Tgl Lahir',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return $model->tgllahir;
			},
		],
		[
			'attribute' => 'Umur',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return $model->usia;
			},
		],
		[
			'attribute' => 'Instansi Pekerjaan',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return "";
			},
		],
		[
			'attribute' => 'Jenis Pekerjaan',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return "";
			},
		],
		[
			'attribute' => 'Kode Kategori',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return "";
			},
		],
		[
			'attribute' => 'NO HP',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return $model->nohp;
			},
		],
		[
			'attribute' => 'Alamat',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return $model->alamat;
			},
		],
		[
			'attribute' => 'Kode Kab',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return '';
			},
		],
		[
			'attribute' => 'Nama Kab',
			'format' => 'raw',
			'value' => function ($model, $key, $index) { 
				return '';
			},
		],

		


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
                  
                         ExportMenu::FORMAT_EXCEL => ['filename' => 'Penerimaan_Barang-'],
                     ],
        'filename' => 'Peserta Vaksin',
		]);
?>
<div class="barang-masuk-view">
	
	<div class="box box-body">
	
		
	<?= GridView::widget([
			'panel' => ['type' => 'default', 'heading' => 'Daftar Pasien'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Obat dan Alkes</h3>',
				'type'=>'warning',
				
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
			 'export' => [
					'label' => 'Page',
				],
    'exportContainer' => [
        'class' => 'btn-group mr-2'
    ],
	

    // your toolbar can include the additional full export menu
    'toolbar' => [
        '{export}',
        $fullExportMenu,
		],
						
						'columns' => $gridcolom,
					]); ?>

	</div>
</div>
