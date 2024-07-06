<div class="form-horizontal">
	<div class="callout callout-info">
		<p style="margin-top: 14px;">
			Daftar ini merupakan penjaminan kecelakaan lalu lintas dari PT. Jasa Raharja.
			<br><b>Silahkan Pilih Data tersebut sesuai dengan berkas kasus sebelumnya(SUPLESI) dengan klik <i class="fa fa-check-circle"></i> warna hijau.</b>
		</p>
		<p style="margin-top: 12px;">
			<b>
				1. Jika Kasus Kecelakaan baru, Silahkan Klik tombol kasus KLL Baru.<br>
				2. Jika Kasus Kecelakaan lama, Silahkan Pilih No.SEP Sebagai SEP Kasus Sebelumnya.<br>
				3. Untuk Melihat Detail Jumlah Dibayar. Silahkan Pilih dengan klik NO.SEP.
			</b>
		</p>
	</div>
	<?php
		$response= Yii::$app->bpjs->suplesi($nobpjs,$tgl);
	?>
	<table class='table table-bordered'>
		<tr>
			<th>Action</th>
			<th>No.SEP</th>
			<th>No.SEP Awal</th>
			<th>Tgl.SEP</th>
			<th>Tgl.Kejadian</th>
			<th>No.Register</th>
			<th>Surat Jaminan</th>
		</tr>
		<?php if($response['metaData']['code'] != 200){ ?>
		<tr>
			<td align=center colspan=7><?= $response['metaData']['message']?></td>
		</tr>
		<?php }else{ ?>
			<?php foreach($response['response']['jaminan'] as $sup){ ?>
			<td><a class='btn btn-success btn-xs'>Pilih</a></td>
			<td><?= $sup['noSep']?></td>
			<td><?= $sup['noSepAwal']?></td>
			<td><?= $sup['tglSep']?></td>
			<td><?= $sup['tglKejadian']?></td>
			<td><?= $sup['noRegister']?></td>
			<td><?= $sup['noSuratJaminan']?></td>
			<td></td>
			<?php } ?>
		<?php } ?>
	</table>
 </div>