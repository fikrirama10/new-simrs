<?php
	use yii\helpers\Url;
	use yii\web\View;
?>
<hr>
<table class='table table-bordered'>
	<tr>
		<th>No</th>
		<th>Nama Spesialis</th>
		<th>Kapasitas</th>
		<th>Jumlah Rujukan</th>
		<th>Prosentase</th>
	</tr>
	<?php  $no=1; foreach($datapoli['response']['list'] as $poli){ ?>
	<tr>
		<td><?= $no++ ?></td>
		<td>
			<a class='btn btn-default btn-xs' id='pilihPoli<?= $poli['kodePoli']?>'><?= $poli['namaPoli'] ?></a>
			<input type='hidden' id='input<?= $poli['kodePoli']?>' value='<?= $poli['kodePoli']?>'>
			<input type='hidden' id='nama<?= $poli['kodePoli']?>' value='<?= $poli['namaPoli']?>'>
		</td>
		<td><?= $poli['kapasitas'] ?></td>
		<td><?= $poli['jmlRencanaKontroldanRujukan'] ?></td>
		<td><?= $poli['persentase'] ?></td>
	</tr>
		<?php 
			$urlDokter = Url::to(['surat-kontrol/show-dokter']);
			$this->registerJs("
				$('#pilihPoli{$poli["kodePoli"]}').on('click',function(e) {
					poli = $('#input{$poli["kodePoli"]}').val();
					namapoli = $('#nama{$poli["kodePoli"]}').val();
					tgl = '{$tgl}';
					kontrol = '{$kontrol}';
					$.ajax({
						type: 'GET',
						url: '{$urlDokter}',
						data: 'poli='+poli+'&tgl='+tgl+'&kontrol='+kontrol,
						
						success: function (data) {
							// alert('{$kontrol}');
							$('#listDokter{$kontrol}').html(data);
							$('#kodepoli{$kontrol}').val(poli);
							$('#namapoli{$kontrol}').val(namapoli);
							
							console.log(data);
							
						},
						
					});
				});
			", View::POS_READY);
		?>
	<tr style='background-color:#d9edf7;' id='listDokter<?= $kontrol?>'></tr>
	<?php } ?>
</table>