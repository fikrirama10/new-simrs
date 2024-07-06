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
use common\models\Dokter;
use common\models\UserDetail;
use common\models\RadiologiHasildetail;

?>
<div class='row'>
	<div class="col-md-4">
		<div class="box">
			<div class="box-header"><h3> Data Pasien</h3></div>
			<div class="box-body">
				<?= DetailView::widget([
						'model' => $pasien,
						'attributes' => [
							'no_rm',
							[                                             
								'label' => 'Nama Pasien',
								'value' => Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan).'. '. $pasien->nama_pasien.' ('.$pasien->jenis_kelamin.')',
								'captionOptions' => ['tooltip' => 'Tooltip'], 
							],
							'tgllahir',
							'nohp',
							[                                                  // the owner name of the model
								'label' => 'Usia Pasien',
								'value' => $pasien->usia_tahun.'thn, '. $pasien->usia_bulan.'bln, '. $pasien->usia_hari.'hr',            
								
								'captionOptions' => ['tooltip' => 'Tooltip'],  // HTML attributes to customize label tag
							],
						],
						
				]) ?>
			</div>
			<div class="box-footer"><a href='<?= Url::to(['/radiologi-order'])?>' class='btn btn-info btn-sm'>Selesai</a></div>
		</div>
		<div class="box">
			<div class="box-header"><h3> Data Rawat</h3></div>
			<div class="box-body">
				<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'idkunjungan',
							'idrawat',
							'poli.poli',
							'dokter.nama_dokter',
							'jenisrawat.jenis',
							'tglmasuk',
							'bayar.bayar',
						],
						
					]) ?>
			</div>
		</div>
	</div>
	<div class='col-md-8'>
		<div class='box box-primary'>
			<div class='box-header with-border'>Tindakan Fisio</div>
			<div class='box-body'>
				<a data-toggle="modal" data-target="#mdFisio" id='tindakanFisio' class='btn btn-xs btn-warning'>Tambah Fisio</a>
				<hr>
				<table class='table table-bordered'>
					<tr>
						<th>No</th>
						<th>Dokter Pengirim</th>
						<th>Tindakan</th>
						<th>Bayar</th>
						<th>Dokter</th>
						<th>Keterangan</th>
						<th>#</th>
					</tr>
					<?php $no=1; foreach($fisio as $f){ ?>
					<tr>
						<td width=10><?= $no++ ?></td>
						<td width=130><?= $f->peminta->nama_dokter ?></td>
						<td width=250><?= $f->pemeriksaan->nama_tarif ?></td>
						<td><?= $f->bayar->bayar ?></td>
						<td width=230>
							<?php if($f->iddokter != null){echo $f->dokter->nama_dokter;} ?>
						</td>
						<td><?= $f->keterangan ?></td>
						<td>
							<?php if($f->status == 1){ ?>
								<a class='btn btn-xs btn-primary' href='<?= Url::to(['kerjakan?id='.$f->id])?>'>Kerjakan</a>
								<a class='btn btn-xs btn-danger' href='<?= Url::to(['hapus?id='.$f->id])?>'>Hapus</a>
							<?php }else{ ?>
								<a class='btn btn-xs btn-primary' href='<?= Url::to(['kerjakan?id='.$f->id])?>'>Lihat</a>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
</div>
<?php 
Modal::begin([
	'id' => 'mdFisio',
	'header' => '<h3>Fisio Terapi</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);
echo '<div class="modalContent">'. $this->render('_formFisio', ['model'=>$model,'fisio_tambah'=>$fisio_tambah]).'</div>';
Modal::end();
?>