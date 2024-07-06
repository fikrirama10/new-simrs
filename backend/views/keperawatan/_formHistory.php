<?php
	use common\models\SoapRajaldokter;
	use common\models\SoapRajalperawat;
	use common\models\SoapRadiologi;
	use common\models\SoapLab;
	use yii\bootstrap\Modal;
	$dokter = SoapRajaldokter::find()->where(['idrawat'=>$rl->id])->one();
	$perawat = SoapRajalperawat::find()->where(['idrawat'=>$rl->id])->one();
	$radiologi = SoapRadiologi::find()->where(['idrawat'=>$rl->id])->all();
	$lab = SoapLab::find()->where(['idrawat'=>$rl->id])->all();
?>
<?php if($rl->idjenisrawat != 2){ ?>
<?php if($dokter){ ?>
<h4>Pemeriksaan Dokter</h4>
<table class='table table-bordered'>
	<tr>
		<th width=100>Anamnesa</th>
		<th width=10>:</th>
		<td><?= $dokter->anamnesa ?></td>
	</tr>
	<tr>
		<th width=100>Planing</th>
		<th width=10>:</th>
		<td><?= $dokter->planing ?></td>
	</tr>
</table>
<?php } ?>
<?php if($perawat){ ?>
<h4>Pemeriksaan Perawat</h4>
<table class='table table-bordered'>
	<tr>
		<th width=200>Anamnesa</th>
		<th width=10>:</th>
		<td><?= $perawat->anamnesa ?></td>
	</tr>
	<tr>
		<th width=100>Tekanan Darah</th>
		<th width=10>:</th>
		<td><?= $perawat->sistole ?> / <?= $perawat->distole ?></td>
		<th width=100>Suhu</th>
		<th width=10>:</th>
		<td><?= $perawat->suhu ?> </td>
	</tr>
	<tr>
		<th width=100>Respirasi</th>
		<th width=10>:</th>
		<td><?= $perawat->respirasi ?></td>
		<th width=100>Nadi</th>
		<th width=10>:</th>
		<td><?= $perawat->nadi ?> </td>
	</tr>
	<tr>
		<th width=100>SpO2</th>
		<th width=10>:</th>
		<td><?= $perawat->saturasi ?></td>
		<th width=100>Alergi</th>
		<th width=10>:</th>
		<td><?= $perawat->alergi ?> </td>
	</tr>
</table>
<?php } ?>
<?php if(count($radiologi) > 0){ ?>
<table class='table table-bordered'>
		<tr>
			<th>Tindakan Radiologi</th>
			<th>#</th>
		</tr>
		
		<?php if(count($radiologi) > 0) {
				foreach($radiologi as $srl):
		?>
			<tr>
				<td>
				
				<a class='btn btn-sm btn-default'><?= $srl->tindakan->nama_tindakan?></a></td>
				<td>
					<?php if($srl->idhasil == null){ ?>
						<a href='<?= Url::to(['poliklinik/hapus-rad?id='.$srl->id]) ?>' class='btn btn-danger'>Hapus</a>
					<?php }else{ ?>
						<a data-toggle="modal" data-target="#mdRad<?= $srl->id?>" class='btn btn-primary btn-xs'>Lihat</a>
					<?php } ?>
				</td>
			</tr>
		<?php endforeach; 
		}?>
	</table>
<?php } ?>
<?php } ?>
<?php
foreach($radiologi as $srl):
if($srl->idhasil != null){
Modal::begin([
	'id' => 'mdRad'.$srl->id,
	'header' => '<h3>Hasil Radiologi</h3>',
	'size'=>'modal-lg',
	'options'=>[
		'data-url'=>'transaksi',
		'tabindex' => ''
	],
]);

echo '<div class="modalContent">'. $this->render('_formHasilRad', ['model'=>$model,'srl'=>$srl]).'</div>';
 
Modal::end();
}
endforeach;
?>