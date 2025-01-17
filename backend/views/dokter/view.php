<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use common\models\DokterJadwal;
/* @var $this yii\web\View */
/* @var $model common\models\Dokter */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Dokters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<br>
<div class="dokter-view">
<div class='row'>
	<div class='col-md-6'>
		
		<div class='box'>
			<div class='box-header with-border'><h4>Detail Dokter</h4></div>
			<div class='box-body'>
				<table class='table'>
					<tr>
						<td width='200px'>
							<div style='width:100%;'><?= Html::img(Yii::$app->params['baseUrl'].'/frontend/images/dokter/thumbnail/'.$model->foto, ['alt'=>'no picture', 'class'=>'tb-grid img img-responsive']);?>
						</td>
						<td>
							<?= DetailView::widget([
								'model' => $model,
								'attributes' => [
									'nama_dokter',
									'kode_dokter',
									//'sip',
									'jenis_kelamin',
									'poli.poli',
									'tgl_lahir',
								],
							]) ?>
						</td>
					</tr>
					<tr>
						
					</tr>
				</table>
				
			</div>
			<div class='box-footer'>
			<?= time() ?>
				<a href='<?= Url::to(['/dokter/selesai?id='.$model->id])?>' class='btn btn-success'>Selesai</a>
				<a href='<?= Url::to(['/dokter/update/'.$model->id])?>' class='btn btn-warning'>Edit</a>
			</div>
		</div>
	</div>
	<div class='col-md-6'>
		<div class='box'>
			<div class='box-header with-border'><h4>Jadwal Dokter</h4></div>
			<div class='box-body'>
				<table class='table table-bordered'>
					<tr>
						<th>No</th>
						<th>Hari</th>
						<th>Kuota</th>
						<th>Jam Praktek</th>
						<th>Keterangan</th>
					<tr>
					<?php $no=1; foreach($hari as $h){ 
					$dokter = DokterJadwal::find()->where(['iddokter'=>$model->id])->andwhere(['idhari'=>$h->id])->one();
					?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?=$h->hari ?></td>
						<td><?php if($dokter){echo "<a href='".Url::to(['dokter/edit-jadwal?id='.$dokter->id])."'><span class='badge bg-green'>".$dokter->kuota."</span></a>";}else{echo"<a href='".Url::to(['dokter/tambah-jadwal?id='.$h->id.'&dokter='.$model->id])."'><span class='badge bg-maroon'>+</span></a>";} ?></td>
						<td><?php if(!$dokter){echo '-';}else{echo date('H:i',strtotime($dokter->jam_mulai)).' - '.date('H:i',strtotime($dokter->jam_selesai)) ;} ?></td>
						<?php if($dokter){ ?>
						<td><?php if($dokter->status == 1){echo '<span class="label label-info">Masuk</span>';}else{echo'<span class="label label-warning">Libur</span>';} ?></td>
						<?php }else{ ?>
						<td>Belum ada Jadwal</td>
						<?php } ?>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
</div>
	
    

</div>
