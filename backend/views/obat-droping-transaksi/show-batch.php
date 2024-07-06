<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
?>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>No Batch</th>
		<th>Merk</th>
		<th>Stok</th>
		<th>ED</th>
	</tr>
	<?php $no=1; foreach($model as $b){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><a class='btn btn-default' id='pilih<?= $b->id?>'><?= $b->no_batch ?></a><input type='hidden' id='input<?= $b->id?>' value='<?= $b->id ?>'></td>
		<td><?= $b->merk ?></td>
		<td><?= $b->stok ?></td>
		<td><?= $b->ed ?></td>
	</tr>
	<?php 			
				$urlGet = Url::to(['obat-droping/get-batch']);
				$this->registerJs("
				
				$('#pilih{$b->id}').on('click',function(){
					id = $('#input{$b->id}').val();
						$.ajax({
						type: 'POST',
						url: '{$urlGet}',
						data: {id: id},
						dataType: 'json',
						success: function (data) {
							if(data !== null){
								$('#lanjutan').show();
								var res = JSON.parse(JSON.stringify(data));
								$('#merk-obat').val(res.merk);
								$('#obatdropingtransaksidetail-idbatch').val(res.id);
								$('#idobat').val(res.idobat);
								
								
								
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

			?>
	<?php } ?>
</table>
<?php $form = ActiveForm::begin(); ?>
	<label>Merk Obat</label>
	<input type='text' id='merk-obat' class='form-control'>
	<?= $form->field($barang, 'idbatch')->hiddenInput()->label(false)?>
	<?= $form->field($barang, 'jumlah')->textInput()->label('Jumlah')?>
	<?= Html::submitButton('Tambah', ['class' => 'btn btn-success']) ?>