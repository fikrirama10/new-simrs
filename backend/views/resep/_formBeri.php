<?php
	use common\models\RawatResepDetail;
	use common\models\ObatBacth;
	use common\models\TransaksiBayar;
	use yii\helpers\Url;
	use yii\helpers\Html;
	use yii\widgets\ActiveForm;
	use yii\web\View;
	use yii\helpers\ArrayHelper;
	$bayar = TransaksiBayar::find()->all();
	$soap = RawatResepDetail::findOne($idobat);
	$obatb = ObatBacth::find()->where(['idobat'=>$soap->idobat])->andwhere(['>','stok_apotek',0])->all();
?>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>No Batch</th>
		<th>Merk</th>
		<th>Stok</th>
		<th>Harga Rp</th>
		<th>ED</th>
		<th>Pilih</th>
	</tr>
	<?php $no=1; foreach($obatb as $ob): ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $ob->no_bacth ?></td>
		<td><?= $ob->merk ?></td>
		<td><?= $ob->stok_apotek ?></td>
		<td><?= $ob->tgl_kadaluarsa ?></td>
		<td><?= $ob->harga_jual ?></td>
		<td><a class='btn btn-warning btn-xs' id='pilih<?= $ob->id?>' >Pilih</a></td>
	</tr>
	
	<tr>
		<td colspan=4>
			<div id='pilih-list<?= $ob->id?>'>
				<?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>
				<input type='hidden' name='idsoap' value='<?= $soap->id ?>'>
				<div class="form-group">
					<label class="col-sm-4 control-label">Obat</label>
					<div class="col-sm-4">
						<input type='text' readonly id='obat-detail<?= $ob->id?>' class='form-control'>
						<input type='hidden' id='obat-idbatch<?= $ob->id?>' name='ObatTransaksiDetail[idbatch]' class='form-control'>
						<input type='hidden' id='obat-idobat<?= $ob->id?>' name='ObatTransaksiDetail[idobat]' class='form-control'>
					</div>	
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Harga</label>
					<div class="col-sm-4">
						<input type='text' readonly id='obat-harga<?= $ob->id?>' name='ObatTransaksiDetail[harga]' class='form-control'>
					</div>	
					<div class="col-sm-4">
						<?= $form->field($resep_detail, 'idbayar')->dropDownList(ArrayHelper::map(TransaksiBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Jenis Obat -','required'=>true])->label(false)?>
					</div>	
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Qty</label>
					<div class="col-sm-4">
						<input type='text' id='obat-qty<?= $ob->id?>' name='ObatTransaksiDetail[qty]' class='form-control'>
					</div>	
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Dosis</label>
					<div class="col-sm-4">
						<input type='text' id='obat-qty<?= $ob->id?>' name='ObatTransaksiDetail[dosis]' class='form-control'>
					</div>	
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Takaran</label>
					<div style='margin-left:15px;' class="col-sm-4">
						<?= $form->field($resep_detail, 'takaran')->dropDownList([ 'tablet' => 'tablet', 'kapsul' => 'kapsul', 'bungkus' => 'bungkus', 'tetes' => 'tetes', 'ml' => 'ml' ,'sendok takar 5ml' => 'sendok takar 5ml','sendok takar 15ml' => 'sendok takar 15ml', ])->label(false) ?>
					</div>	
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label">Diminum</label>
					<div style='margin-left:15px;'  class="col-sm-4">
						<?= $form->field($resep_detail, 'diminum')->dropDownList([ 'Sebelum' => 'Sebelum', 'Sesudah Makan' => 'Sesudah', ])->label(false) ?>
					</div>	
				</div>

					
				<div class="form-group">
					<label class="col-sm-4 control-label">Keterangan</label>
					<div class="col-sm-4">
						<input type='text' id='obat-qty<?= $ob->id?>' name='ObatTransaksiDetail[keterangan]' class='form-control'>
					</div>	
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"></label>
					<div class="col-sm-4">
						<?= Html::submitButton('Save', ['class' => 'btn btn-success btn-xs']) ?>
					</div>	
				</div>
				
				<?php ActiveForm::end(); ?>
			<a class='btn btn-danger btn-xs' id='cancel<?= $ob->id?>' >Cancel</a>
			</div>
		</td>
	</tr>
	<?php
	$urlGet = Url::to(['permintaan-obat/get-bacth']);
	$this->registerJs("
		$('#pilih-list{$ob->id}').hide();
		$('#cancel{$ob->id}').on('click',function(){
			$('#pilih{$ob->id}').show();
			$('#pilih-list{$ob->id}').hide();
		});
		$('#pilih{$ob->id}').on('click',function(){
			$('#pilih{$ob->id}').hide();
			$('#pilih-list{$ob->id}').show();
			id = {$ob->id};
			$.ajax({
				type: 'POST',
				url: '{$urlGet}',
				data: {id:id},
				dataType: 'json',
				success: function (data) {
					if(data !== null){
						var res = JSON.parse(JSON.stringify(data));	
						$('#obat-idobat{$ob->id}').val(res.idobat);
						$('#obat-idbatch{$ob->id}').val(res.id);
						$('#obat-detail{$ob->id}').val(res.merk);
						$('#obat-harga{$ob->id}').val(res.harga_jual);
					}else{
						alert('data tidak ditemukan');
					}
				},
				error: function (exception) {
					alert(exception);
				}
			});	
		});
	", View::POS_READY);
	?>
	<?php endforeach; ?>
</table>

