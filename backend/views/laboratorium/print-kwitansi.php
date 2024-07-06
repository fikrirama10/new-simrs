<?php 
use yii\helpers\Html;
use common\models\PasienAlamat;
use common\models\SettingSimrs;
use common\models\ObatTransaksi;
use common\models\ObatTransaksiDetail;
use Picqer\Barcode\BarcodeGeneratorHTML;
$setting= SettingSimrs::find()->all();
?>
<div class="header">
<div class="header-logo"><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/LOGO RUMKIT BARU.jpg',['class' => 'img img-responsive']);?></div>
	<div class="header-kop"><b>RUMAH SAKIT TNI AU LANUD SULAIMAN</b><br>Jl. Terusan Kopo KM 10 No 456 Sulaiman,Kec. Margahayu, <br> Bandung, Jawa Barat 40229<br>Telp. (022) 5409608</div>
</div>
<div class="datapasien">
	<table style='font-size:10px;'>
		<tr>
			<td>Nama Pasien</td>
			<td>:</td>
			<td width=150px><?= $pasien->nama_pasien ?></td>
			
			<td>Id Lab</td>
			<td>:</td>
			<td><?= $hasil->labid	?></td>
		</tr>
		<tr>
			<td>Tgl Lahir</td>
			<td>:</td>
			<td width=150px><?= $pasien->tgllahir ?></td>
			
			<td>Tgl</td>
			<td>:</td>
			<td><?= $hasil->tgl_hasil ?></td>
		</tr>
		
		<tr>
			<td>No RM</td>
			<td>:</td>
			<td width=250px><?= $pasien->no_rm ?></td>
			
			<td>Jenis Pasien</td>
			<td>:</td>
			<td><?= $hasil->rawat->bayar->bayar ?></td>
			
		</tr>
		
		
	</table>
</div>
<?php $total=0; foreach($model as $m){
	$total += $m->pemeriksaan->tarif->tarif;	
} ?>
<div class='olab'>
	<table>
		<tr>
			<td style='border-top:1px solid; font-size:9px;background:#ececec; border-bottom:1px solid; text-transform:uppercase;' colspan=4><b>Deskripsi</b></td>
			<td align=right style='background:#ececec; border-bottom:1px solid; border-top:1px solid; font-size:9px; text-transform:uppercase;' colspan=2><b>TOTAL</b></td>
		</tr>
		<tr>
			<td style='text-transform:uppercase;' colspan=4><b>layanan laboratorium</b></td>
			<td align=right  style='text-transform:uppercase; font-size:11px; ' colspan=2><b> <?= Yii::$app->algo->IndoCurr($total)?></b></td>
		</tr>
		<?php foreach($model as $m){ ?>
		<tr>
			<td><?= $m->pemeriksaan->nama_pemeriksaan ?> (<?= $m->bayar->bayar ?>)</td>
			<td></td>
			<td>:</td>
			<td><?= Yii::$app->algo->IndoCurr($m->pemeriksaan->tarif->tarif )?></td>
		</tr>
		<?php } ?>
		
	
	</table>
</div>
