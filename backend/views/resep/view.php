<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\ObatTransaksiDetail;
/* @var $this yii\web\View */
/* @var $model common\models\ObatTransaksi */

$this->title = $model->kode_kunjungan;
$this->params['breadcrumbs'][] = ['label' => 'Obat Transaksis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="obat-transaksi-view">
	<div class="row">
		<div class="col-md-4">
			<div class='box'>
			<div class='box-header with-border'><h4>Data Pasien</h4></div>
			<div class='box-body'>
				<?= DetailView::widget([
					'model' => $pasien,
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
		<div class="col-md-8">
			<div class="box">
				<div class="box-header with-border"><h4>List Perawatan</h4></div>
				<div class="box-body">
					<table class='table table-bordered'>
						<tr>
							<th>No</th>
							<th>Jenis Rawat</th>
							<th>Poli / Ruangan</th>
							<th>Tgl Masuk</th>
							<th>Tgl Pulang</th>
							<th>Resep</th>
						</tr>
						<?php $no=1; foreach($rawat as $r): ?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= $r->jenisrawat->jenis ?></td>
							<td>
								<?php if($r->idjenisrawat == 2){echo $r->ruangan->nama_ruangan;}else{echo $r->poli->poli;} ?>
							</td>
							<td><?= $r->tglmasuk ?></td>
							<td><?= $r->tglpulang ?></td>
							<td><a href='<?= Url::to(['resep/add-resep?id='.$model->id.'&idrawat='.$r->id]);?>' class='btn btn-success btn-xs'>Tambah resep</a></td>
						</tr>
						<?php endforeach; ?>
					</table>
					<hr>
					<h4>Histori Pemberian Obat</h4>
					<table class='table table-bordered'>
						<tr>
							<th>No</th>
							<th>Nomor Transaksi</th>
							<th>Tgl</th>
							<th>Obat</th>
							<th>Jumlah</th>
							<th>Total Harga</th>
							<th>Jenis</th>
						</tr>
						<?php $no=1; $total_all=0; foreach($resep as $r){ 
						$detail = ObatTransaksiDetail::find()->where(['idtrx'=>$r->id])->all();
						?>
						<tr>
							<td rowspan=<?= count($detail) + 1?>><?= $no++?></td>
							<td rowspan=<?= count($detail) + 1?>><?= $r->kode_resep ?></td>
							<td rowspan=<?= count($detail) + 1?>><?= $r->tgl ?></td>
						</tr>
							<?php $no2=1; $qty=0; $total=0; foreach($detail as $lo){ 
							$qty += $lo->qty;
							$total += $lo->total;
							?>
							<tr>
								<td><?= $lo->obat->nama_obat ?> (<?= $lo->bacth->merk ?>)</td>
								<?php if($r->status == 1){ ?>
								<td width=100><input readonly type='number' id='qty<?= $lo->id ?>' class='form-control' value='<?= $lo->qty ?>'></td>
								<?php }else{ ?>
								<td width=100><?= $lo->qty ?></td>
								<?php } ?>
								<td><?= $lo->total?></td>
								<td><?= $lo->jenis->bayar ?></td>
							</tr>
							<?php } ?>
							<?php $total_all += $total; ?>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<th>Qty per transaksi</th>
								<th><?= $qty?></th>
								<th><?= $total?></th>
								<td></td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<th>Jasa Racik</th>
								<th><?= $r->jumlahracik?></th>
								<th><?= $r->jasa_racik?></th>
								<td></td>
							</tr>
						<?php } ?>
						
					</table>
				</div>
				
			</div>
		</div>
	</div>
</div>
