<div class='container' style='box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-webkit-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-moz-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24); border-radius:10px;'>
	<br>
	<div class='row'>
		<div class='col-md-12'>
		
			<hr>
			<center>
				<h3>Berikut Data terdaftar berdasarkan nomer rujukan</h3><br>
				<h4><u><?= $data_json['response']['Sbb']?>. <?= $data_json['response']['Nama']?></u></h4>
				<p><b>Poli Tujuan : </b><?= $data_json2['response']['namapoli']?></p>
				<p><b>Dokter : </b><?= $data_json2['response']['namapoli']?></p>
				<p><b>Jadwal Berobat : </b><?= $daftar->tglberobat ?></p>
				<hr>
				<p>NO ANTRIAN POLIKLINIK</p>
				<h2>
					<?php if($daftar->anggota == 1){?>
					<?= $data_json2['response']['icon'].'-'.substr($daftar->noantrian,12)?><br>
					<?php }else{ ?>
					<?= $data_json2['response']['icon'].'-'.substr($daftar->noantrian+5,11)?><br>
					<?php } ?>
				</h2>
				<p><b>Nomor registrasi: </b><?= $daftar->noregistrasi ?></p>
				<span class="badge bg-primary">
					<?php if($daftar->status == 2){ ?>
					Menunggu Konfirmasi
					<?php }else if($daftar->status == 3){echo'Selesai';}else{echo'Batal';}?>
				</span>
			<hr>
			</center>
			<?php if($daftar->status == 2){ ?>
			<div class="alert alert-warning" role="alert">
				<h4 class="alert-heading">Perhatian !!</h4>
				<ul>
					<li>Silahkan Screen Shoot bukti pendaftaran berikut sebagai bukti pendaftaran</li>
					<li>Lakukan resgistrasi ulang kebagian pendaftaran pada tanggal <b><?= $daftar->tglberobat ?></b> pukul <b>07:00 - 08:00</b></li>
					<li>Jika tidak melakukan konfirmasi ulang dalam batas waktu tertentu maka otomatis pendaftaran di batalkan</li>
				</ul>
			</div>
			<?php }else{echo'';}?>
		</div>
	</div>
</div>