<table class='table table-bordered'>
					<tr>
						<th width='10'>No</th>
						<th>Tgl</th>
						<th>Merk</th>
						<th>Jumlah</th>
						<th>Stok Awal</th>
						<th>Stok Akhir</th>
						<th>Jenis Mutasi</th>
						<th>Sub Jenis Mutasi</th>
					</tr>
					
					<?php $no=1; foreach($json as $j): ?>
					<tr>
						<td><?= $no++ ?></td>
						<td><?= $j['tgl'] ?></td>
						<td><?= $j['merk'] ?></td>
						<td><?= $j['jumlah'] ?></td>
						<td><?= $j['stokAwal'] ?></td>
						<td><?= $j['stokAkhir'] ?></td>
						<td><?= $j['jenisMutasi'] ?></td>
						<td><?= $j['subMutasi'] ?></td>
					</tr>
					<?php endforeach; ?>
					
				</table>