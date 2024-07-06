<?php
	use yii\helpers\Url;
	use yii\bootstrap\Modal;
?>
<div class='box'>
	<div class='box-header with-border'>
		<h4>Detail Rincian <?= $rincian['judul'] ?> <?= $start ?> - <?= $end ?></h4>
	</div>
	<div class='box-body'>
		<div class='row'>
			<div class="col-lg-12 col-xs-12">
				<div class="small-box" style='background:<?= $rincian['bg'] ?>;'>
					<div class="inner">
					 <h3 style="color:#fff;" class='text-center'>Rp. <?= Yii::$app->algo->IndoCurr($rincian['total_penerimaan'])?></h3>	
					 <p style="color:#fff;" class='text-center'><?= $rincian['judul'] ?></p>
					</div>	
					<div class="icon ">
					  <i class="fa fa-money"></i>
					</div>		
					
				</div>
			</div>
			<div class="col-lg-6 col-xs-6">
				<div class="small-box" style='background:#ddd;'>
			<div class="inner">
			 <h3 style="color:#fff;" class='text-center'>Rp. <?= Yii::$app->algo->IndoCurr($rincian['total_penerimaan_tarif_manual'])?></h3>	
			 <p style="color:#fff;" class='text-center'>Penerimaan Tarif Manual</p>
			</div>	
			<div class="icon ">
			  <i class="fa fa-money"></i>
			</div>		
			
		</div>
	</div>
	<div class="col-lg-6 col-xs-6">
				<div class="small-box" style='background:red;'>
			<div class="inner">
			 <h3 style="color:#fff;" class='text-center'>Rp. <?= Yii::$app->algo->IndoCurr($rincian['bhp_ok'])?></h3>	
			 <p style="color:#fff;" class='text-center'>Penerimaan BHP OK</p>
			</div>	
			<div class="icon ">
			  <i class="fa fa-money"></i>
			</div>		
			
		</div>
	</div>
		</div>
	</div>
</div>

