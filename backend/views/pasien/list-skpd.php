<?php
use yii\helpers\Url;
use yii\web\View;
 if($list_skpd['metaData']['code'] == 200){ ?>
<br>
	<div class="callout callout-success">
		<h5>Data ditemukan</h5>
	</div>
	<table class='table table-bordered'>
		<tr>
			<th>No Surat Kontrol</th>
			<th>Jenis Layanan</th>
			<th>Tgl Rencana</th>
			<th>Tgl Entry</th>
			<th>Poli Tujuan</th>
			<th>Tgl SEP</th>
			<th>SEP Asal</th>
			<th>Nama Dokter</th>
			<th>Terbit SEP</th>
		</tr>
		<?php foreach($list_skpd['response']['list'] as $list){ ?>
		<tr style='font-size:10px;'>
			<td>
				<?php if($list['terbitSEP'] == 'Belum'){ ?>
				<a class='btn btn-default btn-xs' id='pilih<?= $list['noSuratKontrol']?>'><?= $list['noSuratKontrol']?></a><input type='hidden' id='inputskpd<?= $list['noSuratKontrol']?>' value='<?= $list['noSuratKontrol']?>'>
				<?php }else{ ?>
					<?= $list['noSuratKontrol']?>
				<?php } ?>
			</td>
			<td><?= $list['jnsPelayanan']?></td>
			<td><?= $list['tglRencanaKontrol']?></td>
			<td><?= $list['tglTerbitKontrol']?></td>
			<td><?= $list['namaPoliTujuan']?></td>
			<td><?= $list['tglSEP']?></td>
			<td><?= $list['noSepAsalKontrol']?></td>
			<td><?= $list['namaDokter']?></td>
			<td><?= $list['terbitSEP']?></td>
		</tr>
		
		<?php 
			$tglrawat = date('Y-m-d',strtotime($rawat->tglmasuk));
			$this->registerJs("
				$('#pilih{$list['noSuratKontrol']}').on('click',function(e) {
					dpjp = '{$rawat->dokter->kode_dpjp}';
					dpjpkontrol = '{$list['kodeDokter']}';
					tglkontrol = '{$list['tglRencanaKontrol']}';
					tglsep = '{$tglrawat}';
					if(dpjpkontrol != dpjp){
						alert('Dokter tidak sesuai');
					}else if(tglkontrol != tglsep){
						alert('Tgl tidak sesuai');
					}else{
						$('#no_skpd').val($('#inputskpd{$list['noSuratKontrol']}').val());
						$('#noskpd').val($('#inputskpd{$list['noSuratKontrol']}').val());
						$('#dpjs_kontrol').val('{$list['namaDokter']}');
						$('#kddpjs_kontrol').val('{$list['kodeDokter']}');
						$('#no_sepasal').val('{$list['noSepAsalKontrol']}');
						$('#tglsepasal').val('{$list['tglSEP']}');
						$('#mdRawat').modal('hide');
					}
					
				});
			", View::POS_READY);
		} ?>
	</table>
<?php }else{ ?>
	<br>
	<div class="callout callout-danger">
		<h5>Data tidak ditemukan</h5>
		<p>Silahkan buat surat kontrol terlebih dahulu</p>
	</div>
<?php } ?>