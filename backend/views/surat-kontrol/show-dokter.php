<?php
	use yii\helpers\Url;
	use yii\web\View;
?>
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
								$('#namadpjp{$kontrol}').val('{$dokter['namaDokter']}');
								$('#kodedpjp{$kontrol}').val('{$dokter['kodeDokter']}');
								$('#listPoli{$kontrol}').hide();
							});
						", View::POS_READY);
					?>
				<?php } ?>
			</table>
		</td>