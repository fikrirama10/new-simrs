<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\JenisDiagnosa;
use common\models\Dokter;
use common\models\RawatBayar;
use yii\web\View;
use common\models\Poli;
use common\models\Kamar;
use common\models\Rawat;
use common\models\DokterJadwal;
use common\models\DokterKuota;
use common\models\KategoriPenyakit;
use yii\bootstrap\Modal;
use kartik\checkbox\CheckboxX;
?>

<?php if($cruangan < 1){ ?>
<br>
	<table class='table table-bordered'>
		<tr>
			<th>NO</th>
			<th>KODE BED</th>
		</tr>
		<tr>
			<td colspan=3>Bed Tidak Tersedia</td>
		</tr>
	</table>
<?php }else{ ?>
	<br>

			<div id='data-dokter'>
			<table class='table table-bordered'>
				<tr>
					<th>PILIH</th>
					<th>KODE BED</th>
				</tr>
				<?php foreach($ruangan as $r){ ?>
				
				<tr>
					<td><a class='btn btn-default' id='<?= $r->id?>'>+ PILIH</a><input type='hidden' value='<?= $r->id?>' id='golput<?= $r->id?>'></td>
					<td><?= $r->kodebed?></td>
				</tr>
				<?php 			
					$urlGet = Url::to(['pasien/get-bed']);
					$this->registerJs("
					
					$('#{$r->id}').on('click',function(){
						id = $('#golput{$r->id}').val();
							$.ajax({
							type: 'POST',
							url: '{$urlGet}',
							data: {id: id},
							dataType: 'json',
							success: function (data) {
								if(data !== null){
									$('#data-dokter').hide();
									var res = JSON.parse(JSON.stringify(data));
									
									$('#kodebed').val(res.kodebed);
									$('#rawat-idbed').val(res.id);
									
									
									
									//$('#transaksidetail-harga-disp').val(format_money(parseInt(harga),''));
									// console.log(kode +' '+ idstok);
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
			</div>
			<?php $form = ActiveForm::begin(); ?>
			<label>Kode Bed</label>
			<input type='text' required class='form-control' id='kodebed' readonly >
			<?= $form->field($pelayanan, 'idbed')->hiddeninput(['maxlength' => true,'readonly'=>true,])->label(false) ?>
			<?= $form->field($pelayanan, 'idjenisperawatan')->dropDownList(ArrayHelper::map(KategoriPenyakit::find()->all(), 'id', 'kategori'),['prompt'=>'- Jenis Perawatan -','required'=>true])->label('Jenis Perawatan')?>
			<input type="checkbox" id="operasi" name="Rawat[operasi]" value="1">
			<label for="operasi">Tindakan Bedah ?</label><br>
			<?= $form->field($pelayanan, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Pilih Bayar -','required'=>true])->label('Penanggung',['class'=>'label-class'])->label()?>
			<?= $form->field($pelayanan, 'iddokter')->dropDownList(ArrayHelper::map(Dokter::find()->all(), 'id', 'nama_dokter'),['prompt'=>'- Pilih DPJP -','required'=>true])->label('Penanggung',['class'=>'label-class'])->label('DPJP')?>
			
			<input type="checkbox" id="vehicle1" name="Rawat[anggota]" value="1">
			<label for="vehicle1">Anggota ?</label><br>
			<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
			
		<?php } ?>



<?php 
$this->registerJs("
$('#confirm').hide();
$('#rawat-idbayar').on('change', function(event){
	bayar = $('#rawat-idbayar').val();
	cdokter = $('#cdokter').val();
	if(bayar == 1){
		$('#confirm'). show();
	}else{
		if(cdokter > 0){
			$('#confirm').hide();
			alert('Pasien BPJS tidak bisa lebih dari 1 kunjungan di hari yang sama');
			location.reload();
		}else{
			$('#confirm').show();
		}
	}
});
$('#confirm').on('click', function(event){
	age = confirm('Yakin Untuk menyimpan data');
	if(age == true){
		 return true;
	} else {
		event.preventDefault();
	}
});
", View::POS_READY);
?>