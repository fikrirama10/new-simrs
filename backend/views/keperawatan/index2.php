<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use common\models\Ruangan;
use common\models\Dokter;
$this->title = 'Pelayanan Rawat Inap';
$this->params['breadcrumbs'][] = $this->title;
?>
<br>
<div class='row'>
	<div class='col-md-12'>
		<div class='box box-header'><h3>RAWAT INAP</h3></div>
		<div class='box box-body'>
			<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-barcode"></i> Masukan NO RM Pasien </h4>
                Silahkan scan dengan scaner barcode atau ketik manual NO RM.
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
	<?php foreach($bed as $b){ ?>
		<a href='#'  data-toggle="modal" data-target="#modal-<?= $b['id']?>">
			<div class="col-md-4 col-sm-6 col-xs-12">
			  <div class="info-box" style='-webkit-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);-moz-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);'>

				<span class="info-box-icon bg-navy"><i class='fa fa-bed'></i></span>
					
				<div class="info-box-content">
				  <span class="info-box-text"><?= $b['ruangan']?> ( <?= $b['bed']?> BED )</span>
				  <span class="info-box-number"> <?= $b['bed'] - $b['terisi']?>  Kosong</span> 
				    <span class="info-box-text">Kelas <?php if($b['kelas'] == 4){echo 'VIP';}else{echo $b['kelas'];} ?></span>
				</div> 
				
				
				<!-- /.info-box-content -->
			  </div> 
			  <!-- /.info-box -->
			</div>
		</a>
	<?php } ?>
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
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i>Pasien Keperawatan</h3>',
				'type'=>'success',
				'after'=>Html::a('<i class="fas fa-redo"></i> Reset Grid', ['index'], ['class' => 'btn bg-navy']),
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					'no_rm',
					'idrawat',
					'pasien.nama_pasien',
					'tglmasuk',					
					'tglpulang',					
					[
					'attribute' => 'idruangan', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idruangan == null){
								return '-';
							}else{
								return $model->ruangan->nama_ruangan;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(Ruangan::find()->where(['jenis'=>2])->all(), 'id', 'nama_ruangan'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Ruangan'], // allows multiple authors to be chosen
					'format' => 'raw'
					],					
					[
					'attribute' => 'iddokter', 
					'vAlign' => 'middle',
					'width' => '180px',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->iddokter == null){
								return '-';
							}else{
								return $model->dokter->nama_dokter;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(Dokter::find()->all(), 'id', 'nama_dokter'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'DPJP'], // allows multiple authors to be chosen
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
$urlShowAll = Url::to(['keperawatan/show']);
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