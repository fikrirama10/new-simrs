<?php

/* @var $this yii\web\View */
use yii\bootstrap\Modal;
use dosamigos\chartjs\ChartJs;
use common\models\Poli;
$bulan = array();
$umum = array();
$bpjs = array();
foreach($model as $j):
	array_push($bulan,$j['bulan']);
	array_push($umum,$j['umum']);
	array_push($bpjs,$j['bpjs']);
endforeach;
$this->title = 'sim-RS';
?>	
<div class="site-index">
    <!--<img src="http://127.0.0.1:8882/image/LOGO_RUMKIT_SULAIMAN__2_-removebg-preview.png">-->
 <?php if(Yii::$app->user->identity->userdetail->managemen == 1){ ?>
 <div class='box box-body' style='background:#222d32; -webkit-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);
-moz-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);
box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);'>
	<h3 style="color:#fff;">Grafik Penerimaan Tahun <?= date('Y')?></h3><hr/>
</div>
	
	<div class='box box-body'>
	 <div class='row'>
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

 <?php } ?>
<div class='box box-body' style='background:#222d32; -webkit-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);
-moz-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);
box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);'>
	<h3 style="color:#fff;">Ketersediaan Tempat Tidur</h3><hr/>
</div>
<div class='row'>
	<?php foreach($bed as $b){ ?>
		<a href='#'  data-toggle="modal" data-target="#modal<?= $b['id']?>">
			<div class="col-md-4 col-sm-6 col-xs-12">
			  <div class="info-box" style='-webkit-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);-moz-box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);box-shadow: 11px 11px 7px -6px rgba(97,97,97,1);'>

				<span class="info-box-icon bg-navy"><i class='fa fa-bed'></i></span>
					
				<div class="info-box-content">
				  <span class="info-box-text"><?= $b['ruangan']?> ( <?= $b['bed']?> BED )</span>
				  <span class="info-box-number"> <?= $b['bed'] - $b['terisi']?>  Kosong</span> 
				  <span class="info-box-text">Kelas <?php if($b['kelas'] == 4){echo 'VIP';}else{echo $b['kelas'];} ?></span>
				</div> 
				
				
				<!-- /.info-box-content -->
			  </div> 
			  <!-- /.info-box -->
			</div>
		</a>
	<?php } ?>
</div>

<div class='box box-body'>
	<h3>Kunjungan Pasien</h3> <a href="" class='btn btn-sm btn-success' data-toggle="modal" data-target="#modalLaporan">Laporan Kunjungan Pasien</a> <hr>
	<div class='row'>
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3><?= $kunjungan['pasien']?></h3>

					<p>Pasien Terdaftar</p>
				</div>
			<div class="icon">
				<i class="fa fa-users"></i>
			</div>
				<a href='' data-toggle="modal" data-target="#modalPasien" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-xs-6">
		<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h3><?= $kunjungan['ugd']?></h3>

					<p>Pasien UGD</p>
				</div>
				<div class="icon">
					<i class="fa fa-ambulance"></i>
				</div>
					<a data-toggle="modal" data-target="#modalUgd" class="small-box-footer">
						More info <i class="fa fa-arrow-circle-right"></i>
					</a>
			</div>
		</div>
					
		<div class="col-lg-3 col-xs-6">
		<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
				<h3><?= $kunjungan['ranap']?></h3> 

				<p>Pasien Rawat Inap</p>
				</div>
				<div class="icon">
				<i class="fa fa-hospital-o"></i>
				</div>
				<a data-toggle="modal" data-target="#modalRanap" href="#" class="small-box-footer">
				More info <i class="fa fa-arrow-circle-right"></i>
				</a>
			</div>
		</div>
					
		<div class="col-lg-3 col-xs-6">
		<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
				<h3><?= $kunjungan['rajal']?></h3>

				<p>Pasien Rawat jalan</p>
				</div>
				<div class="icon">
				<i class="fa fa-stethoscope"></i>
				</div>
				<a data-toggle="modal" data-target="#modalRajal" href="" class="small-box-footer">
				More info <i class="fa fa-arrow-circle-right"></i>
				</a>
			</div>
		</div>							
	</div>
</div>
</div>
<?php

foreach($bed as $b):
	Modal::begin([
		'id' => 'modal'.$b['id'],
		'header' => '<h3>'.$b['ruangan'].'</h3>',
		'size'=>'modal-lg',
		'options'=>[
			'data-url'=>'transaksi',
			'tabindex' => ''
		],
	]);

	echo '<div class="modalContent">'. $this->render('_dataPasien', ['b'=>$b['id']]).'</div>';
	 
	Modal::end();
	
endforeach;

Modal::begin([
	'id' => 'modalLaporan',
	'header' => '<h3>Laporan Pasien</h3>'.date('Y-m-d H:i:s',strtotime('+7 hour ,-1 day')),
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);
$poli = Poli::find()->where(['between','id',1,6])->orderBy(['id'=>'SORT_DESC'])->all();
echo '<div class="modalContent">'.  $this->render('_laporan', ['poli'=>$poli]).'</div>';
 
Modal::end();

Modal::begin([
	'id' => 'modalPasien',
	'header' => '<h3>Pasien Baru</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_pasien', ['pasien'=>$pasien]).'</div>';
 
Modal::end();
Modal::begin([
	'id' => 'modalRajal',
	'header' => '<h3>Pasien Rawat Jalan</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_rajal', ['rajal'=>$rajal]).'</div>';
 
Modal::end();

Modal::begin([
	'id' => 'modalRanap',
	'header' => '<h3>Pasien Rawat Inap</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_ranap', ['ranap'=>$ranap]).'</div>';
 
Modal::end();

Modal::begin([
	'id' => 'modalUgd',
	'header' => '<h3>Pasien UGD</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_ugd', ['ugd'=>$ugd]).'</div>';
 
Modal::end();


?>