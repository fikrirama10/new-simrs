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
									
									$('#bed').val(res.kodebed);
									$('#rawatpermintaanpindah-idbedtujuan').val(res.id);
									
									
									
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