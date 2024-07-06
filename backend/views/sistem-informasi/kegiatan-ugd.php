<div class='header'>
	<div class='header1'>
		RUMKIT<BR>
		RSAU dr. SISWANTO
	</div>
	<div class='header2'>
	Lampiran XI Laporan Bulanan Kegiatan RSAU Lanud Sulaiman Tanggal <?= date('d F Y',strtotime($end))?>
	</div>
</div>
<div class='header upper'>
	<b>KEGIATAN UGD <br>BULAN <?= date('F Y',strtotime($end))?></b>
</div>
<div class='si'>
<table>
		<tr>
			<th align=center rowspan="2">No</th>
			<th align=center rowspan="2">Nama Kegiatan</th>
			
			<th colspan="4" >TINDAK LANJUT</th>
			<th rowspan="2">JUMLAH</th>
		</tr>
		<tr align='center'>
			<th scope="col">DIRAWAT</th>
			<th scope="col">DIRUJUK</th>
			<th scope="col">PULANG</th>
			<th scope="col">MENINGGAL</th>
			
		</tr>
		<?php $no=1; for($a=0; $a < count($json); $a++){ ?>
		<tr>
			<td align='center'><?=  $no++	?></td>
			<td><?=  $json[$a]['nama']?></td>
			<?php foreach($json[$a]['katPasien'] as $ja): ?>
				<td align='center'><?= $ja['jumlah'] ?></td>
			<?php endforeach; ?>
			<td align='center'><?=  $json[$a]['total']?></td>
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