<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use common\models\RawatBayar;
use common\models\Dokter;
$rawat_bayar = RawatBayar::find()->all();
$rawat_dokter = Dokter::find()->all();
if($model->idjenisrawat == 1){
    $urlTindakan = Yii::$app->params['baseUrl'].'dashboard/rest/tarif-rajal?idjenis='.$model->idjenisrawat.'&idpoli='.$model->idpoli;
}else{
    $urlTindakan = Yii::$app->params['baseUrl'].'dashboard/rest/tarif-rawat?idjenis='.$model->idjenisrawat;
}
// $urlRadiologi = "http://localhost/simrs2021/dashboard/rest/list-radiologi";
// $urlLab = "http://localhost/simrs2021/dashboard/rest/list-lab";
$formatJs = <<< 'JS'
var formatTindakan = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.tindakan;   
    return marckup ;
};
var formatRepoTindakan = function (repo) {
    return repo.tindakan || repo.text;
}

JS;
 
// Register the formatting script
$this->registerJs($formatJs, View::POS_HEAD);
 
// script to parse the results into the format expected by Select2
$resultsJs = <<< JS
function (data) {    
    return {
        results: data,
        
    };
}
JS;
?>
<div class='row'>
	<div class='col-md-4'>
		<div class='box'>
			<div class='box-header with-border'><h3><a href='<?= Url::to(['/poliklinik/'.$model->id])?>' class='btn btn-default'><i class="fa fa-backward"></i> </a> Data Pasien</h3></div>
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
		<div class='box'>
			<div class='box-header'><h3>Tarif Tindakan</h3></div>
			<div class='box-body'>
				<div class='row'>
					<div class='col-md-5'>
						<?php $form = ActiveForm::begin(); ?>
							<?= $form->field($soaptindakan, 'idrawat')->hiddeninput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
							<?= $form->field($soaptindakan, 'idbayar')->hiddeninput(['maxlength' => true,'value'=>$model->idbayar])->label(false) ?>
							<?= $form->field($soaptindakan, 'tgltindakan')->hiddeninput(['maxlength' => true,'value'=>date('Y-m-d')])->label(false) ?>
							<?= $form->field($soaptindakan, 'idkunjungan')->hiddeninput(['maxlength' => true,'value'=>$model->idkunjungan])->label(false) ?>
							<?= $form->field($soaptindakan, 'iduser')->hiddeninput(['maxlength' => true,'value'=>Yii::$app->user->identity->id])->label(false) ?>
							<?= $form->field($soaptindakan, 'idtindakan')->widget(Select2::classname(), [
								'name' => 'kv-repo-template',
								'options' => ['placeholder' => 'Tindakan Dokter .....'],
								'pluginOptions' => [
								'allowClear' => true,
								'minimumInputLength' => 3,
								'ajax' => [
								'url' => $urlTindakan,
								'dataType' => 'json',
								'delay' => 250,
								'data' => new JsExpression('function(params) { return {q:params.term};}'),
								'processResults' => new JsExpression($resultsJs),
								'cache' => true
								],
								'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
								'templateResult' => new JsExpression('formatTindakan'),
								'templateSelection' => new JsExpression('formatRepoTindakan'),
								],
							])->label('Tindakan Dokter');?>
							<select class='form-control' id='jenis-bayar' name='bayar' required>
								<option value='0'>Jenis Bayar</option>
								<?php foreach($rawat_bayar as $rb): ?>
								<option value='<?= $rb->id ?>'><?= $rb->bayar ?></option>
								<?php endforeach; ?>
								
							</select>
							<p class='text-red'><b>*Kosongkan jika pelayanan dengan penanggungyang sama </b></p>
					
							<select class='form-control' id='dokter-bayar' name='dokter' required>
								<option value='0' >-- Dokter --</option>
								<?php foreach($rawat_dokter as $rd): ?>
								<option value='<?= $rd->id ?>'><?= $rd->nama_dokter ?></option>
								<?php endforeach; ?>
								
							</select>
							<p class='text-red'><b>*Kosongkan jika pelayanan dengan dokter yang sama</b></p>
							<hr>
							<?= $form->field($soaptindakan, 'keterangan')->textinput(['maxlength' => true]) ?>

						<div class="modal-footer">
						<?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm2']) ?>
						</div>
						<?php ActiveForm::end(); ?>
					</div>
					<div class='col-md-7'>
					<hr>
						<table class='table table-bordered'>
							<tr>
								<th>Tindakan</th>
								<th>#</th>
							</tr>
							<?php foreach($tindakan as $t): ?>
							<tr>
								<td><?= $t->tindakan->nama_tarif ?></td>
								<td><a href='<?= Url::to(['poliklinik/hapus-tindakan?id='.$t->id])?>' class='btn btn-xs btn-danger'>Hapus</a></td>
							</tr>
							<?php endforeach; ?>
						</table>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</div>