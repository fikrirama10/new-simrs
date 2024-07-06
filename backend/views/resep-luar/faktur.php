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
<div class='header2'><div class='h2'> FARMASI <?= $s->nama_rs?></div> <div class='h3'><i><?= $s->alamat_rs?></i></div> <div class='h5'><i>Telepon <?= $s->no_tlp?></i></div></div>
<?php endforeach; ?>
<hr>
<div class='center'><h3>Rincian Resep Farmasi</h3></div>
<table>
	<tr>
		<td>Nama Pasien</td>
		<td>:</td>
		<td width=250><?= $model->nama_pasien ?></td>
		<td>Usia</td>
		<td>:</td>
		<td><?= $model->usia ?> th</td>
	</tr>
	<tr>
		<td>NRP</td>
		<td>:</td>
		<td><?= $model->nrp ?></td>
		<td>Tgl Resep</td>
		<td>:</td>
		<td><?= $model->tgl ?></td>
	</tr>
	<tr>
		<td>No Resep</td>
		<td>:</td>
		<td><?= $model->kode_resep ?></td>
		<td>Keterangan</td>
		<td>:</td>
		<td><?= $model->keterangan ?></td>
	</tr>
</table>
<hr>
<div class='olab'>
	<table>
		<tr>
			<th>No</th>
			<th>Nama Obat (Merk)</th>
			<th>Qty</th>
			<th>Harga</th>
			<th>Total</th>
		</tr>
		<?php $total=0; $no=1; foreach($resep as $r){ 
			$total += $r->total ;
		?>
		<tr>
			<td><?= $no++ ?></td>
			<td><?= $r->obat->nama_obat?></td>
			<td><?= $r->jumlah ?></td>
			<td><?= Yii::$app->algo->IndoCurr($r->harga)?></td>
			<td><?= Yii::$app->algo->IndoCurr($r->total)?></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan=4 align=right>Total Harga</td>
			<th>
				<?php if($model->idjenis == 1){ ?>
									<?= Yii::$app->algo->IndoCurr($total)?>
								<?php }else{echo'0,00';}?>
			</th>
		</tr>
		
	</table>
</div>