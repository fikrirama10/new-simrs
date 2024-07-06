<?php 
use yii\helpers\Html;
use Picqer\Barcode\BarcodeGeneratorHTML;
use common\models\SettingSimrs;
use common\models\Rawat;
$setting= SettingSimrs::find()->all();
$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
?>
<?php foreach($setting as $s): ?>
<div class='logo'><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/setting/'.$s->logo_rs,['class' => 'img img-responsive']);?></div>
<div class='header2'><div class='h2'> FARMASI RSAU dr.Norman T Lubis</div> <div class='h3'><i><?= $s->alamat_rs?></i></div> <div class='h5'><i>Telepon <?= $s->no_tlp?></i></div></div>
<?php endforeach; ?>
<hr>
<div class='center'><h5>Rincian Resep</h5></div>
<table>
	<tr>
		<td width=70>Nama Pasien</td>
		<td>:</td>
		<td width=110><?= $model->pasien->nama_pasien ?></td>
		<td>Usia</td>
		<td>:</td>
		<td><?= $model->pasien->usia_tahun ?> th</td>
	</tr>
	<tr>
		<td>No RM</td>
		<td>:</td>
		<td><?= $model->pasien->no_rm ?></td>
		<td>Tgl Lahir</td>
		<td>:</td>
		<td><?= $model->pasien->tgllahir ?></td>
	</tr>
	<tr>
		<td>No Resep</td>
		<td>:</td>
		<td><?= $model->kode_resep ?></td>
		<td>Dokter</td>
		<td>:</td>
		<td><?= $model->rawat->dokter->nama_dokter ?></td>
		
	</tr>
	<tr>
		<td>Tgl Resep</td>
		<td>:</td>
		<td><?= $model->tgl ?></td>
		<td>Poli/Ruangan</td>
		<td>:</td>
		<td>
			<?php if($model->rawat->idjenisrawat == 2){echo $model->rawat->ruangan->nama_ruangan;}else{echo 'Poli '.$model->rawat->poli->poli;} ?>
		</td>
	</tr>
</table>
<hr>
<div class='olab'>
	<table>
		<tr>
			<th>No</th>
			<th>Nama Obat (Merk)</th>
			<th>Qty</th>
			<th>Total</th>
		</tr>
		<?php $total=0; $no=1; foreach($resep as $r){ 
			$total += ceil($r->total) ;
		?>
		<tr>
			<td><?= $no++ ?></td>
			<td><?= $r->obat->nama_obat?></td>
			<td><?= $r->qty ?></td>
			<td align='right'><?= Yii::$app->algo->IndoCurr(round($r->total))?></td>
		</tr>
		<?php } ?>
		<?php if($model->obat_racikan == 1){ ?>
		<tr>
		<td colspan=3 align=right>Jasa Racik</td>
		<th  align='right'><?= Yii::$app->algo->IndoCurr(round($model->jasa_racik))?></th>
		</tr>
		<?php } ?>
		<tr>
			<td colspan=3 align=right>Total Harga</td>
			<?php if($model->obat_racikan == 1){ ?>
			<th  align='right'><?= Yii::$app->algo->IndoCurr(round($total+$model->jasa_racik))?></th>
			<?php }else{ ?>
				<th  align='right'><?= Yii::$app->algo->IndoCurr(round($total))?></th>
			<?php } ?>
		</tr>
		
	</table>
</div>
<?php if(count($resep_kronis) > 0) { ?>
<pagebreak />
<?php foreach($setting as $s): ?>
<div class='logo'><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/setting/'.$s->logo_rs,['class' => 'img img-responsive']);?></div>
<div class='header2'><div class='h2'> FARMASI <?= $s->nama_rs?></div> <div class='h3'><i><?= $s->alamat_rs?></i></div> <div class='h5'><i>Telepon <?= $s->no_tlp?></i></div></div>
<?php endforeach; ?>
<hr>
<div class='center'><h5>Rincian Resep (Kronis)</h5></div>
<table>
	<tr>
		<td width=70>Nama Pasien</td>
		<td>:</td>
		<td width=110><?= $model->pasien->nama_pasien ?></td>
		<td>Usia</td>
		<td>:</td>
		<td><?= $model->pasien->usia_tahun ?> th</td>
	</tr>
	<tr>
		<td>No RM</td>
		<td>:</td>
		<td><?= $model->pasien->no_rm ?></td>
		<td>Tgl Lahir</td>
		<td>:</td>
		<td><?= $model->pasien->tgllahir ?></td>
	</tr>
	<tr>
		<td>No Resep</td>
		<td>:</td>
		<td><?= $model->kode_resep ?></td>
		<td>Dokter</td>
		<td>:</td>
		<td><?= $model->rawat->dokter->nama_dokter ?></td>
		
	</tr>
</table>
<hr>
<div class='olab'>
	<table>
		<tr>
			<th>No</th>
			<th>Nama Obat (Merk)</th>
			<th>Qty</th>
			<th>Total</th>
		</tr>
		<?php $totalrk=0; $no1=1; foreach($resep_kronis as $rk){ 
			$totalrk += $rk->total ;
		?>
		<tr>
			<td><?= $no1++ ?></td>
			<td><?= $rk->obat->nama_obat?></td>
			<td><?= $rk->qty ?></td>
			<td><?= Yii::$app->algo->IndoCurr($rk->total)?></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan=3 align=right>Total Harga</td>
			<th><?= Yii::$app->algo->IndoCurr($totalrk)?></th>
		</tr>
		
	</table>
</div>
<?php }?>