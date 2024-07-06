<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model common\models\BarangAmprah */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Barang Amprahs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="barang-amprah-view">
<br>

    
	<div class='row'>
		<div class='col-md-6'>
			<div class='box box-body'>
				<?= DetailView::widget([
					'model' => $model,
					'attributes' => [
						'tgl_permintaan',
						'unit.unit',
						'amprah.status',
						'keterangan:ntext',
					],
				]) ?>
				<br>
				<?php if($model->status == 2){ ?>
				
					<a id='batalkan' class='btn btn-danger'href='<?= Url::to(['/barang-amprah/batalkan?id='.$model->id])?>'>Batalkan</a>
				<?php }else if($model->status == 1 ){ ?>
					<a id='batalkan' class='btn btn-danger'href='<?= Url::to(['/barang-amprah/batalkan?id='.$model->id])?>'>Batalkan</a>
					<a id='batalkan' class='btn btn-primary'href='<?= Url::to(['/barang-amprah/update?id='.$model->id])?>'>Edit</a>
				<?php } ?>
					<a class='btn btn-warning' target='_blank' href='<?= Url::to(['barang-amprah/print-permintaan?id='.$model->id])?>'>Print</a>
				<a class='btn btn-success'href='<?= Url::to(['/barang-amprah'])?>'>Kembali</a>
				<hr>
				<?= GridView::widget([
							'dataProvider' => $dataProvider,
							'filterModel' => $searchModel,
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],

								'nama_barang',
								'stok',
								'satuan.satuan',
								'harga',
								[
									'attribute' => 'Pilih', 
									'format' => 'raw',
									'value' => function ($model, $key, $index) { 
											return "<a id='btn".$model->id."' class='btn btn-default'>+</a><input type='hidden' value='".$model->id."' id='input".$model->id."'>";
									},
									
									
								],

							],
						]); ?>
				<?php foreach($barang as $o){
				$urlGet = Url::to(['barang-amprah/get-barang']);
					$this->registerJs("
					$('#btn{$o->id}').on('click',function(){
						id = $('#input{$o->id}').val();
							$.ajax({
							type: 'POST',
							url: '{$urlGet}',
							data: {id: id},
							dataType: 'json',
							success: function (data) {
								if(data !== null){
									var res = JSON.parse(JSON.stringify(data));					
									$('#barangamprahdetail-idbarang').val(res.id);
									$('#barangamprahdetail-harga').val(res.harga);
									$('#merk-barang').val(res.nama_barang);
									$('#barangamprahdetail-qty').focus();
								}else{
									alert('data tidak ditemukan');
								}
							},
							error: function (exception) {
								alert(exception);
							}
						});	
					}) ;


				", View::POS_READY);
				}
				?>
				
			</div>
		</div>
		<div class='col-md-6'>
			<?php if($model->status == 1){ ?>
			<div class='box box-body'>
				<?php $form = ActiveForm::begin(); ?>
				<?= $form->field($permintaan, 'idbarang')->hiddenInput(['maxlength' => true])->label(false) ?>
				<div class='row'>
					<div class='col-md-6'>
						<label for='merk-barang'>Nama Barang</label>
						<input type='text' required name='BarangAmprahDetail[nama_barang]' id='merk-barang' class='form-control'>
					</div>
					<div class='col-md-6'>
						<?= $form->field($permintaan, 'qty')->textInput(['maxlength' => true,'required'=>true])->label() ?>
						<?= $form->field($permintaan, 'harga')->textInput(['maxlength' => true,'required'=>true])->label('Harga / Estimasi') ?>
					</div>
				</div>
				<div class="form-group">
					<?= Html::submitButton('Save', ['class' => 'btn btn-success disable']) ?>
				</div>
				<?php ActiveForm::end(); ?>
			</div>
			<?php } ?>
			<div class='box box-body'>
				<table class='table table-bordered'>
					<tr>
						<th width=10>No</th>
						<th>Nama Barang</th>
						<th>Jumlah</th>
						<th>#</th>
					</tr>
					<?php $no=1; foreach($permintaan_list as $pl){ ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $pl->nama_barang ?> 
							<?php if($pl->baru == 1){ ?>
								<span class='label label-success'>Baru</span>
							<?php } ?>
						</td>
						<td><?= $pl->qty ?></td>
						<td>
							<?php if($model->status == 1){ ?>
							<a href='<?= Url::to(['barang-amprah/hapus-item?id='.$pl->id])?>' class='btn btn-xs btn-danger'>hapus</a>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<?php if($model->status == 2){ ?>
					<tr>
						<td colspan=2><a href='<?= Url::to(['barang-amprah/edit-amprah?id='.$model->id]) ?>' class='btn btn-info btn-xs'>Edit</a></td>
					</tr>
					<?php } ?>
				</table>
				
				
				<br>
				<?php if(count($permintaan_list) > 0){ ?>
					<?php if($model->status == 1){ ?>
						<a href='<?= Url::to(['barang-amprah/ajukan?id='.$model->id])?>' class='btn btn-primary' id='confirm'>Ajukan</a>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
	
</div>
<?php 

$this->registerJs("
	
	$('#confirm').on('click', function(event){
		age = confirm('Yakin Untuk mengajukan barang?? , setelah data tersimpan maka data tidak dapat di edit kembali , pastikan pengajuan sudah benar !!.');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	
});
	$('#batalkan').on('click', function(event){
		age = confirm('Yakin Untuk membatalkan pengajuan??');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	
	

", View::POS_READY);

?>