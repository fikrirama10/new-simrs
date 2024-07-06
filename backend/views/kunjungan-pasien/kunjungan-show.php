<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use common\models\Poli;
use common\models\DokterStatus;
use common\models\RawatStatus;
use common\models\RawatBayar;
use common\models\RawatJenis;
use dosamigos\chartjs\ChartJs;
$nama = array();
$bpjs = array();
$umum = array();

$nama2 = array();
$bpjs2 = array();
$umum2 = array();
$total = array();
foreach($model as $j):
	array_push($nama,$j['jenis']);
	array_push($umum,$j['umum']);
	array_push($bpjs,$j['bpjs']);
endforeach;
foreach($model_kunjungan as $jk):
	array_push($nama2,$jk['jenis']);
	array_push($umum2,$jk['umum']);
	array_push($bpjs2,$jk['bpjs']);
endforeach;
?>
	<div class='row'>
		<div class='col-md-6'>
			<h4>Grafik Kunjungan </h4>
			<?= ChartJs::widget([
				'type' => 'bar',
				'options' => [
					'height' => 80,
					'width' => 200,
					'plgin'=>[
						'title'=>[
							'display'=>true,
							'text'=>'Jenjang Usia'
						]
					],
				],
				'data' => [
					'labels' => $nama2,
					'datasets' => [
						[
							'label' => "BPJS",
							'backgroundColor' => "rgba(179,181,198,0.2)",
							'borderColor' => "rgba(179,181,198,1)",
							'pointBackgroundColor' => "rgba(179,181,198,1)",
							'pointBorderColor' => "#fff",
							'pointHoverBackgroundColor' => "#fff",
							'pointHoverBorderColor' => "rgba(179,181,198,1)",
							'data' => $bpjs2
						],
						[
							'label' => "Yanmasum",
							'backgroundColor' => "rgba(255,99,132,0.2)",
							'borderColor' => "rgba(255,99,132,1)",
							'pointBackgroundColor' => "rgba(255,99,132,1)",
							'pointBorderColor' => "#fff",
							'pointHoverBackgroundColor' => "#fff",
							'pointHoverBorderColor' => "rgba(255,99,132,1)",
							'data' => $umum2
						]
					]
				]
			]);
			?>
		</div>
		<div class='col-md-6'>
			<h4>Grafik Kunjungan Poliklinik</h4>
			<?= ChartJs::widget([
				'type' => 'bar',
				'options' => [
					'height' => 70,
					'width' => 150,
					'plgin'=>[
						'title'=>[
							'display'=>true,
							'text'=>'Jenjang Usia'
						]
					],
				],
				'data' => [
					'labels' => $nama,
					'datasets' => [
						[
							'label' => "BPJS",
							'backgroundColor' => "rgba(179,181,198,0.2)",
							'borderColor' => "rgba(179,181,198,1)",
							'pointBackgroundColor' => "rgba(179,181,198,1)",
							'pointBorderColor' => "#fff",
							'pointHoverBackgroundColor' => "#fff",
							'pointHoverBorderColor' => "rgba(179,181,198,1)",
							'data' => $bpjs
						],
						[
							'label' => "Yanmasum",
							'backgroundColor' => "rgba(255,99,132,0.2)",
							'borderColor' => "rgba(255,99,132,1)",
							'pointBackgroundColor' => "rgba(255,99,132,1)",
							'pointBorderColor' => "#fff",
							'pointHoverBackgroundColor' => "#fff",
							'pointHoverBorderColor' => "rgba(255,99,132,1)",
							'data' => $umum
						]
					]
				]
			]);
			?>
		</div>
	</div>
	<hr>
<?= GridView::widget([
	'panel' => ['type' => 'default', 'heading' => 'Poliklinik'],
	'dataProvider' => $dataRajal,
	'filterModel' => $searchRajal,
	'hover' => true,
	'bordered' =>false,
	'pjax'=>true,
	'panel' => [
	'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> '.$this->title.'</h3>',
	'type'=>'success',
	
	'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
	
],
	
	'columns' => [
		['class' => 'kartik\grid\SerialColumn'],
		
		'idrawat',
		'no_rm',
		'pasien.nama_pasien',
							[
		'attribute' => 'tglmasuk', 
		 
		'value' => function ($model, $key, $index, $widget) { 
				return $model->tglmasuk;
				
		},
		'filterType' => GridView::FILTER_DATE ,
		 'filterWidgetOptions' => ([       
			'attribute' => 'tglmasuk',
			//'presetDropdown' => true,
			'convertFormat' => false,
			'pluginOptions' => [
			  'format' => 'yyyy-mm-dd',
			   'autoclose' => true,
			//'todayHighlight' => true
			],
			
		  ]),
		'format' => 'raw'
		],
		[
		'attribute' => 'idjenisrawat', 
		'vAlign' => 'middle',
		'width' => '120px',
		'value' => function ($model, $key, $index, $widget) { 
				if($model->idjenisrawat == null){
					return '-';
				}else{
					return $model->jenisrawat->jenis;
				}
				
		},
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => ArrayHelper::map(RawatJenis::find()->all(), 'id', 'jenis'), 
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear' => true],
		],
		'filterInputOptions' => ['placeholder' => 'Jenis Rawat'], // allows multiple authors to be chosen
		'format' => 'raw'
		],
		[
		'attribute' => 'idpoli', 
		'vAlign' => 'middle',
		'width' => '120px',
		'value' => function ($model, $key, $index, $widget) { 
				if($model->idpoli == null){
					return '-';
				}else{
					return $model->poli->poli;
				}
				
		},
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => ArrayHelper::map(Poli::find()->all(), 'id', 'poli'), 
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear' => true],
		],
		'filterInputOptions' => ['placeholder' => 'Poliklinik'], // allows multiple authors to be chosen
		'format' => 'raw'
		],
		[
		'attribute' => 'idbayar', 
		'vAlign' => 'middle',
		'width' => '120px',
		'value' => function ($model, $key, $index, $widget) { 
				if($model->idbayar == null){
					return '-';
				}else{
					return $model->bayar->bayar;
				}
				
		},
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'), 
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear' => true],
		],
		'filterInputOptions' => ['placeholder' => 'Jenis Bayar'], // allows multiple authors to be chosen
		'format' => 'raw'
		],

		
		// [
			// 'class' => 'yii\grid\ActionColumn',
			// 'template' => '{view}',
			// 'buttons' => [
					
					// 'view' => function ($url,$model) {
						
							// return Html::a(
									// '<span class="label label-primary"><span class="fa fa-folder-open"></span></span>', 
									// $url);
						
					// },
					
					
											
				
					
				// ],
		// ],
		

		
	],
]); ?>