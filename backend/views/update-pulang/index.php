<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use kartik\export\ExportMenu;

$gridcolom = [
	['class' => 'kartik\grid\SerialColumn'],
	[
		'attribute' => 'noSep',
		'value' => function ($model, $key, $index, $widget) {
			return "<a data-toggle='modal' data-target='#mdList" . $model['noSep'] . "'  class='btn btn-default btn-xs'>" . $model['noSep'] . "</a>";
		},

		'format' => 'raw'
	],
	'noSepUpdating',
	[
		'attribute' => 'jnsPelayanan',
		'label' => 'RI/RJ',
		'value' => function ($model, $key, $index, $widget) {
			if ($model['jnsPelayanan'] == 1) {
				return 'RI';
			} else {
				return 'RJ';
			}
		},

		'format' => 'raw'
	],
	'ppkTujuan',
	'noKartu',
	'nama',
	'tglSep',
	'tglPulang',
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
		ExportMenu::FORMAT_EXCEL => ['filename' => 'Laporan Kunjungan Bulanan'],
	],
	'filename' => 'Laporan Kunjungan Bulanan',
]);
?>
<div class="box box-success" id="divHeadAction">
	<div class="box-header with-border">
		<h3 class="box-title">Update Pulang SEP</h3>
	</div>
	<div class="box-body">
		<div>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="active"><a title="Cari Nomor SEP" href="#tab_2" data-toggle="tab" aria-expanded="true"><span class="fa fa-file-text-o"></span> Histori</a></li>

				</ul>
				<div class="tab-content">

					<div class="tab-pane  active" id="tab_2">
						<form class="form-horizontal">
							<div class="form-group">
								<label class="col-md-3 col-sm-3 col-xs-12 control-label">Bulan</label>
								<div class="col-md-5 col-sm-5 col-xs-12">
									<div class="input-group date">
										<select class="form-control" name="Bulan" id="bulan">
											<option value="01">Januari</option>
											<option value="02">Februari</option>
											<option value="03">Maret</option>
											<option value="04">April</option>
											<option value="05">Mei</option>
											<option value="06">Juni</option>
											<option value="07">Juli</option>
											<option value="08">Agustus</option>
											<option value="09">September</option>
											<option value="10">Oktober</option>
											<option value="11">November</option>
											<option value="12">Desember</option>
										</select>

										<span class="input-group-addon">
											Tahun
										</span>
										<select class="form-control" name="Tahun" id="tahun">
											<option value="2023">2023</option>
											<option value="2022">2022</option>
											<option value="2021">2021</option>
											<option value="2020">2020</option>
											<option value="2019">2019</option>
										</select>
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
						<div class='row'>
							<div class='col-md-12'>
								<div id='list-ajax'></div>
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>
		<div class="row">
			<div class="col-md-12">
				<?= GridView::widget([
					'panel' => ['type' => 'default', 'heading' => 'Poliklinik'],
					'dataProvider' => $dataProvider,
					//'filterModel' => $searchModel,
					'hover' => true,
					'bordered' => false,
					'pjax' => true,
					'panel' => [
						'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> ' . $this->title . '</h3>',
						'type' => 'success',

					],
					'export' => [
						'label' => 'Page',
					],
					'exportContainer' => [
						'class' => 'btn-group mr-2'
					],
					'toolbar' => [
						'{export}',
						$fullExportMenu,
					],

					'columns' => $gridcolom,
				]); ?>
			</div>
		</div>
	</div>

</div>
<?php
$urlShowAll = Url::to(['rujukan-faskes/show-data']);
$urlShowList = Url::to(['update-pulang/show-list']);
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
		awal = $('#bulan').val();
		akhir = $('#tahun').val();
		// filter = $('#filter').val();
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