<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use common\models\Obat;
use common\models\ObatBacth;
use common\models\RawatBayar;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\web\JsExpression;
use mdm\widgets\TabularInput;
use kartik\grid\GridView;
$obat = ObatBacth::find()->all();
$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Permintaan Obats', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
	<div class="col-md-6">
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
				<a href='<?= Url::to(['permintaan-obat/form-permintaan?id='.$model->id])?>' target='_blank' class='btn btn-warning'>Print</a>
					<a class='btn btn-success'href='<?= Url::to(['/permintaan-obat/'])?>'>Kembali</a>
					<a href='<?= Url::to(['permintaan-obat/batal-permintaan?id='.$model->id])?>' class='btn btn-danger'>Batalkan</a>
			</div>
		</div>
		<?php if($model->status == 10){ ?>
		<div class="box">
			<div class="box-header"></div>
			<div class="box-body">
				<?= GridView::widget([
					'dataProvider' => $dataProvider,
					'filterModel' => $searchModel,
					'columns' => [
						['class' => 'yii\grid\SerialColumn'],

						'merk',
						'stok_gudang',
						'harga_beli',
						'satuan.satuan',
						[
							'attribute' => 'Pilih', 
							'format' => 'raw',
							'value' => function ($model, $key, $index) { 
									return "<a id='btn".$model->id."' class='btn btn-default'>+</a><input type='hidden' value='".$model->id."' id='input".$model->id."'>";
							},
							
							
						],

					],
				]); ?>
				<?php foreach($obat as $o){
				$urlGet = Url::to(['permintaan-obat/get-obat']);
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
									$('#permintaanobatrequest-idbacth').val(res.id);
									$('#permintaanobatrequest-idobat').val(res.idobat);
									$('#permintaanobatrequest-harga').val(res.harga_beli);
									$('#nama_obat').val(res.merk);
									$('#permintaanobatrequest-jumlah').focus();
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
				}?>
			</div>
		</div>
		<?php } ?>
	</div>
	<?php if($model->status == 10){ ?>

	<div class="col-md-6">
		<div class="box">
			<div class="box-header"></div>
			<div class="box-body">
				<?php $form = ActiveForm::begin(); ?>
				<?= $form->field($permintaan, 'idobat')->hiddenInput(['maxlength' => true,'required'=>true])->label(false) ?>
				<?= $form->field($permintaan, 'idbacth')->hiddenInput(['maxlength' => true,'required'=>true])->label(false) ?>
				<div class="row">
					<div class="col-md-6">
						<label>Nama Obat</label>
						<input type='text' id='nama_obat' name='PermintaanObatRequest[nama_obat]' required class='form-control'>
					</div>
					<div class="col-md-6">
						<?= $form->field($permintaan, 'jumlah')->textInput(['maxlength' => true,'required'=>true]) ?>
						<?= $form->field($permintaan, 'harga')->textInput(['maxlength' => true,'required'=>true])->label('Harga / Estimasi') ?>
					</div>
				</div>
				
				
				<?= $form->field($permintaan, 'idpermintaan')->hiddenInput(['maxlength' => true,'value'=>$model->id])->label(false) ?>
				<div class="form-group">
					<?= Html::submitButton('Save', ['class' => 'btn btn-success disable']) ?>
				</div>

				<?php ActiveForm::end(); ?>
				<h4>List Permintaan Obat</h4>
				<table class='table table-bordered'>
					<thead>
					<tr>
						<th scope="row">No</th>
						<th>Nama Obat</th>
						<th>Jumlah Permintaan</th>
						<th>Satuan</th>
					</tr>
					</thead>
					<tbody>
				<?php $no=1; foreach($permintaan_list as $index => $pl): ?>
					<tr>
						<td scope="col"><?= $no++ ?></td>
						<td>
							<?php if($pl->baru == 1){echo $pl->nama_obat;}else{echo $pl->obat->nama_obat;}?>
							<?php if($pl->baru == 1){ ?>
								<span class='label label-success'>Baru</span>
							<?php } ?>
						</td>
						<?php if($model->status == 10){ ?>
						<td width=200><input type="text" id='jumlahQty-<?= $pl->id?>' class="form-control jumlahQty" readonly value='<?= $pl->jumlah ?>'></td>
						<?php }else{echo'<td>'.$pl->jumlah.'</td>';}?>
						
					</tr>
					
					<?php 
					$urlEdit = Url::to(['permintaan-obat/edit-item']);
					$this->registerJs("
						$('#jumlahQty-{$pl->id}').on('dblclick',function() {
							$('#jumlahQty-{$pl->id}').prop('readonly', false);
						});		
						$('#jumlahQty-{$pl->id}').on('click',function() {
							$('#jumlahQty-{$pl->id}').prop('readonly', true);
							$('.jumlahQty').prop('readonly', true);
						});		
						$('#jumlahQty-{$pl->id}').on('keypress',function(e) {
							if(e.which === 13){
								$('#jumlahQty-{$pl->id}').prop('readonly', true);
								jumlah = $('#jumlahQty-{$pl->id}').val();
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

					?> 
					<?php endforeach; ?>
					</tbody>
				</table>
				
			</div>
			<div class="box-footer">
				<a id='confirm-ajukan' href='<?= Url::to(['permintaan-obat/selesai-pengajuan?id='.$model->id])?>' class='btn btn-primary'>Ajukan</a>
			</div>
		</div>
	</div>
	<?php }else{ ?>
	<div class='col-md-12'>
	
	<div class='box box-body'>
	    <a class='btn btn-primary' href='<?= Url::to(['permintaan-obat/edit-permintaan-alkes?id='.$model->id])?>'>Edit</a>
	<h4>List Permintaan Obat</h4>
		<table class='table table-sm table-dark'>
			<thead>
			<tr>
				<th scope="row">No</th>
				<th>Nama Obat</th>
				<th>Jumlah Permintaan</th>
			</tr>
			</thead>
			<tbody>
			<?php $no=1; foreach($permintaan_list as $index => $pl): ?>
			<tr>
				<td scope="col"><?= $no++ ?></td>
					<td>
							<?php if($pl->baru == 1){echo $pl->nama_obat;}else{echo $pl->obat->nama_obat;}?>
							<?php if($pl->baru == 1){ ?>
								<span class='label label-success'>Baru</span>
							<?php } ?>
						</td>
				<?php if($model->status == 10){ ?>
				<td width=200><input type="text" id='jumlahQty-<?= $pl->id?>' class="form-control jumlahQty" readonly value='<?= $pl->jumlah?>'></td>
				<?php }else{echo'<td>'.$pl->jumlah.'</td>';}?>
				
			</tr>
			
			<?php 
			$urlEdit = Url::to(['permintaan-obat/edit-item']);
			$this->registerJs("
				$('#jumlahQty-{$pl->id}').on('dblclick',function() {
					$('#jumlahQty-{$pl->id}').prop('readonly', false);
				});		
				$('#jumlahQty-{$pl->id}').on('click',function() {
					$('#jumlahQty-{$pl->id}').prop('readonly', true);
					$('.jumlahQty').prop('readonly', true);
				});		
				$('#jumlahQty-{$pl->id}').on('keypress',function(e) {
					if(e.which === 13){
						$('#jumlahQty-{$pl->id}').prop('readonly', true);
						jumlah = $('#jumlahQty-{$pl->id}').val();
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

			?> 
			<?php endforeach; ?>
			</tbody>
		</table>
		</div>
			</div>
	<?php } ?>

	
</div>
	
<?php 

$this->registerJs("
	
	$('#confirm-ajukan').on('click', function(event){
		age = confirm('Yakin Untuk menyimpan data?? , setelah data tersimpan maka data tidak dapat di edit kembali , pastikan pengajuan sudah benar !!.');
		if(age == true){
			 return true;
		} else {
			event.preventDefault();
		}
	});

	
	

", View::POS_READY);

?>