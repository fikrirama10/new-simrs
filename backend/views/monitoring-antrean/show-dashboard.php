<br>
<h3>Dashboard Pertanggal</h3>
<table class='table table-bordered'>
    <tr>
        <th rowspan=2 >No</th>
        <th  rowspan=2>Poli</th>
        <th  rowspan=2>Jumlah Antrean</th>
        <th colspan=6>Jumlah Taks Id</th>
    </tr>
	<tr>
		<th>T1</th>
		<th>T2</th>
		<th>T3</th>
		<th>T4</th>
		<th>T5</th>
		<th>T6</th>
	</tr>
	<?php if($response['metadata']['code'] == 200){ ?>
	<?php $no=1; foreach($response['response']['list'] as $d){ 
		?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $d['namapoli'] ?></td>
		<td><?= $d['jumlah_antrean'] ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['waktu_task1'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['waktu_task1'])['minutes'].' Menit' ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['waktu_task2'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['waktu_task2'])['minutes'].' Menit' ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['waktu_task3'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['waktu_task3'])['minutes'].' Menit' ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['waktu_task4'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['waktu_task4'])['minutes'].' Menit' ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['waktu_task5'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['waktu_task5'])['minutes'].' Menit' ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['waktu_task6'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['waktu_task6'])['minutes'].' Menit' ?></td>
		
	</tr>
	<?php } ?>
	<?php }else{ ?>
	<tr>
		<td colspan=15><b>Data Tidak itemukan</b></td>
	</tr>
	<?php } ?>
	<tr>
        <th rowspan=2 >No</th>
        <th  rowspan=2>Poli</th>
        <th  rowspan=2>Jumlah Antrean</th>
        <th colspan=6>Rata rata Taks Id</th>
    </tr>
	<tr>		
	<th>A1</th>
		<th>A2</th>
		<th>A3</th>
		<th>A4</th>
		<th>A5</th>
		<th>A6</th>
	</tr>
	<?php if($response['metadata']['code'] == 200){ ?>
	<?php $no=1; foreach($response['response']['list'] as $d){ 
		?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $d['namapoli'] ?></td>
		<td><?= $d['jumlah_antrean'] ?></td>
		
		<td><?=  Yii::$app->kazo->getMenitjam($d['avg_waktu_task1'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['avg_waktu_task1'])['minutes'].' Menit' ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['avg_waktu_task2'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['avg_waktu_task2'])['minutes'].' Menit' ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['avg_waktu_task3'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['avg_waktu_task3'])['minutes'].' Menit' ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['avg_waktu_task4'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['avg_waktu_task4'])['minutes'].' Menit' ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['avg_waktu_task5'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['avg_waktu_task5'])['minutes'].' Menit' ?></td>
		<td><?=  Yii::$app->kazo->getMenitjam($d['avg_waktu_task6'])['hours'] .' Jam '. Yii::$app->kazo->getMenitjam($d['avg_waktu_task6'])['minutes'].' Menit' ?></td>
	</tr>
	<?php } ?>
	<?php }else{ ?>
	<tr>
		<td colspan=15><b>Data Tidak itemukan</b></td>
	</tr>
	<?php } ?>
</table>