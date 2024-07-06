<?php 
use yii\helpers\Html;
use common\models\PasienAlamat;
use common\models\SettingSimrs;
use common\models\Rawat;
$rawat = Rawat::find()->where(['no_rm'=>$model->no_rm])->andwhere(['<>','status',5])->limit(1)->orderBy(['tglmasuk'=>SORT_DESC])->all();
// use Picqer\Barcode\BarcodeGeneratorHTML;
// $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
// use Da\QrCode\QrCode;
// $qrCode = (new QrCode($model->no_rm))
//     ->setSize(80)
//     ->setMargin(5);

// now we can display the qrcode in many ways
// saving the result to a file:

// $qrCode->writeFile(__DIR__ . '/code.png'); 
// writer defaults to PNG when none is specified

// display directly to the browser 
// header('Content-Type: '.$qrCode->getContentType());
$alamat = PasienAlamat::find()->where(['idpasien'=>$model->id])->all();
$setting= SettingSimrs::find()->all();
?>
<div class='header1'>
<?php if(count($setting) < 1){ ?>
	<div class='logo'>
	<?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/LOGO RUMKIT BARU.jpg',['class' => 'img img-responsive']);?>
	</div>
	<div class='header2'>
	<h3>RSAU dr.NORMAN T.LUBIS</h3>
	<h5>Jalan Terusan Kopo No 500<br> 
	Telp (022) 5409608</h5>
	</div>
	<div class='headerbr'> 
		
		
	</div>
<?php }else{ ?>
	<?php foreach($setting as $s): ?>
	<div class='logo'>
	<?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/setting/'.$s->logo_rs,['class' => 'img img-responsive']);?>
	</div>
	<div class='header2'>
	<h3><?= $s->nama_rs?></h3>
	<h5><?= $s->alamat_rs?><br> 
	<?= $s->no_tlp?></h5>
	</div>
	<div class='headerbr'> 
		
		
	</div>
	<?php endforeach; ?>
<?php } ?>
</div>
<table>
  <tr>
    <th colspan=3>Formulir Identitas Pasien</th>
  </tr>
  <tr>
    <td width=220>NAMA PASIEN</td>
    <td colspan=2><?= Yii::$app->kazo->getSbb($model->usia_tahun,$model->jenis_kelamin,$model->idhubungan); ?>. <?= $model->nama_pasien?> <b>(<?= $model->jenis_kelamin ?>)</b></td>
  </tr>
  <tr>
    <td>NO REKAMMEDIS</td>
    <td ><span class='rm'><?= $model->no_rm?></span></td>
	<!--<td width=50><div class='barcode'>
	<?php // '<img src="' . $qrCode->writeDataUri() . '">'; ?>
	</div></td>-->
  </tr>
   <tr>
    <td>TEMPAT , TANGGAL LAHIR</td>
    <td colspan=2><?= $model->tempat_lahir?> , <?= Yii::$app->algo->tglIndoNoTime($model->tgllahir); ?></td>
  </tr>
  <tr>
    <td>usia saat daftar</td>
    <td colspan=2><?= $model->usia_tahun?> thn, <?= $model->usia_bulan ?> bln , <?= $model->usia_hari ?> hr</td>
  </tr>
  <tr>
    <td>Agama</td>
    <td colspan=2><?= $model->agama->agama?></td>
  </tr>
  <tr>
    <td>Pendidikan</td>
    <td colspan=2><?= $model->pendidikan->pendidikan?></td>
  </tr>
  <tr>
    <td>Pekerjaan</td>
    <td colspan=2><?= $model->pekerjaan->pekerjaan?></td>
  </tr>
  <tr>
    <td>Golongan Darah</td>
    <td colspan=2><?= $model->darah->golongan_darah?></td>
  </tr>
  <tr>
    <td>Alamat</td>
    <td colspan=2>
      <?php if(count($alamat) > 0){ ?>
		<?php foreach($alamat as $al){ ?>
			<ul>
			  <li><?= $al->alamat?>,<?= $al->idkel == null ?'':$al->kelurahan->nama.',' ?> <?= $al->idkec == null ?'':$al->kecamatan->nama.',' ?>  <?= $al->idkab == null ?'': $al->kabupaten->nama.',' ?> </li> 
			</ul>
		<?php } ?>
		<?php } ?>
	</td>
  </tr>
  <tr>
    <td>No Telpon / HP</td>
    <td colspan=2><?= $model->nohp?></td>
  </tr>
  <tr>
    <td>Email</td>
    <td colspan=2><?= $model->email?></td>
  </tr>
  <tr>
    <td>Tgl Daftar</td>
    <td colspan=2>
            <?php foreach($rawat as $r){  ?>
            <?= $r->tglmasuk ?>
            <?php } ?>
        </td>
  </tr>
</table>
<hr>
<table>
  <tr>
    <th colspan=3>Penanggung Jawab Pasien</th>
  </tr>
  <tr>
    <td width=220>Penanggung Jawab</td>
    <td colspan=2><?= $model->penanggung_jawab?> <b>(<?php if($model->idsb_penanggungjawab == null){echo '-';}else{echo $model->penanggung->penaggungjawab;}   ?>)</b></td>
  </tr>
  <tr>
    <td width=220>No Telpon</td>
    <td colspan=2><?= $model->nohp_penanggungjawab?></td>
  </tr>
  <tr>
    <td width=220>Alamat</td>
    <td colspan=2><?= $model->alamat_penanggunjawab?></td>
  </tr>
 </table>
<hr>
<?php if($model->status_pasien < 5){ ?>
	<table>
		<tr>
			<td width=220>NRP</td>
			<td colspan=2><?= $model->nrp?></td>
		</tr>
		<tr>
			<td >Pangkat</td>
			<td colspan=2><?= $model->pangkat?></td>
		</tr>
		<tr>
			<td >Kesatuan</td>
			<td colspan=2><?= $model->kesatuan?></td>
		</tr>
	</table>
<?php } ?>
