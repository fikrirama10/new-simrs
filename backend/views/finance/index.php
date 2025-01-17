<?php 
use dosamigos\chartjs\ChartJs;
$bulan = array();
$umum = array();
$bpjs = array();
foreach($model as $j):
	array_push($bulan,$j['bulan']);
	array_push($umum,$j['umum']);
	array_push($bpjs,$j['bpjs']);
endforeach;
?>
<div class='row'>
	<div class='box box-body'>
	<div class='col-md-8'>
		<?= ChartJs::widget([
			'type' => 'line',
			'options' => [
				'height' => 80,
				'width' => 200,
				'plgin'=>[
					'title'=>[
						'display'=>true,
						'text'=>'Grafik Pendapatan'
					]
				],
			],
			'data' => [
				'labels' => $bulan,
				'datasets' => [
					[
						'label' => "Pasien Umum",
						'backgroundColor' => "rgba(179,181,198,0.2)",
						'borderColor' => "rgba(179,181,198,1)",
						'pointBackgroundColor' => "rgba(179,181,198,1)",
						'pointBorderColor' => "#fff",
						'pointHoverBackgroundColor' => "#fff",
						'pointHoverBorderColor' => "rgba(179,181,198,1)",
						'data' => $umum
					],
					[
						'label' => "Pasien Bpjs",
						'backgroundColor' => "rgba(255,99,132,0.2)",
						'borderColor' => "rgba(255,99,132,1)",
						'pointBackgroundColor' => "rgba(255,99,132,1)",
						'pointBorderColor' => "#fff",
						'pointHoverBackgroundColor' => "#fff",
						'pointHoverBorderColor' => "rgba(255,99,132,1)",
						'data' => $bpjs
					]
				]
			]
		]);
		?>
	</div>
	<div class='col-md-4'>
		<table class='table table-bordered'>
			<tr>
				<th>Bulan</th>
				<th>Umum</th>
				<th>BPJS</th>
			</tr>
			<tr>
				<?php foreach($model as $j): ?>
				<td><?= $j['bulan'] ?></td>
				<td><?= Yii::$app->algo->IndoCurr(round($j['umum'])) ?></td>
				<td><?= Yii::$app->algo->IndoCurr(round($j['bpjs'])) ?></td>
				<?php endforeach; ?>
			</tr>
		</table>
	</div>
	</div>
</div>
