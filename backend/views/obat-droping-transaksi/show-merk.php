<?php
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\RawatStatus;
use common\models\ObatKategori;
use yii\bootstrap\Modal;
use common\models\ObatJenis;
use common\models\Obat;
use common\models\UsulPesanDetail;
use common\models\PenerimaanBarangDetail;
use common\models\ObatBacth;
use kartik\select2\Select2;
use kartik\checkbox\CheckboxX;
use yii\helpers\Url;
use yii\web\View;
?>
<div class="input-group" id='manual'>
	<span class="input-group-addon">
		<input type="checkbox" id='cek_manual'>
	</span>
		<select class="form-control" id='bacth-pilih' name='ObatDropingTransaksiDetail[idbatch]'>
			<option>-- Pilih Bacth --</option>
			<?php foreach($model as $m): ?>
			<option value='<?= $m->id ?>'><?= $m->no_batch?> - <?= $m->merk ?></option>
			<?php endforeach; ?>
		</select>
</div>
<?php
$urlGet = Url::to(['obat-droping/get-batch']);
$this->registerJs("

	$('#cek_manual').on('change',function(){
		$('#manual').show();
		$('#pilih').hide();
	});
	$('#bacth-pilih').on('change',function(){
		id = $('#bacth-pilih').val();
		$.ajax({
			type: 'POST',
			url: '{$urlGet}',
			data: {id: id},
			dataType: 'json',
			success: function (data) {
				if(data !== null){
					$('#lanjutan').show();
					$( '#obatdropingbatch-jumlah' ).focus();
					var res = JSON.parse(JSON.stringify(data));
					
					$('#obatdropingbatch-merk').val(res.merk);
					$('#obatdropingbatch-produksi').val(res.produksi);
					$('#obatdropingbatch-ed').val(res.ed);
					
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