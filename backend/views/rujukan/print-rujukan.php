<?php 
use yii\helpers\Html;
?>

<div class='header-sep-rujukan'>
	<div style='width:25%; float:left; font-family:arial;'>
	<div style='text-align:center; border-bottom:1px; solid ;width:100%;'>
		PANGKALAN TNI AU SULAIMAN<br>
		RUMAH SAKIT<hr>
	</div>
	<div style='letter-spacing:1px;'><b>Nomor : <i><?= substr($model->kode_rujukan,7)?> / <?= date('Y',strtotime($model->tgl_rujuk))?> / <?= Yii::$app->rujukan->getRomawi(date('n',strtotime($model->tgl_rujuk))) ?>/ RS</i></b></div>
	</div>
	<div  style='width:48%; float:left; line-height:50px; font-family:arial; text-align:center;'>
	<div class='img-logo'><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/LOGO_RUMKIT_SULAIMAN__2_-removebg-preview.png',['class' => 'img img-responsive']);?></div>


	</div>
	<div class='header-sep-nomer'>
	<div class='img-rujukan'><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/logo bpjs-02.png',['class' => 'img img-responsive']);?></div>
	No. <?= $model->no_rujukan ?>
	</div>
</div>
<div style='text-align:center; font-family:arial;'>	<b>SURAT RUJUKAN RSAU LANUD SULAIMAN</b></div>
<div class='body-sep'>
	<table>
		<tr>
			<td width=100>Kepada Yth</td>
			<td>: <?= $rujukan['response']['rujukan']['namaPoliRujukan']?></td>
		</tr>
		<tr>
			<td width=100></td>
			<td>  ` <?= $rujukan['response']['rujukan']['namaPpkDirujuk']?> </td>
			<td width=100></td>
			<td>== <?= $rujukan['response']['rujukan']['namaTipeRujukan']?> ==</td>
		</tr>
		<tr>
			<td colspan=2>Mohon Pemeriksaan dan Penanganan Lebih Lanjut : </td>
			<td width=100></td>
			<td><?php if($rujukan['response']['rujukan']['jnsPelayanan'] == 1){echo'Rawat Inap';}else{echo'Rawat Jalan';}?></td>
		</tr>
		<tr>
			<td width=100>No.Kartu</td>
			<td>: <?= $rujukan['response']['rujukan']['noKartu']?></td>
		</tr>
		<tr>
			<td width=100>Nama Peserta</td>
			<td>: <?= $rujukan['response']['rujukan']['nama']?></td>
		</tr>
		<tr>
			<td width=100>Tgl.Lahir</td>
			<td>: <?= $rujukan['response']['rujukan']['tglLahir']?></td>
		</tr>
		<tr>
			<td width=100>Diagnosa</td>
			<td>: <?= $rujukan['response']['rujukan']['namaDiagRujukan']?></td>
		</tr>
		<tr>
			<td width=100>Keterangan</td>
			<td width=300>: <?= $rujukan['response']['rujukan']['catatan']?></td>
		</tr>
		<tr>
			<td colspan=2>Demikian atas bantuannya,diucapkan banyak terima kasih.</td>
		</tr>
	</table>
	<div class='catatan'>
		* Rujukan Berlaku Sampai Dengan <?= date('d F Y',strtotime('+90 days',strtotime($rujukan['response']['rujukan']['tglRujukan'])))?><br>
		* Tgl.Rencana Berkunjung <?= date('d F Y',strtotime($rujukan['response']['rujukan']['tglRujukan']))?><br><br>
		
		Tgl Cetak  <?= date('d-m-Y H:i:s',strtotime('+7 hours'))?> wib
	</div>
	<div class='ttdpasien'>Mengetahui,<br><br><hr></div>
</div>