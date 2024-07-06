<table class='table table-bordered'>
	<tr>
		<th width=100>Tgl & Jam</th>
		<th >Implementasi</th>
		<th width=100>Petugas</th>
	</tr>
	<?php if($implementasi){
			foreach($implementasi as $il):
	?>
	<tr>
		<td><?= date('d/m/Y',strtotime($il->tgl)) ?><br> (<?= date('H:i',strtotime($il->jam))?>)</td>
		<td><?= $il->implementasi ?></td>
		<td><?= $il->user->userdetail->nama?></td>
	</tr>
	<?php
		endforeach;
	}?>
	
</table>