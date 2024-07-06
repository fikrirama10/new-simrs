<?php 
use yii\helpers\Html;
use Picqer\Barcode\BarcodeGeneratorHTML;
use common\models\SettingSimrs;
$setting= SettingSimrs::find()->all();
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
<?php foreach($resep as $resep): ?>
<?php foreach($setting as $s): ?>
<div class='logo'><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/setting/'.$s->logo_rs,['class' => 'img img-responsive']);?></div>
<div class='header2'><div class='h6'> FARMASI <?= $s->nama_rs?></div></div>
<?php endforeach; ?>
<hr>
<div class='identitas'>
	<div class='nama1'>Nama</div>
	<div class='namaa'>:<?= $model->pasien->nama_pasien ?></div>
	<div class='nama1'>Tgl</div>
	<div class='norm'>:<?= date('d/m/Y',strtotime($model->tgl))?></div>
	<div class='nama1'>Dokter</div>
	<div class='namaa'>:<?= substr($model->rawat->dokter->nama_dokter,0,25);?> </div>
	<div class='nama1'>No RM</div>
	<div class='norm'>:<?= $model->no_rm ?></div>
	<div class='nama1'>Tgl Lahir</div>
	<div class='nama'>:<?= date('d/m/Y',strtotime($model->pasien->tgllahir)) ?></div>
	
	
</div>
<div class='obat' style='text-align:center;'>
	<b><?= $resep->signa ?></b><br> Sehari <?= $resep->dosis?> <?= $resep->takaran?><br>
	<span style='font-size:7px;'><?= $resep->diminum ?> <?php if($resep->diminum != null){echo'makan';} ?></span>
</div><hr>
<div class='nobat'>
	<div class='nama2'>Nama Obat</div>
	<div class='namaobat'>:<strong><?= $resep->obat->nama_obat ?></strong></div>
	<div class='nama2'>Catanan</div>
	<div class='nama22'>:<?= $resep->keterangan?></div>
	<div class='nama2'>Jumlah</div>
	<div class='nama22'>:<?= $resep->qty ?></div>
	<div class='nama2'>Opsi Obat</div>
	<div class='nama22'>: Obat luar / Minum</div>
	<div class='nama2'>ED</div>
	<div class='nama22'>:</div>
</div>
<?php endforeach; ?> 