	<div class='row'>
		<div class='col-md-12'>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<?php foreach($json as $j): ?>
					<li  class=""><a href="#tab_<?= $j['idpoli']?>" data-toggle="tab" aria-expanded="false"> <?= $j['namaPoli']?></a></li>
					<?php endforeach; ?>
				</ul>
				<div class="tab-content">
					
					<?php foreach($json as $j): ?>
						
						<div class="tab-pane" id="tab_<?= $j['idpoli']?>">
							<h3>Jadwal Dokter</h3>
							<table class='table table-bordered'>
								<tr>
									<th width=10>No</th>
									<th width=400>Dokter</th>
									<th>Kuota</th>
								</tr>
								<?php $no2=1; foreach($j['jadwalDokter'] as $jd){ ?>
								<tr>
									<td><?= $no2++ ?></td>
									<td><?= $jd['dokter']?></td>
									<td><?= $jd['kuota']?></td>
								</tr>
								<?php } ?>
							</table>
							<h3>Kuota Poli</h3>
							<table class='table table-bordered'>
								<tr>
									<th width=10>No</th>
									<th>Dokter</th>
									<th>Kuota</th>
									<th>Terdaftar</th>
									<th>Sisa</th>
								</tr>
								<?php $no=1; foreach($j['kuota'] as $k){ ?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $k['dokter'] ?></td>
									<td><?= $k['kuota'] ?></td>
									<td><?= $k['terdaftar'] ?></td>
									<td><?= $k['sisa'] ?></td>
								</tr>
								<?php } ?>
							</table>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- /.tab-content -->
			</div>
		</div>
		
	
		
	</div>