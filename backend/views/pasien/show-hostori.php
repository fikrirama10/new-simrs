<table class='table table-bordered'>
	<tr>
		<th>No.SEP</th>
		<th>RI/RJ</th>
		<th>Tgl.SEP</th>
		<th>Tgl.Pulang</th>
		<th>Diagnosa</th>
		<th>No.Rujukan</th>
		<th>Spesialis</th>
		<th>PPK Pelayanan</th>
	</tr>
	<?php if($response['metaData']['code'] == 200){ 
	foreach($response['response']['histori'] as $histori):
	$diagnosa = explode("-", $histori['diagnosa']);
	?>
	<tr style='font-size:10px;'>
		<td><?= $histori['noSep']?></td>
		<td><?php if($histori['jnsPelayanan'] == 1){echo'RI';}else{echo'RJ';}?></td>
		<td><?= $histori['tglSep']?></td>
		<td><?= $histori['tglPlgSep']?></td>
		<td><?= $diagnosa[1]?></td>
		<td><?= $histori['noRujukan']?></td>
		<td><?= $histori['poli']?></td>
		<td><?= $histori['ppkPelayanan']?></td>
	</tr>
	<?php 
	endforeach;
		} 
	?>
</table>
