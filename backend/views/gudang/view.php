<?php 
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use yii\bootstrap\Modal;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;
use yii\web\View;
use common\models\PenerimaanBarangDetail;
use common\models\PenerimaanBarang;
?>
<div class='row'>		
	<div class='col-md-3'>
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
				<?php if($model->status == 1){
					 echo Html::a('<i class="fas fa-redo"></i> Kembali', ['/gudang/barang-terima'], ['class' => 'btn bg-orange','id'=>'confirm']);
				}else{
					 echo Html::a('<i class="fas fa-redo"></i> Kembali', ['/gudang/barang-terima-selesai'], ['class' => 'btn bg-orange','id'=>'confirm']);
				} ?>
			</div>
		</div>
	</div>
	<div class='col-md-9'>
		<div class="box">
			<div class="box-header"></div>
			<div class="box-body">
				<?php 
					if($model->status == 1){
						$tombol =  Html::a('<i class="fas fa-redo"></i> Masuk Stok', ['/gudang/masuk-stok?id='.$model->id], ['class' => 'btn bg-navy','id'=>'confirm']);
					}else{
						$tombol ='';
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
				'after'=>$tombol,
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
					'jumlah',
					'obat.satuan.satuan',
					// 'obat.stok_gudang',
					// 'obat.stok_apotek',
					[
						'attribute' => 'Jumlah Barang diterima', 
							'width' => '40px',
						'format' => 'raw',
						'value' => function ($model, $key, $index) { 
								$penerimaan = PenerimaanBarang::find()->where(['id'=>$model->idpenerimaan])->one();
								if($penerimaan->status == 1){
									if($model->diterima != 1){
										return "<input class='form-control' readonly id='jumlahL-".$model->id."' type='text' value='".$model->jumlah."'>";
									}else{
										return "<input class='form-control' readonly id='jumlahL-".$model->id."' type='text' value='".$model->jumlah_diterima."'>";
									}
								}else{
									return $model->jumlah_diterima;
								}
						},					
						
					],
					

				],
				]); ?>
				<?php 
	foreach($penerimaan as $pl){
	$urlEdit = Url::to(['gudang/terima-barang']);
	$this->registerJs("
		$('#jumlahL-{$pl->id}').on('dblclick',function() {
			$('#jumlahL-{$pl->id}').prop('readonly', false);
		});		
		$('#jumlahL-{$pl->id}').on('click',function() {
			$('#jumlahL-{$pl->id}').prop('readonly', true);
			$('.jumlahL').prop('readonly', true);
		});		
		$('#jumlahL-{$pl->id}').on('keypress',function(e) {
			if(e.which === 13){
				$('#jumlahL-{$pl->id}').prop('readonly', true);
				jumlah = $('#jumlahL-{$pl->id}').val();
				$.ajax({
				type: 'GET',
					url: '{$urlEdit}',
					data: 'id='+{$pl->id}+'&jumlah='+jumlah,
					dataType: 'json',
					success: function (data) {
						var res = JSON.parse(JSON.stringify(data));
						if(res.code == 404){
							alert('Jumlah tidak boleh kurang dari 0');
							location.reload();
						}else{
							location.reload();
						}

					},
					 
				});

			}
		});
		
	

	", View::POS_READY);
	}
	?> 
			</div>
			
		</div>
	</div>
</div>