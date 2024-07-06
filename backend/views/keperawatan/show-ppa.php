<?php
	$tanggal = date('Y-m-d');
?>
<table class='table table-bordered'>
	<tr>
		<th width=100>Tanggal</th>
		<th width=100>Profesi (PPA)</th>
		<th colspan=2>Hasil Asesmen Pasien , Intruksi & Tindak Lanjut</th>
	</tr>
	<?php if(!$cppta){ ?>
	<tr>
		<td colspan=4>Data Tidak ada</td>
	</tr>
	<?php } ?>
	<?php foreach($cppta as $ca){ ?>
	<tr>
		<td rowspan=5>	
		<?php if($ca->tgl != $tanggal){echo $ca->tgl.'<br>'.date('H:i',strtotime($ca->jam));}else{ ?>
		<a data-toggle="modal" data-target="#mdCp<?=$ca->id?>" href=''><?= $ca->tgl ?><br> (<?= date('H:i',strtotime($ca->jam))?>)</a></td>
		<?php } ?>
		</td>
		<td rowspan=5><?= $ca->profesi ?></td>
	</tr>
	<tr>
		<th width=10>S</td>
		<td><?= $ca->subjektif?></td>
	</tr>
	<tr>
		<th>O</td>
		<td><?= $ca->objektif?></td>
	</tr>
	<tr>
		<th>A</td>
		<td><?= $ca->asesmen?></td>
	</tr>
	<tr>
		<th>P</td>
		<td><?= $ca->plan?></td>
	</tr>
	<tr>
		<td colspan=4></td>
	</tr>
	<?php } ?>
</table>