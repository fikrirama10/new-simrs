<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Obat;
use common\models\ObatBacth;
use common\models\RawatBayar;
use common\models\PermintaanObatRequest;
use common\models\PermintaanObat;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use mdm\widgets\TabularInput;
use kartik\grid\GridView;
use yii\bootstrap\Modal;
?>
<div class="row">
	<div class="col-md-4">
		<div class="box">
			<div class="box-header with-border"><h4>Permintaan Obat</h4></div>
			<div class="box-body">
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'kode_permintaan',
						'tgl_permintaan',
						'user.userdetail.nama',
						'unit.unit',
						'keterangan:ntext',
					],
				]) ?>
			</div>
			<div class='box-footer'>
				<a href='<?= Url::to(['permintaan-obat/form-setuju?id='.$model->id])?>' target='_blank' class='btn btn-warning'>Print</a>
				<a href='<?= Url::to(['jangkes/list-permintaan-obat'])?>' target='_blank' class='btn btn-success'>Kembali</a>
			</div>
		</div>

	</div>

	<div class='col-md-8'>
				<div class="box">
			<div class="box-header with-border"><h4>List Permintaan </h4></div>
			<div class="box-body">
				<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'showPageSummary' => true,
		'columns' => [
			['class' => 'yii\grid\SerialColumn'],

					[
					'attribute' => 'idobat', 
					'vAlign' => 'middle',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->idobat == null){
								return $model->nama_obat."<span class='label label-success'>Baru</span>";
							}else{
								return $model->obat->nama_obat;
							}
							
					},
					'filterType' => GridView::FILTER_SELECT2,
					'filter' => ArrayHelper::map(PermintaanObatRequest::find()->where(['idpermintaan'=>$model->id])->all(), 'idobat', 'obat.nama_obat'), 
					'filterWidgetOptions' => [
						'pluginOptions' => ['allowClear' => true],
					],
					'filterInputOptions' => ['placeholder' => 'Nama Obat'], // allows multiple authors to be chosen
					'format' => 'raw'
					],
					
					'jumlah',
					[
						'attribute' => 'harga', 
						'width' => '40px',
						'format' => 'raw',
						'value' => function ($model, $key, $index) { 
								return "<input readonly id='harga-".$model->id."' type='text' value='".$model->harga."'>";
						},
						
						
						],
					[
						'attribute' => 'total',
						'width' => '150px',
						'hAlign' => 'right',
						'format' => ['decimal', 0],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
					[
						'attribute' => 'Jumlah Setuju', 
							'width' => '40px',
						'format' => 'raw',
						'value' => function ($model, $key, $index) { 
								return "<input readonly id='jumlahSetuju-".$model->id."' type='text' value='".$model->jumlah_setuju."'>";
						},
						
						
						],
						[
						'attribute' => 'total_setuju',
						'width' => '150px',
						'hAlign' => 'right',
						'format' => ['decimal', 0],
						'pageSummary' => true,
						'pageSummaryFunc' => GridView::F_SUM
					],
					[
					'attribute' => 'status', 
					'vAlign' => 'middle',
					'value' => function ($model, $key, $index, $widget) { 
							if($model->status == 1){
								return 'diajukan';
							}else if($model->status == 2){
								return 'disetujui';
							}else if($model->status == 4){
								return 'ditolak';
							}
							
					},
					],
			[
				'attribute' => 'Pilih', 
				'format' => 'raw',
				'value' => function ($model, $key, $index) { 
						$obatPermintaan = PermintaanObat::findOne($model->idpermintaan);
						if($obatPermintaan->status == 1){
							$tombol = "<a id='btn".$model->id."' data-toggle='modal' data-target='#mdPermintaan".$model->id."' class='btn btn-default  btn-sm'>Lihat Stok</a><a id='btn".$model->id."' data-toggle='modal' data-target='#mdTolak".$model->id."' class='btn btn-danger btn-sm'>Tolak</a>";
						}else{
							$tombol='';
						}
						
						return $tombol;
						
						
				},
				
				
			],

		],
	]); ?>
	
	<?php 
	foreach($permintaan_list as $pl){
	$urlEdit = Url::to(['jangkes/edit-setuju']);
	$urlHarga = Url::to(['jangkes/edit-harga']);
	$this->registerJs("
		$('#jumlahSetuju-{$pl->id}').on('dblclick',function() {
			$('#jumlahSetuju-{$pl->id}').prop('readonly', false);
		});		
		$('#jumlahSetuju-{$pl->id}').on('click',function() {
			$('#jumlahSetuju-{$pl->id}').prop('readonly', true);
			$('.jumlahSetuju').prop('readonly', true);
		});		
		$('#jumlahSetuju-{$pl->id}').on('keypress',function(e) {
			if(e.which === 13){
				$('#jumlahSetuju-{$pl->id}').prop('readonly', true);
				jumlah = $('#jumlahSetuju-{$pl->id}').val();
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
		
		$('#harga-{$pl->id}').on('dblclick',function() {
			$('#harga-{$pl->id}').prop('readonly', false);
		});		
		$('#harga-{$pl->id}').on('click',function() {
			$('#harga-{$pl->id}').prop('readonly', true);
			$('.harga').prop('readonly', true);
		});		
		$('#harga-{$pl->id}').on('keypress',function(e) {
			if(e.which === 13){
				$('#harga-{$pl->id}').prop('readonly', true);
				jumlah = $('#harga-{$pl->id}').val();
				$.ajax({
				type: 'GET',
					url: '{$urlHarga}',
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
	<a href='<?= Url::to(['jangkes/selesai?id='.$model->id])?>' id='confirm' class='btn btn-primary'>Selesai</a>
	
			</div>
		</div>
	</div>
</div>
<?php 
$this->registerJs("
	
	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data?? , setelah data tersimpan maka data tidak dapat di edit kembali , pastikan sudah benar !!.');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	
	

", View::POS_READY);

foreach($permintaan_list as $pl):
	Modal::begin([
		'id' => 'mdPermintaan'.$pl->id,
		'header' => '<h3>Permintaan</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formPermintaan', ['model'=>$model,'pl'=>$pl]).'</div>';
	 
	Modal::end();
	Modal::begin([
		'id' => 'mdSetuju'.$pl->id,
		'header' => '<h3>Permintaan Setujui</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formSetuju', ['model'=>$model,'pl'=>$pl]).'</div>';
	 
	Modal::end();
	Modal::begin([
		'id' => 'mdTolak'.$pl->id,
		'header' => '<h3>Permintaan Tolak</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_formTolak', ['model'=>$model,'pl'=>$pl]).'</div>';
	 
	Modal::end();
endforeach;

?>