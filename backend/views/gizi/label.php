
<div class="label-pasien">
	<b><?= Yii::$app->kazo->getSbb($model->pasien->usia_tahun,$model->pasien->jenis_kelamin,$model->pasien->idhubungan); ?>. <?= substr($model->pasien->nama_pasien,0,17);?></b><br>
	<a><?= $model->no_rm ?> </a> | 
	<a><?= $model->ruangan->nama_ruangan ?> </a><br>
	<b><?= $gizi->diit ?> </b><br>
</div>