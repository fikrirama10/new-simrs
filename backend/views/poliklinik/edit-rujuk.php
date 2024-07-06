<?php 
use kartik\checkbox\CheckboxX;
use kartik\time\TimePicker;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use common\models\RawatRujukan;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use common\models\SoapRajaldokter;
use common\models\SoapRajalperawat;
use common\models\SoapRajalicdx;
?>
<div class='row'>
	<div class='col-md-4'>
		<div class='box'>
			<div class='box-header with-border'><h3> Data Pasien</h3></div>
			<div class='box-body with-border'>
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
		<div class='box box-body'>
		<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($model, 'tujuan_rujuk')->textInput(['required'=>true])?>
		<?= $form->field($model, 'diagnosa_klinis')->textInput(['required'=>true])->label('Diagnosa Rujuk')?>
		<?= $form->field($model, 'tgl_kunjungan')->hiddenInput(['maxlength' => true,'value'=>$model->tgl_kunjungan])->label(false) ?>
		<?=	$form->field($model, 'tgl_rujuk')->widget(DatePicker::classname(),['type' => DatePicker::TYPE_COMPONENT_APPEND,
			'pluginOptions' => [
			'autoclose'=>true,
			'format' => 'yyyy-mm-dd',
			'required'=>true
		]])?>
		<?= $form->field($model, 'alasan_rujuk')->textarea(['rows' => 2,'required'=>true])?>
		<div class="form-group">
			<?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
		</div>

    <?php ActiveForm::end(); ?>
	</div>
	</div>
	</div>