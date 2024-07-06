<?php
use Picqer\Barcode\BarcodeGeneratorHTML;
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
<div class="barcode-pasien">
<b><?= $model->no_rm ?></b> 
	<b><?= Yii::$app->kazo->getSbb($model->pasien->usia_tahun,$model->pasien->jenis_kelamin,$model->pasien->idhubungan); ?>. <?= substr($model->pasien->nama_pasien,0,17);?></b><br>
	<a><?= $model->pasien->tgllahir?>  (<?= $model->pasien->usia_tahun ?> thn , <?= $model->pasien->usia_bulan ?> bln <?= $model->pasien->usia_hari ?> hr )</a><br>
	<?= '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($model->idrawat, $generator::TYPE_CODE_128)) . '">'; ?>
	<?= $model->tglmasuk ?>
</div>