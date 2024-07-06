<?php for($a=0; $a < $jumlah; $a++){ ?>
<div class="label-pasien">
<span class='rm-label'><?= $model->no_rm ?></span><br>
	<b><?= Yii::$app->kazo->getSbb($model->pasien->usia_tahun,$model->pasien->jenis_kelamin,$model->pasien->idhubungan); ?>. <?= substr($model->pasien->nama_pasien,0,17);?></b><br>
	<a style='font-size:13px;'><?= $model->pasien->tgllahir?>  (<?= $model->pasien->usia_tahun ?> thn , <?= $model->pasien->usia_bulan ?> bln <?= $model->pasien->usia_hari ?> hr )</a><br>
	
	<a>
		<?php if($model->pasien->jenis_kelamin == "L"){echo "Laki-Laki";}else{echo"Perempuan";} ?>
	</a>
</div>
<?php } ?>