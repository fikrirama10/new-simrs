<?php 
use yii\helpers\Html;
?>
<div class='header-sep'>
	<div class='header-sep-logo'>
	<?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/logo bpjs-02.png',['class' => 'img img-responsive']);?>
	</div>
	<div class='header-sep-judul'>
	SURAT RENCANA INAP<br> RSAU LANUD SULAIMAN
	</div>
	<div class='header-sep-nomer'>
	No. <?= $model->spri_bpjs ?>
	</div>
</div>
<div class='body-sep sep_paddingtop20 sep_margintop20'>
	<a>Mohon Pemeriksaan dan Penanganan Lebih Lanjut :</a>
	<table>
		<tr>
			<td width=100>No.Kartu</td>
			<td>: <?= $pasien->no_bpjs?></td>
		</tr>
		<tr>
			<td width=100>Nama Peserta</td>
			<td>: <?= $pasien->nama_pasien?> (<?php if($pasien->jenis_kelamin == 'L'){echo'Laki - laki';}else{echo'Perempuan';} ?>)</td>
		</tr>
		<tr>
			<td width=100>Tgl.Lahir</td>
			<td>: <?= date('Y-m-d',strtotime($pasien->tgllahir))?></td>
		</tr>
		<tr>
			<td width=100>Diagnosa</td>
			<td>: <?= $spri['response']['sep']['diagnosa']?></td>
		</tr>
		<tr>
			<td width=100>Rencana Inap</td>
			<td>: <?= $spri['response']['tglRencanaKontrol']?></td>
		</tr>
	</table>
	<a>Demikian atas bantuannya,diucapkan banyak terima kasih.</a>
	<div class='tglcetak'>Tgl.Entri: <?= $spri['response']['tglTerbit']?> | Tgl.Cetak: <?= date('d-m-Y H:i A',strtotime('+7 hour'))?></div>
	<div class='ttd'>Mengetahui DPJP,<br><br><br><?= $spri['response']['namaDokter']?></div>
</div>