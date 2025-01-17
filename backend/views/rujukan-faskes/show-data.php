<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\web\View;

if($model){
	$nobpjs = $model->no_bpjs;
}else{
	$nobpjs = '000001000010';
}

$response= Yii::$app->bpjs->histori_pelayanan($nobpjs,date('Y-m-d',strtotime('-60 day')),date('Y-m-d'));
?>

<?php if($response['metaData']['code'] == 200){ ?>
	<table class='table table-bordered'>
		<tr style='font-size:11px;'>
			<th>No</th>
			<th WIDTH=200 >Nama Peserta</th>
			<th>Tgl.Pelanayan</th>
			<th>Jns.Pelanayan</th>
			<th WIDTH=150>Poli.Pelanayan</th>
			<th WIDTH=200>PPK.Pelanayan</th>
			<th>No.SEP</th>
			<th>No.Kartu</th>
			<th>No.Rujukan</th>
		</tr>
		<?php $no=1; foreach($response['response']['histori'] as $histori){ ?>
		<tr style='font-size:11px;'>
			<td><?= $no++?></td>
			<td><?= $histori['namaPeserta']?></td>
			<td><?= $histori['tglSep']?></td>
			<td><?php if($histori['jnsPelayanan'] == 1){echo'Rawat Inap';}else{echo'Rawat Jalan';} ?></td>
			<td><?= $histori['poli']?></td>
			<td><?= $histori['ppkPelayanan']?></td>
			<td><a href='<?= Url::to(['rujukan-faskes/buat-rujukan?id='.$histori['noSep']])?>' class='btn btn-default btn-xs'><?= $histori['noSep']?></a></td>
			<td><?= $histori['noKartu']?></td>
			<td><?= $histori['noRujukan']?></td>
		</tr>
		<?php } ?>
	</table>
<?php }else{ ?>
	<div class="alert alert-danger alert-dismissible">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-ban"></i> Perhatian!</h4>
		<?= $response['metaData']['message'] ?>
	</div>
<?php } ?>