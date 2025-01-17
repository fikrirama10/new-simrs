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
use common\models\LaboratoriumPemeriksaan;
$urlLab = Yii::$app->params['baseUrl']."dashboard/rest/list-lab";
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

var formatTindakan = function (repo) {
    if (repo.loading) {
        return repo.text;
		
    }
    var marckup =repo.tindakan+' - Rp.'+ repo.tarif;   
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
<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($tambahPeriksa, 'idhasil')->hiddeninput(['maxlength' => true,'value'=>$labid])->label(false) ?>
	<?= $form->field($tambahPeriksa, 'idpemeriksaan')->dropDownList(ArrayHelper::map(LaboratoriumPemeriksaan::find()->all(), 'id', 'nama_pemeriksaan'),['prompt'=>'- Laboratorium -'])->label('Laboratorium')?>
	
	
	<div class="modal-footer">
		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		<?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm']) ?>
	</div>
<?php ActiveForm::end(); ?>