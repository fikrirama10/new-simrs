<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use common\models\ObatSuplier;
use common\models\ObatKategori;
use yii\helpers\ArrayHelper;
use common\models\RawatBayar;
use kartik\date\DatePicker;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\Obat */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Obats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class='row'>
	<div class='col-md-4'>
		<div class="box">
			<div class="box-header with-border"><h4>Data Obat</h4></div>
			<div class="box-body">
				 <?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'nama_obat',
						'stok_gudang',
						'stok_apotek',
						'harga_jual',
						'harga_beli',
						'jenis.jenis',
						'satuan.satuan',
					],
				]) ?>
			</div>
			<div class="box-footer">
				<a href='<?= Url::to(['/obat'])?>' class='btn btn-info'>Kembali</a>
			</div>
		</div>
	</div>
	<div class='col-md-8'>
		<div class="box">
			<div class="box-header with-border"></div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						
						<a data-toggle="modal" data-target="#updateModal" class="btn btn-app">
							<span class="badge bg-purple"><i class="fa fa-edit"></i></span>
							<i class="fa fa-edit"></i> Edit
						</a>
						
						<a href='<?= Url::to(['/obat/grafik?id='.$model->id])?>' class="btn btn-app">
							<span class="badge bg-yellow"><i class="fa  fa-bar-chart"></i></span>
							<i class="fa fa-bar-chart"></i> Grafik  Obat
						</a>
						<a href='<?= Url::to(['/obat-suplier/create?id='.$model->id])?>' class="btn btn-app">
							<span class="badge bg-red"><i class="fa fa-inbox"></i></span>
							<i class="fa fa-truck"></i> Tambah Suplier
						</a>
						
						<hr>
					</div>
					
					<div class="col-md-12">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_1" data-toggle="tab">Mutasi Obat</a></li>
								<li><a href="#tab_2" data-toggle="tab">Kartu Stok</a></li>
								<li><a href="#tab_3" data-toggle="tab">Koreksi Stok</a></li>
								
								<li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1">
									<b>Mutasi Obat:</b>
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
									<div id='list-ajax'></div>
								</div>

								<div class="tab-pane" id="tab_2">
									<b>Kartu Stok:</b>
									<form class="form-horizontal">
										<div class="form-group">
											<label class="col-md-3 col-sm-3 col-xs-12 control-label">Tanggal</label>
											<div class="col-md-5 col-sm-5 col-xs-12">
												<div class="input-group date">
													<input type="date" class="form-control datepicker" id="txtTgl12" maxlength="10">
													
													<span class="input-group-addon">
														s.d
													</span>
													<input type="date" class="form-control datepicker" id="txtTgl22" maxlength="10">
													
												</div>

											</div>
										</div>
										<div class="form-group">
											<div class="col-md-3 col-sm-3 col-xs-12"></div>
											<div class="col-md-3 col-sm-3 col-xs-12">
												<button class="btn btn-success" id="btnCari2" type="button"> <i class="fa fa-search"></i> Cari</button>
											</div>
										</div>
									</form>
									<div id='list-stok'></div>
								</div>
								<div class="tab-pane" id="tab_3">
									<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
										<div class="form-group">
											<label class="col-sm-4 control-label">Stok Gudang</label>
											<div class="col-sm-2">
												<input type='text' readonly name='Obat[stok_gudang]' class='form-control' value='<?= $model->stok_gudang?>'>
											</div>
											<label class="col-sm-2 control-label">Stok Apotek</label>
											<div class="col-sm-2">
												<input type='text' readonly name='Obat[stok_apotek]' class='form-control' value='<?= $model->stok_apotek?>'>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label">Stok Koreksi <?php if(Yii::$app->user->identity->userdetail->idgudang == 1){echo'Gudang';}else if(Yii::$app->user->identity->userdetail->idgudang == 2){echo'Apotek';} ?></label>
											<div class="col-sm-2">
												<input type='text' name='koreksi' class='form-control' >
											</div>
											
										</div>
										<div class="box-footer">
											<button type="submit" id='confirm' class="btn btn-info pull-right ">Simpan</button>
										</div>
									<?php ActiveForm::end(); ?>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
			<div class="box-footer"></div>
		</div>
	</div>

</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" id="updateModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h4 class="modal-title" id="exampleModalLabel">Tambah Batch Obat</h4>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			  <span aria-hidden="true">&times;</span>
			</button>
		  </div>
		  <div class="modal-body">
				<?= $this->render('_form', [
					'model' => $model,
				]) ?>
		  </div>
	  </div>
	</div>
</div>



<?php 
$urlShowAll = Url::to(['obat/show-data']);
$urlShowKartu = Url::to(['obat/show-kartu']);
$this->registerJs("
	$('#btnCari').on('click',function(e) {
		//rm = $('#norms').val();
		awal = $('#txtTgl1').val();
		akhir = $('#txtTgl2').val();
		idobat = '{$model->id}';
		$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'idobat='+idobat+'&awal='+awal+'&akhir='+akhir,
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
	
	$('#btnCari2').on('click',function(e) {
		//rm = $('#norms').val();
		awal = $('#txtTgl12').val();
		akhir = $('#txtTgl22').val();
		idobat = '{$model->id}';
		$.ajax({
				type: 'GET',
				url: '{$urlShowKartu}',
				data: 'idobat='+idobat+'&awal='+awal+'&akhir='+akhir,
				beforeSend: function(){
				// Show image container
				$('#loading').show();
				},
				success: function (data) {
					$('#list-stok').show();
					$('#list-stok').animate({ scrollTop: 0 }, 200);
					$('#list-stok').html(data);
					
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