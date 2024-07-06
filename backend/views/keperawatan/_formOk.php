<table class='table table-bordered'>
<tr>
	<th>Tindakan</th>
	<th>Keterangan</th>
	<th>Harga</th>
	<th>BHP</th>
	<th>Dokter</th>
</tr>
<?php foreach($okTindakan as $to){ ?>
<tr>
	<td><?= $to->tarif->nama_tarif ?></td>
	<td><?= $to->keterangan_tindakan ?></td>
	<td><?= $to->tarif->tarif ?></td>
	<td><?= $to->harga_bhp ?></td>
	<td><?= $to->dokter->nama_dokter ?></td>
	
</tr>
<?php } ?>
</table>