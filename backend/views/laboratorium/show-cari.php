	<div class='row'>
		<div class='col-md-12'>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li  class="active" class=""><a href="#tab_usia" data-toggle="tab" aria-expanded="false"> Pemeriksaan</a></li>
					<li class=""><a href="#tab_agama" data-toggle="tab" aria-expanded="false"> Pelayanan</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_usia">
						<?= $this->render('_dataPemeriksaan',[
							'model' => $model,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_agama">
						<?= $this->render('_dataPelayanan',[
							'model' => $model,
						]) ?>
					</div>

				</div>
				<!-- /.tab-content -->
			</div>
		</div>
		
	
		
	</div>