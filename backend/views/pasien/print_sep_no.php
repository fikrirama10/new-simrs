<?php 
use yii\helpers\Html;
?>
<div class='header-sep'>
	<div class='header-sep-logo'>
	<?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/logo bpjs-02.png',['class' => 'img img-responsive']);?>
	</div>
	<div class='header-sep-judul'>
	SURAT ELEGIBILITAS PESERTA<BR> RSAU LANUD SULAIMAN
	</div>
	<div class='header-sep-nomer'>
	<?= $peserta['peserta']['informasi']['prolanisPRB']?>
	</div>
</div>
<div class='body-sep'>
	<table>
		<tr>
			<td width=100>No.SEP</td>
			<td>: <?= $sep['response']['noSep'] ?> </td>
		</tr>
		<tr>
			<td width=100>Tgl.SEP</td>
			<td>: <?= $sep['response']['tglSep']?></td>
			<td width=100>Peserta</td>
			<td>: <?= $sep['response']['peserta']['jnsPeserta']?></td>
		</tr>
		<tr>
			<td width=100>No.Kartu</td>
			<td>: <?= $peserta['peserta']['noKartu'] ?> ( MR. <?= $peserta['peserta']['mr']['noMR'] ?> )</td>
		</tr>
		<tr>
			<td width=100>Nama Peserta</td>
			<td width=300> : <?= $sep['response']['peserta']['nama']?></td>
			<td width=100>Jns.Rawat</td>
			<td>: <?= $sep['response']['jnsPelayanan']?></td>
		</tr>
		<tr>
			<td width=100>Tgl.Lahir</td>
			<td>: <?= $peserta['peserta']['tglLahir'] ?>  Kelamin : <?php if($peserta['peserta']['sex'] == 'L'){echo'Laki - laki';}else{echo'Perempuan';} ?></td>
			<td width=100>Jns.Kunjungan</td>
			<td>:
				
			</td>
		</tr>
		<tr>
			<td width=100>No.Telepon</td>
			<td>: </td>
			<td></td>
			<td>
				
			</td>
		</tr>
		<tr>
			<td width=100>Sub/Spesialis</td>
			<td>: <?= $sep['response']['poli']?></td>
			<td width=100>Poli Perujuk</td>
			<td>: -</td>
		</tr>
		<tr>
			<td width=100>Dokter</td>
			<td>:  
			<?php $sep['response']['dpjp']['nmDPJP'] ?>
			<td width=100>Kls.Hak</td>
			<td>: Kelas <?= $sep['response']['klsRawat']['klsRawatHak']?></td>
		</tr>
		<tr>
			<td width=100>Faskes Perujuk</td>
			<td>:
				<?php $rujukan = Yii::$app->rujukan->get_rujukan($sep['response']['noRujukan']);
					echo $rujukan['rujukan']['peserta']['provUmum']['nmProvider']; ?>
			</td>
			<td width=100>Kls.Rawat</td>
			<td>: <?php 
				echo 'Kelas '.$sep['response']['klsRawat']['klsRawatHak'];
			?> </td>
		</tr>
		<tr>
			<td width=100>Diagnosa Awal</td>
			<td>: <?= $sep['response']['diagnosa']?></td>
			<td width=100>Penjamin</td>
			<td>: <?= $sep['response']['penjamin']?></td>
		</tr>
		<tr>
			<td width=100>Catatan</td>
			<td>: <?= $sep['response']['catatan'] ?></td>
		</tr>
	</table>
	<div class='catatan'>
		*Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan<br>
		*SEP Bukan sebagai bukti penjamin peserta<br><br>
		
		Cetakan ke  <?= date('d-m-Y H:i:s')?> wib
	</div>
	<div class='ttdpasien'>Pasien/Keluarga Pasien<br><hr></div>
</div>