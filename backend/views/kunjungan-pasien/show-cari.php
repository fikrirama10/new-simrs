	<div class='row'>
		<div class='col-md-12'>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li  class="active" class=""><a href="#tab_usia" data-toggle="tab" aria-expanded="false"> Jenjang Usia</a></li>
					<li class=""><a href="#tab_agama" data-toggle="tab" aria-expanded="false"> Agama</a></li>
					<li class=""><a href="#tab_etnis" data-toggle="tab" aria-expanded="false"> Etnis</a></li>
					<li class=""><a href="#tab_pendidikan" data-toggle="tab" aria-expanded="false"> Pendidikan</a></li>
					<li class=""><a href="#tab_kelurahan" data-toggle="tab" aria-expanded="false"> Kelurahan</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_usia">
					
						<?= $this->render('_dataUsia',[
							'model' => $model,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_agama">
						<?= $this->render('_dataAgama',[
							'model' => $model,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_etnis">
						<?= $this->render('_dataEtnis',[
							'model' => $model,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_pendidikan">
						<?= $this->render('_dataPendidikan',[
							'model' => $model,
						]) ?>
					</div>
					<div class="tab-pane" id="tab_kelurahan">
						<?= $this->render('_dataKelurahan',[
							'model' => $model,
						]) ?>
					</div>
				</div>
				<!-- /.tab-content -->
			</div>
		</div>
		
	
		
	</div>