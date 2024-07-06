<?php 
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
?>
<table class='table table-bordered'>
	<tr>
		<th>Merk</th>
		<th>Merk</th>
		<th>Stok</th>
		<th>Harga</th>
	</tr>
	<?php foreach($model as $m): ?>
	<tr>
		<td><?= $m->merk ?></td>
		<td>
		    <?php if($m->stok_apotek > 0){ ?>
		    <a id='<?= $m->id?>' class='btn btn-default btn-sm'><?= $m->no_bacth ?></a>
		    	<input type='hidden' id='golput<?= $m->id?>' value='<?= $m->id?>'>
		    <?php }else{ ?>
		    <?= $m->no_bacth ?>
		    <?php } ?>
	
		<td><?= $m->stok_apotek ?></td>
		<td><?= $m->harga_jual ?></td>
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
						$('#idbatch').val(res.id);
						$('#idbayar').val(res.idbayar);
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
	<?php endforeach; ?>
</table>