<?php 

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
				<td><a id='<?= $m->id?>' class='btn btn-default btn-xs'><?= $m->no_bacth ?></a>
				<input type='hidden' id='golput<?= $m->id?>' value='<?= $m->id?>'>
				</td>
				<td><?= $m->merk ?></td>
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
								$('#obat-batch').hide();
								$('#lanjutan').show();
								var res = JSON.parse(JSON.stringify(data));
								$('#obatstokopnamedetail-merk').val(res.merk);
								$('#obatstokopnamedetail-jumlah').focus();
								$('#obatstokopnamedetail-idbatch').val(res.id);
								$('#obatstokopnamedetail-harga').val(res.harga_jual);
								
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
</div>