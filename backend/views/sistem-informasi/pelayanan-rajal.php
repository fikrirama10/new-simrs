<div class='header'>
	<div class='header1'>
		RUMKIT<BR>
		RSAU dr. SISWANTO
	</div>
	<div class='header2'>
	Lampiran II Laporan Bulanan Kegiatan RSAU Lanud Sulaiman Tanggal <?= date('d F Y',strtotime($end))?>
	</div>
</div>
<div class='header upper'>
	<b>PELAYANAN RAWAT JALAN <br>BULAN <?= date('F Y',strtotime($end))?></b>
</div>
<div class='judul-kunjungan'>
</div>
<div class='si'>
	<table class='table table-bordered' style='text-align:center;'>
		<tr>
			
			<th align=center rowspan="4">No</th>
			<th align=center width=220 rowspan="4">Jenis Pelayanan Rawat Jalan</th>				
			<th align=center colspan="18">Kunjungan Baru / Ulang</th>
			<th align=center rowspan="4">Total</th>
		
			
		</tr>
		<tr>
			<th scope="col" colspan="6">AU</th>
			<th scope="col" rowspan="2" colspan="2">AD</th>
			<th scope="col" rowspan="2" colspan="2">AL</th>
			<th scope="col" rowspan="2" colspan="2">POLISI</th>
			
			<th align=center rowspan="2" colspan="2">BPJS </th>
			<th align=center rowspan="2" colspan="2">Yanmas</th>
			<th align=center rowspan="2" colspan="2">Jumlah</th>
		</tr>
		<tr>
			<!-- TNI AU -->
			<th scope="col" colspan="2">Mil</th>
			<th scope="col" colspan="2">Sip</th>
			<th scope="col" colspan="2">Kel</th>
		</tr>
		<tr>
			
			
			<th scope="col">B</th>
			<th scope="col">L</th>
			
			<th scope="col">B</th>
			<th scope="col">L</th>
			
			<th scope="col">B</th>
			<th scope="col">L</th>
			
			<th scope="col">B</th>
			<th scope="col">L</th>
			
			
			<th scope="col">B</th>
			<th scope="col">L</th>
			
			<th scope="col">B</th>
			<th scope="col">L</th>
			
			<th scope="col">B</th>
			<th scope="col">L</th>
			
			<th scope="col">B</th>
			<th scope="col">L</th>
			<th scope="col">B</th>
			<th scope="col">L</th>
			
			
		</tr>
		<?php $no=1; for($a=0; $a < count($json); $a++){ ?>
		<tr>
			<td align='center'><?=  $no++	?></td>
			<td><?=  $json[$a]['nama']?></td>
			<?php foreach($json[$a]['katPasien'] as $ja): ?>
				<td align='center'><?= $ja['baru'] ?></td>
				<td align='center'><?= $ja['lama'] ?></td>
			<?php endforeach; ?>
			<td align='center'></td>
			<td align='center'></td>
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