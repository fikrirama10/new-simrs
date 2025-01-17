<?php 
use yii\helpers\Html;
use common\models\Rawat;
use common\models\PasienAlamat;
use common\models\LaboratoriumHasildetail;
use common\models\LabHasil;
use Picqer\Barcode\BarcodeGeneratorHTML;
use common\models\SettingSimrs;
$setting= SettingSimrs::find()->all();
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
$rawat = Rawat::find()->where(['id'=>$model->idrawat])->one();
$alamat = PasienAlamat::find()->where(['idpasien'=>$rawat->pasien->id])->andwhere(['utama'=>1])->one();

$hasiljoin = LaboratoriumHasildetail::find()->joinWith(['pemeriksaan as pemeriksaan'])->where(['idhasil'=>$model->id])->groupBy('pemeriksaan.idlab')->all();

?>
<div class="header">
<div class="header-logo"><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/LOGO RUMKIT BARU.jpg',['class' => 'img img-responsive']);?></div>
	<div class="header-kop"><b>RUMAH SAKIT TNI AU LANUD SULAIMAN</b><br>Jl. Terusan Kopo KM 10 No 456 Sulaiman,Kec. Margahayu, <br> Bandung, Jawa Barat 40229<br>Telp. (022) 5409608</div>
</div>
<div class="header2">
	<b>HASIL PEMERIKSAAN LABORATORIUM</b><br>
	
	<hr>
</div>
<div style="width:100%; text-align:right; float:right; margin-bottom:20px;">
    Penanggung jawab : dr. Ida Widayati Djajadisastra, Sp.PK
</div>
<div class="datapasien">
	
	
	<table>
		<tr>
			<td>Nama Pasien</td>
			<td>:</td>
			<td width=250px><?= $rawat->pasien->nama_pasien ?></td>
			
			<td>Alamat Pasien</td>
			<td>:</td>
			<td><?= $alamat->alamat ?></td>
		</tr>
		<tr>
			<td>Tgl Lahir</td>
			<td>:</td>
			<td width=250px><?= $rawat->pasien->tgllahir ?> (<?= $rawat->pasien->usia_tahun?> th)</td>
			
			<td>Ruangan / Poli</td>
			<td>:</td>
			<td>
				<?php if($rawat->idjenisrawat == 2){echo $rawat->ruangan->nama_ruangan; }else{echo $rawat->poli->poli;} ?>
			</td>
		</tr>
		
		<tr>
			<td>No RM</td>
			<td>:</td>
			<td width=250px><?= $rawat->no_rm ?></td>
			
			<td>Dr Pengirim</td>
			<td>:</td>
			<td><?= $rawat->dokter->nama_dokter ?></td>
		</tr>
		<tr>
			<td>Jenis Kelamin</td>
			<td>:</td>
			<td width=250px><?= $rawat->pasien->jenis_kelamin ?></td>
			
			<td>Tgl Pemeriksaan</td>
			<td>:</td>
			<td><?= date('d/m/Y',strtotime($model->tgl_hasil)) ?></td>
		</tr>
		<tr>
			<td>Jenis Bayar</td>
			<td>:</td>
			<td width=250px><?= $rawat->bayar->bayar ?></td>
			
		</tr>
	
	</table>
	
</div>

<div class='olab'>
	<table>
		<tr>
			<th width=200>Nama Pemeriksaan</th>
			<th width=100>Hasil Pemeriksaan</th>
			<th width=250>Nilai Normal</th>
			<th>Satuan</th>
		</tr>
		<?php foreach($hasiljoin as $hj){ 
		
		?>
		<tr>
			<td style='background:#ececec;' colspan=4><b><?= $hj->pemeriksaan->layanan->nama_layanan?></b></td>
		</tr>
		<?php $hasil = LaboratoriumHasildetail::find()->joinWith(['pemeriksaan as pemeriksaan'])->where(['idhasil'=>$hj->idhasil])->andwhere(['pemeriksaan.idlab'=>$hj->pemeriksaan->idlab])->all(); ?>
		<?php foreach($hasil as $hs){ ?>
		<?php $hasilfix = LabHasil::find()->where(['idpemeriksaan'=>$hs->idpemeriksaan])->andwhere(['idhasil'=>$hs->idhasil])->all(); ?>
		<?php foreach($hasilfix as $hf){ ?>
		<tr>
			<td><?= $hf->item ?></td>
			<td><?= $hf->hasil ?></td>
			<td><?= $hf->nilai_rujukan ?></td>
			<td><?= $hf->satuan ?></td>
		</tr>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	</table>
</div>
<div class='tandatangan'>
    <b>Dokter</b>
	<br><br><br><br><br>
</div>
<div class='tandatangan'>
	<b>Pemeriksa</b>
	<br><br><br><br><br>
</div>
