<?php 
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
use yii\web\View;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
?>
<div class='col-md-12'>
	<div class='box box-body'>
		<?php 
			if(count($model) < 1){
				echo 'tidak ada data transaksi';
			}else{
		?>
			<table class='table table-stripped'>
				<thead>
					<tr>
						<th>No</th>
						<th>Txr Id</th>
						<th>Nama Pasien</th>
						<th>Tgl</th>
						<th>idkunjungan</th>
						<th>#</th>
					</tr>
				</thead>
				<tbody>
					<?php $no=1; foreach($model as $m): ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $m->idtransaksi ?></td>
						<td><?= $m->pasien->nama_pasien ?></td>
						<td><?= $m->tgl_masuk ?></td>
						<td><?= $m->kode_kunjungan ?></td>
						<td><a href='<?= Url::to(['billing/view?id='.$m->id])?>' class='btn btn-success'>Lihat</a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php }?>
	</div>
</div>