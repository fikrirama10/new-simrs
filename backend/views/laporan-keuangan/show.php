<?php
	use yii\helpers\Url;
?>
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
			<a target='_blank' href="<?= Url::to(['/laporan-keuangan/detail?start='.$start.'&end='.$end.'&bayar='.$idbayar])?>" class="small-box-footer">
			  More info <i class="fa fa-arrow-circle-right"></i>
			</a> 
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
<h3>Rincian</h3>
<div class='row'>
	
	<?php foreach($rincian['rincian']['rincian'] as $r){ ?>
	<div class='col-md-3'>
		<div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><?= $r['ket']?></span>
              <span class="info-box-number"><?= Yii::$app->algo->IndoCurr($r['jumlah'])?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
	</div>
	<?php } ?>
</div>