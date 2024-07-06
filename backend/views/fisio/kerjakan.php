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
				<table class='table table-bordered'>
					<tr>
						<th>Tindakan</th>
						<th>Penanggung</th>
					</tr>
					<tr>
						<td><?= $fisio->pemeriksaan->nama_tarif?></td>
						<td><?= $fisio->bayar->bayar?></td>
					</tr>
				</table>
				<?php $form = ActiveForm::begin(); ?>
					<?= $form->field($fisio, 'idtindakan')->hiddenInput(['maxlength' => true])->label(false); ?>
					<?= $form->field($fisio, 'iddokter')->dropDownList(ArrayHelper::map(Dokter::find()->all(), 'id', 'nama_dokter'),['prompt'=>'- Dokter -'])->label('Dokter')?>
					<?= $form->field($fisio, 'keterangan')->TextArea(['maxlength' => true,'rows'=>6])->label('Deskripsi Tindakan'); ?>
					
					<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
				<?php ActiveForm::end(); ?>
			</div>
		</div>
	</div>
</div>