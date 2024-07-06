<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use common\models\RawatJenis;
$this->title = 'RESEP';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class='row'>
	<div class='col-md-5'>
		<div class='box box-header'>
			<h4>Buat Resep</h4>
		</div>
		<div class='box box-body'>
			<div class='row'>
				<div class='col-md-2 col-xs-4 '><span class='pull-right pd-top'>NO RM</span></div>
				<div class='col-md-8 col-xs-8'>
					<div class="input-group">
						<input type='text' id='kode-pasien' class='form-control'>								
						<a id="manual" class="input-group-addon btn btn-success btn-sm" ><span class="fa fa-search"></span></a>								
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id='show-rawat'>
	
</div>
<div class='box box-body'>
		<h2>Data Resep</h2><hr>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Data Resep'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Data Resep</h3>',
				'type'=>'success',
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					'kode_resep',
					'no_rm',
					'pasien.nama_pasien',
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
					'rawat.poli.poli',
					'rawat.dokter.nama_dokter',
					'rawat.bayar.bayar',
					
					[
					'attribute' => 'tgl', 
					 
					'value' => function ($model, $key, $index, $widget) { 
							return $model->tgl;
							
					},
					'filterType' => GridView::FILTER_DATE ,
					 'filterWidgetOptions' => ([       
						'attribute' => 'tgl',
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
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}',
						'buttons' => [
								
								'view' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-primary"><span class="fa fa-folder-open"></span></span>', 
												Url::to(['resep-new/view?id='.$model->idrawat]));
									
								},
								
								
														
							
								
							],
					],
					
	
					
				],
			]); ?>
		
	</div>

<?php 
$urlShowAll = Url::to(['resep-new/show-pasien']);
$this->registerJs("
	$('#manual').on('click',function(e) {
		kode = $('#kode-pasien').val();

			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+kode,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#show-rawat').show();
					$('#show-rawat').animate({ scrollTop: 0 }, 200);
					$('#show-rawat').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				// Hide image container
				$('#loading').hide();
				}
			});
		
	});
", View::POS_READY);
?>