<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
?>
<div class='row'>
<div class='col-md-7'>
	<div class='box box-body'>
		<table class='table table-bordered'>
			<tr style='font-size:9px;'>
				<th>Tgl Kunjungan</th>
				<th>Jenis Kunjungan</th>
				<th>Ruangan</th>
				<th>Poli</th>
				<th>Dokter</th>
				<th>#</th>
			</tr>
			<?php if(count($rawat) < 0){ ?>
			<tr>
				<td colspan=6>
					<div class="alert alert-danger alert-dismissible">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-ban"></i> Alert!</h4>
						Danger alert preview. This alert is dismissable. A wonderful serenity has taken possession of my entire
						soul, like these sweet mornings of spring which I enjoy with my whole heart.
					</div>
				</td>
			</tr>
			<?php }else{ ?>
				<div class="alert alert-info alert-dismissible">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-info"></i> Perhatian!</h4>
					Perhatikan data Kunjungan Pasien (Tgl Kunjungan , Jenis Kunjungan , dan data lainnya) , untuk keakuratan data
				</div>
				<?php foreach($rawat as $r){ ?>
				<tr style='font-size:9px;'>
					<td width=100><b><?= date('d/m/Y',strtotime($r->tglmasuk))?></b></td>
					<td width=100><?= $r->jenisrawat->jenis?></td>
					<td width=120><?= $r->ruangan->nama_ruangan?></td>
					<td width=120><?= $r->poli->poli?></td>
					<td width=150><?= $r->dokter->nama_dokter?></td>
					<td width=50><a href='<?= Url::to(['view?id='.$r->id])?>' class='btn btn-success btn-xs'>+ Resep</a></td>
				</tr>
				<?php } ?>
			<?php } ?>
		</table>
	</div>
</div>
</div>