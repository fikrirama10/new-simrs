<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\RawatStatus;
use common\models\RawatJenis;
use common\models\Poli;
use common\models\RawatBayar;
use common\models\RadiologiTindakan;
$this->title = 'Pelayanan Radiologi';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class='row'>
	<div class='col-md-12'>
		<div class='box box-header'><h3>Radiologi</h3></div>
		<div class='box box-body'>
			<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-barcode"></i> Masukan Rekam medis Pasien  </h4>
                Silahkan scan dengan scaner barcode atau ketik manual Rekam medis Pasien.
              </div>
			<input type='text' id='kode-pasien' autofocus class='form-control'>
		</div>
	</div>
	<div id='loading' style='display:none;'>
		<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
	</div>
	<div class='col-md-12'>
	<div id='pasien-ajax'></div>
	</div>
</div>

<div class='row'>
<div class='col-md-12'>
	<div class='box box-body'>
	<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Poliklinik'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
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
					
					[
					'attribute' => 'no_rm', 
					 
					'value' => function ($model, $key, $index, $widget) { 
							return $model->rawats->no_rm;
							
					},

					'format' => 'raw'
					],
					'rawats.pasien.nama_pasien',
					[
					'attribute' => 'tgl_permintaan', 
					 
					'value' => function ($model, $key, $index, $widget) { 
							return $model->tgl_permintaan;
							
					},
					'filterType' => GridView::FILTER_DATE ,
					 'filterWidgetOptions' => ([       
						'attribute' => 'tgl_permintaan',
						//'presetDropdown' => true,
						'convertFormat' => false,
						'pluginOptions' => [
						  'format' => 'yyyy-mm-dd',
							'todayHighlight' => true,
							'autoclose' => true
						],
						
					  ]),
					'format' => 'raw'
					],
					'rawats.jenisrawat.jenis',
					[
					'attribute' => 'idtindakan', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idtindakan == null){
								return '-';
							}else{
								return $model->tindakan->nama_tindakan;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(RadiologiTindakan::find()->all(), 'id', 'nama_tindakan'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Tindakan'], // allows multiple authors to be chosen
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
								if($model->status == 1){
									return "<span class='label label-danger'>Belum Periksa</span>";
								}else if($model->status == 2){
									return "<span class='label label-success'>Selesai</span>";
								}else{
									return "<span class='label label-danger'>Batal</span>";
								}
								
							}
							
					},
					
					'format' => 'raw'
					],
					
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}',
						'buttons' => [
								
								'view' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-primary"><span class="fa fa-folder-open"></span></span>', 
												Url::to(['radiologi-order/view?id='.$model->idrawat]));
									
								},
								
								
														
							
								
							],
					],
					
	
					
				],
			]); ?>
	</div>
</div>
</div>
<?php 
$urlShowAll = Url::to(['radiologi-order/show']);
$this->registerJs("
	$('#kode-pasien').on('keypress',function(e) {
		kode = $('#kode-pasien').val();
		if(e.which === 13){
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+kode,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#pasien-ajax').show();
					$('#pasien-ajax').animate({ scrollTop: 0 }, 200);
					$('#pasien-ajax').html(data);
					
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