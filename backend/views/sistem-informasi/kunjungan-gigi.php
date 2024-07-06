<div class='header'>
	<div class='header1'>
		RUMKIT<BR>
		RSAU dr. SISWANTO
	</div>
	<div class='header2'>
	Lampiran V Laporan Bulanan Kegiatan RSAU Lanud Sulaiman Tanggal <?= date('d F Y',strtotime($end))?>
	</div>
</div>
<div class='header upper'>
	<b>PELAYANAN RAWAT INAP <br>BULAN <?= date('F Y',strtotime($end))?></b>
</div>
<div class='si'> 
<table class='table table-bordered' style='text-align:center;'>
	<tr >
		<th align=center rowspan="2">Pengunjung / Kunjungan</th>
		
		<th colspan="3">TNI AU</th>
		<th rowspan="2">TNI AD</th>
		<th rowspan="2">TNI AL</th>
		<th rowspan="2">POLRI</th>
		<th align=center rowspan="2">BPJS </th>
		<th align=center rowspan="2">Yanmas</th>
		<th align=center rowspan="2">Jumlah</th>
	</tr>
	<tr>
		<!-- TNI AU -->
		<th scope="col">Mil</th>
		<th scope="col">Sip</th>
		<th scope="col">Kel</th>
		
		
	</tr>
		
	
	<tr>
	<th id="navi" scope="row">Kunjungan Baru</th>
		<?php foreach($json as $js){ ?>
			<?php foreach($js['arrdip'] as $j){ ?>
			<td><?= $j['kunjungan_baru'] ?></td>
			<?php } ?>
		<td><?= $js['baru'] ?></td>
		<?php } ?>
	</tr>
	<tr>
	<th id="navi" scope="row">Kunjungan Ulang</th>
		<?php foreach($json as $js){ ?>
			<?php foreach($js['arrdip'] as $j){ ?>
			<td><?= $j['kunjungan_lama'] ?></td>
			<?php } ?>
			<td><?= $js['lama'] ?></td>
		<?php } ?>
	</tr>
	<tr>
	<th id="navi" scope="row">Jumlah</th>
		<?php foreach($json as $js){ ?>
			<?php foreach($js['arrdip'] as $j){ ?>
			<td><?= $j['kunjungan'] ?></td>
			<?php } ?>
			<td><?= $js['semua'] ?></td>
		<?php } ?>
	</tr>
	
</table>
</div>
<div class='header'>

	<div class='header3'>
		a.n Komandan Pangkalan TNI AU Sulaiman<br>
		Kepala Rumah Sakit,<br><br><br><br>
		
	</div>
</div>