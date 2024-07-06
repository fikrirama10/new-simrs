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
use common\models\LaboratoriumHasildetail;

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
						],
						
					]) ?>
			</div>
			<div class="box-footer"><a href='<?= Url::to(['/laboratorium'])?>' class='btn btn-info btn-sm'>Selesai</a></div>
		</div>
	</div>
	<div class="col-md-8">
		<div class="box">
			<div class="box-header with-border"><h3> Pengantar Laboratorium</h3></div>
			<div class="box-body">
				<table class='table table-bordered'>
					<tr>
						<th>No</th>
						<th>Pemeriksaan</th>
						<th>Catatan</th>
						<th>Tanggal Permintaan</th>
						<th>Dokter Pengirim</th>
						<th>#</th>
					</tr>
					<?php $no=1; if(count($soaplab) < 1){ ?>
					<tr>
						<td colspan=6>Tidak ada permintaan Lab</td>
					</tr>
					
					<?php }else {
							foreach($soaplab as $sl):
					?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $sl->pemeriksaan->nama_pemeriksaan ?></td>
						<td><?= $sl->catatan ?></td>
						<td><?= date('Y-m-d',strtotime($sl->tgl_permintaan)) ?></td>
						<td><?= $sl->dokter->nama_dokter ?></td>
						<td><a href='<?= Url::to(['laboratorium/hapus-soap?id='.$sl->id])?>' class='btn btn-xs btn-danger'>Hapus</a></td>
						
					</tr>
					
					<?php endforeach; } ?>
					<tr>
						<td colspan=5><a data-toggle="modal" data-target="#mdTemplate" class='btn btn-primary'>Tambah Pemeriksaan</a></td>
					</tr>
				</table>
			</div>
		</div>
		<div class="box">
			<div class="box-body">
			
				<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($hasil, 'iddokter')->dropDownList(ArrayHelper::map(Dokter::find()->where(['idspesialis'=>8])->all(), 'id', 'nama_dokter'),['prompt'=>'- Dokter -','required'=>true])->label('Dokter')?>
					<?= $form->field($hasil, 'idpetugas')->dropDownList(ArrayHelper::map(UserDetail::find()->where(['idunit'=>4])->all(), 'id', 'nama'),['prompt'=>'- Petugas Laboratorium -','required'=>true])->label('Petugas Laboratorium')?>
				<div class="form-group">
				<?= Html::submitButton('Kerjakan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
				</div>
				<?php ActiveForm::end(); ?>
				
				<hr>
				<?php foreach($hasillab as $hl): 
				$detaillab = LaboratoriumHasildetail::find()->where(['idhasil'=>$hl->id])->all();
				?>
				<table class='table table-bordered'>
					<tr><td colspan=3><a href='<?= Url::to(['laboratorium/hasil-print?id='.$hl->id])?>' target='_blank' class='btn btn-warning btn-xs'><i class='fa fa-print'></i></a> <a href='<?= Url::to(['laboratorium/kwitansi?id='.$hl->id])?>' target='_blank' class='btn btn-success btn-xs'>Kwitansi</a>
					<a class='btn btn-default btn-xs' data-toggle="modal" data-target="#mdTemplate<?= $hl->id?>">Tambah Layanan</a>
					<a href='<?= Url::to(['laboratorium/edit-pelayanan?id='.$hl->id])?>'  class='btn btn-info btn-xs'>Edit</a>
					<a href='<?= Url::to(['laboratorium/hapus-pelayanan?id='.$hl->id])?>'  class='btn btn-danger btn-xs'>Hapus</a>
					</td></tr>
					<tr><td colspan=3><?= $hl->tgl_hasil?></td></tr>
					<tr>
						<th>Pemeriksaan</th>
						<th>Status</th>
						<th>#</th>
					</tr>
					<?php foreach($detaillab as $dl){ ?>
					<tr>
						<td width=300><?= $dl->pemeriksaan->nama_pemeriksaan ?></td>
						<td width=200px>
							<?php if($dl->status < 2){ echo'Hasil belum di input';}else{
								echo'Hasil sudah terinput';
							} ?>
						</td>
						<td>
						<a href='<?= Url::to(['/laboratorium/input-hasil?id='.$dl->id])?>' class='btn btn-xs btn-primary'>Input Hasil</a> 
						<a href='<?= Url::to(['/laboratorium/hapus-pemeriksaan?id='.$dl->id])?>' class='btn btn-xs btn-danger'>Hapus Hasil</a> 
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
$this->registerJs("
	
	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data??');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	
	

", View::POS_READY);
	

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
foreach($hasillab as $hl){
	Modal::begin([
		'id' => 'mdTemplate'.$hl->id,
		'header' => '<h3>Input Pemeriksaan Tambahan </h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formTambahan',['model'=>$model,'labid'=>$hl->id,'tambahPeriksa'=>$tambahPeriksa]).'</div>';
	 
	Modal::end();
}
?>
