<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\TransaksiStatus;
$this->title = 'BILLING';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class='row'>
	<div class='col-md-12'>
		<div class='box box-header'><h3><?= $this->title ?></h3></div>
		<div class='box box-body'>
			<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-money"></i> Masukan No RM Pasien </h4>
                Silahkan scan dengan scaner barcode atau ketik manual No RM Pasien.
              </div>
			<input type='text' id='kode-pasien' autofocus class='form-control'>
		</div>
	</div>
	<div id='loading' style='display:none;'>
		<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
	</div>
	<div id='pasien-ajax'></div>
</div>
<div class='row'>
<div class='col-md-12'>
	<div class='box box-body'>
		<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data Dokter'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Transaksi Belum Selesai</h3>',
				'type'=>'success',
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					[
					'attribute' => 'tgltransaksi', 
					 
					'value' => function ($model, $key, $index, $widget) { 
							return $model->tgltransaksi;
							
					},
					'filterType' => GridView::FILTER_DATE ,
					 'filterWidgetOptions' => ([       
						'attribute' => 'tgltransaksi',
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
					'idtransaksi',					
					'no_rm',					
					'pasien.nama_pasien',					
					[
					'attribute' => 'total', 
					 
					'value' => function ($model, $key, $index, $widget) { 
							return Yii::$app->algo->IndoCurr(round($model->total));
							
					},
					
					'format' => 'raw'
					],	
[
					'attribute' => 'total_bayar', 
					 
					'value' => function ($model, $key, $index, $widget) { 
							return Yii::$app->algo->IndoCurr(round($model->total_bayar));
							
					},
					
					'format' => 'raw'
					],						
					//'tgltransaksi',
					
					[
					'attribute' => 'status', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->status == null){
								return '-';
							}else{
								if($model->status == 1){
									return "<span class='label label-warning'>Belum Bayar</span>";
								}else if($model->status == 2){
									return "<span class='label label-success'>Lunas</span>";
								}else{
									return "<span class='label label-danger'>Batal</span>";
								}
								
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(TransaksiStatus::find()->all(), 'id', 'status'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Status Transaksi'], // allows multiple authors to be chosen
					'format' => 'raw'
					],	
					[
					'attribute' => 'hide', 
					 
					'value' => function ($model, $key, $index, $widget) { 
						if($model->hide == 1){
							return "<span class='label label-danger'>Hide</span>";
						}else{
							return "<span class='label label-primary'>Unhide</span>";
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
												$url);
									
								},
								
								
														
							
								
							],
					],
					
	
					
				],
			]); ?>
	</div>
</div>
</div>
<?php 
$urlShowAll = Url::to(['billing/show']);
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