<div class='box'>
	<div class='box-body'>
		<div class="col-md-4">
			<p class="text-center">
			<strong>Penerimaan Ruangan</strong>
			</p>
			<?php foreach($ruangan['rincian'] as $r){ 
			$persen = $r['total'] / $rincian['total_penerimaan']; ?>
				<div class="progress-group">
				<a data-toggle="modal" data-target="#mdDetail<?=$r['id']?>" href=''><span class="progress-text">
				<?= $r['ruangan'] ?></span></a>
				
				<span class="progress-number"><b> <?= Yii::$app->algo->IndoCurr($r['total'])?></b>/ <?= Yii::$app->algo->IndoCurr($rincian['total_penerimaan'])?> </span>
	
				<div class="progress sm">
				<div class="progress-bar progress-bar-aqua" style="width:<?= round($persen * 100)?>%"></div>
				</div>
				</div>
			<?php } ?>
		</div>
		<div class="col-md-4">
			<p class="text-center">
			<strong>Penerimaan Ruangan Rawat</strong>
			</p>
			<?php foreach($rawat['rincian'] as $ra){  
			$persen2= $ra['total'] / $rawat['jumlah'];
			?>
				<div class="progress-group">
				<a data-toggle="modal" data-target="#mdDetailRuangan<?=$ra['id']?>" href=''><span class="progress-text">
				<?= $ra['ruangan'] ?></span></a>
				<span class="progress-number"><b> <?= Yii::$app->algo->IndoCurr($ra['total'])?></b>/ <?= Yii::$app->algo->IndoCurr($rawat['jumlah'])?> </span>
	
				<div class="progress sm">
				<div class="progress-bar progress-bar-danger" style="width:<?= round($persen2 * 100)?>%"></div>
				</div>
				</div>
			<?php } ?>
		</div>
		
		<div class="col-md-4">
			<p class="text-center">
			<strong>Penerimaan Poli Spesialis</strong>
			</p>
			<?php foreach($poli['rincian'] as $p){  
			$persen3= $p['total'] / $poli['total'];
			?>
				<div class="progress-group">
				<a data-toggle="modal" data-target="#mdDetailPoli<?=$p['id']?>" href=''><span class="progress-text">
				<?= $p['poli'] ?></span></a>
				<span class="progress-number"><b> <?= Yii::$app->algo->IndoCurr($p['total'])?></b>/ <?= Yii::$app->algo->IndoCurr($poli['total'])?> </span>
	
				<div class="progress sm">
				<div class="progress-bar progress-bar-success" style="width:<?= round($persen3 * 100)?>%"></div>
				</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>


		<div class='row'>
			<div class='col-md-6'>
				<div class='box box-primary'>
				<div class='box-header with-border'><h3 class="box-title">Jasa Dokter Poli Spesialis</h3>
				<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
				</div>
				<div class='box-body'>
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Dokter</th>
								<th>Poli</th>
								<th>Jasa</th>
								<th>Operator OK</th>
							</tr>
						</thead>
						<tbody>
							<?php $no=1; $jasa =0; $operator=0; foreach($spesialis as $sp){ 
							$jasa += $sp['jasa'];
							$operator += $sp['operator'];
							?>
							<tr>
								<td><?= $no++ ?></td>
								<td><a data-toggle="modal" data-target="#mdDetailDokter<?=$sp['id']?>" class='btn btn-xs btn-default'><?= $sp['dokter'] ?></a></td>
								<td><?= $sp['poli'] ?></td>
								<td><span class="label label-warning">Rp. <?= Yii::$app->algo->IndoCurr($sp['jasa'])?></span></td>
								<td><span class="label label-success">Rp. <?= Yii::$app->algo->IndoCurr($sp['operator'])?></span></td>
							</tr>
							<?php } ?>
							<tr>
								<td colspan=3></td>
								<td><b><p class="text-yellow">Rp. <?= Yii::$app->algo->IndoCurr($jasa) ?></p></b></td>
								<td><b><p class="text-green">Rp. <?= Yii::$app->algo->IndoCurr($operator) ?></p></b></td>
							</tr>
						</tbody>
					</table>
              </div>
			  </div>
			</div>
			</div>
			<div class='col-md-6'>
				<div class='box box-primary'>
				<div class='box-header with-border'><h3 class="box-title">Jasa Dokter UGD Spesialis</h3>
				<div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
				</div>
				<div class='box-body'>
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Dokter</th>
								<th>Jasa</th>
								<th>Operator OK</th>
							</tr>
						</thead>
						<tbody>
							<?php $no2=1; $jasa2 =0; $operator2=0; foreach($ugd as $ug){ 
							$jasa2 += $ug['jasa'];
							$operator2 += $ug['operator'];
							?>
							<tr>
								<td><?= $no2++ ?></td>
								<td><a data-toggle="modal" data-target="#mdDetailDokterUgd<?=$ug['id']?>" class='btn btn-xs btn-default'><?= $ug['dokter'] ?></a></td>
								<td><span class="label label-warning">Rp. <?= Yii::$app->algo->IndoCurr($ug['jasa'])?></span></td>
								<td><span class="label label-success">Rp. <?= Yii::$app->algo->IndoCurr($ug['operator'])?></span></td>
							</tr>
							<?php } ?>
							<tr>
								<td colspan=2></td>
								<td><b><p class="text-yellow">Rp. <?= Yii::$app->algo->IndoCurr($jasa2) ?></p></b></td>
								<td><b><p class="text-green">Rp. <?= Yii::$app->algo->IndoCurr($operator2) ?></p></b></td>
							</tr>
						</tbody>
					</table>
              </div>
			  </div>
			</div>
			</div>
		</div>
<?php 
foreach($ruangan['rincian'] as $r):
	Modal::begin([
	'id' => 'mdDetail'.$r['id'],
	'header' => $r['ruangan'],
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);
	echo"
		<table class='table table-bordered'>
		<tr>
			<th>Rincian</th>
			<th>Jumlah</th>
		</tr>
	";
	foreach($r['rincian'] as $rc){
		echo'<tr>';
			echo '<td>'.$rc['tindakan'].'</td>';
			echo '<td>'.Yii::$app->algo->IndoCurr($rc['jumlah']).'</td>';
		echo'</tr>';
	}
	echo'</table>';
	// echo '<div class="modalContent">'. $this->render('_formDetailRuangan', ['r'=>$r]).'</div>';
	 
	Modal::end();
	endforeach;

foreach($rawat['rincian'] as $ra):
Modal::begin([
'id' => 'mdDetailRuangan'.$ra['id'],
'header' => $ra['ruangan'],
'size'=>'modal-lg',
'options'=>[
	'data-url'=>'transaksi',
	'tabindex' => ''
],
]);
echo"
	<table class='table table-bordered'>
	<tr>
		<th>Rincian</th>
		<th>Jumlah</th>
	</tr>
";
foreach($ra['rincian'] as $rca){
	echo'<tr>';
		echo '<td>'.$rca['tindakan'].'</td>';
		echo '<td>'.Yii::$app->algo->IndoCurr($rca['jumlah']).'</td>';
	echo'</tr>';
}
echo'</table>';
// echo '<div class="modalContent">'. $this->render('_formDetailRuangan', ['r'=>$r]).'</div>';
 
Modal::end();
endforeach;

foreach($poli['rincian'] as $p):
Modal::begin([
'id' => 'mdDetailPoli'.$p['id'],
'header' => $p['poli'],
'size'=>'modal-lg',
'options'=>[
	'data-url'=>'transaksi',
	'tabindex' => ''
],
]);
echo"
	<table class='table table-bordered'>
	<tr>
		<th>Rincian</th>
		<th>Jumlah</th>
	</tr>
";
foreach($p['rincian'] as $pa){
	echo'<tr>';
		echo '<td>'.$pa['tindakan'].'</td>';
		echo '<td>'.Yii::$app->algo->IndoCurr($pa['jumlah']).'</td>';
	echo'</tr>';
}
echo'</table>';
// echo '<div class="modalContent">'. $this->render('_formDetailRuangan', ['r'=>$r]).'</div>';
 
Modal::end();
endforeach;

foreach($spesialis as $sp):
Modal::begin([
'id' => 'mdDetailDokter'.$sp['id'],
'header' => $sp['dokter'],
'size'=>'modal-lg',
'options'=>[
	'data-url'=>'transaksi',
	'tabindex' => ''
],
]);
echo"
	<table class='table table-bordered'>
	<tr>
		<th>Tindakan</th>
		<th>Jumlah</th>
		<th>Tarif</th>
		<th>Total</th>
	</tr>
";
foreach($sp['tindakan'] as $spa){
	echo'<tr>';
		echo '<td>'.$spa['tindakan'].'</td>';
		echo '<td>'.$spa['jumlah'].' x</td>';
		echo '<td>'.$spa['tarif'].'</td>';
		echo '<td>'.Yii::$app->algo->IndoCurr($spa['total']).'</td>';
	echo'</tr>';
}
	echo'<th colspan=3>Total</th>';
	echo'<td>'.Yii::$app->algo->IndoCurr($sp['jasa']).'</td>';
echo'</table>';

echo"
	<h4>Tindakan OK</h4>
	<table class='table table-bordered'>
	<tr>
		<th>Tindakan</th>
		<th>Jumlah</th>
		<th>Tarif</th>
		<th>Total</th>
	</tr>
";
foreach($sp['tindakanop'] as $spaop){
	echo'<tr>';
		echo '<td>'.$spaop['tindakan'].'</td>';
		echo '<td>'.$spaop['jumlah'].' x</td>';
		echo '<td>'.$spaop['tarif'].'</td>';
		echo '<td>'.Yii::$app->algo->IndoCurr($spaop['total']).'</td>';
	echo'</tr>';
}
	echo'<th colspan=3>Total</th>';
	echo'<td>'.Yii::$app->algo->IndoCurr($sp['operator']).'</td>';
echo'</table>';
// echo '<div class="modalContent">'. $this->render('_formDetailRuangan', ['r'=>$r]).'</div>';
 
Modal::end();
endforeach;

foreach($ugd as $ug):
Modal::begin([
'id' => 'mdDetailDokterUgd'.$ug['id'],
'header' => $ug['dokter'],
'size'=>'modal-lg',
'options'=>[
	'data-url'=>'transaksi',
	'tabindex' => ''
],
]);
echo"
	<table class='table table-bordered'>
	<tr>
		<th>Tindakan</th>
		<th>Jumlah</th>
		<th>Tarif</th>
		<th>Total</th>
	</tr>
";
foreach($ug['tindakan'] as $u){
	echo'<tr>';
		echo '<td>'.$u['tindakan'].'</td>';
		echo '<td>'.$u['jumlah'].' x</td>';
		echo '<td>'.$u['tarif'].'</td>';
		echo '<td>'.Yii::$app->algo->IndoCurr($u['total']).'</td>';
	echo'</tr>';
}
	echo'<th colspan=3>Total</th>';
	echo'<td>'.Yii::$app->algo->IndoCurr($ug['jasa']).'</td>';
echo'</table>';
// echo '<div class="modalContent">'. $this->render('_formDetailRuangan', ['r'=>$r]).'</div>';
 
Modal::end();
endforeach;

?>