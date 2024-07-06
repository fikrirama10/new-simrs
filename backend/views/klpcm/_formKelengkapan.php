<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use kartik\checkbox\CheckboxX;
use yii\widgets\ActiveForm;
use common\models\KlpcmFormulir;

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

$klpcmfor = KlpcmFormulir::find()->all();
?>

<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($klpcm, 'idformulir')->widget(Select2::classname(), [
		'name' => 'kv-repo-template',
		'options' => ['placeholder' => 'Cari Formulir .....'],
		'pluginOptions' => [
		'allowClear' => true,
		'minimumInputLength' => 3,
		'ajax' => [
		'url' => "https://new-simrs.rsausulaiman.com/auth/listformulir",
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
	])->label('Formulir');?>
	
	 <?=	$form->field($klpcm, 'idtidaklengkap[]')->widget(Select2::className(),
			[
				'data'=> common\models\KlpcmFormulirDetail::getOptions(),
				'options' => [
					'tags' => true,
					'multiple' => true
				],
			]
		)->label('Tidak lengkap');
	?>
	
	<?= Html::submitButton('+', ['class' => 'btn btn-success btn-sm']) ?>
<?php ActiveForm::end(); ?>
<hr>
<table class='table table-bordered'>
	<tr>
		<th>#</th>
		<th>No</th>
		<th>Formulir</th>
		<th>Tidak lengkap</th>
	</tr>
	<?php $no=1; foreach($klpcm_list as $kl){ ?>
	<tr>
		<td width=10><a href='<?= Url::to(['klpcm/hapus-kelengkapan?id='.$kl->id])?>' class='btn btn-danger btn-xs'><span class='fa fa-trash'></span></a></td>
		<td><?= $no++ ?></td>
		<td><?= $kl->nama_formulir ?></td>
		<td>- 
		    <?php if($kl->idtidaklengkap == NULL){ echo '';}else{
		    echo $kl->pecah($kl->idtidaklengkap);
		    } ?>
		 </td>
	</tr>
	<?php } ?>
</table>