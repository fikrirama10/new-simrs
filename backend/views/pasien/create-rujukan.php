<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use kartik\date\DatePicker;
use yii\widgets\DetailView;
$url = "http://localhost/simrs2021/dashboard/rest/faskes";
$formatJs = <<< 'JS'
var formatRepo = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.nama;   
    return marckup ;
};
var formatRepoSelection = function (repo) {
    return repo.nama || repo.text;
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
<br>
<div class="row">
	<div class="col-md-4">
		<div class="box">
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
								'value' => $pasien->usia_tahun.'thn, ',            
								
								'captionOptions' => ['tooltip' => 'Tooltip'],  // HTML attributes to customize label tag
							],
						],
						
				]) ?>
			</div>
		</div>
		<div class="box">
			<div class="box-body">
				<?= DetailView::widget([
					'model' => $rawat,
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
		</div>
	</div>
	<div class="col-md-8">
		<div class='box box-body'>
		<?php $form = ActiveForm::begin(); ?>
			<table class='table'>
				<tr>
					<td class='pd-top'><span class='pull-right' style='line-height:20px;'>Tgl Rujukan</span></td>
					<td colspan=2><?= $form->field($rujukan, 'tgl_kunjungan')->textinput(['maxlength' => true,])->label(false)?></td>
				</tr>
				<tr>
					<td class='pd-top'><span class='pull-right' style='line-height:20px;'>Rencana Kunjungan</span></td>
					<td colspan=2><?=	$form->field($rujukan, 'tgl_rujuk')->widget(DatePicker::classname(),[
					'type' => DatePicker::TYPE_COMPONENT_APPEND,
					'pluginOptions' => [
					'autoclose'=>true,
					'format' => 'yyyy-mm-dd'
					]
					])->label(false)?></td>
				</tr>
				<tr>
					<td class='pd-top'><span class='pull-right' style='line-height:20px;'>Dirujuk Ke</span></td>
					<td><?= $form->field($rujukan, 'kode_tujuan')->widget(Select2::classname(), [
						'name' => 'kv-repo-template',
						'options' => ['placeholder' => 'Faskes Tujuan'],
						'pluginOptions' => [
						'allowClear' => true,
						'minimumInputLength' => 3,
						'ajax' => [
						'url' => $url,
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
					])->label(false);?></td>
					<td><a class='btn btn-sm btn-success'><span class='fa fa-search'></span></a></td>
					
				</tr>
				<tr>
					<td class='pd-top'><span class='pull-right' style='line-height:20px;'>Jenis Rujukan</span></td>
					<td><?= $form->field($rujukan, 'jenis_rujukan')->dropDownList([ 'Penuh' => 'Penuh', 'Partial' => 'Partial', ], ['prompt' => 'Jenis Rujukan'])->label(false)?></td>
				</tr>
				<tr>
					<td class='pd-top'><span class='pull-right' style='line-height:20px;'>Diagnosa Rujukan</span></td>
					<td></td>
				</tr>
			</table>
			
			
			
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>