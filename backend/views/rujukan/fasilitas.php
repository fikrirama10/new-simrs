<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\PasienStatus;
use common\models\PasienAlamat;
use common\models\RuanganKelas;
use common\models\Ruangan;
use common\models\Rawat;
use common\models\RawatBayar;
use common\models\KategoriPenyakit;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
?>
<?php if($model){ ?>
<div class='box'>
	<div class='box-body'>
		<table class='table table-bordered'>
			<tr>
				<th>Spesialis</th>
				<th>Kapasitas</th>
				<th>Jumlah Rujukan</th>
				<th>Persentasi</th>
			</tr>
			<?php foreach($model as $m){ ?>
			<tr>
				<td><a class='btn btn-default btn-xs'><?= $m['namaSpesialis']?></a><input type='hidden' id='input<?=$m['kodeSpesialis']?>' value='<?=$m['kodeSpesialis']?> '>
				<input type='hidden' id='inputnama<?=$m['kodeSpesialis']?>' value='<?=$m['namaSpesialis']?> '>
				</td>
				<td><?= $m['kodeSpesialis']?></td>
				<td><?= $m['kapasitas']?></td>
				<td><?= $m['jumlahRujukan']?></td>
				<td><?= $m['persentase']?></td>
				<td><a class='btn btn-success btn-xs' id='pilih<?=$m['kodeSpesialis']?>'><span class='fa fa-check'></span></a></td>
			</tr>
			<?php 
				$this->registerJs("

					$('#pilih{$m['kodeSpesialis']}').on('click',function(e) {
						
		
						kode_spesialis = $('#input{$m['kodeSpesialis']}').val();
						nama_spesialis = $('#inputnama{$m['kodeSpesialis']}').val();
						kode_faskes = $('#kodeFaskes').val();
						nama_faskes = $('#nameFaskes').val();
						$('#tglrencanaRujuk').val($('#txttglrencanarujukan').val());
						$('#txtnmpoli').val(nama_spesialis);
						$('#txtkdpoli').val(kode_spesialis);
						$('#txtnmppkdirujuk').val(nama_faskes);
						$('#txtkdppkdirujuk').val(kode_faskes);
						$('#utama').show();
						$('#kedua').hide();
						$('#fasilitas').hide();
					});
				", View::POS_READY);
				?>
			<?php } ?>
		</table>
	</div>
</div>
<?php } ?>