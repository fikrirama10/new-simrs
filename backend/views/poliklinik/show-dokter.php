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

<?php if(count($dokter) < 1){ ?>
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

			<div id='data-dokter'>
			<table class='table table-bordered'>
				<tr>
					<th>PILIH</th>
					<th>NAMA DOKTER</th>
				</tr>
				<?php foreach($dokter as $d){ ?>
				
				<tr>
					<?php 						
						echo "<td><a class='btn btn-default' id='".$d->id."'>+ PILIH</a><input type='hidden' value='".$d->id."' id='golput".$d->id."'></td>";						
					?>
					<td><?= $d->nama_dokter?></td>
					
				</tr>
				<?php 			
					$urlGet = Url::to(['poliklinik/get-dokter']);
					$this->registerJs("
					
					$('#{$d->id}').on('click',function(){
						
						id = $('#golput{$d->id}').val();
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
									$('#rawatspri-iddokter').val(res.id);
									
									
									
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
			


<?php } ?>

<?php 
$this->registerJs("
$('#tunggu-klik').hide();
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