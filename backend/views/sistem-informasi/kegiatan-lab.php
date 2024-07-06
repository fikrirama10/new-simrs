<div class='header'>
	<div class='header1'>
		KODIKLATAU<BR>
		PANGKALAN TNI AU SULAIMAN
	</div>
	<div class='header2'>
	Lampiran XII Laporan Bulanan Kegiatan RSAU Lanud Sulaiman Tanggal <?= date('d F Y',strtotime($end))?>
	</div>
</div>
<div class='header upper'>
	<b>PEMERIKSAAN LABORATORIUM KLINIK <br>BULAN <?= date('F Y',strtotime($end))?></b>
</div>
<div class='si'>
<table>
		<tr>
			<th align=center rowspan="2">No</th>
			<th align=center rowspan="2">Nama Layanan</th>
			<th align=center rowspan="2">Nama Pemeriksaan</th>

			<th colspan="3">TNI AU</th>
			<th colspan="3">Angkatan Lain</th>
			<th align=center rowspan="2">BPJS </th>
			<th align=center rowspan="2">Yanmas</th>
			<th align=center rowspan="2">Jumlah</th>
		</tr>
		<tr align='center'>
			<th scope="col">MIL</th>
			<th scope="col">SIP</th>
			<th scope="col">KEL</th>
			<th scope="col">AD</th>
			<th scope="col">AL</th>
			<th scope="col">POL</th>
		</tr>
		<?php $no=1; foreach($json as $js){ ?>
		<tr>
			<td rowspan=<?= count($js['pemeriksaan'])+1 ?>><?= $no++ ?></td>
			<td rowspan=<?= count($js['pemeriksaan'])+1 ?>><?= $js['nama'] ?></td>
		</tr>
			<?php foreach($js['pemeriksaan'] as $j): ?>
		<tr>
			<td><?= $j['nama'] ?></td>
			<?php foreach($j['katPasien'] as $jk): ?>
			<td><?= $jk['jumlah'] ?></td>
			<?php endforeach; ?>
			<td><?= $j['total'] ?></td>
		</tr>
			<?php endforeach; ?>
		<?php } ?>
		
</table>
</div>
<div class='header'>

	<div class='header3'>
		a.n Komandan Pangkalan TNI AU Sulaiman<br>
		Kepala Rumah Sakit,<br><br><br><br>
		
	</div>
</div>