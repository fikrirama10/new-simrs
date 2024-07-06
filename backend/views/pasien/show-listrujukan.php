<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;
?>
<div class="form-group">
	<div class="col-sm-12">
		<table class='table table-bordered'>
			<tr>
				<th>No.</th>
				<th>No.Rujukan</th>
				<th>Tgl.Rujukan</th>
				<th>No.Kartu</th>
				<th>Nama</th>
				<th>PPK Perujuk</th>
				<th>Sub/Spesialis</th>
			</tr>
			<?php if($json['metaData']['code'] != 200 ){?>
			<tr>
				<td colspan=7><?= $json['metaData']['message']?></td>
			</tr>
			<tr>
				<td colspan=7><a class='btn btn-danger'>Rujukan Manual/IGD</a></td>
			</tr>
			
			
			<?php }else{ ?>
			<?php 
			$no=1;
			if($faskes == 1){
				$response= Yii::$app->rujukan->get_nokas($norujukan);	
			}else{
				$response= Yii::$app->rujukan->get_nokas_rs($norujukan);	
			}
			foreach($response['response']['rujukan'] as $rujukan): 
			$cek = Yii::$app->rujukan->total_sep($faskes,$rujukan['noKunjungan']);
			$cek_status = Yii::$app->rujukan->cek_rujukan($rujukan['tglKunjungan']);
			$kadaluarsa = date('Y-m-d',strtotime('+90 days',strtotime($rujukan['tglKunjungan'])));
			
		    //print_r($cek);
			?>
			<tr>
				<td><?= $no++?></td>
				<td>
					<?php if($cek_status == 1){?>
					<a href='<?= Url::to(['pasien/sep?id='.$rawat->id.'&rujukan='.$rujukan['noKunjungan'].'&tgl='.$rujukan['tglKunjungan'].'&poli='.$rawat->poli->id.'&faskes='.$faskes])?>'  class='btn btn-xs btn-default'><?= $rujukan['noKunjungan'] ?>
					<?php }else{ ?>
					<?= $rujukan['noKunjungan'] ?>
					<?php } ?>
				</td>
				<td><?= $rujukan['tglKunjungan'] ?></td>
				<td><?= $rujukan['peserta']['noKartu'] ?></td>
				<td><?= $rujukan['peserta']['nama'] ?></td>
				<td><?= $rujukan['provPerujuk']['nama'] ?></td>
				<td><?= $rujukan['poliRujukan']['nama'] ?></td>
			</tr>
			<tr style='background:#ddd; font-size:10px; height:10px;'>
				<th colspan=2>Status Rujukan : <?php if($cek_status == 1){echo'<span class="text-success">Rujukan Aktif</span>';}else{echo'<span class="text-danger">Rujukan Habis</span>';} ?></th>
				<th colspan=2>Total SEP (Kunjungan) : <?php if($cek['metaData']['code'] == 200){echo $cek['response']['jumlahSEP'] ;}?></th>
				<th colspan=2>Kadaluarsa : <?= $kadaluarsa?></th>
				<th>Keterangan : 
				    <?php 
				        if($cek['metaData']['code'] == 200){
				            if($cek['response']['jumlahSEP'] < 1){echo'Kunjungan Baru';}else{echo'Kunjungan Ulang';} 
				        }
				    ?>
				</th>
			</tr>
			<?php endforeach; ?>
			<?php } ?>
		</table>
	</div>
</div>