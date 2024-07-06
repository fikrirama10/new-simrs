	<div class='row'>
		<div class='col-md-12'>
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li  class="active" class=""><a href="#tab_usia" data-toggle="tab" aria-expanded="false">Pasien Poliklinik</a></li>
					<li class=""><a href="#tab_agama" data-toggle="tab" aria-expanded="false"> Pasen Rawat Inap</a></li>
					<li class=""><a href="#tab_etnis" data-toggle="tab" aria-expanded="false"> Pasien UGD</a></li>
				</ul>
				<div class="tab-content">
					<div class="tab-pane active" id="tab_usia">
						<?= $this->render('_dataRajal',[
							'rajal' => $rajal,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_agama">
						<?= $this->render('_dataRanap',[
							'ranap' => $ranap,
						]) ?>
					</div>
				<!-- /.tab-pane -->
					<div class="tab-pane" id="tab_etnis">
						<?= $this->render('_dataUgd',[
							'ugd' => $ugd,
						]) ?>
					</div>
				
				</div>
				<!-- /.tab-content -->
			</div>
		</div>
		
	
		
	</div>