<div class='container' style='box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-webkit-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24);
-moz-box-shadow: -1px 2px 23px 6px rgba(0,0,0,0.24); border-radius:10px;'>
	<br>
	<div class='row'>
		<div class='col-md-12'>
			<div class="alert alert-success" role="alert">
				<h4 class="alert-heading">Pendaftaran Berhasil !!</h4>
				<ul>
					
				</ul>
			</div>
			<hr>
			<center>
				<h3>Berikut Data Pendaftaran Vaksin</h3><br>
				<h4><u></u></h4>
				<p><b>Nama Lengkap : </b><?= $model->nama?></p>
				<p><b>Tgl Lahir : </b><?= $model->tgllahir?></p>
				<p><b>Alamat : </b><?= $model->alamat ?></p>
				<p><b>Vaksin Ke : </b><?= $model->vaksin?></p>
				<p><b>Jadwal Vaksin :</b> <?= $model->tglvaksin?>
					<?php if($model->antri > 0 && $model->antri < 31){echo'(08:00 - 09:00) ';}else if($model->antri > 30 && $model->antri < 61){echo'(09:00 - 10:00) ';}else if($model->antri > 60 && $model->antri < 101){echo'(10:00 - 11:00) ';} ?>
				</p>
				<p><b>Lokasi Vaksin : </b> AULA RSAU LANUD SULAIMAN</p>
				<hr>
				<p>NO ANTRIAN VAKSIN</p>
				<h2>
					<?= substr($model->noantrian,9)?><br>
				
				</h2>
				<p>NO REGISTRASI</p>
				<span class="badge bg-primary">
					<h3>
						<?= $model->noregister?>
					</h3>
				</span>
				
			<hr>
			</center>
			
			<div class="alert alert-warning" role="alert">
				<h4 class="alert-heading">Perhatian !!</h4>
				<ul>
					<li>Silahkan Screen Shoot bukti pendaftaran berikut sebagai bukti pendaftaran</li>
					<li>Pemberian Vaksin diberikan <b></b> pukul <b><?php if($model->antri > 0 && $model->antri < 31){echo'(08:00 - 09:00) ';}else if($model->antri > 30 && $model->antri < 61){echo'(09:00 - 10:00) ';}else if($model->antri > 60 && $model->antri < 101){echo'(10:00 - 11:00) ';} ?> WIB</b></li>
					<li>Jika tidak hadir di saat jadwal vaksin maka akan otomatis di batalkan</li>
				</ul>
			</div>
		</div>
	</div>
</div>