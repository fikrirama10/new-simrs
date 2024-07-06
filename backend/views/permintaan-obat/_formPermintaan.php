<?php
	use common\models\ObatBacth;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Url;
	use yii\web\View;
	$bacth = ObatBacth::find()->where(['idobat'=>$pl->idobat])->all();
?>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Batch Obat</th>
		<th>Merk Obat</th>
		<th>ED</th>
		<th>Stok</th>
	</tr>
	<?php $no=1;foreach($bacth as $b): ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><a id='btn<?= $b->id?>' class='btn btn-default btn-xs'><input type='hidden' id='input<?= $b->id ?>' value='<?= $b->id ?>'><?= $b->no_bacth ?></a></td>
		<td><?= $b->merk ?></td>
		<td><?= $b->tgl_kadaluarsa ?></td>
		<td><?= $b->stok_gudang ?></td>
	</tr>
	<?php endforeach; ?>
</table>
				<?php foreach($bacth as $o){
				$urlGet = Url::to(['permintaan-obat/get-bacth']);
					$this->registerJs("
					$('#btn{$o->id}').on('click',function(){
						$('#mdPermintaan{$pl->id}').modal('hide');
						$('#confirm4').show();
						id = $('#input{$o->id}').val();
							$.ajax({
							type: 'POST',
							url: '{$urlGet}',
							data: {id: id},
							dataType: 'json',
							success: function (data) {
								if(data !== null){
									var res = JSON.parse(JSON.stringify(data));					
									$('#permintaanobatdetail-idobat').val(res.idobat);
									$('#permintaanobatdetail-idbacth').val(res.id);
									$('#merk-obat').val(res.merk);
									$('#permintaanobatdetail-jumlah_setuju').focus();
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

