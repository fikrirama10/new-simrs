<?php
use common\models\SettingSimrs;
use common\models\PasienAlamat;
use Picqer\Barcode\BarcodeGeneratorHTML;
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
$setting= SettingSimrs::find()->all();
$alamat = PasienAlamat::find()->where(['idpasien'=>$model->id])->andwhere(['utama'=>1])->one();
?>
<div class="label">
	<b><?= Yii::$app->kazo->getSbb($model->usia_tahun,$model->jenis_kelamin,$model->idhubungan); ?>. <?= substr($model->nama_pasien,0,17); ?></b><br>
	<b class='norm'><?= $model->no_rm ?></b><br>
	<?= $model->tempat_lahir?> , <?= date('d / m / Y',strtotime($model->tgllahir))?><hr>
	<?php if($alamat): ?>
	<?= $alamat->alamat?>
	<?php endif; ?>
	<div style='width:70%; float:right;'>
	<?php if(count($setting) < 1){ ?>
	RSAU LANUD SULAIMAN<br>
	<?php }else{ ?>
	<?php foreach($setting as $s): ?>
	<?= $s->nama_rs?><br>
	<?php endforeach; ?>
	<?php } ?>
	<?= '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($model->no_rm, $generator::TYPE_CODE_128)) . '">'; ?>
	</div>
	<br>
</div>

<div class="label">
	<b><?= Yii::$app->kazo->getSbb($model->usia_tahun,$model->jenis_kelamin,$model->idhubungan); ?>. <?= substr($model->nama_pasien,0,17); ?></b><br>
	<br>
	<b class='norm' style='font-size:15px;'><?= $model->no_rm ?></b><br>
	<hr>
	
	<div style='width:70%; float:right;'>
	<?php if(count($setting) < 1){ ?>
	RSAU LANUD SULAIMAN<br>
	<?php }else{ ?>
	<?php foreach($setting as $s): ?>
	<?= $s->nama_rs?><br>
	<?php endforeach; ?>
	<?php } ?>
	<?= '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($model->no_rm, $generator::TYPE_CODE_128)) . '">'; ?>
	</div>
	
</div>
