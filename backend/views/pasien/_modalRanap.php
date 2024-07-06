<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\View;
use yii\bootstrap\Modal;
use yii\web\JsExpression;
use yii\widgets\DetailView;
use common\models\Dokter;
use common\models\RawatPermintaanPindah;
use common\models\RawatRuangan;
use common\models\Rawat;
$ranap = Rawat::find()->where(['idjenisrawat'=>2])->andwhere(['idkunjungan'=>$kunjungan->idkunjungan])->one();

?>

<?php if($ranap){ 
	$ruanganlist = RawatRuangan::find()->where(['idrawat'=>$ranap->id])->all();
?>
	<h4>Riwayat Ruangan</h4>
	<table class="table table-bordered">
		<tr>
			<th>Nama Ruangan</th>
			<th>Kelas</th>
			<th>Tgl Masuk </th>
			<th>Tgl Keluar </th>
			<th>Penanggung </th>
			<th>Status </th>
		</tr>
		<?php foreach($ruanganlist as $rl): ?>
		<tr>
			<td><?= $rl->ruangan->nama_ruangan ?></td>
			<td><?= $rl->ruangan->idkelas0->kelas ?></td>
			<td><?= $rl->tgl_masuk ?></td>
			<td><?= $rl->tgl_keluar ?></td>
			<td><?= $rl->bayar->bayar ?></td>
			<td>
				<?php if($rl->status == 1){echo '<span class="label bg-green">
                    Aktif
                  </span>';}else{echo '<span class="label bg-red">
                    Non Aktif
                  </span>';} ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>

<?php } ?>