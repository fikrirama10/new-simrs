<div class='row'>
	<div class='col-md-7'>
		<div class='box'>
			<div class='box-header with-border'>
				<h4>Barang Keluar Masuk</h4>
			</div>
			<div class='box-body'>
				<table class='table table-bordered'>
					<tr>
						<th>No</th>
						<th>Obat</th>
						<th>Barang Keluar</th>
						<th>Barang Masuk</th>
					</tr>
					<?php $no=1; foreach($json as $j){ ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $j['idobat'] ?> (<?= $j['idbatch'] ?>)</td>
						<td><?= $j['keluar'] ?></td>
						<td><?= $j['masuk'] ?></td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</div>
	</div>
</div>