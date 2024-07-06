<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\DataPekerjaan;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel common\models\PasienSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pasiens';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class='row'>
	<div class='col-md-12'>
		<div class='box box-header'><h3>PENDAFTARAN</h3></div>
		<div class='box box-body'>
			<div class="alert alert-primary alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-barcode"></i> Masukan NO RM PASIEN </h4>
                Silahkan scan dengan scaner barcode atau ketik manual No RM
              </div>
			<input type='text' id='kode-pasien' autofocus class='form-control'>
		<div id='loading' style='display:none;'>
		<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
		</div>
		<div id='pasien-ajax'></div>
		</div>
	</div>
	
</div>
<div class='box box-body'>
		<h4>Pasien</h4><hr>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data Pasien'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Daftar Pasien</h3>',
				'type'=>'primary',
				'before'=>Html::a('<i class="fas fa-redo"></i> Tambah Pasien', ['create'], ['class' => 'btn bg-orange']),
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'no_rm',
					[
					'attribute' => 'nama_pasien', 
					'vAlign' => 'middle',
					'width' => '280px',
					'value' => function ($model, $key, $index, $widget) { 
								return Html::a('<b>'.$model->nama_pasien.'</b>', ['view', 'id' => $model->id]);
					},
					'format' => 'raw'
					
					],
					[
					'attribute' => 'no_bpjs', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
						return $model->no_bpjs;
					},
					
					],
					[
					'attribute' => 'nik', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
						return $model->nik;
					},
					
					],
					[
					'attribute' => 'tgllahir', 
					 
					'value' => function ($model, $key, $index, $widget) { 
							return $model->tgllahir;
							
					},
					'filterType' => GridView::FILTER_DATE ,
					 'filterWidgetOptions' => ([       
						'attribute' => 'tgllahir',
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
					'nohp',
					
					[
					'attribute' => 'idpekerjaan', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							$pekerjaan = DataPekerjaan::findOne($model->idpekerjaan);
							if($pekerjaan){
							    return $model->pekerjaan->pekerjaan;
							}
							return '-';
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(DataPekerjaan::find()->all(), 'id', 'pekerjaan'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Pekerjaan'], // allows multiple authors to be chosen
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
<?php 
$urlShowAll = Url::to(['pasien/show-rujukan']);
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
					$('#loading').show();
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