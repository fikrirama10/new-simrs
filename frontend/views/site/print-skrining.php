<?php 
use yii\helpers\Html;
?>
<div style='width:100%;  '>
	<div style='width:49%; float:left;'>
	<div style='width:100%;font-size:10px; '><b>A. MEJA PRA-REGISTRASI</b></div>
		<div style='width:100%; background:#3755b8; color:#fff;'>
			<div style='width:80%; background:#3755b8; font-size:10px; float:left; color:#fff;'>VERIFIKASI DATA IDENTITAS</div>
			<div style='width:19%; background:#3755b8; font-size:10px; float:left; color:#fff; text-align:center;'>PARAF PETUGAS</div>
		</div>
		<div style='width:100%; border:1px solid; float:left;'>
			<div style='width:80%; border:1px solid;  float:left;'>
				<div style='width:100%; border-bottom:1px solid;  float:left;'>
					<div style='width:20%; font-size:9px; border-right:1px solid; float:left;'>Nama</div>
					<div style='width:70%; font-size:9px; float:left;'><?= $model->nama ?></div>
				</div>
				<div style='width:100%; border-bottom:1px solid;  float:left;'>
					<div style='width:20%; font-size:9px; border-right:1px solid; float:left;'>NIK</div>
					<div style='width:70%; font-size:9px; float:left;'><?= $model->nik ?></div>
				</div>
				<div style='width:100%; border-bottom:1px solid;  float:left;'>
					<div style='width:20%; font-size:9px; border-right:1px solid; float:left;'>Tanggal Lahir</div>
					<div style='width:70%; font-size:9px; float:left;'><?= $model->tgllahir ?></div>
				</div>
				<div style='width:100%; border-bottom:1px solid;  float:left;'>
					<div style='width:20%; font-size:9px; border-right:1px solid; float:left;'>Ho HP</div>
					<div style='width:70%; font-size:9px; float:left;'><?= $model->nohp ?></div>
				</div>
				<div style='width:100%; border-bottom:1px solid;  float:left;'>
					<div style='width:20%; font-size:9px; border-right:1px solid; float:left;'>Alamat</div>
					<div style='width:70%; font-size:9px;border-left:1px solid; float:left;'><?= $model->alamat ?></div>
				</div>
				<div style='width:100%;  float:left;'>
					<div style='width:20%; font-size:9px; border-right:1px solid; float:left;'>Vaksin yang diberikan</div>
					<div style='width:70%; font-size:9px; float:left;'></div>
				</div>
				
				
			</div>
			<div style='width:20%;  float:left;'>
				
			</div>
		</div>
		<div style='width:100%;font-size:10px; '><b>B. MEJA 1 (SKRINING DAN VAKSINASI)</b></div>
		<div style='width:100%;'><?= Html::img(Yii::$app->params['baseUrl2'].'/frontend/images/SKRINING.jpg');?></div>
	</div>
	<div style='width:2%; float:left;'></div>
	<div style='width:49%; float:right;'>
		<div style='width:100%;'><?= Html::img(Yii::$app->params['baseUrl2'].'/frontend/images/SKRINING2.jpg');?></div>
	</div>
</div>