<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
?>
<div class="form-horizontal">
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
		<div class="col-md-3 col-sm-3 col-xs-12">
			`
			<button type="button" id="btnCariHistori" class="btn btn-primary pull-left"><i class="fa fa-search"></i> Cari</button>
		</div>
	</div>
	<div id='historiAjax'></div>
 </div>
 <?php 
$urlShowList = Url::to(['pasien/show-histori-bpjs']);
$this->registerJs("
	$('#btnCariHistori').on('click',function(e) {
		noka = '{$model->no_bpjs}';
		awal = $('#txtTgl1').val();
		akhir = $('#txtTgl2').val();
		$.ajax({
				type: 'GET',
				url: '{$urlShowList}',
				data: 'noka='+noka+'&awal='+awal+'&akhir='+akhir,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#historiAjax').show();
					$('#historiAjax').html(data);
					
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