<?php 
use yii\helpers\Html;
?>
<div style='text-align:center;'>
	<h3>SURAT PERSETUJUAN VAKSINASI COVID-19<br>
	KABUPATEN BANDUNG<br>
	(INFORMED CONCENT)</h3>
</div>
<hr>
<div style='width:100%; float:left; font-size:14px; padding:5px 0 5px 0;'>Saya yang bertandatangan di bawah ini :</div>

<div style='width:100%; float:left;'>
	<div style='width:30%; float:left;'>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>Nama</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>NIK</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>Jenis Kelamin</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>Usia</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>Tempat , Tanggal Lahir</div>
		<div style='width:100%; height:60px; font-size:14px; padding:5px 0 5px 0;'>Alamat</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>Telepon / HP</div>
	</div>
	<div style='width:5%;float:left; '>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>:</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>:</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>:</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>:</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>:</div>
		<div style='width:100%;  height:60px;  font-size:14px; padding:5px 0 5px 0;'>:</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>:</div>
	</div>
	<div style='width:60%;float:left; '>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'><?=$model->nama?></div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'><?=$model->nik?></div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'><b>L / P</b></div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'><?= $model->usia ?> Thn</div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'>_____________ <?= date('d / m / Y',strtotime($model->tgllahir))?></div>
		<div style='width:100%;  height:60px;  font-size:14px; padding:5px 0 5px 0;'><?= $model->alamat?></div>
		<div style='width:100%; font-size:14px; padding:5px 0 5px 0;'><?= $model->nohp?></div>
	</div>
</div>
<hr>
<div style='width:100%;'><?= Html::img(Yii::$app->params['baseUrl2'].'/frontend/images/aaa.jpg');?></div>