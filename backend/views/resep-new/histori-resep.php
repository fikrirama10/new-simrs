<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;

?>
<div class='row'>
	<div class='col-md-4'>
		<div class='box'>
			<div class='box-header with-border'>
				<h3>Data Pasien </h3>
			</div>
			<div class='box-body'>
				<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'no_rm',
							'nik',
							'no_bpjs',
							'nama_pasien',
							'tgllahir',
							'tempat_lahir',
							'nohp',
							'usia_tahun',
							'kepesertaan_bpjs',
							'pekerjaan.pekerjaan',
						],
					]) ?>
			</div>
			</div>
	</div>
	<div class='col-md-8'>
		<div class='box box-primary'>
			<div class='box-header with-border'>
				<h3>Data Resep </h3>
			</div>
			<div class='box-body'>
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
										<button class="btn btn-success" id="btnCari" type="button"> <i class="fa fa-search"></i> Cari</button>
									</div>
								</div>
							</form>
		</div>
		</div>
		
	</div>

</div>
<?php 
$urlShowAll = Url::to(['resep-new/show-data']);
$this->registerJs("
	$('#btnCari').on('click',function(e) {
		//rm = $('#norms').val();
		awal = $('#txtTgl1').val();
		akhir = $('#txtTgl2').val();
		$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
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
	});"
	, View::POS_READY);
?>