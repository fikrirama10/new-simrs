<?php 
use dosamigos\chartjs\ChartJs;
$nama = array();
$laki = array();
$perempuan = array();
$total = array();
foreach($model['jenjangUsia'] as $j):
	array_push($nama,$j['nama']);
	array_push($laki,$j['laki']);
	array_push($perempuan,$j['perempuan']);
	array_push($total,$j['total']);
endforeach;
?>
<div class='row'>
	<div class='col-md-8'>
		<?= ChartJs::widget([
    'type' => 'bar',
    'options' => [
        'height' => 80,
        'width' => 200,
		'plgin'=>[
			'title'=>[
				'display'=>true,
				'text'=>'Jenjang Usia'
			]
		],
    ],
    'data' => [
        'labels' => $nama,
        'datasets' => [
            [
                'label' => "Laki Laki",
                'backgroundColor' => "rgba(179,181,198,0.2)",
                'borderColor' => "rgba(179,181,198,1)",
                'pointBackgroundColor' => "rgba(179,181,198,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' => $laki
            ],
            [
                'label' => "Perempuan",
                'backgroundColor' => "rgba(255,99,132,0.2)",
                'borderColor' => "rgba(255,99,132,1)",
                'pointBackgroundColor' => "rgba(255,99,132,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(255,99,132,1)",
                'data' => $perempuan
            ]
        ]
    ]
]);
?>
	</div>
	<div class='col-md-4'>
		<table class='table table-bordered'>
			<tr>
				<th rowspan=2>No</th>
				<th rowspan=2>Jenjang Usia</th>
				<th colspan=2>Jumlah</th>
			</tr>
			<tr>
				<th>Laki laki</th>
				<th>Perempuan</th>
			</tr>
			<?php $no=1; foreach($model['jenjangUsia'] as $ag){ ?>
			<tr>
				<td><?= $no++ ?></td>
				<td><?= $ag['nama'] ?></td>
				<td><?= $ag['laki'] ?></td>
				<td><?= $ag['perempuan'] ?></td>
			</tr>
			<?php } ?>
		</table>
	</div>
</div>
