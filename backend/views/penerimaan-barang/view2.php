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
$urlObat= "http://localhost/simrs-solo/dashboard/rest/list-obat";
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
		<div class='col-md-4'>
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
		<div class='col-md-8'>
			<div class="box">
				<div class="box-header">
					<?php if($model->status == 1){ ?>
						<?php $form = ActiveForm::begin(); ?>
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
							])->label('Jenis Obat / Alkes');?>
							<div id='lanjutan'>
								<table class='table'>
									<tr>
										<td width=100>Stok Apotek</td>
										<td><input type='text' readonly id='stok_apotek' class='form-control'></td>
										<td width=100>Stok Gudang</td>
										<td><input type='text' readonly id='stok_gudang' class='form-control'></td>
										<td width=100>Tgl Kadaluarsa</td>
										<td>
											<?=	$form->field($barang, 'tglkadaluarsa')->widget(DatePicker::classname(),[
												'type' => DatePicker::TYPE_COMPONENT_APPEND,
												'pluginOptions' => [
												'autoclose'=>true,
												'format' => 'yyyy-mm-dd',
												'required'=>true

												]
												])->label(false)?>
										</td>
									</tr>
									<tr>
										<td width=100>Jumlah</td>
										<td><input type='text' name='PenerimaanBarangDetail[jumlah]' id='jumlah' required class='form-control'></td>
										<td width=100>Satuan</td>
										<td><?= $form->field($barang, 'satuan')->dropDownList(ArrayHelper::map(ObatSatuan::find()->all(), 'id', 'satuan'),['prompt'=>'- Satuan Obat -','required'=>true])->label(false)?></td>
										<td width=100>Harga Beli</td>
										<td><input type='text' id='harga_beli' name='PenerimaanBarangDetail[harga]' required class='form-control'></td>
									</tr>
									<tr>
										<td width=100>PPN</td>
										<td><input type='text' id='ppn' name='PenerimaanBarangDetail[ppn]' class='form-control'></td>
										<td width=100>Diskon %</td>
										<td><input type='text' id='diskon' name='PenerimaanBarangDetail[diskon]' class='form-control'></td>
									</tr>
									<tr>
										<td colspan=6><button class='btn btn-success'>+ Barang</button></td>
									</tr>
								</table>
							</div>
						<?php ActiveForm::end(); ?>
					<?php } ?>
				</div>
				<div class="box-body">
					
				</div>
				<div class='box-footer'>
					
				</div>
			</div>
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
$urlShowAll = Url::to(['penerimaan-barang/get-stok']);
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
	$('#lanjutan').hide();
	
	$('#penerimaanbarangdetail-idbarang').on('change',function(){		
		id = $('#penerimaanbarangdetail-idbarang').val();
		$.ajax({
			type: 'POST',
				url: '{$urlShowAll}',
				data: {id: id},
				dataType: 'json',
				success: function (data) {
					if(data == 201){
						alert('Obat Tidak ada');
						$('#lanjutan').hide();
					}else{
						var res = JSON.parse(JSON.stringify(data));
						$('#lanjutan').show();
						$('#stok_apotek').val(res.stok_apotek);
						$('#stok_gudang').val(res.stok_gudang);
						$('#harga_beli').val(res.harga_beli);
						$('#penerimaanbarangdetail-satuan').val(res.idsatuan);
						$('#penerimaanbarangdetail-tglkadaluarsa').val(res.kadaluarsa);
					}
				},
				error: function (exception) {
					alert(exception);
				}
			});	
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