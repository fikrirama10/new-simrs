<?php
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\RawatBayar;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\web\View;
use yii\bootstrap\Modal;
use dosamigos\chartjs\ChartJs;
$poli = Poli::find()->all();
$hari = array();
$umum = array();
$bpjs = array();
$kategori = array();
$kategori_jumlah = array();
$usia = array();
$usia_jumlah = array();
foreach($model['hari'] as $a):
	array_push($hari,$a['hari']);
	array_push($umum,$a['umum']);
	array_push($bpjs,$a['bpjs']);
endforeach;
foreach($model['kategori'] as $kat):
	array_push($kategori,$kat['kategori']);
	array_push($kategori_jumlah,$kat['jumlah']);
endforeach;
foreach($model['usia'] as $uus):
	array_push($usia,$uus['jenjang']);
	array_push($usia_jumlah,$uus['jumlah']);
endforeach;
?>
<a target='_blank' href='<?= Url::to(['poliklinik/print-laporan?bulan='.$bulann.'&tahun='.$tahun.'&poli='.$polii])?>' class='btn btn-warning'>Print Laporan</a><hr>


<div class='row'>
	<div class='col-md-12'>
	<center><h3>LAPORAN POLIKLINIK <?= $poliklinik ?> BULAN <?= $bulan ?> TAHUN <?= $tahun ?></h3></center>
		<?= ChartJs::widget([
		'type' => 'bar',
		'options' => [
			'height' => 80,
			'width' => 300,
			'plugin'=>[
				'title'=>[
					'display'=>true,
					'text'=>'Jenjang Usia'
				]
			],
		],
		'data' => [
			'labels' => $hari,
			'datasets' => [
				[
					'label' => "Umum",
					'backgroundColor' => "rgba(179,181,198,0.5)",
					'borderColor' => "RED",
					'pointBackgroundColor' => "rgba(179,181,198,1)",
					'pointBorderColor' => "#fff",
					'pointHoverBackgroundColor' => "#fff",
					'pointHoverBorderColor' => "rgba(179,181,198,1)",
					'data' => $umum
				],
				[
					'label' => "BPJS",
					'backgroundColor' => "rgba(18, 201, 52,0.5)",
					'borderColor' => "rgba(18, 201, 52,1)",
					'pointBackgroundColor' => "rgba(18, 201, 52,1)",
					'pointBorderColor' => "#fff",
					'pointHoverBackgroundColor' => "#fff",
					'pointHoverBorderColor' => "rgba(18, 201, 52,1)",
					'data' => $bpjs
				]
			]
		]
		]);
		?>
	</div>
	
	<div class='col-md-6'>
	<hr>
	<h3>GRAFIK KATEGORI PASIEN POLIKLINIK <?= $poliklinik ?> BULAN <?= $bulan ?> TAHUN <?= $tahun ?></h3>
	<hr>
	<?=
	ChartJs::widget([
    'type' => 'pie',
    'id' => 'structurePie',
    'options' => [
        'height' => 400,
        'width' => 600,
    ],
    'data' => [
        'radius' =>  "100%",
        'labels' => $kategori, // Your labels
        'datasets' => [
            [
                'data' => $kategori_jumlah, // Your dataset
                'label' => '',
                'backgroundColor' => [
                        '#ADC3FF',
                        '#FF9A9A',
						'#f5c542',
						'#8ff2ff',
						'#ede434',
						'#ac00eb',
						'#34ed69',
						'#eb0000',
                ],
                'borderColor' =>  [
                        '#fff',
                        '#fff',
                        '#fff'
                ],
                'borderWidth' => 1,
                'hoverBorderColor'=>[
				'#ADC3FF',
                        '#FF9A9A',
						'#f5c542',
						'#8ff2ff',
						'#ede434',
						'#ac00eb',
						'#34ed69',
						'#eb0000',
				],                
            ]
        ]
    ],
    'clientOptions' => [
        'legend' => [
            'display' => true,
            'position' => 'right',
            'labels' => [
                'fontSize' => 14,
                'fontColor' => "#425062",
            ]
        ],
        'tooltips' => [
            'enabled' => true,
            'intersect' => true
        ],
        'hover' => [
            'mode' => true
        ],
        'maintainAspectRatio' => true,

    ],
   
])
	?>
	</div>
	<div class='col-md-6'>
	<hr>
	<h3>GRAFIK JENJANG USIA PASIEN POLIKLINIK <?= $poliklinik ?> BULAN <?= $bulan ?> TAHUN <?= $tahun ?></h3>
	<hr>
	<?=
	ChartJs::widget([
    'type' => 'pie',
    'id' => 'sss',
    'options' => [
        'height' => 400,
        'width' => 600,
    ],
    'data' => [
        'radius' =>  "100%",
        'labels' => $usia, // Your labels
        'datasets' => [
            [
                'data' => $usia_jumlah, // Your dataset
                'label' => '',
                'backgroundColor' => [
                        '#ADC3FF',
                        '#FF9A9A',
						'#f5c542',
						'#ede434',
						'#ac00eb',
						'#34ed69',
						'#eb0000',
						'#8ff2ff',
                ],
                'borderColor' =>  [
                        '#fff',
                        '#fff',
                        '#fff'
                ],
                'borderWidth' => 1,
                'hoverBorderColor'=>[
				'#ADC3FF',
                        '#FF9A9A',
						'#f5c542',
						'#ede434',
						'#ac00eb',
						'#34ed69',
						'#eb0000',
						'#8ff2ff',
				],                
            ]
        ]
    ],
    'clientOptions' => [
        'legend' => [
            'display' => true,
            'position' => 'right',
            'labels' => [
                'fontSize' => 14,
                'fontColor' => "#425062",
            ]
        ],
        'tooltips' => [
            'enabled' => true,
            'intersect' => true
        ],
        'hover' => [
            'mode' => true
        ],
        'maintainAspectRatio' => true,

    ],
   
])
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
	
		'format' => 'raw'
		],
		[
		'attribute' => 'idjenisrawat', 
		'vAlign' => 'middle',
		'width' => '120px',
		'value' => function ($model, $key, $index, $widget) { 
			return $model->jenisrawat->jenis;
				
		},
		
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
		'icdx',
		[
		'attribute' => 'idbayar', 
		'vAlign' => 'middle',
		'width' => '120px',
		'value' => function ($model, $key, $index, $widget) { 
				return $model->bayar->bayar;
				
		},
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'), 
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear' => true],
		],
		'filterInputOptions' => ['placeholder' => 'Poliklinik'], // allows multiple authors to be chosen
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