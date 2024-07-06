<?php
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatStatus;
use yii\bootstrap\Modal;
use common\models\ObatJenis;
use common\models\Obat;
use common\models\UsulPesanDetail;
use common\models\ObatBacth;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;
use yii\web\View;
$urlObat= "http://localhost/simrs2021/dashboard/rest/list-obat";
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
$this->title = $model->kode_up;
$this->params['breadcrumbs'][] = ['label' => 'Usul Pesans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="usul-pesan-view">
<br>
    <div class="row">
    	<div class="col-md-12">
    		<div class="box">
				<div class="box-header with-border"><h4>Usul Pesan</h4></div>
				<div class="box-body">
					 <?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'kode_up',
							'tgl_up',
							'permintaan.status',
							'jenis.bayar',
							'total_harga',
						],
					]) ?>
				</div>
				<div class="box-footer with-border">
					<?php if($model->status == 10) {?>
					<a id='confirm-aju'  href='<?= Url::to(['/usul-pesan/ajukan?id='.$model->id]);?>' class='btn btn-warning'>Ajukan</a>
					<a href='<?= Url::to(['/usul-pesan']);?>' class='btn btn-primary'>Kembali</a>
					<?php }else{ ?>
						<?php if($model->status == 1) { ?>
							<?php if(Yii::$app->user->identity->userdetail->managemen == 1){ ?>
							<a id='confirm-aju'  href='<?= Url::to(['/usul-pesan/setuju?id='.$model->id]);?>' class='btn btn-success'>Setujui</a>
							<a id='confirm-aju'  href='<?= Url::to(['/usul-pesan/tolak?id='.$model->id]);?>' class='btn btn-danger'>Tolak</a>
							<a href='<?= Url::to(['/usul-pesan']);?>' class='btn btn-primary'>Kembali</a>
							<?php }else{ ?>
							<a id='confirm-aju'  href='<?= Url::to(['/usul-pesan/edit-pengajuan?id='.$model->id]);?>' class='btn btn-warning'>Edit Pengajuan</a>
							<a href='<?= Url::to(['/usul-pesan']);?>' class='btn btn-primary'>Kembali</a>
						<?php } ?>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
			<?php if($model->status == 10){ ?>
			<?php $form = ActiveForm::begin(); ?>
			<div class="box">
				<div class="box-body">
					<div class="row">
					<div class="col-md-6">
					 <?= $form->field($obat, 'idobat')->widget(Select2::classname(), [
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
					])->label(false);?>
					<div id='obat-bacth'></div>
					<div id='lanjutan'>
						<?= $form->field($obat, 'idbacth')->hiddenInput()->label(false) ?>
						<?= $form->field($obat, 'idup')->hiddenInput(['value'=>$model->id])->label(false)?>
						<?= $form->field($obat, 'jumlah')->textInput(['required'=>true]) ?>
						<?= $form->field($obat, 'harga')->hiddenInput()->label(false) ?>
						<?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm3']) ?>
					</div>
					</div>
					</div>
				</div>
			</div>
			<?php ActiveForm::end(); ?>
			<?php } ?>
    	</div>
		<div class='col-md-12'>
			<?= GridView::widget([
				'panel' => ['type' => 'default', 'heading' => 'Poliklinik'],
				'dataProvider' => $dataProvider,
				'filterModel' => $searchModel,
				'showPageSummary' => true,
				'hover' => true,
				'bordered' =>false,
				'pjax'=>true,
				'panel' => [
				'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon glyphicon-list-alt"></i> '.$this->title.'</h3>',
				'type'=>'success',
				
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					[
						'attribute' => 'idobat', 
						'vAlign' => 'middle',
						'width' => '380px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->obat->nama_obat.' <a>('. $model->merk->merk .')</a>';
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(UsulPesanDetail::find()->where(['idup'=>$model->id])->all(), 'obat.id', 'obat.nama_obat'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Item'], // allows multiple authors to be chosen
						'format' => 'raw'
					],
					[
						'attribute' => 'jenis', 
						'vAlign' => 'middle',
						'width' => '180px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->jeniss->jenis;
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(ObatJenis::find()->all(), 'id', 'jenis'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Jenis'], // allows multiple authors to be chosen
						'format' => 'raw'
					],
					[
						'attribute' => 'jumlah',
						'hAlign' => 'right',
						'format' => ['decimal', 0],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
					[
						'attribute' => 'harga',
						'hAlign' => 'right',
						'format' => ['decimal', 2],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
					[
						'attribute' => 'total',
						'hAlign' => 'right',
						'format' => ['decimal', 2],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}',
						'buttons' => [
								
								'view' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-primary"><span class="fa fa-pencil"></span></span>', 
												Url::to(['usul-pesan/edit?id='.$model->id]));
									
								},
								
								
														
							
								
							],
					],
					
	
					
				],
			]); ?>
		</div>
    </div>
   

</div>
<?php
$urlShowAll = Url::to(['usul-pesan/show-batch']);
$this->registerJs("
	$('#confirm3').on('click', function(event){
			age = confirm('Yakin Untuk menyimpan data');
			if(age == true){
				 return true;
			} else {
				event.preventDefault();
			}
	});
	$('#confirm-aju').on('click', function(event){
			age = confirm('Yakin Untuk mengajukan UP ?');
			if(age == true){
				 return true;
			} else {
				event.preventDefault();
			}
	});
	$('#obat-bacth').hide();
	$('#lanjutan').hide();
	$('#usulpesandetail-idobat').on('change',function(){
			$('#obat-bacth').show();
			id = $('#usulpesandetail-idobat').val();
			jenis = {$model->jenis_up};
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+id+'&jenis='+jenis,
				beforeSend: function(){
				$('#loading').show();
				},
				success: function (data) {
					$('#obat-bacth').show();
					$('#obat-bacth').animate({ scrollTop: 0 }, 200);
					$('#obat-bacth').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				$('#loading').hide();
				}
			});
		
	});

	
           
	

", View::POS_READY);


?>