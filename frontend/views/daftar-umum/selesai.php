<div class='container' style='box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-webkit-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-moz-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24); border-radius:10px;'>
	<br>
	<div class='row'>
		<div class='col-md-12'>
			<div class="alert alert-success" role="alert">
				Pendaftaran Selesai
			</div>
			<hr>
			<center>
				<h3>Terima kasih sudah mendaftar berikut jadwal berobat anda</h3><br>
				<h4><u>Tn / Ny / An . <?= $daftar->nama ?></u></h4>
				<p><b>Poli Tujuan : </b><?= $data_json2['response']['namapoli']?></p>
				<p><b>Dokter : </b><?= $data_json2['response']['nama']?></p>
				<p><b>Jadwal Berobat : </b><?= $daftar->tgl_daftar ?></p>
				<hr>
				
				<p><b>Nomor registrasi: </b><?= $daftar->kodedaftar ?></p>
				<span class="badge bg-primary">
					<?php if($daftar->status == 1){ ?>
					Menunggu Konfirmasi
					<?php }else if($daftar->status == 2){echo'Selesai';}else{echo'Batal';}?>
				</span>
			<hr>
			</center>
			<div class="alert alert-warning" role="alert">
				<h4 class="alert-heading">Selesai !!</h4>
				<ul>
					<li>Silahkan Screenshot bukti pendaftaran berikut sebagai bukti pendaftaran</li>
					<li>Petugas Kami akan segera menghubungi via telpon , untuk memberitahukan jadwal dan nomer antrian , pastikan nomer yang anda daftarkan aktif </li>
					<li>Jika tidak melakukan konfirmasi ulang dalam batas waktu tertentu maka otomatis pendaftaran di batalkan</li>
				</ul>
			</div>
		</div>
	</div>
</div>
