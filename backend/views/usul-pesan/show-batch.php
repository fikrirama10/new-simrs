<?php 

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
?>
<table class="table table-bordered">
	<tr>
		<th>No</th>
		<th>Merk</th>
		<th>Harga Beli</th>
		<th>Stok</th>
		<th>Satuan</th>
		<th>Pilih</th>
	</tr>
	<?php $no=1; foreach($model as $m): ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $m->merk ?></td>
		<td>Rp .<?= $m->harga_beli ?></td>
		<td><?= $m->stok_gudang?></td>
		<td><?= $m->obat->satuan->satuan?></td>
		<td><a id='<?= $m->id ?>' class='btn btn-default btn-sm'>Pilih</a><input type='hidden' id='golput<?= $m->id?>' value='<?= $m->id?>'></td>
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
								$('#usulpesandetail-idbacth').val(res.id);
								$('#usulpesandetail-harga').val(res.harga_beli);
								
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