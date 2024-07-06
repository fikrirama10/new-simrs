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
use common\models\Obat;
use common\models\ObatSatuan;
use common\models\UsulPesanDetail;
use common\models\PenerimaanBarangDetail;
use common\models\ObatBacth;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;
use yii\web\View;
$urlObat= "http://localhost/new-simrs/dashboard/rest/list-obat";
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
$this->title = $model->kode_penerimaan;
$this->params['breadcrumbs'][] = ['label' => 'Usul Pesans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="penerimaan-barang-view">
	<br>
	<div class='row'>		
		<div class='col-md-12'>
			<div class="box">
				<div class="box-header"></div>
				<div class="box-body">
					<?= DetailView::widget([
						'model' => $model,
						'attributes' => [
							'kode_penerimaan',
							'no_faktur',
							'nilai_faktur',
							'tgl_faktur',
							'bayar.bayar',
							'suplier.suplier',
						],
					]) ?>
				</div>
				<div class='box-footer'>
					<?= Html::a('<i class="fas fa-redo"></i> Batalkan', ['penerimaan-batal?id='.$model->id], ['class' => 'btn bg-orange','id'=>'confirm2']); ?>
					<a class='btn btn-success btn-sm' data-toggle='modal' data-target='#mdTambah'>+ Barang Baru</a>
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
					 <?= $form->field($barang, 'idbarang')->widget(Select2::classname(), [
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
					<?= $form->field($bacth, 'idbayar')->hiddenInput(['value'=>$model->idbayar])->label(false) ?>
					</div>
					</div>
					<div id='lanjutan'>
						<div id='row'>
							<div class='col-md-6'>
								<label>Nomer Bacth</label>
								<div class="input-group" id='manual'>
									<span class="input-group-addon">
										<input type="checkbox" id='cek_bacth'>
									</span>
										<input type="text" id='barang_bacth' class="form-control" name="ObatBacth[no_bacth]">
								</div>
								<div id='pilih'>
									
								</div>
							</div>
							<div class='col-md-6'>
								<?= $form->field($bacth, 'merk')->textInput()->label('Merk') ?>
								
							</div>
						</div>
						<div id='row'>
							<div class='col-md-6'>
								<?=	$form->field($bacth, 'tgl_produksi')->widget(DatePicker::classname(),[
									'type' => DatePicker::TYPE_COMPONENT_APPEND,
									'pluginOptions' => [
									'autoclose'=>true,
									'format' => 'yyyy-mm-dd',
									'required'=>true

									]
									])->label('Tgl Produksi')?>
							</div>
							<div class='col-md-6'>
								<?=	$form->field($bacth, 'tgl_kadaluarsa')->widget(DatePicker::classname(),[
									'type' => DatePicker::TYPE_COMPONENT_APPEND,
									'pluginOptions' => [
									'autoclose'=>true,
									'format' => 'yyyy-mm-dd',
									'required'=>true

									]
									])->label('Tgl ED')?>
							</div>
						</div>
						<div id='row'>
							
							<div class='col-md-6'>
							<div class='row'>
								<div class='col-md-6'>
								<?= $form->field($bacth, 'harga_beli')->textInput(['maxlength' => true,'required'=>true])->label('Harga Satuan') ?>
								</div>
								<div class='col-md-6'>
								<?= $form->field($barang, 'satuan')->dropDownList(ArrayHelper::map(ObatSatuan::find()->all(), 'id', 'satuan'),['prompt'=>'- Satuan Obat -','required'=>true])->label('Satuan Obat')?>
								</div>
								</div>
							</div>
						</div>
						
						<div id='row'>
							<div class='col-md-6'>
							<div class='row'>
								<div class='col-md-4'>
								<?= $form->field($barang, 'jumlah')->textInput() ?>
								</div>
								<div class='col-md-4'>
								<?= $form->field($barang, 'diskon')->textInput()->label('Diskon %') ?>
								</div>
								<div class='col-md-4'>
								<?= $form->field($barang, 'ppn')->textInput()->label('PPN') ?>
								</div>
								</div>
								
							</div>
							<div class='col-md-6'>
								<?= $form->field($bacth, 'kat_obat')->dropDownList(ArrayHelper::map(ObatKategori::find()->all(), 'id', 'kategori'),['prompt'=>'- Kategori Obat -','required'=>true])->label('Kategori Obat')?>
							</div>
						</div>
						
						<hr>
						<div id='row'>
							<div class='col-md-12'>
								<?= Html::submitButton('Tambah', ['class' => 'btn btn-success']) ?>
							</div>
							
						</div>
						
						
						<?= $form->field($barang, 'idpenerimaan')->hiddenInput(['value'=>$model->id])->label(false)?>
						
						
					</div>
					</div>
					</div>
				</div>
			</div>
			<?php ActiveForm::end(); ?>
		</div>
		<?php } ?>
		<div class='col-md-12'>
			<div class='box box-body'>
				<?php 
					if($model->status > 0){
						$masuk = Html::a('<i class="fas fa-redo"></i> Selesai', ['/penerimaan-barang/selesai?id='.$model->id], ['class' => 'btn bg-navy','id'=>'confirm']);
					}else{
						$masuk='';
					}
				?>
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
				'after'=>$masuk,
				
				
			],
				
				'columns' => [
					['class' => 'kartik\grid\SerialColumn'],
					
					[
						'attribute' => 'idbarang', 
						'vAlign' => 'middle',
						'width' => '380px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->obat->nama_obat;
						},
						'filterType' => GridView::FILTER_SELECT2,
						'filter' => ArrayHelper::map(PenerimaanBarangDetail::find()->where(['idpenerimaan'=>$model->id])->all(), 'obat.id', 'obat.nama_obat'), 
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
					'obat.satuan.satuan',
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
						'attribute' => 'diskon', 
						'vAlign' => 'middle',
						'width' => '80px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->diskon.' %';
						},
						
						'format' => 'raw'
					],
					[
						'attribute' => 'total_diskon',
						'hAlign' => 'right',
						'format' => ['decimal', 2],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
					[
						'attribute' => 'ppn', 
						'vAlign' => 'middle',
						'width' => '80px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->ppn;
						},
						
						'format' => ['decimal', 2],
					],
					[
						'attribute' => 'Total Bayar', 
						'vAlign' => 'middle',
						'width' => '80px',
						'value' => function ($model, $key, $index, $widget) { 
								return $model->ppn + $model->total_diskon;
						},
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM,
						'format' => ['decimal', 2],
					],
					[
						'class' => 'yii\grid\ActionColumn',
						'template' => '{view}{delete-barang}',
						'buttons' => [
								
								'view' => function ($url,$model) {
								if($model->penerimaan->status == 1){
								return Html::a(
												'<span class="label label-primary"><span class="fa fa-pencil"></span></span>', 
												Url::to(['penerimaan-barang/edit?id='.$model->id]));
								}
									
								},
								'delete-barang' => function ($url,$model) {
										if($model->penerimaan->status == 1){
										return Html::a(
												'<span class="label label-danger"><span class="fa fa-trash"></span></span>', 
												Url::to(['penerimaan-barang/delete-barang?id='.$model->id]));
										}
									
								},
								
								
								
														
							
								
							],
					],
					
	
					
				],
			]); ?>
			</div>
		</div>
	</div>
