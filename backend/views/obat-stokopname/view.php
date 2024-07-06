<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\web\View;
$urlObat= Yii::$app->params['baseUrl'].'dashboard/rest/list-obat';
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
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Obat Stok Opnames', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="row">
<div class="obat-stok-opname-view col-md-4">
	<input type='hidden' value='<?= Yii::$app->user->identity->userdetail->idgudang?>' id='idgudang'>
	<div class='box'>
		<div class='box-header'><h4>Stok Opname</h4></div>
		<div class='box-body'>
			<?= DetailView::widget([
				'model' => $model,
				'attributes' => [
					'kode_so',
					'tgl_so',
					'periode.periode',
					'selisih',
					'keterangan:ntext',
				],
			]) ?>
		</div>
	</div>
</div>
<?php if($model->status == 1){ ?>
<div class="obat-stok-opname-view col-md-8">
	<div class='box'>
		<div class='box-header with-border'><h4>List Stok Opname</h4></div>
		<div class='box-body'>
			<?php if($model->status == 1){ ?>
			<?php $form = ActiveForm::begin(); ?>
			<?= $form->field($detail, 'idbarang')->widget(Select2::classname(), [
				'name' => 'kv-repo-template',
				'options' => ['placeholder' => 'Obat .....'],
				'pluginOptions' => [
				'allowClear' => true,
				'minimumInputLength' => 3,
				'ajax' => [
				'url' => $urlObat,
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
			])->label('Jenis Obat');?>
			<div id='obat-batch'></div>
			<div id='lanjutan'>
				<div class='row'>
					<?= $form->field($detail, 'idbatch')->hiddeninput()->label(false) ?>
					<?= $form->field($detail, 'harga')->hiddeninput()->label(false) ?>
					<div class='col-md-3'><?= $form->field($detail, 'merk')->textinput() ?></div>
					<div class='col-md-3'><?= $form->field($detail, 'jumlah')->textinput() ?></div>
					<div class='col-md-3'>
					<label></label><br>
					<?= Html::submitButton('+', ['class' => 'btn btn-success btn-sm']) ?></div>
				</div>
			</div>
			<?php ActiveForm::end(); ?>
			<?php } ?>
		</div>
	</div>  
</div>
<?php } ?>
</div>
<div class="row">
<div class="col-md-12">
	<div class="box">
	<div class="box-header with-border"><h3>Data Batch Obat</h3></div>
	<div class="box-body">
		<?= $this->render('_dataSo', [
					'model' => $model,
					'dataProvider' => $dataProvider,
					'searchModel' => $searchModel,
				]) ?>
	</div>
	<div class="box-footer">
			<?php if($model->status == 1){ ?>
			<a href='<?= Url::to(['obat-stokopname/stok-adjustmen?id='.$model->id]) ?>' class='btn btn-success'>Cocokan Stok</a>
			<?php } ?>
	</div>
</div>
</div>
</div>
<?php
$urlShowAll = Url::to(['obat-stokopname/show-bacth']);
$this->registerJs("
	$('#obat-batch').hide();
	$('#lanjutan').hide();
	$('#obatstokopnamedetail-idbarang').on('change',function(){
			$('#obat-batch').show();
			id = $('#obatstokopnamedetail-idbarang').val();
			
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+id,
				beforeSend: function(){
				$('#loading').show();
				},
				success: function (data) {
					$('#obat-batch').show();
					$('#obat-batch').animate({ scrollTop: 0 }, 200);
					$('#obat-batch').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				$('#loading').hide();
				}
			});
		
	});

	
           
	

", View::POS_READY);


?>
