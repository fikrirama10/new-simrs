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
		<th>Jenis Obat</th>
		<th>ED</th>
		<th>Stok Gudang</th>
		<th>Harga Beli</th>
		<th>Harga Jual</th>
	</tr>
	<?php $no=1;foreach($bacth as $b): ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><a id='btn<?= $b->id?>' class='btn btn-default btn-xs'><input type='hidden' id='input<?= $b->id ?>' value='<?= $b->id ?>'><?= $b->no_bacth ?></a></td>
		<td><?= $b->merk ?></td>
		<td><?= $b->bayar->bayar ?></td>
		<td><?= $b->tgl_kadaluarsa ?></td>
		<td><?= $b->stok_gudang ?></td>
		<td><?= $b->harga_beli ?></td>
		<td><?= $b->harga_jual ?></td>
	</tr>
	<?php endforeach; ?>
	<?php if($pl->idobat == null){ ?>
	<tr>
		<td colspan=8>Obat Belum tersedia</td>
	</tr>
	<tr>
		<td colspan=8><a class='btn btn-success' href='<?= Url::to(['pengadaan/tambah-obat?id='.$pl->id])?>'>Tambah ke daftar obat</a></td>
	</tr>
	<?php } ?>
	
</table>
<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($pl, 'idbacth')->textInput(['required'=>true])  ?>
<?php ActiveForm::end(); ?>
				<?php foreach($bacth as $o){
				$urlGet = Url::to(['permintaan-obat/get-bacth']);
					$this->registerJs("
					$('#btn{$o->id}').on('click',function(){
						
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
									$('#permintaanobatdetail-harga').val(res.harga_beli);
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

