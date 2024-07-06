<?php 
use yii\helpers\Html;
use common\models\PasienAlamat;
use common\models\SettingSimrs;
$setting= SettingSimrs::find()->all();
use Picqer\Barcode\BarcodeGeneratorHTML;
$alamat = PasienAlamat::find()->where(['idpasien'=>$model->rawat->pasien->id])->andwhere(['utama'=>1])->one();

?>
<div class="header">
	<?php if(count($setting) < 1){ ?>
	<div class="header-logo"><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/LOGO RUMKIT BARU.jpg',['class' => 'img img-responsive']);?></div>
	<div class="header-kop"><b>RUMAH SAKIT TNI AU LANUD SULAIMAN</b><br>Jl. Terusan Kopo KM 10 No 456 Sulaiman,Kec. Margahayu, <br> Bandung, Jawa Barat 40229<br>Telp. (022) 5409608</div>
	<?php }else{ ?>
			<?php foreach($setting as $s): ?>
			<div class="header-logo"><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/setting/'.$s->logo_rs,['class' => 'img img-responsive']);?></div>
			<div class="header-kop"><b>RSAU dr. SISWANTO </b><br><?= $s->alamat_rs?><br> <br><?= $s->no_tlp?></div>
			<?php endforeach; ?>
	<?php } ?>
</div>
<div class="header2">
	<b>HASIL PEMERIKSAAN RADIOLOGI</b>
</div>
<div class="datapasien">
	<table>
		<tr>
			<td>Nama Pasien</td>
			<td>:</td>
			<td width=150px><?= $model->rawat->pasien->nama_pasien ?></td>
			
			<td>Alamat Pasien</td>
			<td>:</td>
			<td><?= $alamat->alamat ?></td>
		</tr>
		<tr>
			<td>Tgl Lahir</td>
			<td>:</td>
			<td width=150px><?= $model->rawat->pasien->tgllahir ?></td>
			
			<td>Ruangan / Poli</td>
			<td>:</td>
			<td>
				<?php if($model->rawat->idjenisrawat == 2){echo $model->rawat->ruangan->nama_ruangan; }else{echo $model->rawat->poli->poli;} ?>
			</td>
		</tr>
		
		<tr>
			<td>No RM</td>
			<td>:</td>
			<td width=250px><?= $model->rawat->no_rm ?></td>
			
			<td>Dr Pengirim</td>
			<td>:</td>
			<td><?= $model->rawat->dokter->nama_dokter ?></td>
		</tr>
		<tr>
			<td>Jenis Kelamin</td>
			<td>:</td>
			<td width=250px><?= $model->rawat->pasien->jenis_kelamin ?></td>
			
			<td>Tgl Pemeriksaan</td>
			<td>:</td>
			<td><?= date('d/m/Y',strtotime($model->rawat->tglmasuk)) ?></td>
		</tr>
	
	</table>
</div>
<div class='klinis'>
	<b><u>KETERANGAN KLINIS</u></b>
	<p> <?= nl2br (stripslashes ($model->klinis)); ?></p>
</div>
<div class='hasil'>
	<b><u>URAIAN HASIL PEMERIKSAAN</u></b>
	<p> <?= nl2br (stripslashes ($model->hasil)); ?></p>
</div>
<div class='kesan'>
	<b><u>KESAN / KESIMPULAN</u></b>
	<p> <?= nl2br (stripslashes ($model->kesan)); ?></p>
</div>
<div class='catatan'></div>
<div class='tandatangan'>
	<b>Dokter Pemeriksa</b>
	<div class='gambarttd'>
	<?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/ttd_dr_radiologi.jpg',['class' => 'img','width'=>170]);?>
    </div>
	<?= $model->hasilrad->dokterrad->nama_dokter ?><br>
	<?= $model->hasilrad->dokterrad->sip ?>
</div>