<div class='row'>
	<div class='col-md-7'>
		<div class='box'>
			<div class='box-header with-border'>
				<h4>Barang Habis</h4>
			</div>
			<div class='box-body'>
				<table class='table table-bordered'>
					<tr>
						<th>No</th>
						<th>Obat</th>
						<th>Stok Minimal</th>
						<th>Stok</th>
					</tr>
					<?php $no=1; foreach($json as $j){ ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $j['namaObat'] ?></td>
						<td><?= $j['stok_min'] ?></td>
						<td><?= $j['stok'] ?></td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
</div>