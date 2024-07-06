<?php 
use dosamigos\chartjs\ChartJs;
$nama = array();
$umum = array();
$bpjs = array();
foreach($model['Layanan'] as $j):
	array_push($nama,$j['nama']);
	array_push($umum,$j['jumlahUmum']);
	array_push($bpjs,$j['jumlahBpjs']);
endforeach;
?>
<?= ChartJs::widget([
    'type' => 'bar',
    'options' => [
        'height' => 50,
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
                'label' => "Umum",
                'backgroundColor' => "rgba(179,181,198,0.2)",
                'borderColor' => "rgba(179,181,198,1)",
                'pointBackgroundColor' => "rgba(179,181,198,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(179,181,198,1)",
                'data' => $umum
            ],
            [
                'label' => "BPJS",
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
<hr>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Jenis Pemeriksaan</th>
		<th>Umum</th>
		<th>BPJS</th>
	</tr>
	<?php $no=1; foreach($model['Layanan'] as $pel){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $pel['nama'] ?></td>
		<td><?= $pel['jumlahUmum'] ?></td>
		<td><?= $pel['jumlahBpjs'] ?></td>
	</tr>
	<?php } ?>
</table>