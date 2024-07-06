<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;

?>
<div class='row'>
	<div class='col-md-3'>
		<div class="box box-widget widget-user">
			<div class="widget-user-header bg-purple-active">
				<h4 class="widget-user-username" id="lblnama"><?= Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan) ?>. <?= $pasien->nama_pasien?></h4>
				<p class="widget-user-desc" id="lblnoka"><?= $pasien->no_rm?></p>
				<input type="hidden" id="txtkelamin" value="L">
				<input type="hidden" id="txtkdstatuspst" value="0">
			</div>
				<div class="box-body">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a title="Profile Peserta" href="#tab_1" data-toggle="tab"><span class="fa fa-user"></span></a></li>
						
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item">
									<span class="fa fa-sort-numeric-asc"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->nik?></span>
								</li>
								<li class="list-group-item">
									<span class="fa fa-credit-card"></span>  <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->no_bpjs?></span>
								</li>
								<li class="list-group-item">
									<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $pasien->tgllahir ?>  (<?= $pasien->usia_tahun?>th)</span>
								</li>
								<li class="list-group-item">
									<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $rawat->tglmasuk ?> </span>
								</li>
								<?php if($rawat->status != 2){ ?>
								<li class="list-group-item">
									<span class="fa fa-calendar"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $rawat->tglpulang ?> </span>
								</li>
								<?php } ?>
								<li class="list-group-item">
									<span class="fa fa-user-md"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $rawat->dokter->nama_dokter ?> </span>
								</li>
								<li class="list-group-item">
									<span class="fa fa-bed"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $rawat->ruangan->nama_ruangan ?> </span>
								</li>
								<li class="list-group-item">
									<span class="fa  fa-money"></span> <span title="NIK" class="pull-right-container" id="lblnik"><?= $rawat->bayar->bayar ?> </span>
								</li>
							
							</ul>
						</div>
					</div>
				</div>
				<div id="divriwayatKK" style="display: none;">
					<button type="button" id="btnRiwayatKK" class="btn btn-danger btn-block"><span class="fa fa-th-list"></span> Pasien Memiliki Riwayat KLL/KK/PAK <br><i>(klik lihat data)</i></button>
				</div>
			</div>
		</div>
	</div>
	<div class='col-md-9'>
		<div class='box'>
		<div class='box-header with-border'><h4>BHP OK</h4></div>
		<div class='box-body'>
			<table class='table table-bordered'>
				<tr>
					<th>Tindakan</th>
					<th>Harga</th>
					<th>Dokter</th>
				</tr>
				<tr>
					<td><?= $model->tarif->nama_tarif ?></td>
					<td><?= $model->tarif->tarif ?></td>
					<td><?= $model->dokter->nama_dokter ?></td>
				</tr>
			</table>
			<a class='btn btn-warning btn-xs' href='<?= Url::to(['/operasi/'.$model->idok])?>'>Kembali</a>
			<hr>
		
			<div class='row'>
				<div class='col-md-8'>					
					<?php $form = ActiveForm::begin(); ?>
					<table class='table'>
						<tr>
							<td><?= $form->field($bhp, 'nama_obat')->textinput(['maxlength' => true,'required'=>true]) ?></td>
							<td width=100><?= $form->field($bhp, 'jumlah')->textinput(['maxlength' => true,'required'=>true]) ?></td>
							<td width=100><?= $form->field($bhp, 'satuan')->textinput(['maxlength' => true,'required'=>true]) ?></td>
							<td width=100><?= $form->field($bhp, 'harga')->textinput(['maxlength' => true,'required'=>true]) ?></td>
							<td width=100>
								<label></label><br>
								<button class='btn btn-success btn-xs'>+</button>
							</td>
						</tr>
					</table>
					<?= $form->field($bhp, 'idoperasi')->hiddeninput(['maxlength' => true,'value'=>$model->idok])->label(false) ?>
					<?= $form->field($bhp, 'idtindakan')->hiddeninput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
					<?= $form->field($bhp, 'iddokter')->hiddeninput(['maxlength' => true,'value'=>$model->iddokter])->label(false) ?>
					<?php ActiveForm::end(); ?>
				</div>
			</div>
			<div class='row'>
				<div class='col-md-12'>
				<?php if(count($bhp_list) > 0){ ?>
					<table class='table table-bordered'>
						<tr>
							<th width=50>No</th>
							<th>Nama Barang</th>
							<th width=80>Jumlah</th>
							<th>Harga Total</th>
							<th>Hapus</th>
						</tr>
						<?php $no=1; $total = 0; foreach($bhp_list as $bl){ 
							$total += $bl->harga;
						?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= $bl->nama_obat ?></td>
							<td><?= $bl->jumlah ?> <?= $bl->satuan?></td>
							<td><?= $bl->harga ?> </td>
							<td><a class='btn btn-danger btn-xs' href='<?= Url::to(['hapus-bhp?id='.$bl->id])?>'>Hapus</a></td>
						</tr>
						<?php } ?>
						<tr>
							<td colspan='3' align=right>Jumlah Total</td>
							<td><?= $total ?></td>
						</tr>
					</table>
					<hr>
				<?php } ?>
					<a class='btn btn-success btn-sm' href='<?= Url::to(['/operasi/bhp-selesai?id='.$model->id])?>'>Selesai</a>
				</div>
			</div>
		</div>
		</div>
	</div>
</div>