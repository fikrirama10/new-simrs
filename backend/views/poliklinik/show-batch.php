<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
?>
<div id='batch-obat'>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>No Batch</th>
		<th>Merk</th>
		<th>ED</th>
		<th>Stok</th>
	</tr>
	<?php $no=1; if(count($model) < 1){ ?>
	<tr>
		<th colspan=5>Jenis Obat tidak tersedia</th>
	</tr>
	<?php }else{
		foreach($model as $m):
		?>
			<tr>
				<td><?= $no++ ?></td>
				<td><a id='<?= $m->id?>' class='btn btn-default btn-sm'><?= $m->no_bacth ?></a>
				<input type='hidden' id='golput<?= $m->id?>' value='<?= $m->id?>'>
				</td>
				<td><?= $m->merk ?></td>
				<td><?= $m->tgl_kadaluarsa ?></td>
				<td><?= $m->stok_apotek ?></td>
			</tr>
			<?php 			
				$urlGet = Url::to(['poliklinik/get-batch']);
				$this->registerJs("
				
				$('#{$m->id}').on('click',function(){
					id = $('#golput{$m->id}').val();
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
								$('#harga-obat').val(res.harga_jual);
								$('#resep-idbatch').val(res.id);
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
	<?php 
		endforeach;
	} ?>
</table>
<?php if(count($model) > 0){ ?>
<div id='form-obat'>
	<?php $form = ActiveForm::begin(); ?>
			<table class=''>			
			<tr>
				<th>Merk Obat</th>
				<td><input type='text' id='merk-obat' class='form-control'><input type='hidden' id='resep-idbatch' name='RawatResepDetail[idbacth]' class='form-control'></td>
			</tr>
			<tr>
				<th width=100>Aturan Pakai</th>
				<td width=400>
			
					<div class="input-group">
					<input type='text' id='resep-signa1' name='RawatResepDetail[signa1]' class='form-control' >
					<span class="input-group-addon" id="basic-addon1">X</span>
					<input type='text' id='resep-signa2' name='RawatResepDetail[signa2]'  class='form-control' >
					</div>
			
				</td>
			</tr>
			<tr>
				<th>Jumlah</th>
				<td><input type='text' id='resep-jumlah' name='RawatResepDetail[qty]'  class='form-control'></td>
			</tr>
			<tr>
				<th>Catatan</th>
				<td><?= $form->field($resep_obat, 'catatan')->textarea(['maxlength' => true])->label(false) ?></td>
			</tr>
			<tr>
				<th colspan=2>-</th>
			</tr>
			<tr>
				<th colspan=2><?= Html::submitButton('Tambah', ['class' => 'btn btn-success','id'=>'confirm3']) ?></th>
			</tr>
		</table>
</div>
<?php } ?>
</div>