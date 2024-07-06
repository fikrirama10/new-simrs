<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
?>
<table class='table table-bordered'>
	<tr>
		<th>Merk Obat</th>
		<th>Jenis Obat</th>
		<th>Stok Obat</th>
		<th>Pilih</th>
	</tr>
	<?php foreach($obat as $o): ?>
	<tr>
		<td><?= $o->merk?><input type='hidden' id='golput<?= $o->id?>' value='<?= $o->id?>'></td>
		<td><?= $o->bayar->bayar ?></td>
		<td><?= $o->stok_apotek ?></td>
		<td><a id='<?= $o->id?>'  class='btn btn-default'>+ Pilih</a></td>
	</tr>
	<?php 			
				$urlGet = Url::to(['poliklinik/get-batch']);
				$this->registerJs("
				
				$('#{$o->id}').on('click',function(){
					id = $('#golput{$o->id}').val();
						$.ajax({
						type: 'POST',
						url: '{$urlGet}',
						data: {id: id},
						dataType: 'json',
						success: function (data) {
							if(data !== null){
								$('#lanjutan').show();
								var res = JSON.parse(JSON.stringify(data));
								$('#form-obat').show();
								$('#merk-obat').val(res.merk);
								$('#resep-idbatch').val(res.id);
								
								
								
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
	<?php endforeach; ?>
</table>
<?php if(count($obat) > 0){ ?>
<div id='form-obat'>
	<?php $form = ActiveForm::begin(); ?>
			<table class=''>			
			<tr>
				<th>Merk Obat</th>
				<td><input type='text' id='merk-obat' class='form-control'><input type='hidden' id='resep-idbatch' name='RawatResepDetail[idbacth]' class='form-control'></td>
			</tr>
			<tr>
				<th width=100>Signa</th>
				<td width=400>
			
					<div class="input-group">
					<input type='text' id='resep-signa1' name='RawatResepDetail[signa1]' class='form-control' >
					<span class="input-group-addon" id="basic-addon1">X</span>
					<input type='text' id='resep-signa2' name='RawatResepDetail[signa2]'  class='form-control' >
					</div>
			
				</td>
			</tr>
			<tr>
				<th>Dosis</th>
				<td><?= $form->field($resep_obat, 'dosis')->textarea(['maxlength' => true])->label(false) ?></td>
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