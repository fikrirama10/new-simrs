<?php 
use yii\helpers\Html;
use common\models\PasienAlamat;
use common\models\SettingSimrs;
use common\models\ObatTransaksi;
use common\models\ObatTransaksiDetail;
$bpjs = ObatTransaksiDetail::find()->joinWith(['transaksi as tran'])->where(['tran.id'=>$model->id])->andwhere(['idbayar'=>2])->sum('obat_transaksi_detail.total');
$kronis = ObatTransaksiDetail::find()->joinWith(['transaksi as tran'])->where(['tran.id'=>$model->id])->andwhere(['idbayar'=>3])->sum('obat_transaksi_detail.total');
$umum = ObatTransaksiDetail::find()->joinWith(['transaksiobat as trano'])->joinWith(['transaksi as tran'])->where(['tran.id'=>$model->id])->andwhere(['idbayar'=>1])->sum('obat_transaksi_detail.total');
$racik = ObatTransaksi::find()->where(['idtrx'=>$model->id])->sum('jasa_racik');
$total_resep = $bpjs ;
$obat = ObatTransaksi::find()->where(['idtrx'=>$model->id])->all();
$setting= SettingSimrs::find()->all();
use Picqer\Barcode\BarcodeGeneratorHTML;

?>
<?php if(count($model_detail) > 0){ ?>
<div class="header">
    <div class="header-logo"><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/LOGO RUMKIT BARU.jpg',['class' => 'img img-responsive']);?></div>
	<h1>RUMAH SAKIT TNI AU LANUD SULAIMAN</h1>
	<div class="header-kop">Jl. Terusan Kopo KM 10 No 456 Sulaiman,Kec. Margahayu, <br> Bandung, Jawa Barat 40229<br>Telp. (022) 5409608</div>
	<div class='header-kop2'><h5>RINCIAN PASIEN BPJS</h5></div>

</div>


<div class="datapasien">
	<table style='font-size:10px;'>
		<tr>
			<td width=70px>Nama Pasien</td>
			<td>:</td>
			<td width=110px><?= $pasien->nama_pasien ?></td>
			
			<td width=70px>No Faktur</td>
			<td>:</td>
			<td><?= $model->idtransaksi	?></td>
		</tr>
		<tr>
			<td>Tgl Lahir</td>
			<td>:</td>
			<td width=150px><?= $pasien->tgllahir ?></td>
			
			<td>Tgl</td>
			<td>:</td>
			<td><?= $model->tgl_masuk ?> s/d <?= $model->tgl_keluar?></td>
		</tr>
		
		<tr>
			<td>No RM</td>
			<td>:</td>
			<td width=250px><?= $pasien->no_rm ?></td>
			
			
		</tr>
		
		
	</table>
</div>
<br>
<?php 

$total_bpjs = 0;
foreach($model_detail as $md){
	$total_bpjs += $md['total'];
}
?>
<div class='olab'>
	<table>
		<tr>
			<td style='border-top:1px solid; font-size:9px;background:#ececec; border-bottom:1px solid; text-transform:uppercase;' colspan=4><b>Deskripsi</b></td>
			<td align=right style='background:#ececec; border-bottom:1px solid; border-top:1px solid; font-size:9px; text-transform:uppercase;' colspan=2><b>TOTAL</b></td>
		</tr>
		<tr>
			<td style='text-transform:uppercase;' colspan=4><b>tindakan dan layanan medis</b></td>
			<td align=right  style='text-transform:uppercase; font-size:11px; ' colspan=2><b> <?= Yii::$app->algo->IndoCurr(round($total_bpjs))?></b></td>
		</tr>
		<?php foreach($model_detail as $md){ ?>
		<tr>
			<td></td>
			<td style='font-size:11px;'><?= $md['nama_tarif']?></td>
			<td width=50>x <?= $md['jumlah']?></td>
			<td>:</td>
			<td style='font-size:11px;' > <?= Yii::$app->algo->IndoCurr(round($md['total']))?></td>
		</tr>
		<?php } ?>
		
		<tr>
			<td style='text-transform:uppercase;' colspan=4><b>Alkes & Obat obatan</b></td>
			<td align=right  style='text-transform:uppercase; font-size:11px; ' colspan=2><b> <?= Yii::$app->algo->IndoCurr(round($total_resep))?></b></td>
		</tr>
		<tr>
			<td></td>
			<td>BPJS</td>
			<td></td>
			<td>:</td>
			<td style='font-size:11px;' > <?= Yii::$app->algo->IndoCurr(round($bpjs))?>
			
			</td>
		</tr>
		<?php if($racik > 0){ ?>
		<tr>
			<td></td>
			<td>Jasa Racik</td>
			<td></td>
			<td>:</td>
			<td style='font-size:11px;' > <?= Yii::$app->algo->IndoCurr(round($racik))?>
			
			</td>
		</tr>
		<?php }?>
		<tr><td colspan=6></td></tr>
		<tr><td style='text-transform:uppercase; border-top:1px solid; font-size:13px; ' align='right' colspan=6><b><?= Yii::$app->algo->IndoCurr(round($total_bpjs + $total_resep + $racik))?></b></td></tr>
	</table>
</div>
<div class='olab'>
<table style='font-size:10px;'>
	<tr>
		<td>Total Tagihan</td>
		<td>:</td>
		<td><?= Yii::$app->algo->IndoCurr(round($total_bpjs + $total_resep))?></td>
		
		<td>Total Diskon %</td>
		<td>:</td>
		<td><?= Yii::$app->algo->IndoCurr(round($model->diskon))?></td>
	</tr>
	<tr>
		<td>Total Ditanggung (BPJS) </td>
		<td>:</td>
		<td><?= Yii::$app->algo->IndoCurr(round($total_bpjs + $total_resep))?></td>
		
	</tr>
	
</table>
</div>

<?php } ?>
<?php if(count($model_detail_umum) > 0 || count($obat_detail_umum) > 0) { ?>
<pagebreak />
<div class="header">
	<div class="header-logo"><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/LOGO RUMKIT BARU.jpg',['class' => 'img img-responsive']);?></div>
	<h1>RUMAH SAKIT TNI AU LANUD SULAIMAN</h1>
	<div class="header-kop">Jl. Terusan Kopo KM 10 No 456 Sulaiman,Kec. Margahayu, <br> Bandung, Jawa Barat 40229<br>Telp. (022) 5409608</div>
	<div class='header-kop2'><h5>RINCIAN PASIEN BPJS</h5></div>
</div>

<div class="datapasien">
	<table style='font-size:10px;'>
		<tr>
			<td width=70px>Nama Pasien</td>
			<td>:</td>
			<td width=110px><?= $pasien->nama_pasien ?></td>
			
			<td width=70px>No Faktur</td>
			<td>:</td>
			<td><?= $model->idtransaksi	?></td>
		</tr>
		<tr>
			<td>Tgl Lahir</td>
			<td>:</td>
			<td width=150px><?= $pasien->tgllahir ?></td>
			
			<td>Tgl</td>
			<td>:</td>
			<td><?= $model->tgl_masuk ?> s/d <?= $model->tgl_keluar?></td>
		</tr>
		
		<tr>
			<td>No RM</td>
			<td>:</td>
			<td width=250px><?= $pasien->no_rm ?></td>
			
			
		</tr>
		
		
	</table>
</div>
<br>
<?php 

$total_tarif = 0;
foreach($model_detail_umum as $mdu){
	$total_tarif += $mdu['total'];
}
?>
<div class='olab'>
	<table>
		<tr>
			<td style='border-top:1px solid; font-size:9px;background:#ececec; border-bottom:1px solid; text-transform:uppercase;' colspan=4><b>Deskripsi</b></td>
			<td align=right style='background:#ececec; border-bottom:1px solid; border-top:1px solid; font-size:9px; text-transform:uppercase;' colspan=2><b>TOTAL</b></td>
		</tr>
		<tr>
			<td style='text-transform:uppercase;' colspan=4><b>tindakan dan layanan medis</b></td>
			<td align=right  style='text-transform:uppercase; font-size:11px; ' colspan=2><b> <?= Yii::$app->algo->IndoCurr(round($total_tarif))?></b></td>
		</tr>
		<?php foreach($model_detail_umum as $mdu){ ?>
		<tr>
			<td></td>
			<td style='font-size:11px;'><?= $mdu['nama_tarif'] ?></td>
			<td>@ <?= $mdu['jumlah']?></td>
			<td>:</td>
			<td style='font-size:11px;' > <?= Yii::$app->algo->IndoCurr(round($mdu['total']))?></td>
		</tr>
		<?php } ?>
		
		<tr>
			<td style='text-transform:uppercase;' colspan=4><b>Alkes & Obat obatan</b></td>
			<td align=right  style='text-transform:uppercase; font-size:11px; ' colspan=2><b> <?= Yii::$app->algo->IndoCurr(round($umum))?></b></td>
		</tr>
		<tr>
			<td></td>
			<td>UMUM</td>
			<td></td>
			<td>:</td>
			<td style='font-size:11px;' > <?= Yii::$app->algo->IndoCurr(round($umum))?>
			
			</td>
		</tr>
		<tr><td colspan=6></td></tr>
		<tr><td style='text-transform:uppercase; border-top:1px solid; font-size:13px; ' align='right' colspan=6><b><?= Yii::$app->algo->IndoCurr(round($umum + $total_tarif))?></b></td></tr>
	</table>
</div>
<div class='olab'>
<table style='font-size:10px;'>
	<tr>
		<td>Total Tagihan</td>
		<td>:</td>
		<td><?= Yii::$app->algo->IndoCurr(round($umum + $total_tarif))?></td>
		
		<td>Total Diskon %</td>
		<td>:</td>
		<td><?= Yii::$app->algo->IndoCurr(round($model->diskon))?></td>
	</tr>
	<tr>
		<td>Total Bayar (Yanmasum) </td>
		<td>:</td>
		<td><?= Yii::$app->algo->IndoCurr(round($umum + $total_tarif))?></td>
		
	</tr>
	
</table>
</div>
<?php } ?>
<?php if($kronis > 0){ ?>
<pagebreak/>
<div class="header">
	<?php if(count($setting) < 1){ ?>
	<div class="header-logo"><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/LOGO RUMKIT BARU.jpg',['class' => 'img img-responsive']);?></div>
	<h1>RUMAH SAKIT TNI AU LANUD SULAIMAN</h1>
	<div class="header-kop">Jl. Terusan Kopo KM 10 No 456 Sulaiman,Kec. Margahayu, <br> Bandung, Jawa Barat 40229<br>Telp. (022) 5409608</div>
	<?php }else{ ?>
			<?php foreach($setting as $s): ?>
			<div class="header-logo"><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/setting/'.$s->logo_rs,['class' => 'img img-responsive']);?></div>
			<div class="header-kop"><h3>RSAU dr.SISWANTO</h3><?= $s->alamat_rs?><br>Tlp: <?= $s->no_tlp?></div>
			<?php endforeach; ?>
			<div class='header-kop2'><h5>RINCIAN OBAT KRONIS</h5></div>
	<?php } ?>
</div>

<div class="datapasien">
	<table style='font-size:10px;'>
		<tr>
			<td width=70px>Nama Pasien</td>
			<td>:</td>
			<td width=110px><?= $pasien->nama_pasien ?></td>
			
			<td width=70px>No Faktur</td>
			<td>:</td>
			<td><?= $model->idtransaksi	?></td>
		</tr>
		<tr>
			<td>Tgl Lahir</td>
			<td>:</td>
			<td width=150px><?= $pasien->tgllahir ?></td>
			
			<td>Tgl</td>
			<td>:</td>
			<td><?= $model->tgl_masuk ?> s/d <?= $model->tgl_keluar?></td>
		</tr>
		
		<tr>
			<td>No RM</td>
			<td>:</td>
			<td width=250px><?= $pasien->no_rm ?></td>
			
			
		</tr>
		
		
	</table>
</div>
<br>
<div class='olab'>
	<table>
		<tr>
			<td style='border-top:1px solid; font-size:9px;background:#ececec; border-bottom:1px solid; text-transform:uppercase;' colspan=4><b>Deskripsi</b></td>
			<td align=right style='background:#ececec; border-bottom:1px solid; border-top:1px solid; font-size:9px; text-transform:uppercase;' colspan=2><b>TOTAL</b></td>
		</tr>
		<tr>
			<td style='text-transform:uppercase;' colspan=4><b>Alkes & Obat obatan</b></td>
			<td align=right  style='text-transform:uppercase; font-size:11px; ' colspan=2><b> <?= Yii::$app->algo->IndoCurr(round($umum))?></b></td>
		</tr>
		<tr>
			<td></td>
			<td>KRONIS</td>
			<td></td>
			<td>:</td>
			<td style='font-size:11px;' > <?= Yii::$app->algo->IndoCurr(round($kronis))?>
			
			</td>
		</tr>
		<tr><td colspan=6></td></tr>
		<tr><td style='text-transform:uppercase; border-top:1px solid; font-size:13px; ' align='right' colspan=6><b><?= Yii::$app->algo->IndoCurr(round($kronis))?></b></td></tr>
	</table>
</div>
<div class='olab'>
<table style='font-size:10px;'>
	<tr>
		<td>Total Harga</td>
		<td>:</td>
		<td><?= Yii::$app->algo->IndoCurr(round($kronis))?></td>
	
	</tr>
	
	
</table>
</div>
<?php } ?>