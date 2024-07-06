<div class='header'>
	<div class='header1'>
		RUMKIT<BR>
		RSAU dr. SISWANTO
	</div>
	<div class='header2'>
	Lampiran IV Laporan Bulanan Kegiatan RSAU Lanud Sulaiman Tanggal <?= date('d F Y',strtotime($end))?>
	</div>
</div>
<div class='header upper'>
	<b>JUMLAH KELAHIRAN DAN KEMATIAN IBU KARENA MELAHIRKAN <br>BULAN <?= date('F Y',strtotime($end))?></b>
</div>
<div class='si'>
<table>
		<tr>
			<th align=center rowspan="2">No</th>
			<th align=center rowspan="2">Nama Penyakit</th>

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
		<?php $no=1; for($a=0; $a < count($json); $a++){ ?>
		<tr>
			<td align='center'><?=  $no++	?></td>
			<td><?=  $json[$a]['kelahiran']?></td>
			<?php foreach($json[$a]['katPasien'] as $ja): ?>
				<td align='center'><?= $ja['jumlah'] ?></td>
			<?php endforeach; ?>
			<td align='center'></td>
		</tr>
		<?php } ?>
</table>
</div>
<div class='header'>

	<div class='header3'>
		a.n Komandan Pangkalan TNI AU Sulaiman<br>
		Kepala Rumah Sakit,<br><br><br><br>
		
	</div>
</div>