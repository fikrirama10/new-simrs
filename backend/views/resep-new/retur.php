<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use common\models\ObatTransaksiDetail;
use common\models\ObatRacik;
use common\models\ObatRacikDetail;
use yii\bootstrap\Modal;
?>

<div class='row'>
	<div class='col-md-8'>
		<div class='box'>
			<div class='box-header with-border'><h4>Data Pasien</h4></div>
			<div class='box-body'>
				<table>
					<tr>
						<td><b>Nama Pasien</b> <input type='text' value='<?= $model->pasien->nama_pasien?>' disabled></td>
						<td><b>Tgl Lahir</b> <input type='text' value='<?= $model->pasien->tgllahir?>' disabled></td>
						
						<td><b>Usia</b> <input type='text' value='<?= $model->pasien->usia_tahun?>th <?= $model->pasien->usia_bulan?>bln <?= $model->pasien->usia_hari?>hr' disabled></td>
					</tr>
					<tr>
						<td><b>Tgl Kunjungan</b> <input type='text' value='<?= $model->tglmasuk?>' disabled></td>
						<td><b>Jenis Kunjungan</b> <input type='text' value='<?= $model->jenisrawat->jenis?>' disabled></td>
						<td><b>Ruangan</b> <input type='text' value='<?= $model->ruangan->nama_ruangan?>' disabled></td>
					</tr>
					<tr>
						<td><b>Dokter</b> <input type='text' value='<?= $model->dokter->nama_dokter?>' disabled></td>
						<td><b>Poli</b> <input type='text' value='<?= $model->poli->poli?>' disabled></td>
						<td><b>Penanggung</b> <input type='text' value='<?= $model->bayar->bayar?>' disabled></td>
					</tr>
				</table>
			</div>
			<div class='box-footer'>
				<div class='box-footer'>
					<a href='<?= Url::to(['resep-new/view?id='.$model->id])?>' class='btn btn-primary pull-right'>Kembali</a>
				</div>
			</div>
		</div>
		<div class='box box-danger'>
			<div class='box-header with-border'><h4><?= $resep->kode_resep ?></h4></div>
			<div class='box-body'>
				<table class='table table-bordered'>
					<tr style='font-size:10px;'>
						<td width=10>No</td>
						<td>Obat</td>
						<td>Signa</td>
						<td>Jumlah</td>
						<td>Harga</td>
						<td>Total</td>
						<td>#</td>
					</tr>
					<?php $data_resep = ObatTransaksiDetail::find()->where(['idtrx'=>$resep->id])->all(); 
						$no=1;
						foreach($data_resep as $dr){ ?>
						<tr>
							<td><?= $no++ ?></td>
							<td>
								<?= $dr->nama_obat ?> (<?= $dr->signa?> <?= $dr->diminum ?> <?php if($dr->diminum != ''){echo'makan';} ?>  <?= $dr->keterangan?>)
								<?php if($dr->idbayar == 1){echo'<span class="label label-default">Pribadi</span>';}else if($dr->idbayar == 2){echo'<span class="label label-success">BPJS</span>';}else if($dr->idbayar == 3){echo'<span class="label label-warning">Kronis</span>';}else{echo'<span class="label label-warning">Covid</span>';}  ?>
							</td>
							<td><?= $dr->satuan_obat ?></td>
							<td><?= $dr->qty ?></td>
							<td><?= $dr->harga ?></td>
							<td><?= $dr->total ?></td>
							<td><a data-toggle="modal" data-target="#retur<?= $dr->id ?>"   class='btn btn-danger btn-xs'>Retur</a></td>
						</tr>
						
						<?php }?>
				</table>
			</div>
			<div class='box-footer'>
				
			</div>
		</div>
		
	</div>
	<div class='col-md-4'>
		<div class='box box-body'>
			<h4>Data Racik</h4>
			<table class='table table-bordered'>
				<tr>
					<td>No</td>
					<td>Id Racik</td>
					<td>Tgl</td>
					<td>Harga</td>
					<td>Hapus</td>
				</tr>
				<?php $no=1; foreach($racik as $rck){ ?>
				<tr>
					<td><?= $no++ ?></td>
					<td><?= $rck->kode_racik ?></td>
					<td><?= $rck->tgl ?></td>
					<td>Rp. 10.000</td>
					<td><a href='<?= Url::to(['resep-new/retur-racik?id='.$rck->id.'&idresep='.$resep->id])?>' class='btn btn-danger btn-xs'>Hapus</a></td>
				</tr>
				<?php } ?>
			</table>
		</div>
	</div>
</div>
<?php
	foreach($data_resep as $dr){
		Modal::begin([
			'id' => 'retur'.$dr->id,
			'header' => '<h3>Retur Obat</h3>',
			'size'=>'modal-lg',
			'options'=>[
				'data-url'=>'transaksi',
				'tabindex' => ''
			],
		]);

		echo '<div class="modalContent">'.$this->render('_formRetur', ['id'=>$dr->id,'retur'=>$retur]).'</div>';
		 
		Modal::end();
	}
?>