	<div class='row'>
		<div class='col-md-12'>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<?php foreach($model as $m){ ?>
						<li class="<?= $m['active']?>" class=""><a href="#tab_<?= $m['id']?>" data-toggle="tab" aria-expanded="false"> <?= $m['nama']?></a></li>
					<?php } ?>
				</ul>
				<div class="tab-content">
				<?php foreach($model as $m){ ?>
					<div class="tab-pane  <?= $m['active']?>" id="tab_<?= $m['id']?>">
						<?php 
							$nama = array();
							$total = array();
							foreach($m['diagnosa'] as $md):
								array_push($nama,$md['nama']);
								array_push($total,$md['jumlah']);
							endforeach;
						?>
						<?= $this->render('_data',[
							'model' => $model,
							'jenis' => $m['id'],
							'nama' => $nama,
							'total' => $total,
							'awal' => $awal,
							'akhir' => $akhir,
						]) ?>
					</div>	
				<?php } ?>
				</div>
		
	
		
			</div>
		</div>
	</div>