<div class='row'>
	<div class='col-md-7'>
		<div class='box'>
			<div class='box-header with-border'>
				<h4>Barang Kadaluarsa</h4>
			</div>
			<div class='box-body'>
				<table class='table table-bordered'>
					<tr>
						<th>No</th>
						<th>Bacth Obat</th>
						<th>Merk Obat</th>
						<th>Tgl ED</th>
						<th>Waktu ED</th>
						<th>Jumlah Barang</th>
					</tr>
					<?php $no=1; foreach($json as $j){ ?>
					<?php if($j['stok'] > 0){ ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $j['kodeBacth'] ?></td>
						<td><?= $j['merkObat'] ?></td>
						<td><?= $j['tglED'] ?></td>
						<td><?= $j['ED'] ?></td>
						<td><?= $j['stok'] ?></td>
					</tr>
					<?php } ?>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
</div>