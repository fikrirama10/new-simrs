<?php
use yii\helpers\Url;
use yii\web\View;
?>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Nama Spesialis</th>
		<th>Kapasitas</th>
		<th>Jumlah Rujukan</th>
		<th>Prosentase</th>
	</tr>
	<?php  $no=1; foreach($dataPoli['response']['list'] as $poli){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td><?= $poli['namaPoli'] ?></td>
		<td><?= $poli['kapasitas'] ?></td>
		<td><?= $poli['jmlRencanaKontroldanRujukan'] ?></td>
		<td><?= $poli['persentase'] ?></td>
	</tr>
	<?php } ?>
	<tr style='background-color:#d9edf7;'>
		<td colspan=5>
			<table class='table table-bordered'>
				<tr>
					<th>No</th>
					<th>Nama Dokter</th>
					<th>Jadwal Praktek</th>
					<th>Kapasitas</th>
					<th>#</th>
				</tr>
				<?php $no1=1; foreach($dataDokter['response']['list'] as $dokter){ ?>
				<tr>
					<td><?= $no1++?></td>
					<td><?= $dokter['namaDokter']?></td>
					<td><?= $dokter['jadwalPraktek']?></td>
					<td><?= $dokter['kapasitas']?></td>
					<td><a class='btn btn-success btn-xs' id='pilihdokter<?= $dokter['kodeDokter']?>'><span class='fa fa-check'></span></a></td>
				
				</tr>
					<?php 
						$this->registerJs("
							$('#pilihdokter{$dokter['kodeDokter']}').on('click',function(e) {
								alert('Berhasil pilih {$dokter['namaDokter']}');
								$('#nama_dokter').val('{$dokter['namaDokter']}');
								$('#kode_dokter').val('{$dokter['kodeDokter']}');
								$('#dataPoli').hide();
							});
						", View::POS_READY);
					?>
				<?php } ?>
			</table>
		</td>
	</tr>
</table>