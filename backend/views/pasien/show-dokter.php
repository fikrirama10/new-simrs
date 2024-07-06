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
use common\models\DokterJadwal;
use common\models\DokterKuota;
use yii\bootstrap\Modal;
use kartik\checkbox\CheckboxX;

?>

<?php if($cdokter < 1){ ?>
<br>
	<table class='table table-bordered'>
		<tr>
			<th>NO</th>
			<th>NAMA DOKTER</th>
			<th>KUOTA</th>
		</tr>
		<tr>
			<td colspan=3>Dokter Tidak Tersedia</td>
		</tr>
	</table>
<?php }else{ ?>
	<br>
		<?php if($cekrajal > 2){ ?>
		<div class="callout callout-warning">
			<h5>Terdapat Kunjungan RAWATJALAN  di hari yang sama</h5>
		</div>
		<?php }else{ ?>
			<div id='data-dokter'>
			<table class='table table-bordered'>
				<tr>
					<th>PILIH</th>
					<th>NAMA DOKTER</th>
					<th>KUOTA</th>
				</tr>
				<?php foreach($dokter as $d){ ?>
				<?php 
					$dokter_kuotac = DokterKuota::find()->where(['iddokter'=>$d->iddokter])->andwhere(['tgl'=>$kunjungan])->andWhere(['idhari'=>date('N',strtotime($kunjungan))])->count();
					$dokter_kuota = DokterKuota::find()->where(['iddokter'=>$d->iddokter])->andwhere(['tgl'=>$kunjungan])->andWhere(['idhari'=>date('N',strtotime($kunjungan))])->one();
				?>
				<tr>
					<?php if($d->kuota > 0){ ?>
						<?php 
							if($dokter_kuotac < 1){
								echo "<td><a class='btn btn-default' id='".$d->iddokter."'>+ PILIH</a><input type='hidden' value='".$d->iddokter."' id='golput".$d->iddokter."'></td>";
							}else{
								if($dokter_kuota->sisa > 0){
									echo "<td><a class='btn btn-default' id='".$d->iddokter."'>+ PILIH</a><input type='hidden' value='".$d->iddokter."' id='golput".$d->iddokter."'></td>";
								}else{
									echo '<td>Kuota Habis</td>';
								}
							}
						?>
					
					<?php }else{ ?>
					<td>Tidak ada kuota</td>
					<?php } ?>
					<td><?= $d->dokter->nama_dokter?></td>
					<td><?php 
							if($dokter_kuotac < 1){
								echo $d->kuota;
							}else{
								echo $dokter_kuota->sisa;
							}
						?>
					</td>
				</tr>
				<?php 			
					$urlGet = Url::to(['pasien/get-dokter']);
					$this->registerJs("
					
					$('#{$d->iddokter}').on('click',function(){
						
						id = $('#golput{$d->iddokter}').val();
							$.ajax({
							type: 'POST',
							url: '{$urlGet}',
							data: {id: id},
							dataType: 'json',
							success: function (data) {
								if(data !== null){
									$('#data-dokter').hide();
									var res = JSON.parse(JSON.stringify(data));
									$('#tunggu-klik').show();
									$('#nama_dokter').val(res.nama_dokter);
									$('#rawat-iddokter').val(res.id);
									
									
									
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
			<div id='tunggu-klik'>
			<?php $form = ActiveForm::begin(); ?>
			<input type='text' required class='form-control' id='nama_dokter' readonly >
			<?= $form->field($pelayanan, 'iddokter')->hiddeninput(['maxlength' => true,'readonly'=>true,])->label(false) ?>
	    	<?= $form->field($pelayanan, 'kunjungan')->dropDownList([ '1' => 'Kunjungan Baru ', '2' => 'Kunjungan Ulang','3'=>'Kunjungan Post Ranap'], ['prompt' => 'Jenis Kunjungan','required'=>true])?>
		
			
		
			<?= $form->field($pelayanan, 'idbayar')->dropDownList(ArrayHelper::map(RawatBayar::find()->all(), 'id', 'bayar'),['prompt'=>'- Pilih Bayar -','required'=>true])->label('Penanggung',['class'=>'label-class'])->label()?>
			<input type='hidden' id='cdokter' value='<?= $cekrajal ?>'>
			
			
			<input type="checkbox" id="vehicle1" name="Rawat[anggota]" value="1">
			<label for="vehicle1">Anggota ?</label><hr>
			<input type="checkbox" id="vehicle1" name="Rawat[online]" value="1">
			<label for="vehicle1">Online ?</label><hr>
			<?= Html::submitButton('Simpan', ['class' => 'btn btn-success','id'=>'confirm']) ?>
			</div>
		<?php } ?>


<?php } ?>

<?php 
$this->registerJs("
$('#tunggu-klik').hide();
$('#confirm').hide();
$('#rujukan').hide();
$('#kontrol').hide();
$('#rawat-kunjungan').on('change', function(event){
	kunjungan = $('#rawat-kunjungan').val();
	if(kunjungan == 1){
		$('#rujukan').show();
		$('#kontrol').hide();
	}else{
		$('#rujukan').hide();
		$('#kontrol').show();
	}
});
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