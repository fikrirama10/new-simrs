<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Pjax;
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
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = $this->title;
?>
<div class='box'>
<div class='box-header with-border'><h4>Statistik Kunjungan Pasien</h4></div>
<div class='box-body'>
	<div class='row'>
		<div class='col-md-6'>
			<table class='table'>
				<tr>
					<td>Start</td>
					<td>End</td>
				</tr>
				<tr>
					<td><input type='date' id='start-tgl' class='form-control'></td>
					<td><input type='date' id='end-tgl' class='form-control'></td>
				</tr>
			</table>
		</div>
	</div>
	<div id='loading' style='display:none;'>
	<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
	</div>
	<div id='grafik2'></div>
	<div id='grafik'>

	<div class='row'>
		<div class='col-md-8'>
			
		</div>
		<div class='col-md-3'></div>
	</div>
	
	</div>
	<hr>

<?php Pjax::begin(); ?>
<?= GridView::widget([
	'panel' => ['type' => 'default', 'heading' => 'Poliklinik'],
	'dataProvider' => $dataRajal,
	'filterModel' => $searchRajal,
	'id' => 'ajax_gridview',
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
		'width' => '180px',
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
		'width' => '180px',
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
		'width' => '180px',
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
		[
		'attribute' => 'status', 
		'vAlign' => 'middle',
		'width' => '180px',
		'value' => function ($model, $key, $index, $widget) { 
				if($model->status == null){
					return '-';
				}else{
					return $model->rawatstatus->status;
				}
				
		},
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => ArrayHelper::map(RawatStatus::find()->all(), 'id', 'status'), 
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear' => true],
		],
		'filterInputOptions' => ['placeholder' => 'Status Pasien'], // allows multiple authors to be chosen
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
<?php Pjax::end(); ?>
	</div>
	
</div>
<?php

$urlShowAll = Url::to(['kunjungan-pasien/show']);
$this->registerJs("	
	$('#end-tgl').on('change',function(){
		end = $('#end-tgl').val();
		start = $('#start-tgl').val();
		if(start == ''){
			alert('Silahkan isi parameter dengan lengkap');
		}else{
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'start='+start+'&end='+end,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#ajax_gridview').html(data);					
					console.log(data);					
				},	
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});
		}
	});

", View::POS_READY);




?>