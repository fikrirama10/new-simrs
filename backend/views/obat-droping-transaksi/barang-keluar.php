<?php 
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatStatus;
use common\models\ObatKategori;
use yii\bootstrap\Modal;
use common\models\ObatJenis;
use common\models\ObatDropingTransaksiDetail;
use common\models\Obat;
use common\models\UsulPesanDetail;
use common\models\PenerimaanBarangDetail;
use common\models\ObatBacth;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;
use yii\web\View;
$urlObat= Yii::$app->params['baseUrl']."dashboard/rest/list-obat-droping";
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
<div class='row'>		
	<div class='col-md-12'>
		<div class="box">
			<div class="box-header with-border"><h4>Barang Keluar  (Obat Droping)</h4></div>
			<div class="box-body">
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'idtrx',
						'tgl',
						'jenis.jenis',
						'ketrangan',
					],
				]) ?>
			</div>
			<div class='box-footer'>
				<a class='btn btn-primary' href='<?= Url::to(['/obat-droping-transaksi'])?>'>Kembali</a>
				<a class='btn btn-primary' href='<?= Url::to(['/obat-droping-transaksi/print?id='.$model->id])?>'>Kembali</a>
			</div>
		</div>
	</div>
			<?php if($model->status == 1){ ?>
		<div class='col-md-12'>
			<?php $form = ActiveForm::begin(); ?>
			<div class="box">
				<div class="box-body">
					<div class="row">
					<div class="col-md-6">
					<div class="row">
					<div class="col-md-6">
					<?= $form->field($barang, 'idobat')->widget(Select2::classname(), [
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
					</div>
					</div>
					<div id='lanjutan'>

					</div>
					</div>
					</div>
				</div>
				<?= $form->field($barang, 'idtrx')->hiddenInput(['value'=>$model->id])->label(false)?>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
		<?php } ?>
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
						'filter' => ArrayHelper::map(ObatDropingTransaksiDetail::find()->where(['idtrx'=>$model->id])->all(), 'obat.id', 'obat.nama_obat'), 
						'filterWidgetOptions' => [
							'pluginOptions' => ['allowClear' => true],
						],
						'filterInputOptions' => ['placeholder' => 'Item'], // allows multiple authors to be chosen
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
						'class' => 'yii\grid\ActionColumn',
						'template' => '{edit-item}{hapus}',
						'buttons' => [
								
								'edit-item' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-primary"><span class="fa fa-pencil"></span></span>', 
												Url::to(['obat-droping-transaksi/edit-item?id='.$model->id]));
									
								},
								'hapus' => function ($url,$model) {
									
										return Html::a(
												'<span class="label label-danger"><span class="fa fa-trash"></span></span>', 
												Url::to(['obat-droping-transaksi/hapus?id='.$model->id]));
									
								},
								
								
														
							
								
							],
					],
					
	
					
				],
			]); ?>
			<?php if($model->status == 1){ ?>
				<a class='btn btn-warning' href='<?= Url::to(['barang-keluar-stok?id='.$model->id])?>'>Keluarkan Barang</a>
			<?php } ?>
		</div>
</div>
<?php
$urlShowAll = Url::to(['obat-droping-transaksi/show-batch']);
$this->registerJs("
	$('#confirm').on('click', function(event){
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
	$('#obatdropingtransaksidetail-idobat').on('change',function(){
			id = $('#obatdropingtransaksidetail-idobat').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+id,
				beforeSend: function(){
				$('#loading').show();
				},
				success: function (data) {
					$('#lanjutan').show();
					$('#lanjutan').animate({ scrollTop: 0 }, 200);
					$('#lanjutan').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				$('#loading').hide();
				}
			});
	});
	$('#obatdropingtransaksidetail-idobat').on('change',function(){
			
			$('#lanjutan').show();
			
	});

	
           
	

", View::POS_READY);


?>