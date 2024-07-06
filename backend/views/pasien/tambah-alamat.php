<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use yii\widgets\DetailView;
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.nama + " - " + repo.Kec + " - " + repo.Kab;   
    return marckup ;
};
var formatRepoSelection = function (repo) {
    return repo.nama || repo.text;
}
JS;
$this->registerJs($formatJs, View::POS_HEAD);
$resultsJs = <<< JS
function (data) {    
    return {
        results: data,
        
    };
}
JS;
?>
	<?php $form = ActiveForm::begin(); ?>
	
	<div id='alamat' class='col-md-4'>
		<div class='box box-success'>
			<div class='box-header with-border'><h4>Tambah Alamat Pasien</h4></div>
			<div class='box-body'>
				<?= $form->field($alamat, 'idkel')->widget(Select2::classname(), [
					'name' => 'kv-repo-template',
					'options' => ['placeholder' => 'Pilih Kelurahan'],
					'pluginOptions' => [
					'allowClear' => true,
					'minimumInputLength' => 3,
					'ajax' => [
					'url' => "https://simrs.rsausulaiman.com/api/alamat",
					'dataType' => 'json',
					'delay' => 250,
					'data' => new JsExpression('function(params) { return {q:params.term};}'),
					'processResults' => new JsExpression($resultsJs),
					'cache' => true
					],
					'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
					'templateResult' => new JsExpression('formatRepo'),
					'templateSelection' => new JsExpression('formatRepoSelection'),
				],
				]);?>
				<?= $form->field($alamat, 'alamat')->textarea(['rows' => 5])->label('alamat') ?>
				<?= $form->field($alamat, 'kodepos')->textInput(['maxlength' => true])?>
				
			</div>
			<div class='box-footer'>
				<div class="form-group">
					<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
				</div>
			</div>
				
		</div>
	</div>
	<?php ActiveForm::end(); ?>
	<div id='alamat' class='col-md-4'>
		<div class='box box-body'>
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'no_rm',
					'nik',
					'no_bpjs',
					'nama_pasien',
					'tgllahir',
					'tempat_lahir',
					'usia_tahun',
					'kepesertaan_bpjs',
					'pekerjaan.pekerjaan',
				],
			]) ?>
		</div>
	</div>