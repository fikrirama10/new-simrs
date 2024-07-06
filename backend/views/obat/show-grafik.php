<?php
use yii\helpers\ArrayHelper;
use common\models\Poli;
use common\models\RawatBayar;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\Pjax;
use yii\web\View;
use yii\bootstrap\Modal;
use dosamigos\chartjs\ChartJs;
$poli = Poli::find()->all();
$hari = array();
$masuk = array();
$keluar = array();
foreach($model['hari'] as $a):
	array_push($hari,$a['hari']);
	array_push($masuk,$a['masuk']);
	array_push($keluar,$a['keluar']);
endforeach;

?>

<div class='row'>
	<div class='col-md-12'>
	<center><h3>LAPORAN PENGGUNAN  ( <?= $obat ?> ) BULAN <?= $bulan ?> TAHUN <?= $tahun ?></h3></center>
		<?= ChartJs::widget([
		'type' => 'bar',
		'options' => [
			'height' => 80,
			'width' => 300,
			'plugin'=>[
				'title'=>[
					'display'=>true,
					'text'=>'Obat'
				]
			],
		],
		'data' => [
			'labels' => $hari,
			'datasets' => [
				[
					'label' => "Masuk",
					'backgroundColor' => "rgba(179,181,198,0.5)",
					'borderColor' => "RED",
					'pointBackgroundColor' => "rgba(179,181,198,1)",
					'pointBorderColor' => "#fff",
					'pointHoverBackgroundColor' => "#fff",
					'pointHoverBorderColor' => "rgba(179,181,198,1)",
					'data' => $masuk
				],
				[
					'label' => "Keluar",
					'backgroundColor' => "rgba(18, 201, 52,0.5)",
					'borderColor' => "rgba(18, 201, 52,1)",
					'pointBackgroundColor' => "rgba(18, 201, 52,1)",
					'pointBorderColor' => "#fff",
					'pointHoverBackgroundColor' => "#fff",
					'pointHoverBorderColor' => "rgba(18, 201, 52,1)",
					'data' => $keluar
				]
			]
		]
		]);
		?>
		<hr>
	</div>
	
	<div class='col-md-4'>
		<table class='table table-bordered'>
			<tr>
				<th>Barang Masuk</th>
				<th><?= $model['obat_masuk']?></th>
			</tr>
			<tr>
				<th>Barang Keluar</th>
				<th><?= $model['obat_keluar']?></th>
			</tr>
		</table>
	</div>
</div>