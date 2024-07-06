<table class='table table-bordered'>
	<tr>
		<th>No Register</th>
		<th>Tgl Daftar</th>
		<th>Tgl Berobat</th>
		<th>Poliklinik</th>
		<th>Dokter</th>
		<th>No Antrian</th>
		<th>Status</th>
	</tr>
	<?php if(!$kuota){ ?>
	<tr>
		<th colspan=7>Data tidak ditemukan</th>
	</tr>
	<?php }else{ ?>
	<?php  foreach($kuota as $kuota){ ?>
	<tr>
		<td><?= $kuota['noregistrasi']?></td>
		<td><?= $kuota['tgldaftar']?></td>
		<td><?= $kuota['tglberobat']?></td>
		<td><?= $kuota['poli']?></td>
		<td><?= $kuota['dokter']?></td>
		<td>
			<?php if($kuota['status']!=2){ ?>
				<span style='font-size:15px; line-height:20px;' class="badge bg-primary">
					<?php if($kuota['anggota'] == 1){?>
					<?= $kuota['icon'].'-'.substr($kuota['noantrian'],12)?>
					<?php }else{ ?>
					<?= $kuota['icon'].'-'.substr($kuota['noantrian']+5,11)?>
					<?php } ?>
					<br>
				</span>
			<?php } ?>
		</td>
		<td>
			<?php if($kuota['status']==1){ ?>
				<span style='background:;' class="badge bg-primary">Menunggu Konfirmasi</span>
			<?php }else if($kuota['status']==3){ ?>
				<span style='background:green;' class="badge bg-primary">Terkonfirmasi</span>
			<?php }else { ?>
				<span style='background:red;' class="badge bg-primary">Batal</span>
			<?php } ?>
		</td>
	</tr>
	<?php } ?>
	<?php } ?>
</table>