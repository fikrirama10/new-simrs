<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\Rawat;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
?>
<div class='row'>
	<div class='col-md-4'>
		<div class='box box-body'>
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
		<div class='box box-body'>
			<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'kode_resep',
						'tgl',
						'jenisrawat.jenis',
					],
			]) ?>
		</div>
	</div>
	<div class='col-md-8'>
		<div class='box'>
			<div class='box-header'><h4>List Obat</h4></div>
			<div class='box-body'>
			<a href='<?= Url::to(['resep/etiket?id='.$model->id])?>' target='_blank' class='btn btn-default btn-xs'>Etiket</a>
			<a href='<?= Url::to(['resep/faktur?id='.$model->id])?>' class='btn btn-default btn-xs'>Faktur</a>
				<table class='table table-bordered'>
					<tr>
						<th>No</th>
						<th>Nama Obat (merk)</th>
						<th>Jenis</th>
						<th>Jumlah</th>
						<th>Harga</th>
						<th>Total</th>
					</tr>
					<?php $no=1; foreach($transaksi as $trx){ ?>
					<tr>
						<td><?= $no++  ?></td>
						<td><?= $trx->obat->nama_obat ?> (<?= $trx->bacth->merk ?>)</td>
						<td><?= $trx->jenis->bayar ?></td>
						<td><?= $trx->qty ?></td>
						<td><?= $trx->harga ?></td>
						<td><?= $trx->total ?></td>
					</tr>
					<?php } ?>
				</table>
				<HR>
				<a href='<?= Url::to(['resep/list-resep']) ?>' class='btn btn-success'>Kembali</a>
			</div>
		
		</div>
	</div>
</div>