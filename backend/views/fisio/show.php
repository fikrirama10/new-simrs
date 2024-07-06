<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\Pasien;
?>
		<div class='box box-body'>
		<?php if(count($rawat) < 1){ ?>
			<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-close"></i> Data tidak ditemukan </h4>
                Silahkan masukan Id Kunjungan dengan benar.
            </div>
		<?php }else{ ?>
			<?php 
				$pasien = Pasien::find()->where(['no_rm'=>$model->no_rm])->one();
			?>
			<table class='table'>
				<tr>
					<th>Nama Pasien</th>
					<td><?=  Yii::$app->kazo->getSbb($pasien->usia_tahun,$pasien->jenis_kelamin,$pasien->idhubungan) ?>. <?= $pasien->nama_pasien ?> (<?= $pasien->jenis_kelamin?>)</td>
					<th>Usia Pasien</th>
					<td><?= $pasien->usia_tahun ?> Thn</td>
					<th>No RM</th>
					<td><?= $pasien->no_rm ?></td>
				</tr>
				
			</table>
			<hr>
			<table class='table table-bordered'>
				<tr>
					<th>IdRawat</th>
					<th>Jenis Rawat</th>
					<th>Poliklinik</th>
					<th>Ruangan</th>
					<th>Tgl Kunjungan</th>
					<th>Penanggung</th>
				</tr>
				<?php foreach($rawat as $r){ ?>
				<tr>
					<td><a target='_blank' href='<?= Url::to(['/fisio/view?id='.$r->id])?>' class='btn btn-default'><?= $r->idrawat?></a></td>
					<td><?= $r->jenisrawat->jenis ?></td>
					<?php if($r->idjenisrawat != 2){ ?>
						<td><?= $r->poli->poli ?></td>
					<?php }else{echo'<td></td>';}?>
					<?php if($r->idjenisrawat == 2){ ?>
						<td><?= $r->ruangan->nama_ruangan ?></td>
					<?php }else{ ?>
						<td>-</td>
					<?php } ?>
					<td><?= $r->tglmasuk ?></td>
					<td><?= $r->bayar->bayar ?></td>
				</tr>
				<?php } ?>
			</table>
		<?php } ?>
		
		</div>