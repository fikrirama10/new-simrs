<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatStatus;
?>
<div class='row'>
	<div class='col-md-4'>
		<div class='box'>
			<div class='box-header with-border'><h3>Data Pasien</h3></div>
			<div class='box-body'>
			<?= DetailView::widget([
				'model' => $pasien,
				'attributes' => [
					'no_rm',
					'nik',
					'no_bpjs',
					[                                             
						'label' => 'Nama Pasien',
						'value' => Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan).'. '. $pasien->nama_pasien.' ('.$pasien->jenis_kelamin.')',
						'captionOptions' => ['tooltip' => 'Tooltip'], 
					],
					'tgllahir',
					'tempat_lahir',
					'nohp',
					[                                                  // the owner name of the model
						'label' => 'Usia Pasien',
						'value' => $pasien->usia_tahun.'thn, '. $pasien->usia_bulan.'bln, '. $pasien->usia_hari.'hr',            
						
						'captionOptions' => ['tooltip' => 'Tooltip'],  // HTML attributes to customize label tag
					],
					'hubungan.hubungan',
					'kepesertaan_bpjs',
					'pekerjaan.pekerjaan',
					'darah.golongan_darah',
				],
				
			]) ?>
			</div>
		</div>
	</div>
	<div class='col-md-8'>
		<div class='box'>
			<div class='box-header with-border'><h3>Edit Data Soap</h3></div>
			<div class='box-body'>
				<?php if($jenis == 1){ ?>
					<?= $this->render('_formdokter-edit',[
							'model' => $model,
							'pasien' => $pasien,
							'rawat' => $rawat,
					]) ?>
				<?php }else{ ?>
					<?= $this->render('_formperawat-edit',[
							'model' => $model,
							'pasien' => $pasien,
							'rawat' => $rawat,
					]) ?>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
