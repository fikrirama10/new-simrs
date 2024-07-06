<?php
use common\models\SettingSimrs;
use common\models\PasienAlamat;
use Picqer\Barcode\BarcodeGeneratorHTML;
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
$setting= SettingSimrs::find()->all();
$alamat = PasienAlamat::find()->where(['idpasien'=>$model->id])->andwhere(['utama'=>1])->one();
?>


<div class="label">
	<b  style='font-size:14px;'><?= Yii::$app->kazo->getSbb($model->usia_tahun,$model->jenis_kelamin,$model->idhubungan); ?>. <?= substr($model->nama_pasien,0,15);?> (<?= $model->jenis_kelamin?>)</b><br>
	<br><b class='norm' style='font-size:20px; line-height:30px;'><?= $model->no_rm ?></b><br><br>
    <span style='font-size:12px;'><?= $model->tempat_lahir?> , <?= date('d / m / Y',strtotime($model->tgllahir))?> ,  <br> (<?= $model->usia_tahun ?>th ,<?=  $model->usia_bulan ?>bln , <?=  $model->usia_hari?> hari)<br>
	<br>
	<?php if($alamat): ?>
	<?=$alamat->alamat?></span>
	<?php endif; ?>
</div>