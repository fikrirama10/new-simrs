<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatJenis;
use common\models\Poli;
use yii\bootstrap\Modal;
use common\models\Rawat;
use common\models\ObatTransaksi;
use common\models\ObatTransaksiDetail;

$bpjs = ObatTransaksiDetail::find()->joinWith(['transaksi as tran'])->where(['tran.idkunjungan'=>$model->id])->andwhere(['idbayar'=>2])->sum('obat_transaksi_detail.total');
$kronis = ObatTransaksiDetail::find()->joinWith(['transaksi as tran'])->where(['tran.idkunjungan'=>$model->id])->andwhere(['idbayar'=>3])->sum('obat_transaksi_detail.total');
?>
<div class='row'>
	<div class='col-md-12'>
		<div class='box box-body box-primary'>
			<table class='table table-bordered'>
				<tr>
					<th>No Rawat</th>
					<th>SEP</th>
					<th>Jenis Rawat</th>
					<th>Poli</th>
					<th>Ruangan</th>
					<th>Penanggung</th>
					<th>Dokter</th>
					<th>Tgl Masuk</th>
					<th>Tgl Keluar</th>
				</tr>
				<?php foreach($rawat as $r){ ?>
				<tr>
					<td><a id='kunjungan<?= $r->id ?>' class='btn btn-default btn-xs'><?= $r->idrawat ?></a></td>
					<td><input type='hidden' id='input-kunjungan<?= $r->id ?>' value='<?= $r->id?>'></td>
					<td><?= $r->jenisrawat->jenis ?></td>
					<td><?= $r->poli->poli ?></td>
					<td><?= $r->ruangan->nama_ruangan ?></td>
					<td><?= $r->bayar->bayar ?></td>
					<td><?= $r->dokter->nama_dokter ?></td>
					<td><?= $r->tglmasuk ?></td>
					<td><?= $r->tglpulang ?></td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>
<div class='row'>
	<div class='col-md-3'>
				<div class="box box-widget widget-user">
			<div class="widget-user-header bg-purple-active">
				<h4 class="widget-user-username" id="lblnama"><?= Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan) ?>. <?= $pasien->nama_pasien?></h4>
				<p class="widget-user-desc" id="lblnoka"><?= $pasien->no_rm?></p>
				<input type="hidden" id="txtkelamin" value="L">
				<input type="hidden" id="txtkdstatuspst" value="0">
			</div>
	
	<!-- /.box-body -->
	
	<!-- /.box -->
	<!-- About Me Box -->
	
	<!-- /.box-header -->
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

					
					</ul>
				</div>
				<!-- /.tab-pane -->
			
			</div>
			<!-- /.tab-content -->
		</div>
		<div id="divriwayatKK" style="display: none;">
			<button type="button" id="btnRiwayatKK" class="btn btn-danger btn-block"><span class="fa fa-th-list"></span> Pasien Memiliki Riwayat KLL/KK/PAK <br><i>(klik lihat data)</i></button>
		</div>
	</div>
	<div class='box-footer'>
		<a class='btn btn-success' href='<?= Url::to(['/klaim'])?>'>Kembali</a>
	</div>
	<!-- /.box-body -->
</div>
	</div>
	<div class='col-md-5'>
		<div class='box box-body box-success'>
			<h4>Rincian Tindakan</h4>
			<?php foreach($rawat as $r){ ?>
			<?php 
			$urlInput = Url::to(['klaim/show-rincian']);
			$this->registerJs("
			$('#kunjungan{$r->id}').on('click',function(){
				
				$('#hidden-pelayanan').hide();
				kode = $('#input-kunjungan{$r->id}').val();
				$.ajax({
					type: 'GET',
					url: '{$urlInput}',
					data: 'id='+kode,
					
					success: function (data) {
						$('#riwayat-rincian').show();
						$('#riwayat-rincian').animate({ scrollTop: 0 }, 200);
						$('#riwayat-rincian').html(data);
						
						console.log(data);
						
					},
				
				});
					
			}) ;
		
		", View::POS_READY);
		?>
			<?php } ?>
			<div id='riwayat-rincian'></div>
			<table class='table table-bordered'>
				<tr>
					<th>No</th>
					<th>Nama Tindakan</th>
					<th>Jumlah</th>
					<th>Harga</th>
					<th>Total</th>
				</tr>
				<?php $no=1; $total=0; foreach($rincian as $m){ 
				$total += $m['total'];
				?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= $m['nama_tarif'] ?></td>
					<td><?= $m['jumlah'] ?> x</td>
					<td><?= Yii::$app->algo->IndoCurr(round($m['harga'])) ?></td>
					<td><?= Yii::$app->algo->IndoCurr(round($m['total'])) ?></td>
				</tr>
				<?php } ?>
				<tr>
					<td colspan=4><span class='pull-right'>Total Biaya</span></td>
					<td><?= Yii::$app->algo->IndoCurr(round($total)) ?></td>
				</tr>
			</table>
		</div>
		
	</div>
	<div class='col-md-4'>
			<div class='box box-body box-danger'>
				<h4>Rincian Obat</h4>
				<table class='table table-bordered'>
					<tr>
						<th>BPJS</th>
						<td><?= Yii::$app->algo->IndoCurr(round($bpjs)) ?></td>
					</tr>
					<tr>
						<th>Kronis</th>
						<td><?= Yii::$app->algo->IndoCurr(round($kronis)) ?></td>
					</tr>
				</table>
			</div>
		</div>
</div>