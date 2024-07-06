<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
?>
<div class="col-md-12">
	<div class="box box-success" id="divHeadAction">
		<div class="box-header with-border">
			<h3 class="box-title">Rujukan</h3>
		</div>
		<div class="box-body">
			<div>
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a title="Cari Nomor SEP" href="#tab_1" data-toggle="tab" aria-expanded="true"><span class="fa fa-file-text-o"></span> Nomor RM</a></li>
						<li><a title="Cari Nomor SEP" href="#tab_2" data-toggle="tab" aria-expanded="true"><span class="fa fa-list-ul"></span> List Rujukan</a></li>
						<li><a title="Cari Nomor SEP" href="#tab_3" data-toggle="tab" aria-expanded="true"><span class="fa fa-list-ul"></span> List Semua Rujukan</a></li>
					   
					</ul>
					<div class="tab-content">
						<div class="tab-pane" id="tab_3">
							<?= GridView::widget([
								'panel' => ['type' => 'default', 'heading' => 'List Rujukan'],
								'dataProvider' => $dataProvider,
								// 'filterModel' => $searchModel,
								'hover' => true,
								'bordered' =>false,
								// 'pjax'=>true,	
								'columns' => [
									['class' => 'kartik\grid\SerialColumn'],
									
									[
										'attribute' => 'no_rujukan', 
										'vAlign' => 'middle',
										'width' => '100px',
										'value' => function ($model, $key, $index, $widget) { 
											if($model->no_sep != null){
												return '<a href="'.Url::to(['rujukan/view-rujukan?id='.$model->id]).'" class="btn btn-default btn-xs">'.$model->no_rujukan.'</a>';	
											}else{
												return '<a href="'.Url::to(['rujukan/view-rujukan?id='.$model->id]).'" class="btn btn-default btn-xs">'.$model->kode_rujukan.'</a>';	
											}
										},
										
										'format' => 'raw'
									],
									[
										'attribute' => 'tgl_rujuk', 
										'vAlign' => 'middle',
										'width' => '120px',
										'value' => function ($model, $key, $index, $widget) { 
											return date('d-m-Y',strtotime($model->tgl_rujuk));					
										},
										
										'format' => 'raw'
									],
									'jenisrawat.jenis',
									'no_sep',
									'pasien.no_bpjs',
									'pasien.nama_pasien',
									'faskes_tujuan',
									'alasan_rujuk',
									[
										'class' => 'yii\grid\ActionColumn',
										'template' => '{print-label}{hapus-rujukan}',
										'buttons' => [
												
												'print-label' => function ($url,$model) {
													
														return Html::a(
																'<span class="btn btn-primary btn-xs">Print Label</span>', 
																$url,['target'=>'_blank']);
													
												},
												'hapus-rujukan' => function ($url,$model) {
													
														return Html::a(
																'<span class="btn btn-danger btn-xs">Hapus</span>', 
																$url);
													
												},
												
												
																		
											
												
											],
									],
									
								],
							]); ?>
													</div>
						<div class="tab-pane active" id="tab_1">
							<form class="form-horizontal">
								<div class="form-group">
									<label class="col-md-3 col-sm-3 col-xs-12 control-label">No.Rm</label>
									<div class="col-md-3 col-sm-3 col-xs-12">
										<input type="text" class="form-control" id="norms" placeholder="ketik nomor rekammedis" maxlength="6">
									</div>
								</div>
								<div class="form-group">
									<div class="col-md-3 col-sm-3 col-xs-12"></div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										<button class="btn btn-primary" id="btnCari" type="button"> <i class="fa fa-search"></i> Cari</button>
									</div>
								</div>
							</form>
							<div id='loading' style='display:none;'>
								<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
							</div>
							<div id='pasien-ajax'></div>
						</div>
						<div class="tab-pane" id="tab_2">
							<form class="form-horizontal">
							<div class="form-group">
								<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal</label>
								<div class="col-md-5 col-sm-5 col-xs-12">
									<div class="input-group date">
										<input type="date" class="form-control datepicker" id="txtTgl1" maxlength="10">
										
										<span class="input-group-addon">
											s.d
										</span>
										<input type="date" class="form-control datepicker" id="txtTgl2" maxlength="10">
										
									</div>

								</div>
							</div>
								<div class="form-group">
									<div class="col-md-3 col-sm-3 col-xs-12"></div>
									<div class="col-md-3 col-sm-3 col-xs-12">
										<button class="btn btn-success" id="btnCarilist" type="button"> <i class="fa fa-search"></i> Cari</button>
									</div>
								</div>
							</form>
							<div id='loading' style='display:none;'>
								<center><img src='https://www.launchpads.com.au/assets/css/icons/animated/search/animat-search-color.gif'></center>
							</div>
							<div id='list-ajax'></div>
						</div>
					</div>
				</div>

			</div>
			
		</div>
		
	</div>
</div>
<?php 
$urlShowAll = Url::to(['rujukan/show-data']);
$urlShowList = Url::to(['rujukan/show-list']);
$this->registerJs("
	$('#btnCari').on('click',function(e) {
		rm = $('#norms').val();
		$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+rm,
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
	});
	
	$('#btnCarilist').on('click',function(e) {
		//rm = $('#norms').val();
		awal = $('#txtTgl1').val();
		akhir = $('#txtTgl2').val();
		$.ajax({
				type: 'GET',
				url: '{$urlShowList}',
				data: 'awal='+awal+'&akhir='+akhir,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#list-ajax').show();
					$('#list-ajax').animate({ scrollTop: 0 }, 200);
					$('#list-ajax').html(data);
					
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