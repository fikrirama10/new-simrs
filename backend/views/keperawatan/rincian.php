<?php
	use common\models\TransaksiDetail;
	use common\models\TransaksiDetailRinci;
	use yii\helpers\Url;
use yii\web\View;
	$total = 0;
?>
<section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-globe"></i> RINCIAN PASIEN.
            <small class="pull-right">Date: <?= date('d/m/Y')?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          Pasien
          <address>
            <strong><?= Yii::$app->kazo->getSbb($model->pasien->usia_tahun,$model->pasien->jenis_kelamin,$model->pasien->idhubungan); ?>. <?= $model->pasien->nama_pasien?></strong><br>
			<p><?= $model->no_rm?></p>
          </address>
        </div>
        <!-- /.col -->
       
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <b>Invoice #</b><br>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th>Tindakan Medis</th>
              <th>Tarif Rp</th>
              <th>Subtotal Rp</th>
            </tr>
            </thead>
            <tbody>
			<?php $total=0; foreach($transaksi as $tr){ 
			$rincian = TransaksiDetailRinci::find()->where(['idrawat'=>$model->id])->andwhere(['tgl'=>$tr->tgl])->all();
			?>
			<tr>
				<td colspan=3><?= $tr->tgl?></td>
			</tr>
			<?php foreach($rincian as $r) :
			$total += $r->tarif;
			?>
			<tr>
				<td><?= $r->tindakan->nama_tarif?></td>
				<td><?= Yii::$app->algo->IndoCurr($r->tindakan->tarif)?></td>
				<td><?= Yii::$app->algo->IndoCurr($r->tarif)?></td>
			</tr>
			<?php endforeach; ?>
			<?php } ?>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-6">
         
          <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
            Biaya Administrasi Ranap akan muncul setelah status pasien di pulangkan dari ruangan
          </p>
        </div>
        <!-- /.col -->
        <div class="col-xs-6">
          <p class="lead">Amount Due <?= date('d/m/Y')?></p>

          <div class="table-responsive">
            <table class="table">
              <tbody><tr>
                <th style="width:50%">Subtotal:</th>
                <td>Rp.<?= Yii::$app->algo->IndoCurr($total)?></td>
              </tr>
             
            </tbody></table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          
          <a class="btn btn-success pull-right" href='<?= Url::to(['/keperawatan/'.$model->id])?>'> Kembali
          </a>
         
        </div>
      </div>
    </section>