</div>
<?php
$urlShowAll = Url::to(['penerimaan-barang/show-merk']);
$this->registerJs("
	$('#confirm').on('click', function(event){
			age = confirm('Yakin Untuk menyimpan data');
			if(age == true){
				 return true;
			} else {
				event.preventDefault();
			}
	});
	$('#confirm2').on('click', function(event){
			age = confirm('Yakin Untuk Batalkan');
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
	$('#pilih').hide();
	$('#obat-bacth').hide();
	$('#lanjutan').hide();
	$('#cek_bacth').on('change',function(){
			id = $('#penerimaanbarangdetail-idbarang').val();
			$.ajax({
				type: 'GET',
				url: '{$urlShowAll}',
				data: 'id='+id,
				beforeSend: function(){
				$('#loading').show();
				},
				success: function (data) {
					document.getElementById('barang_bacth').value= '' ;
					$('#manual').hide();
					$('#pilih').show();
					$('#pilih').animate({ scrollTop: 0 }, 200);
					$('#pilih').html(data);
					
					console.log(data);
					
				},
				complete:function(data){
				$('#loading').hide();
				}
			});
	});
	$('#penerimaanbarangdetail-idbarang').on('change',function(){
			
			$('#lanjutan').show();
			
	});

	
           
	

", View::POS_READY);
Modal::begin([
	'id' => 'mdTambah',
	'header' => 'Tambah Barang',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formTambah', ['model'=>$model,'dataBarang' => $dataBarang]).'</div>';
 
Modal::end();

?>