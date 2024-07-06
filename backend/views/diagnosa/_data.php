<?php 
use dosamigos\chartjs\ChartJs;
use yii\helpers\Url;
?>
<a class='btn btn-primary btn-sm' target='_blank' href='<?= Url::to(['diagnosa/print-diagnosa?jenis='.$jenis.'&awal='.$awal.'&akhir='.$akhir])?>'><i class='fa fa-print'></i> Print</a>
<?= ChartJs::widget([
    'type' => 'bar',
    'options' => [
        'height' => 50,
        'width' => 200
    ],
    'data' => [
        'labels' => $nama,
        'datasets' => [
            [
                'label' => "Diagnosa",
                'backgroundColor' => "rgba(3, 127, 252,0.7)",
                'borderColor' => "rgba(3, 127, 252,1)",
                'pointBackgroundColor' => "rgba(3, 127, 252,1)",
                'pointBorderColor' => "#fff",
                'pointHoverBackgroundColor' => "#fff",
                'pointHoverBorderColor' => "rgba(3, 127, 252,1)",
                'data' => $total
            ],
        ]
    ]
]);
?>