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
<div class="row">
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
	<div class="col-md-8">
		<div class="box">
			<div class="box-header"><h3> Pengantar Radiologi</h3></div>
			<div class="box-body">
				
					<table class='table table-hovered'>
						<tr>
							<th>No</th>
							<th>Klinis</th>
							<th>Tindakan</th>
							<th>Catatan</th>
							<th>Tgl Permintaan</th>
							<th>Dokter Pengirim</th>
						</tr>
						<?php $no=1; foreach($soapradiologi as $sr){ ?>
						<tr>
							<td><?= $no++ ?></td>
							<td><?= $sr->klinis ?></td>
							<td><?= $sr->tindakan->nama_tindakan ?></td>							
							<td><?= $sr->catatan ?></td>
							<td><?= date('d/m/Y',strtotime($sr->tgl_permintaan)) ?></td>
							<td><?= $sr->dokter->nama_dokter ?></td>
						</tr>
						<?php } ?>
						<tr>	

									<?php if($model->idjenisrawat != 2){ ?>
									<td colspan=6><a data-toggle="modal" data-target="#mdTemplate" class='btn btn-primary'>Tambah Pemeriksaan</a></td>
								
								<?php }else if($model->idjenisrawat == 2){ ?>
									<td colspan=6><a data-toggle="modal" data-target="#mdTemplate" class='btn btn-primary'>Tambah Pemeriksaan</a></td>
								<?php } ?>
					</table>
					<br>
					
					<hr>
					<?php $form = ActiveForm::begin(); ?>
						<?= $form->field($hasil, 'iddokter')->dropDownList(ArrayHelper::map(Dokter::find()->where(['idspesialis'=>6])->all(), 'id', 'nama_dokter'),['prompt'=>'- Dokter -','required'=>true])->label('Dokter')?>
						<?= $form->field($hasil, 'idpetugas')->dropDownList(ArrayHelper::map(UserDetail::find()->where(['idunit'=>3])->all(), 'id', 'nama'),['prompt'=>'- Petugas Radiologi -','required'=>true])->label('Petugas Radiologi')?>
						<div class="form-group">
							<?= Html::submitButton('Kerjakan', ['class' => 'btn btn-success']) ?>
						</div>
					<?php ActiveForm::end(); ?>
					<?php foreach($hasilrad as $hr): 
						$detailrad = RadiologiHasildetail::find()->where(['idhasil'=>$hr->id])->orderBy(['tgl_hasil'=>SORT_ASC])->all();
					?>	<hr>
						<table class='table table-borderd'>
							<tr>
								<th>Pemeriksaan</th>
								<th>Klinis</th>
								<th>#</th>
							</tr>
							<tr>
								<td colspan=2><?= $hr->tgl_hasil?></td>
								<td>
									<a href='<?= Url::to(['/radiologi-order/batalkan?id='.$hr->id])?>' class='btn btn-default btn-xs'>Tambah Pemeriksaan</a>
									<?php if($hr->status == 1){ ?>
										<a href='<?= Url::to(['/radiologi-order/batalkan?id='.$hr->id])?>' class='btn btn-danger btn-xs'>Batalkan</a>
									<?php }else if(count($detailrad) < 1){ ?>
										<a href='<?= Url::to(['/radiologi-order/batalkan?id='.$hr->id])?>' class='btn btn-danger btn-xs'>Batalkan</a>
									<?php } ?>
								</td>
							</tr>
							<?php foreach($detailrad as $dr){ ?>
							<tr>
								<td width=300><?= $dr->tindakan->nama_tindakan ?></td>
								<td width=200><?= $dr->klinis?></td>
								<td><a href='<?= Url::to(['/radiologi-order/delete-periksa?id='.$dr->id])?>' class='btn btn-danger btn-xs'>Delete</a> 
								<a href='<?= Url::to(['/radiologi-order/baca?id='.$dr->id])?>' class='btn btn-primary btn-xs'>Baca</a> 
								<a href='<?= Url::to(['/radiologi-order/upload-foto?id='.$dr->id])?>' class='btn btn-warning btn-xs'>Upload Foto</a>
								
								<?php if($dr->status > 1){echo "<a href=".Url::to(['radiologi-order/hasil-print?id='.$dr->id])." class='btn btn-success btn-xs'>Print</a>";} ?>
								</td>
							</tr>
							<?php } ?>
							
						</table>
						<?php endforeach; ?>
			
		
			</div>
		</div>
	</div>
</div>
<?php 
Modal::begin([
	'id' => 'mdTemplate',
	'header' => '<h3>Input Pemeriksaan</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formTambah', ['tambahSoap'=>$tambahSoap,'tambahPeriksa'=>$tambahPeriksa,'model'=>$model ]).'</div>';
 
Modal::end();
?>