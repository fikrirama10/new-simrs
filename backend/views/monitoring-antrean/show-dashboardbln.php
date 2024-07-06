<br>
<h3>Dashboard Per Bulan</h3>
<table class='table table-bordered'>
    <tr>
        <th rowspan=2 >No</th>
        <th  rowspan=2>Poli</th>
        <th  rowspan=2>Jumlah Antrean</th>
        <th colspan=6>Taks Id</th>
        <th colspan=6>Avg Taks Id</th>
    </tr>
	<tr>
		<th>T1</th>
		<th>T2</th>
		<th>T3</th>
		<th>T4</th>
		<th>T5</th>
		<th>T6</th>
		
		<th>A1</th>
		<th>A2</th>
		<th>A3</th>
		<th>A4</th>
		<th>A5</th>
		<th>A6</th>
	</tr>
	<?php if($response['metadata']['code'] == 200){ ?>
	<?php $no=1; foreach($response['response']['list'] as $d){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $d['namapoli'] ?></td>
		<td><?= $d['jumlah_antrean'] ?></td>
		<td><?= $d['waktu_task1'] ?></td>
		<td><?= $d['waktu_task2'] ?></td>
		<td><?= $d['waktu_task3'] ?></td>
		<td><?= $d['waktu_task4'] ?></td>
		<td><?= $d['waktu_task5'] ?></td>
		<td><?= $d['waktu_task6'] ?></td>
		
		<td><?= $d['avg_waktu_task1'] ?></td>
		<td><?= $d['avg_waktu_task2'] ?></td>
		<td><?= $d['avg_waktu_task3'] ?></td>
		<td><?= $d['avg_waktu_task4'] ?></td>
		<td><?= $d['avg_waktu_task5'] ?></td>
		<td><?= $d['avg_waktu_task6'] ?></td>
	</tr>
	<?php } ?>
	<?php }else{ ?>
	<tr>
		<td colspan=15><b>Data Tidak itemukan</b></td>
	</tr>
	<?php } ?>
	
</table>