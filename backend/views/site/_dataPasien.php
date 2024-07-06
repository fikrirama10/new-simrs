<?php
	use common\models\Rawat;
	use common\models\Pasien;
	use yii\helpers\Url;;
	$rawat = Rawat::find()->where(['status'=>2])->andwhere(['idruangan'=>$b])->all();
?>

<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>No RM</th>
		<th>Nama Pasien</th>
		<th>Tgl Masuk</th>
		<th>Diagnosa Masuk</th>
		<th>Action</th>
	</tr>
	<?php $no=1; foreach($rawat as $r): ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $r->no_rm ?></td>
		<td>
		    <?php
		        $pasien_data = Pasien::find()->where(['no_rm'=>$r->no_rm])->one();
		        if($pasien_data){
		    ?>
		    <?= $r->pasien->nama_pasien ?>
		    <?php } ?>
		 </td>
		<td><?= $r->tglmasuk ?></td>
		<td><?= $r->icdx ?></td>
		<td>
			<a href="<?= Url::to(['admisi/pulang/'.$r->id]) ?>" class='btn btn-warning'>Pulang</a>
		</td>
	</tr>
	<?php endforeach; ?>
</table> 